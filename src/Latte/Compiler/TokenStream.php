<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\CompileException;
use Latte\Strict;


/**
 * Token traversing helper.
 */
class TokenStream
{
	use Strict;

	/** @var LegacyToken[] */
	private array $tokens;
	private int $index = 0;


	/**
	 * @param  LegacyToken[]  $tokens
	 */
	public function __construct(array $tokens)
	{
		$this->tokens = $tokens;
	}


	public function current(): ?LegacyToken
	{
		return $this->tokens[$this->index] ?? null;
	}


	public function is(int|string ...$args): bool
	{
		$this->current()?->is(...$args) ?? false;
	}


	public function peek(int $offset): ?LegacyToken
	{
		return $this->tokens[$this->index + $offset] ?? null;
	}


	public function consume(int|string ...$args): LegacyToken
	{
		$token = $this->tokens[$this->index] ?? null;
		if (!$token) {
			throw new CompileException('Unexpected end.');
		} elseif ($args && !$token->is(...$args)) {
			throw new CompileException("Unexpected '" . trim($token->text, "\n") . "'.");
		}
		$this->index++;
		return $token;
	}


	public function tryConsume(int|string ...$args): ?LegacyToken
	{
		$token = $this->tokens[$this->index] ?? null;
		if ($token?->is(...$args)) {
			$this->index++;
			return $token;
		}
		return null;
	}


	public function seek(int $index): void
	{
		if ($index > count($this->tokens)) {
			throw new CompileException('The position is greater than the current number of tokens.');
		}
		$this->index = $index;
	}


	public function getIndex(): int
	{
		return $this->index;
	}


	public function getTokens(): array
	{
		return $this->tokens;
	}
}
