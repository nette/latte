<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\Helpers;


/** @internal */
final class Block
{
	public string $method;
	public string $content;
	public string $context;

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
		return Helpers::isNameDynamic($this->name);
	}
}
