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
	private array $tokens = [];
	private int $index = 0;
	private \Generator $generator;


	public function __construct(\Generator $generator)
	{
		$this->generator = $generator;
		$this->tokens[] = $generator->current();
	}


	public function current(): ?LegacyToken
	{
		return $this->peek(0);
	}


	public function is(int|string ...$args): bool
	{
		return $this->peek(0)?->is(...$args) ?? false;
	}


	public function peek(int $offset): ?LegacyToken
	{
		$pos = $this->index + $offset;
		while ($pos >= 0 && !isset($this->tokens[$pos])) {
			$this->generator->next();
			if (!$this->generator->valid()) {
				break;
			}
			$this->tokens[] = $this->generator->current();
		}

		return $this->tokens[$pos] ?? null;
	}


	public function consume(int|string ...$args): LegacyToken
	{
		$token = $this->peek(0);
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
		$token = $this->peek(0);
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
		$this->peek(1000000);
		return $this->tokens;
	}
}
