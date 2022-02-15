<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Extensions\Nodes;

use Latte\CompileException;
use Latte\Compiler\Compiler;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\ExpressionNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\NopNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\TagInfo;
use Latte\Helpers;


/**
 * {foreach $expr as $key => $value} & {else}
 */
class ForeachNode extends StatementNode
{
	public ExpressionNode $args;
	public string $argsText; // TODO
	public Node $content;
	public ?Node $else = null;
	public ?bool $iterator = null;
	public bool $checkArgs = true;


	/** @return \Generator<int, ?array, array{FragmentNode, ?TagInfo}, self> */
	public static function parse(TagInfo $tag): \Generator
	{
		$tag->extractModifier();
		$tag->validate(true);

		$tag->data->iterateWhile = $tag->args;

		$node = new self;
		$node->args = $tag->tokenizer;
		$node->argsText = $tag->args;
		$node->checkArgs = !Helpers::removeFilter($tag->modifiers, 'nocheck');
		$noIterator = Helpers::removeFilter($tag->modifiers, 'noiterator');
		if ($tag->modifiers) {
			throw new CompileException('Only modifiers |noiterator and |nocheck are allowed here.');
		} elseif ($tag->empty) {
			$node->content = new NopNode;
			return $node;
		}

		$node->iterator = $noIterator ? false : null;
		[$node->content, $nextTag] = yield ['else'];
		if ($nextTag?->name === 'else') {
			$nextTag->validate(false);
			[$node->else] = yield;
		}

		return $node;
	}


	public function compile(Compiler $compiler): string
	{
		$args = $this->args->compile($compiler);
		$content = $this->content->compile($compiler);
		$iterator = $this->else || ($this->iterator ?? preg_match('#\$iterator\W|\Wget_defined_vars\W#', $content));
		$content .= '$iterations++;';

		if ($this->else) {
			$content .= $compiler->write(
				'} if ($iterator->isEmpty()) %line { ',
				$this->else->line,
			) . $this->else->compile($compiler);
		}

		if ($iterator) {
			$args = preg_replace('#(.*)\s+as\s+#i', '$1, $ʟ_it ?? null) as ', $args, 1);
			return $compiler->write(
				<<<'XX'
					$iterations = 0;
					foreach ($iterator = $ʟ_it = new Latte\Extensions\CachingIterator(%raw) %line {
						%raw
					}
					$iterator = $ʟ_it = $ʟ_it->getParent();


					XX,
				$args,
				$this->line,
				$content,
			);

		} else {
			return $compiler->write(
				<<<'XX'
					$iterations = 0;
					foreach (%raw) %line {
						%raw
					}


					XX,
				$args,
				$this->line,
				$content,
			);
		}
	}


	public function &getIterator(): \Generator
	{
		yield $this->content;
		if ($this->else) {
			yield $this->else;
		}
	}
}
