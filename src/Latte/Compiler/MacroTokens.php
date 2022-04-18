<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\CompileException;


/**
 * Legacy macro tag tokenizer.
 */
class MacroTokens
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

	public const
		SIGNIFICANT = [self::T_SYMBOL, self::T_NUMBER, self::T_VARIABLE, self::T_STRING, self::T_CAST, self::T_KEYWORD, self::T_CHAR],
		NON_SIGNIFICANT = [self::T_COMMENT, self::T_WHITESPACE];

	/** @var array<array{string, int, int}> */
	public array $tokens;

	public int $position = -1;

	/** @var int[] */
	public array $ignored = [];

	public int $depth = 0;
	private static ?Tokenizer $tokenizer = null;


	/**
	 * @param  string|array<array{string, int, int}>  $input
	 */
	public function __construct(string|array $input = [])
	{
		$this->tokens = is_array($input) ? $input : $this->parse($input);
		$this->ignored = self::NON_SIGNIFICANT;
	}


	/**
	 * @return array<array{string, int, int}>
	 */
	public function parse(string $s): array
	{
		self::$tokenizer = self::$tokenizer ?: new Tokenizer([
			self::T_WHITESPACE => '\s+',
			self::T_COMMENT => '(?s)/\*.*?\*/',
			self::T_STRING => TemplateLexer::ReString,
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
	 */
	public function append($val, ?int $position = null): static
	{
		if ($val != null) { // intentionally @
			array_splice(
				$this->tokens,
				$position ?? count($this->tokens),
				0,
				is_array($val) ? [$val] : $this->parse($val),
			);
		}

		return $this;
	}


	/**
	 * Prepends simple token or string (will be parsed).
	 * @param  string|array{string, int, int}  $val
	 */
	public function prepend($val): static
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
	public function joinUntilSameDepth(int|string ...$args): string
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
	public function fetchWordWithModifier(string|array $modifiers): ?array
	{
		$modifiers = (array) $modifiers;
		$pos = $this->position;
		if (
			($mod = $this->nextValue(...$modifiers))
			&& ($this->nextToken($this::T_WHITESPACE) || !ctype_alnum($mod))
			&& ($name = $this->fetchWord())
		) {
			return [$name, $mod];
		}

		$this->position = $pos;
		$name = $this->fetchWord();
		return $name === null ? null : [$name, null];
	}


	public function reset(): static
	{
		$this->depth = 0;
		$this->position = -1;
		return $this;
	}


	protected function next(): void
	{
		$this->position++;
		if ($this->isCurrent('[', '(', '{')) {
			$this->depth++;
		} elseif ($this->isCurrent(']', ')', '}')) {
			$this->depth--;
		}
	}


	/**
	 * Returns current token.
	 * @return ?array{string, int, int}
	 */
	public function currentToken(): ?array
	{
		return $this->tokens[$this->position] ?? null;
	}


	/**
	 * Returns current token value.
	 */
	public function currentValue(): ?string
	{
		return $this->tokens[$this->position][Tokenizer::VALUE] ?? null;
	}


	/**
	 * Returns next token.
	 * @param  int|string  ...$args  desired token type or value
	 * @return ?array{string, int, int}
	 */
	public function nextToken(int|string ...$args): ?array
	{
		return $this->scan($args, true, true); // onlyFirst, advance
	}


	/**
	 * Returns next token value.
	 * @param  int|string  ...$args  desired token type or value
	 */
	public function nextValue(int|string ...$args): ?string
	{
		return $this->scan($args, true, true, true); // onlyFirst, advance, strings
	}


	/**
	 * Returns all next tokens.
	 * @param  int|string  ...$args  desired token type or value
	 * @return array<array{string, int, int}>
	 */
	public function nextAll(int|string ...$args): array
	{
		return $this->scan($args, false, true); // advance
	}


	/**
	 * Returns all next tokens until it sees a given token type or value.
	 * @param  int|string  ...$args  token type or value to stop before (required)
	 * @return array<array{string, int, int}>
	 */
	public function nextUntil(int|string ...$args): array
	{
		return $this->scan($args, false, true, false, true); // advance, until
	}


	/**
	 * Returns concatenation of all next token values.
	 * @param  int|string  ...$args  token type or value to be joined
	 */
	public function joinAll(int|string ...$args): string
	{
		return $this->scan($args, false, true, true); // advance, strings
	}


	/**
	 * Returns concatenation of all next tokens until it sees a given token type or value.
	 * @param  int|string  ...$args  token type or value to stop before (required)
	 */
	public function joinUntil(int|string ...$args): string
	{
		return $this->scan($args, false, true, true, true); // advance, strings, until
	}


	/**
	 * Checks the current token.
	 * @param  int|string  ...$args  token type or value
	 */
	public function isCurrent(int|string ...$args): bool
	{
		if (!isset($this->tokens[$this->position])) {
			return false;
		}

		$token = $this->tokens[$this->position];
		return in_array($token[Tokenizer::VALUE], $args, true)
			|| in_array($token[Tokenizer::TYPE], $args, true);
	}


	/**
	 * Checks the next token existence.
	 * @param  int|string  ...$args  token type or value
	 */
	public function isNext(int|string ...$args): bool
	{
		return (bool) $this->scan($args, true, false); // onlyFirst
	}


	/**
	 * Checks the previous token existence.
	 * @param  int|string  ...$args  token type or value
	 */
	public function isPrev(int|string ...$args): bool
	{
		return (bool) $this->scan($args, true, false, false, false, true); // onlyFirst, prev
	}


	/**
	 * Returns next expected token or throws exception.
	 * @param  int|string  ...$args  desired token type or value
	 * @throws CompileException
	 */
	public function consumeValue(int|string ...$args): string
	{
		if ($token = $this->scan($args, true, true)) { // onlyFirst, advance
			return $token[Tokenizer::VALUE];
		}

		$pos = $this->position + 1;
		while (($next = $this->tokens[$pos] ?? null) && in_array($next[Tokenizer::TYPE], $this->ignored, true)) {
			$pos++;
		}

		throw new CompileException($next ? "Unexpected token '" . $next[Tokenizer::VALUE] . "'." : 'Unexpected end.');
	}


	/**
	 * Looks for (first) (not) wanted tokens.
	 * @param  array<int|string>  $wanted  of desired token types or values
	 */
	protected function scan(
		array $wanted,
		bool $onlyFirst,
		bool $advance,
		bool $strings = false,
		bool $until = false,
		bool $prev = false,
	): mixed {
		$res = $onlyFirst ? null : ($strings ? '' : []);
		$pos = $this->position + ($prev ? -1 : 1);
		do {
			if (!isset($this->tokens[$pos])) {
				if (!$wanted && $advance && !$prev && $pos <= count($this->tokens)) {
					$this->next();
				}
				return $res;
			}

			$token = $this->tokens[$pos];
			if (
				!$wanted
				|| (
					in_array($token[Tokenizer::VALUE], $wanted, true)
					|| in_array($token[Tokenizer::TYPE], $wanted, true)
				) ^ $until
			) {
				while ($advance && !$prev && $pos > $this->position) {
					$this->next();
				}

				if ($onlyFirst) {
					return $strings ? $token[Tokenizer::VALUE] : $token;
				} elseif ($strings) {
					$res .= $token[Tokenizer::VALUE];
				} else {
					$res[] = $token;
				}
			} elseif ($until || !in_array($token[Tokenizer::TYPE], $this->ignored, true)) {
				return $res;
			}

			$pos += $prev ? -1 : 1;
		} while (true);
	}
}
