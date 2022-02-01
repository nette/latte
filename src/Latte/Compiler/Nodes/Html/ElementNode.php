<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Html;

use Latte\Compiler\Node;
use Latte\Compiler\Nodes;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\AuxiliaryNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Context;


/**
 * HTML element node.
 */
class ElementNode extends AreaNode
{
	public ?FragmentNode $attributes = null;
	public bool $selfClosing = false;
	public ?AreaNode $content = null;

	/** @var Tag[] */
	public array $nAttributes = [];

	/** n:tag & n:tag- support */
	public AreaNode $tagNode;
	public bool $captureTagName = false;
	private ?string $endTagVar;


	public function __construct(
		public /*readonly*/ string $name,
		public /*readonly*/ ?Position $position = null,
		public /*readonly*/ ?self $parent = null,
		public /*readonly*/ ?\stdClass $data = null,
	) {
		$this->data ??= new \stdClass;
		$this->tagNode = new AuxiliaryNode(\Closure::fromCallable([$this, 'printStartTag']));
	}


	public function getAttribute(string $name): string|Node|bool|null
	{
		foreach ($this->attributes?->children as $child) {
			if ($child instanceof AttributeNode
				&& strcasecmp($name, $child->name) === 0
			) {
				return self::nodeToString($child->value) ?? $child->value ?? true;
			}
		}

		return null;
	}


	public function print(PrintContext $context): string
	{
		$res = $this->endTagVar = null;
		if ($this->captureTagName) {
			$endTag = $this->endTagVar = '$ʟ_tag[' . $context->generateId() . ']';
			$res = "$this->endTagVar = '';";
		} else {
			$endTag = var_export('</' . $this->name . '>', true);
		}

		$res .= $this->tagNode->print($context); // calls $this->printStartTag()

		if ($this->content) {
			$this->setInnerContext($context);
			$res .= $this->content->print($context);
			$context->setEscapingContext(Context::HtmlText);
			$res .= 'echo ' . $endTag . ';';
		}

		return $res;
	}


	private function printStartTag(PrintContext $context): string
	{
		$context->setEscapingContext(Context::HtmlTag);
		$res = "echo '<';";

		$namePhp = var_export($this->name, true);
		if ($this->endTagVar) {
			$res .= 'echo $ʟ_tmp = ' . $namePhp . ';';
			$res .= $this->endTagVar . ' = '
				. "'</' . \$ʟ_tmp . '>'"
				. ' . ' . $this->endTagVar . ';';
		} else {
			$res .= 'echo ' . $namePhp . ';';
		}

		foreach ($this->attributes?->children ?? [] as $attr) {
			if ($attr instanceof AttributeNode) {
				$this->setAttributeContext($context, $attr);
			}
			$res .= $attr->print($context);
		}

		$res .= "echo '" . ($this->selfClosing ? '/>' : '>') . "';";
		$context->setEscapingContext(Context::HtmlText);
		return $res;
	}


	private function setInnerContext(PrintContext $context): void
	{
		$name = strtolower($this->name);
		if (
			$context->getContentType() === Context::Html
			&& !$this->selfClosing
			&& ($name === 'script' || $name === 'style')
			&& is_string($attr = $this->getAttribute('type') ?? 'css')
			&& preg_match('#(java|j|ecma|live)script|module|json|css|plain#i', $attr)
		) {
			$context->setEscapingContext($name === 'script'
				? Context::HtmlJavaScript
				: Context::HtmlCss);
		} else {
			$context->setEscapingContext(Context::HtmlText);
		}
	}


	private function setAttributeContext(PrintContext $context, AttributeNode $attr): void
	{
		if ($context->getContentType() !== Context::Html) {
			$context->setEscapingContext($attr->quote ? Context::XmlAttribute : Context::XmlTag);
			return;
		}

		$attrName = strtolower($attr->name);

		if ($attr->quote) {
			$escapingContext = Context::HtmlAttribute;
			if (str_starts_with($attrName, 'on')) {
				$escapingContext = Context::HtmlAttributeJavaScript;
			} elseif ($attrName === 'style') {
				$escapingContext = Context::HtmlAttributeCss;
			}
		} else {
			$escapingContext = Context::HtmlTag;
		}

		if ((in_array($attrName, ['href', 'src', 'action', 'formaction'], true)
			|| ($attrName === 'data' && strtolower($this->name) === 'object'))
		) {
			$escapingContext = $escapingContext === Context::HtmlTag
				? Context::HtmlAttributeUnquotedUrl
				: Context::HtmlAttributeUrl;
		}

		$context->setEscapingContext($escapingContext);
	}


	private static function nodeToString(?Node $node): ?string
	{
		if ($node instanceof Nodes\FragmentNode) {
			$res = null;
			foreach ($node->children as $child) {
				if (($s = self::nodeToString($child)) === null) {
					return null;
				}
				$res .= $s;
			}

			return $res;
		}

		return match (true) {
			$node instanceof Nodes\TextNode => $node->content,
			default => null,
		};
	}


	public function &getIterator(): \Generator
	{
		yield $this->tagNode;
		if ($this->attributes) {
			yield $this->attributes;
		}
		if ($this->content) {
			yield $this->content;
		}
	}
}
