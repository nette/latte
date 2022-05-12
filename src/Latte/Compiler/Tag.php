<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte;
use Latte\CompileException;
use Latte\Compiler\Nodes\Html\ElementNode;


/**
 * Latte tag or n:attribute.
 */
final class Tag
{
	use Latte\Strict;

	public const
		PrefixInner = 'inner',
		PrefixTag = 'tag',
		PrefixNone = '';

	public const
		OutputNone = 0,
		OutputKeepIndentation = 1,
		OutputRemoveIndentation = 2;

	public MacroTokens $parser;
	public int $outputMode = self::OutputNone;
	public string $args = '';


	public function __construct(
		public /*readonly*/ string $name,
		array $tokens,
		public /*readonly*/ bool $void = false,
		public /*readonly*/ bool $closing = false,
		public /*readonly*/ int $location = 0,
		public /*readonly*/ ?ElementNode $htmlElement = null,
		public ?self $parent = null,
		public /*readonly*/ ?string $prefix = null,
		public /*readonly*/ ?Position $position = null,
		public /*readonly*/ ?\stdClass $data = null,
	) {
		$args = '';
		foreach ($tokens as $token) {
			$args .= $token->text;
		}

		$this->setArgs($args);
		$this->data ??= new \stdClass;
	}


	public function isInHead(): bool
	{
		return $this->location === TemplateParser::LocationHead && !$this->parent;
	}


	public function isInText(): bool
	{
		return $this->location <= TemplateParser::LocationText;
	}


	public function isNAttribute(): bool
	{
		return $this->prefix !== null;
	}


	public function setArgs(string $args): void
	{
		$this->args = trim($args);
		$this->parser = new MacroTokens($this->args);
	}


	public function getNotation(bool $withArgs = false): string
	{
		$args = $withArgs ? $this->args : '';
		return $this->isNAttribute()
			? TemplateLexer::NPrefix . ($this->prefix ? $this->prefix . '-' : '')
				. $this->name
				. ($args === '' ? '' : '="' . $args . '"')
			: '{'
				. ($this->closing ? '/' : '')
				. rtrim($this->name
				. ($args === '' ? '' : ' ' . $args))
			. '}';
	}


	/**
	 * @param  string[]  $names
	 */
	public function closestTag(array $names, ?callable $condition = null): ?self
	{
		$tag = $this->parent;
		while ($tag && (
			!in_array($tag->name, $names, true)
			|| ($condition && !$condition($tag))
		)) {
			$tag = $tag->parent;
		}

		return $tag;
	}


	/**
	 * @throws CompileException
	 */
	public function expectArguments(string|bool|null $arguments = true): void
	{
		if ($arguments && $this->args === '') {
			$label = is_string($arguments) ? $arguments : 'arguments';
			throw new CompileException('Missing ' . $label . ' in ' . $this->getNotation(), $this->position);

		} elseif ($arguments === false && $this->args !== '') {
			throw new CompileException('Arguments are not allowed in ' . $this->getNotation(), $this->position);
		}
	}


	public function extractModifier(): void
	{
		if (preg_match('~^
			(?<args>(?:' . TemplateLexer::ReString . '|[^\'"])*?)
			(?<modifiers>(?<!\|)\|[a-z](?<modArgs>(?:' . TemplateLexer::ReString . '|(?:\((?P>modArgs)\))|[^\'"/()]|/(?=.))*+))?
		$~Disx', $this->args, $match)) {
			$this->setArgs(trim($match['args']));
			$this->parser->modifiers = $match['modifiers'] ?? '';
		}
	}


	public function replaceNAttribute(Node $node): void
	{
		$index = array_search($this->data->node, $this->htmlElement->attributes->children, true);
		$this->htmlElement->attributes->children[$index] = $node;
	}
}
