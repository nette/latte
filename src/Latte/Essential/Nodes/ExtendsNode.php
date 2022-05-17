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
 * {extends none | "file"}
 * {layout none | "file"}
 */
class ExtendsNode extends StatementNode
{
	public ExpressionNode $extends;


	public static function create(Tag $tag): static
	{
		$tag->expectArguments();
		$node = new static;
		if (!$tag->isInHead()) {
			throw new CompileException("{{$tag->name}} must be placed in template head.", $tag->position);
		} elseif (isset($tag->data->extends)) {
			throw new CompileException("Multiple {{$tag->name}} declarations are not allowed.", $tag->position);
		} elseif ($tag->args === 'none') {
			$node->extends = new ExpressionNode('false');
		} else {
			$node->extends = new ExpressionNode($tag->parser->fetchWord());
		}
		$tag->data->extends = true;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		$context->initialization .= $context->format('$this->parentName = %word;', $this->extends);
		return '';
	}


	public function &getIterator(): \Generator
	{
		yield $this->extends;
	}
}
