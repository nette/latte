<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte;
use Latte\CompileException;


final class TokenStream
{
	use Latte\Strict;

	/** @var Token[] */
	private array $tokens;
	private int $index = 0;


	/**
	 * @param  Token[]  $tokens
	 */
	public function __construct(array $tokens)
	{
		$this->tokens = $tokens;
	}


	/**
	 * Tells whether the token at current position is of given kind.
	 */
	public function is(int|string ...$kind): bool
	{
		return $this->peek()->is(...$kind);
	}


	/**
	 * Gets the token at $offset from the current position.
	 */
	public function peek(int $offset = 0): ?Token
	{
		return $this->tokens[$this->index + $offset] ?? null;
	}


	/**
	 * Consumes the current token (if is of given kind) or throws exception on end.
	 * @throws CompileException
	 */
	public function consume(int|string ...$kind): Token
	{
		$token = $this->peek();
		if ($kind && !$token->is(...$kind)) {
			$kind = array_map(fn($item) => is_string($item) ? "'$item'" : $item, $kind);
			$this->throwUnexpectedException($kind);
		} elseif (!$token->isEnd()) {
			$this->index++;
		}
		return $token;
	}


	/**
	 * Consumes the current token of given kind or returns null.
	 */
	public function tryConsume(int|string ...$kind): ?Token
	{
		$token = $this->peek();
		if (!$token->is(...$kind)) {
			return null;
		} elseif (!$token->isEnd()) {
			$this->index++;
		}
		return $token;
	}


	/**
	 * Sets the input cursor to the position.
	 */
	public function seek(int $index): void
	{
		if ($index >= count($this->tokens) || $index < 0) {
			throw new \InvalidArgumentException('The position is out of range.');
		}
		$this->index = $index;
	}


	/**
	 * Returns the cursor position.
	 */
	public function getIndex(): int
	{
		return $this->index;
	}


	/**
	 * @throws CompileException
	 * @return never
	 */
	public function throwUnexpectedException(array $expected = [], string $addendum = ''): void
	{
		$s = null;
		$i = 0;
		do {
			$token = $this->peek($i++);
			if ($token->isEnd()) {
				break;
			}
			$s .= $token->text;
			if (strlen($s) > 5) {
				break;
			}
		} while (true);

		throw new CompileException(
			'Unexpected '
			. ($s === null
				? 'end'
				: "'" . trim($s, "\n") . "'")
			. ($expected && count($expected) < 5
				? ', expecting ' . implode(', ', $expected)
				: '')
			. $addendum,
			$this->peek()->position ?? null,
		);
	}
}
