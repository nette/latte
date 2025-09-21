<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes;

use Latte;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\ModifierNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;


/**
 * {= ...}
 */
class PrintNode extends StatementNode
{
	public ExpressionNode $expression;
	public ModifierNode $modifier;


	public static function create(Tag $tag, TemplateParser $parser): static
	{
		$tag->outputMode = $tag::OutputKeepIndentation;
		$tag->expectArguments();
		$node = new static;
		$node->expression = $tag->parser->parseExpression();
		$node->modifier = $tag->parser->parseModifier();
		$node->modifier->escape = true;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		return $context->format(
			"echo %modify(%node) %line;\n",
			$this->modifier,
			$this->expression,
			$this->position,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->expression;
		yield $this->modifier;
	}
}


class_alias(PrintNode::class, Latte\Essential\Nodes\PrintNode::class);
