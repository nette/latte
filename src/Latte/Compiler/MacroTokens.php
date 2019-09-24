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
	 * @param  string|array  $input
	 */
	public function __construct($input = [])
	{
		parent::__construct(is_array($input) ? $input : $this->parse($input));
		$this->ignored = [self::T_COMMENT, self::T_WHITESPACE];
	}


	public function parse(string $s): array
	{
		self::$tokenizer = self::$tokenizer ?: new Tokenizer([
			self::T_WHITESPACE => '\s+',
			self::T_COMMENT => '(?s)/\*.*?\*/',
			self::T_STRING => Parser::RE_STRING,
			self::T_KEYWORD => '(?:true|false|null|TRUE|FALSE|NULL|INF|NAN|and|or|xor|clone|new|instanceof|return|continue|break)(?![\w\pL_])', // keyword
			self::T_CAST => '\((?:expand|string|array|int|integer|float|bool|boolean|object)\)', // type casting
			self::T_VARIABLE => '\$[\w\pL_]+',
			self::T_NUMBER => '[+-]?[0-9]+(?:\.[0-9]+)?(?:e[0-9]+)?',
			self::T_SYMBOL => '[\w\pL_]+(?:-+[\w\pL_]+)*',
			self::T_CHAR => '::|=>|->|\+\+|--|<<|>>|<=>|<=|>=|===|!==|==|!=|<>|&&|\|\||\?\?|\?>|\*\*|\.\.\.|[^"\']', // =>, any char except quotes
		], 'u');
		return self::$tokenizer->tokenize($s);
	}


	/**
	 * Appends simple token or string (will be parsed).
	 * @return static
	 */
	public function append($val, int $position = null)
	{
		if ($val != null) { // intentionally @
			array_splice(
				$this->tokens,
				$position === null ? count($this->tokens) : $position,
				0,
				is_array($val) ? [$val] : $this->parse($val)
			);
		}
		return $this;
	}


	/**
	 * Prepends simple token or string (will be parsed).
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
	 * Reads single token (optionally delimited by comma) from string.
	 */
	public function fetchWord(): ?string
	{
		$words = $this->fetchWords();
		return $words ? implode(':', $words) : null;
	}


	/**
	 * Reads single tokens delimited by colon from string.
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
