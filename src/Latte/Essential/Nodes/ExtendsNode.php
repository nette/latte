<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Nodes\Php\Expression\ArrayNode;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\Scalar\BooleanNode;
use Latte\Compiler\Nodes\Php\Scalar\NullNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {extends 'parent.latte' [, args]}
 * {layout 'layout.latte' [, args]}
 */
class ExtendsNode extends StatementNode
{
	public ExpressionNode $extends;
	public ArrayNode $args;


	public static function create(Tag $tag): static
	{
		$tag->expectArguments();
		$node = new static;
		if (!$tag->isInHead()) {
			throw new CompileException("{{$tag->name}} must be placed in template head.", $tag->position);
		} elseif ($tag->parser->stream->tryConsume('auto')) {
			$node->extends = new NullNode;
		} elseif ($tag->parser->stream->tryConsume('none')) {
			$node->extends = new BooleanNode(false);
		} else {
			$node->extends = $tag->parser->parseUnquotedStringOrExpression();
		}
		$tag->parser->consumeCommaBeforeArguments();
		$node->args = $tag->parser->parseArguments();
		if ($node->args->items && $node->extends instanceof BooleanNode) {
			throw new CompileException("{{$tag->name} none} cannot have arguments.", $tag->position);
		}
		return $node;
	}


	public function print(PrintContext $context): string
	{
		return $context->format('$this->parentName = %node;', $this->extends)
			. ($this->args->items ? $context->format('$this->parentArgs = %node;', $this->args) : '');
	}


	public function &getIterator(): \Generator
	{
		yield $this->extends;
		yield $this->args;
	}
}
