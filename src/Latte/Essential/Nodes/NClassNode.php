<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Nodes\ExpressionNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * n:class="..."
 */
final class NClassNode extends StatementNode
{
	public ExpressionNode $args;


	public static function create(Tag $tag): static
	{
		if ($tag->htmlElement->getAttribute('class')) {
			throw new CompileException('It is not possible to combine class with n:class.', $tag->position);
		}

		$tag->expectArguments();
		$node = new static;
		$node->args = $tag->parser->parseExpression();
		return $node;
	}


	public function print(PrintContext $context): string
	{
		return $context->format(
			'echo ($ʟ_tmp = array_filter(%array)) ? \' class="\' . LR\Filters::escapeHtmlAttr(implode(" ", array_unique($ʟ_tmp))) . \'"\' : "" %line;',
			$this->args,
			$this->position,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->args;
	}
}
