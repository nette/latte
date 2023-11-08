<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {breakIf ...}
 * {continueIf ...}
 * {skipIf ...}
 * {exitIf ...}
 */
class JumpNode extends StatementNode
{
	public string $type;
	public ExpressionNode $condition;


	public static function create(Tag $tag): static
	{
		$tag->expectArguments();
		$allowed = match ($tag->name) {
			'breakIf', 'continueIf' => ['for', 'foreach', 'while'],
			'skipIf' => ['foreach'],
			'exitIf' => ['block', 'define', null],
		};
		for (
			$parent = $tag->parent;
			in_array($parent?->name, ['if', 'ifset', 'ifcontent'], true);
			$parent = $parent->parent
		);
		if (!in_array($parent?->name, $allowed, true)) {
			throw new CompileException("Tag {{$tag->name}} is unexpected here.", $tag->position);
		}

		$last = $parent?->prefix === Tag::PrefixNone
			? $parent->htmlElement->parent
			: $parent?->htmlElement;
		$el = $tag->htmlElement;
		while ($el && $el !== $last) {
			$el->breakable = true;
			$el = $el->parent;
		}

		$node = new static;
		$node->type = $tag->name;
		$node->condition = $tag->parser->parseExpression();
		return $node;
	}


	public function print(PrintContext $context): string
	{
		return $context->format(
			"if (%node) %line %raw\n",
			$this->condition,
			$this->position,
			match ($this->type) {
				'breakIf' => 'break;',
				'continueIf' => 'continue;',
				'skipIf' => '{ $iterator->skipRound(); continue; }',
				'exitIf' => 'return;',
			},
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->condition;
	}
}
