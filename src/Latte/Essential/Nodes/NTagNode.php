<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use function preg_match;


/**
 * n:tag="..."
 */
final class NTagNode extends StatementNode
{
	public static function create(Tag $tag): void
	{
		if (preg_match('(style$|script$)iA', $tag->htmlElement->name)) {
			throw new CompileException('Attribute n:tag is not allowed in <script> or <style>', $tag->position);
		}

		$tag->expectArguments();
		$tag->htmlElement->variableName = $tag->parser->parseExpression();
	}


	public function print(PrintContext $context): string
	{
		throw new \LogicException('Cannot directly print');
	}


	public function &getIterator(): \Generator
	{
		false && yield;
	}
}
