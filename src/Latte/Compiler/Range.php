<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler;


/**
 * Source range (start and exclusive end position) within a template.
 * @property-read int $length  length of the range in bytes
 */
final readonly class Range
{
	public function __construct(
		public Position $start,
		public Position $end,
	) {
	}


	public function __get(string $name): int
	{
		return match (true) {
			$name === 'length' => $this->end->offset - $this->start->offset,
			default => throw new \LogicException("Attempt to read undeclared property $name."),
		};
	}


	public function __isset(string $name): bool
	{
		return $name === 'length';
	}
}
