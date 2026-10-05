<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Sandbox\Nodes;

use Latte\Compiler\{Node, PrintContext};
use Latte\Compiler\Nodes\Php\{IdentifierNode, NameNode};
use Latte\Compiler\Nodes\Php\Scalar\StringNode;


/**
 * Member access whose name the sandbox checks before the access itself.
 * @internal
 */
trait MemberAccess
{
	/**
	 * Prints the name for the check and for the access, so that a dynamic name is evaluated once.
	 * @return array{string, string}
	 */
	private function printName(PrintContext $context, Node $name): array
	{
		if ($name instanceof IdentifierNode || $name instanceof NameNode) {
			return [$context->encodeString((string) $name), (string) $name];
		} elseif ($name instanceof StringNode) {
			return [$name->print($context), '{' . $name->print($context) . '}'];
		}

		$var = '$ʟ_name' . $context->generateId();
		return [$context->format("$var = %node", $name), '{' . $var . '}'];
	}


	/**
	 * Short-circuiting of a nullsafe chain ends at the check call, so the links after it must be nullsafe too.
	 */
	private function printOperator(): string
	{
		return $this->hasNullsafe() ? '?->' : '->';
	}
}
