<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;


/** @internal */
final class Block
{
	public array $parameters = [];


	public function __construct(
		public /*readonly*/ string $name,
		public /*readonly*/ int|string $layer,
	) {
	}


	public static function isDynamic($name): bool
	{
		return str_contains($name, '$') || str_contains($name, ' ');
	}
}
