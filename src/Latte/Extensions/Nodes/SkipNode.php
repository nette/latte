<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Extensions\Nodes;

use Latte\CompileException;
use Latte\Compiler\Compiler;
use Latte\Compiler\Nodes\ExpressionNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\TagInfo;


/**
 * {breakIf ...}
 * {continueIf ...}
 * {skipIf ...}
 */
class SkipNode extends StatementNode
{
	public string $type;
	public ExpressionNode $expression;
	public ?string $endTag = null;


	public static function parse(TagInfo $tag): self
	{
		$tag->validate('condition');
		if (!$tag->closest($tag->name === 'skipIf' ? ['foreach'] : ['for', 'foreach', 'while'])) {
			throw new CompileException("Tag {{$tag->name}} is unexpected here.");
		}

		$node = new self;
		$node->type = $tag->name;
		$node->expression = $tag->tokenizer;
		if (isset($tag->htmlElement->nAttrs['foreach'])) {
			$node->endTag = $tag->htmlElement->startTag->getName();
		}
		return $node;
	}


	public function compile(Compiler $compiler): string
	{
		$cmd = $this->type === 'skipIf'
			? '{ $iterator->skipRound(); continue; }'
			: str_replace('If', '', $this->type) . ';';

		if ($this->endTag) {
			$cmd = "{ echo \"</$this->endTag>\\n\"; $cmd; } ";
		}

		return $compiler->write(
			"if (%raw) %line %raw\n",
			$this->expression->compile($compiler),
			$this->line,
			$cmd,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->expression;
	}
}
