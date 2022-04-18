<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php;

use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class NullableTypeNode extends ComplexTypeNode
{
	public IdentifierNode|NameNode $type;


	public function __construct(
		string|IdentifierNode|NameNode $type,
		public ?Position $position = null,
	) {
		$this->type = is_string($type) ? new IdentifierNode($type) : $type;
	}


	public function print(PrintContext $context): string
	{
		return '?' . $this->type->print($context);
	}


	public function &getIterator(): \Generator
	{
		yield $this->type;
	}
}
