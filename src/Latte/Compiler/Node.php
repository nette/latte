<?php

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\Strict;


abstract class Node
{
	use Strict;

	public ?int $line = null;


	abstract public function compile(Compiler $compiler): string;
}
