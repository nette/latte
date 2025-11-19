<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Html;

use Latte\Compiler\Node;
use Latte\Compiler\NodeHelpers;
use Latte\Compiler\Nodes;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\ContentType;


/**
 * HTML element node.
 */
class ElementNode extends AreaNode
{
	public FragmentNode $attributes;
	public bool $selfClosing = false;
	public ?AreaNode $content = null;

	/** @var Tag[] */
	public array $nAttributes = [];
	public ?AreaNode $dynamicTag = null;
	public bool $breakable = false;


	public function __construct(
		public readonly string $name,
		public ?Position $position = null,
		public readonly ?self $parent = null,
		public string $contentType = ContentType::Html,
	) {
		$this->attributes = new FragmentNode;
	}


	public function getAttribute(string $name): string|Node|bool|null
	{
		foreach ($this->attributes->children as $child) {
			if ($child instanceof AttributeNode
				&& $child->name instanceof Nodes\TextNode
				&& $this->matchesIdentifier($name, $child->name->content)
			) {
				return NodeHelpers::toText($child->value) ?? $child->value ?? true;
			} elseif ($child instanceof ExpressionAttributeNode
				&& $this->matchesIdentifier($name, $child->name)
			) {
				return true;
			}
		}

		return null;
	}


	public function is(string $name): bool
	{
		return $this->matchesIdentifier($this->name, $name);
	}


	private function matchesIdentifier(string $a, string $b): bool
	{
		return $this->contentType === ContentType::Html
			? strcasecmp($a, $b) === 0
			: $a === $b;
	}


	public function isRawText(): bool
	{
		return $this->contentType === ContentType::Html
			&& ($this->is('script') || $this->is('style'));
	}


	public function print(PrintContext $context): string
	{
		$res = $this->dynamicTag
			? $this->dynamicTag->print($context)
			: (new TagNode($this))->print($context, captureEnd: false);

		if ($this->content) {
			if ($this->dynamicTag) {
				$endTag = '$ʟ_tags[' . ($context->generateId()) . ']';
				$res = "\$ʟ_tag = ''; $res $endTag = \$ʟ_tag;";
			} else {
				$endTag = var_export('</' . $this->name . '>', true);
			}

			$context->beginEscape()->enterHtmlText($this);
			$content = $this->content->print($context);
			$context->restoreEscape();
			$res .= $this->breakable
				? 'try { ' . $content . ' } finally { echo ' . $endTag . '; } '
				: $content . ' echo ' . $endTag . ';';
		}

		return $res;
	}


	public function &getIterator(): \Generator
	{
		if ($this->dynamicTag) {
			yield $this->dynamicTag;
		}
		yield $this->attributes;
		if ($this->content) {
			yield $this->content;
		}
	}
}
