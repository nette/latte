<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;


/**
 * Macro tag tokenizer.
 */
class MacroTokens extends TokenIterator
{
	public const
		T_WHITESPACE = 1,
		T_COMMENT = 2,
		T_SYMBOL = 3,
		T_NUMBER = 4,
		T_VARIABLE = 5,
		T_STRING = 6,
		T_CAST = 7,
		T_KEYWORD = 8,
		T_CHAR = 9;

	/** @var int */
	public $depth = 0;

	/** @var Tokenizer|null */
	private static $tokenizer;


	/**
	 * @param  string|array<array{string, int, int}>  $input
	 */
	public function __construct($input = [])
	{
		parent::__construct(is_array($input) ? $input : $this->parse($input));
		$this->ignored = [self::T_COMMENT, self::T_WHITESPACE];
	}


	/**
	 * @return array<array{string, int, int}>
	 */
	public function parse(string $s): array
	{
		self::$tokenizer = self::$tokenizer ?: new Tokenizer([
			self::T_WHITESPACE => '\s+',
			self::T_COMMENT => '(?s)/\*.*?\*/',
			self::T_STRING => Parser::RE_STRING,
			self::T_KEYWORD => '(?:true|false|null|TRUE|FALSE|NULL|INF|NAN|and|or|xor|AND|OR|XOR|clone|new|instanceof|return|continue|break)(?!\w)', // keyword
			self::T_CAST => '\((?:expand|string|array|int|integer|float|bool|boolean|object)\)', // type casting
			self::T_VARIABLE => '\$\w+',
			self::T_NUMBER => '[+-]?[0-9]+(?:\.[0-9]+)?(?:e[0-9]+)?',
			self::T_SYMBOL => '\w+(?:-+\w+)*',
			self::T_CHAR => '::|=>|->|\?->|\?\?->|\+\+|--|<<|>>|<=>|<=|>=|===|!==|==|!=|<>|&&|\|\||\?\?|\?>|\*\*|\.\.\.|[^"\']', // =>, any char except quotes
		], 'u');
		return self::$tokenizer->tokenize($s);
	}


	/**
	 * Appends simple token or string (will be parsed).
	 * @param  string|array{string, int, int}  $val
	 * @return static
	 */
	public function append($val, int $position = null)
	{
		if ($val != null) { // intentionally @
			array_splice(
				$this->tokens,
				$position ?? count($this->tokens),
				0,
				is_array($val) ? [$val] : $this->parse($val)
			);
		}
		return $this;
	}


	/**
	 * Prepends simple token or string (will be parsed).
	 * @param  string|array{string, int, int}  $val
	 * @return static
	 */
	public function prepend($val)
	{
		if ($val != null) { // intentionally @
			array_splice($this->tokens, 0, 0, is_array($val) ? [$val] : $this->parse($val));
		}
		return $this;
	}


	/**
	 * Reads single expression optionally delimited by comma.
	 */
	public function fetchWord(): ?string
	{
		if ($this->isNext('(')) {
			$expr = $this->nextValue('(') . $this->joinUntilSameDepth(')') . $this->nextValue(')');
		} else {
			$expr = $this->joinUntilSameDepth(self::T_WHITESPACE, ',');
			if ($this->isNext(...[
				'%', '&', '*', '.', '<', '=', '>', '?', '^', '|', ':',
				'::', '=>', '->', '?->', '??->', '<<', '>>', '<=>', '<=', '>=', '===', '!==', '==', '!=', '<>', '&&', '||', '??', '**',
				'instanceof',
			])) {
				$expr .= $this->joinUntilSameDepth(',');
			}
		}
		$this->nextToken(',');
		$this->nextAll(self::T_WHITESPACE, self::T_COMMENT);
		return $expr === '' ? null : $expr;
	}


	/**
	 * @deprecated
	 */
	public function fetchWords(): array
	{
		do {
			$words[] = $this->joinUntil(self::T_WHITESPACE, ',', ':');
		} while ($this->nextToken(':'));

		if (count($words) === 1 && ($space = $this->nextValue(self::T_WHITESPACE))
			&& (($dot = $this->nextValue('.')) || $this->isPrev('.'))) {
			$words[0] .= $space . $dot . $this->joinUntil(',');
		}
		$this->nextToken(',');
		$this->nextAll(self::T_WHITESPACE, self::T_COMMENT);
		return $words === [''] ? [] : $words;
	}


	/**
	 * @param  int|string  ...$args  token type or value to stop before (required)
	 */
	public function joinUntilSameDepth(...$args): string
	{
		$depth = $this->depth;
		$res = '';
		do {
			$res .= $this->joinUntil(...$args);
			if ($this->depth === $depth) {
				return $res;
			}
			$res .= $this->nextValue();
		} while (true);
	}


	/**
	 * @param  string|string[]  $modifiers
	 * @return ?array{string, ?string}
	 */
	public function fetchWordWithModifier($modifiers): ?array
	{
		$modifiers = (array) $modifiers;
		$pos = $this->position;
		if (
			($mod = $this->nextValue(...$modifiers))
			&& $this->nextToken($this::T_WHITESPACE)
			&& ($name = $this->fetchWord())
		) {
			return [$name, $mod];
		}
		$this->position = $pos;
		$name = $this->fetchWord();
		return $name === null ? null : [$name, null];
	}


	/** @return static */
	public function reset()
	{
		$this->depth = 0;
		return parent::reset();
	}


	protected function next(): void
	{
		parent::next();
		if ($this->isCurrent('[', '(', '{')) {
			$this->depth++;
		} elseif ($this->isCurrent(']', ')', '}')) {
			$this->depth--;
		}
	}
}
