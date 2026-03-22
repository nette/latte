<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler;

use function strlen, strrpos, substr_count;


/**
 * Source position (line, column, byte offset) within a template.
 */
final readonly class Position
{
	public static function range(?self $start, ?self $end): ?self
	{
		return $start && $end
			? $start->withLength($end->offset + $end->length - $start->offset)
			: $start;
	}


	public function __construct(
		public int $line = 1,
		public int $column = 1,
		public int $offset = 0,
		public ?int $length = null,
	) {
	}


	public function withLength(int $length): self
	{
		return new self($this->line, $this->column, $this->offset, $length);
	}


	/**
	 * Returns a new position advanced by the length of the given string.
	 */
	public function advance(string $str): self
	{
		if ($lines = substr_count($str, "\n")) {
			return new self(
				$this->line + $lines,
				strlen($str) - strrpos($str, "\n"),
				$this->offset + strlen($str),
			);
		} else {
			return new self(
				$this->line,
				$this->column + strlen($str),
				$this->offset + strlen($str),
			);
		}
	}


	public function __toString(): string
	{
		return "on line $this->line" . ($this->column ? " at column $this->column" : '');
	}
}
