<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\CompileException;
use Latte\Compiler\Nodes\Html\ElementNode;
use Latte\Extensions\Filters;
use Latte\Strict;


/**
 * Latte tag info.
 */
class TagInfo
{
	use Strict;

	public const
		PREFIX_INNER = 'inner',
		PREFIX_TAG = 'tag',
		PREFIX_NONE = 'none';

	public string $args;
	public MacroTokens $tokenizer;
	public \stdClass $data;


	public function __construct(
		public /*readonly*/ string $name,
		string /*readonly*/ $args,
		public /*readonly*/ string $modifiers = '',
		public /*readonly*/ bool $empty = false,
		public /*readonly*/ bool $closing = false,
		public /*readonly*/ ?int $line = null,
		public /*readonly*/ int $location = 0,
		public /*readonly*/ ?ElementNode $htmlElement = null,
		public /*readonly*/ ?self $parent = null,
		public /*readonly*/ ?string $prefix = null,
		public /*readonly*/ ?string $indentation = null,
		public /*readonly*/ bool $newline = false,
	) {
		$this->data = new \stdClass;
		$this->setArgs($args);
	}


	public function isInHead(): bool
	{
		return $this->location === Parser::LOCATION_HEAD;
	}


	public function isInText(): bool
	{
		return $this->location <= Parser::LOCATION_TEXT;
	}


	public function isInTag(): bool
	{
		return $this->location === Parser::LOCATION_TAG;
	}


	public function setArgs(string $args): void
	{
		$this->args = $args;
		$this->tokenizer = new MacroTokens($args);
		$this->tokenizer->line = $this->line;
		$this->tokenizer->modifier = &$this->modifiers;
	}


	public function getNotation(): string
	{
		return $this->prefix
			? Lexer::N_PREFIX . ($this->prefix === self::PREFIX_NONE ? '' : $this->prefix . '-') . $this->name
			: '{' . $this->name . '}';
	}


	public function closest(array $names, ?callable $condition = null): ?self
	{
		$item = $this->parent;
		while ($item && (
				!in_array($item->name, $names, true)
				|| ($condition && !$condition($item))
			)) {
			$item = $item->parent;
		}

		return $item;
	}


	/**
	 * @param  string[]  $parents
	 * @throws CompileException
	 */
	public function validate(string|bool|null $arguments, array $parents = [], bool $modifiers = false): void
	{
		if ($parents && (!$this->parentNode || !in_array($this->parentNode->name, $parents, true))) {
			throw new CompileException('Tag ' . $this->getNotation() . ' is unexpected here.');

		} elseif ($this->modifiers !== '' && !$modifiers) {
			throw new CompileException('Filters are not allowed in ' . $this->getNotation());

		} elseif ($arguments && $this->args === '') {
			$label = is_string($arguments) ? $arguments : 'arguments';
			throw new CompileException('Missing ' . $label . ' in ' . $this->getNotation());

		} elseif ($arguments === false && $this->args !== '') {
			throw new CompileException('Arguments are not allowed in ' . $this->getNotation());
		}
	}


	public function checkExtraArgs(): void
	{
		if ($this->tokenizer->isNext(...$this->tokenizer::SIGNIFICANT)) {
			$args = Filters::truncate($this->tokenizer->joinAll(), 20);
			throw new CompileException("Unexpected arguments '$args' in " . $this->getNotation());
		}
	}
}
