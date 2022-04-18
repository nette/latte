<?php

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\Strict;


abstract class Node
{
	use Strict;

	public ?Position $position = null;


	abstract public function print(PrintContext $context): string;
}
