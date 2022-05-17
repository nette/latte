<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte;


/** @internal */
final class Block
{
	use Latte\Strict;

	public string $method;
	public string $content;
	public string $escaping;

	/** @var string[] */
	public array $parameters = [];


	public function __construct(
		public /*readonly*/ string $name,
		public /*readonly*/ int|string $layer,
		public /*readonly*/ Tag $tag,
	) {
	}


	public function isDynamic(): bool
	{
		return Latte\Helpers::isNameDynamic($this->name);
	}
}
