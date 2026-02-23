<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler\Nodes\Php;


/**
 * Interface for expression nodes that represent operators with precedence.
 */
interface OperatorNode
{
	/** Operator associativity or position */
	public const
		AssocLeft = -1,
		AssocNone = 0,
		AssocRight = 1;

	/**
	 * Returns [precedence, associativity] for this operator.
	 * @return array{int, self::AssocLeft|self::AssocNone|self::AssocRight}
	 */
	public function getOperatorPrecedence(): array;
}
