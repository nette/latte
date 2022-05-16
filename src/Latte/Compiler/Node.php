<?php

declare(strict_types=1);

namespace Latte\Compiler;

use Latte;


abstract class Node
{
	use Latte\Strict;

	public ?Position $position = null;


	abstract public function print(PrintContext $context): string;
}
