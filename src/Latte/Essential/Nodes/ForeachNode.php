<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\ExpressionNode;
use Latte\Compiler\Nodes\NopNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Helpers;


/**
 * {foreach $expr as $key => $value} & {else}
 */
class ForeachNode extends StatementNode
{
	public ExpressionNode $args;
	public AreaNode $content;
	public ?AreaNode $else = null;
	public ?bool $iterator = null;
	public bool $checkArgs = true;


	/** @return \Generator<int, ?array, array{AreaNode, ?Tag}, static> */
	public static function create(Tag $tag): \Generator
	{
		$tag->extractModifier();
		$tag->expectArguments();

		$node = new static;
		$node->args = $tag->parser->parseExpression();
		$tag->data->iterateWhile = $tag->args;

		$modifier = $tag->parser->modifiers;
		$node->checkArgs = !Helpers::removeFilter($modifier, 'nocheck');
		$noIterator = Helpers::removeFilter($modifier, 'noiterator');
		if ($modifier) {
			throw new CompileException('Only modifiers |noiterator and |nocheck are allowed here.', $tag->position);
		} elseif ($tag->void) {
			$node->content = new NopNode;
			return $node;
		}

		$node->iterator = $noIterator ? false : null;
		[$node->content, $nextTag] = yield ['else'];
		if ($nextTag?->name === 'else') {
			$nextTag->expectArguments(false);
			[$node->else] = yield;
		}

		return $node;
	}


	public function print(PrintContext $context): string
	{
		$args = $this->args->print($context);
		$content = $this->content->print($context);
		$iterator = $this->else || ($this->iterator ?? preg_match('#\$iterator\W|\Wget_defined_vars\W#', $content));
		$content .= '$iterations++;';

		if ($this->else) {
			$content .= $context->format(
				'} if ($iterator->isEmpty()) %line { ',
				$this->else->position,
			) . $this->else->print($context);
		}

		if ($iterator) {
			$args = preg_replace('#(.*)\s+as\s+#i', '$1, $ʟ_it ?? null) as ', $args, 1);
			return $context->format(
				<<<'XX'
					$iterations = 0;
					foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator(%raw) %line {
						%raw
					}
					$iterator = $ʟ_it = $ʟ_it->getParent();


					XX,
				$args,
				$this->position,
				$content,
			);

		} else {
			return $context->format(
				<<<'XX'
					$iterations = 0;
					foreach (%raw) %line {
						%raw
					}


					XX,
				$args,
				$this->position,
				$content,
			);
		}
	}


	public function &getIterator(): \Generator
	{
		yield $this->args;
		yield $this->content;
		if ($this->else) {
			yield $this->else;
		}
	}
}
