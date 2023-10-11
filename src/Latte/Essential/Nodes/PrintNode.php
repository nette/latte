<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\ModifierNode;
use Latte\Compiler\Nodes\StatementNode;
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
	private ?string $followsQuote = null;


	public static function create(Tag $tag, TemplateParser $parser): static
	{
		$tag->outputMode = $tag::OutputKeepIndentation;
		$tag->expectArguments();
		$node = new static;
		$node->followsQuote = preg_match('#["\']#A', $parser->getStream()->peek()->text)
			? $tag->getNotation(true)
			: null;
		$node->expression = $tag->parser->parseExpression();
		$node->modifier = $tag->parser->parseModifier();
		$node->modifier->escape = true;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		if ($this->followsQuote && $context->getEscaper()->export() === 'html/js') {
			throw new CompileException("Do not place {$this->followsQuote} inside quotes in JavaScript.", $this->position);
		}
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
