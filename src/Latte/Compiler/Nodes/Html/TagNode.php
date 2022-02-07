<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Html;

use Latte\Compiler\Compiler;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\TextNode;
use Latte\Helpers;


/**
 * HTML tag.
 */
class TagNode extends Node
{
	public function __construct(
		public Node $name,
		public ?FragmentNode $attrs = null,
		public bool $closing = false,
		public bool $selfClosing = false,
		public ?string $indentation = '',
		public bool $newline = false,
		public ?int $line = null,
	) {
	}


	public function getName(): ?string
	{
		return $this->name instanceof TextNode
			? strtolower($this->name->content)
			: null;
	}


	public function getAttribute(string $name): string|Node|bool|null
	{
		foreach ($this->attrs?->children as $child) {
			if ($child instanceof AttributeNode
				&& strcasecmp($name, $child->name) === 0
			) {
				return Helpers::nodeToString($child->value) ?? $child->value ?? true;
			}
		}

		return null;
	}


	public function compile(Compiler $compiler): string
	{
		$html = $compiler->getContentType() === Compiler::CONTENT_HTML;
		$res = 'echo ' . var_export($this->indentation . ($this->closing ? '</' : '<'), true) . ';';
		$compiler->setContext($html ? Compiler::CONTEXT_HTML_TAG : Compiler::CONTEXT_XML_TAG);
		$res .= $this->name->compile($compiler);
		$res .= $this->compileAttrs($compiler, $html);
		$compiler->setContext($this->getAfterContext($html));
		$res .= 'echo ' . var_export(($this->selfClosing ? '/>' : '>') . ($this->newline ? "\n" : ''), true) . ';';
		return $res;
	}


	private function compileAttrs(Compiler $compiler, bool $html): string
	{
		$res = '';
		foreach ($this->attrs?->children ?? [] as $attr) {
			if ($attr instanceof AttributeNode) {
				$compiler->setContext($this->getAttributeContext($attr, $html));
			}
			$res .= $attr->compile($compiler);
		}

		return $res;
	}


	private function getAfterContext(bool $html): ?string
	{
		if (!$html) {
			return Compiler::CONTEXT_XML_TEXT;
		}

		$name = $this->getName();
		if (
			!$this->closing && !$this->selfClosing
			&& ($name === 'script' || $name === 'style')
			&& is_string($attr = $this->getAttribute('type') ?? 'css')
			&& preg_match('#(java|j|ecma|live)script|module|json|css#i', $attr)
		) {
			return $name === 'script'
				? Compiler::CONTEXT_HTML_JS
				: Compiler::CONTEXT_HTML_CSS;
		}
		return Compiler::CONTEXT_HTML_TEXT;
	}


	private function getAttributeContext(AttributeNode $attr, bool $html): string
	{
		if (!$html) {
			return $attr->quote ? Compiler::CONTEXT_XML_ATTRIBUTE : Compiler::CONTEXT_XML_TAG;
		}

		$attrName = strtolower($attr->name);

		if ($attr->quote) {
			$context = Compiler::CONTEXT_HTML_ATTRIBUTE;
			if (str_starts_with($attrName, 'on')) {
				$context = Compiler::CONTEXT_HTML_ATTRIBUTE_JS;
			} elseif ($attrName === 'style') {
				$context = Compiler::CONTEXT_HTML_ATTRIBUTE_CSS;
			}
		} else {
			$context = Compiler::CONTEXT_HTML_TAG;
		}

		if ((in_array($attrName, ['href', 'src', 'action', 'formaction'], true)
			|| ($attrName === 'data' && $this->getName() === 'object'))
		) {
			$context = $context === Compiler::CONTEXT_HTML_TAG
				? Compiler::CONTEXT_HTML_ATTRIBUTE_UNQUOTED_URL
				: Compiler::CONTEXT_HTML_ATTRIBUTE_URL;
		}

		return $context;
	}
}
