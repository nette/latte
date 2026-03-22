<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler;


/**
 * Source range (starting position plus byte length) within a template.
 */
final readonly class Range extends Position
{
	public static function span(?self $start, ?self $end): ?self
	{
		return $start && $end
			? new self($start->line, $start->column, $start->offset, $end->offset + $end->length - $start->offset)
			: $start;
	}


	public function __construct(
		public int $line,
		public int $column,
		public int $offset,
		public int $length,
	) {
	}
}
