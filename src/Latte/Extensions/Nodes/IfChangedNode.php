<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Extensions\Nodes;

use Latte\Compiler\Compiler;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\ExpressionNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\NopNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\TagInfo;


/**
 * {ifchanged [$var]} ... {else}
 */
class IfChangedNode extends StatementNode
{
	public ?ExpressionNode $condition = null;
	public Node $then;
	public ?Node $else = null;


	/** @return \Generator<int, ?array, array{FragmentNode, ?TagInfo}, self> */
	public static function parse(TagInfo $tag): \Generator
	{
		$tag->validate(null);

		$node = new self;
		$node->condition = $tag->args === '' ? null : $tag->tokenizer;
		if ($tag->empty) {
			$node->then = new NopNode;
			return $node;
		}

		[$node->then, $nextTag] = yield ['else'];
		if ($nextTag?->name === 'else') {
			$nextTag->validate(false);
			[$node->else] = yield;
		}

		return $node;
	}


	public function compile(Compiler $compiler): string
	{
		return $this->condition
			? $this->compileExpression($compiler)
			: $this->compileCapturing($compiler);
	}


	private function compileExpression(Compiler $compiler): string
	{
		return $this->else
			? $compiler->write(
				<<<'XX'
					if (($ʟ_loc[%var] ?? null) !== ($ʟ_tmp = [%raw])) {
						$ʟ_loc[%0.var] = $ʟ_tmp;
						%raw
					} else %line {
						%raw
					}


					XX,
				$compiler->generateId(),
				$this->condition->compile($compiler),
				$this->then->compile($compiler),
				$this->else->line,
				$this->else->compile($compiler),
			)
			: $compiler->write(
				<<<'XX'
					if (($ʟ_loc[%var] ?? null) !== ($ʟ_tmp = [%raw])) {
						$ʟ_loc[%0.var] = $ʟ_tmp;
						%2.raw
					}


					XX,
				$compiler->generateId(),
				$this->condition->compile($compiler),
				$this->then->compile($compiler),
			);
	}


	private function compileCapturing(Compiler $compiler): string
	{
		return $this->else
			? $compiler->write(
				<<<'XX'
					ob_start(fn() => '');
					try %line {
						%raw
					} finally { $ʟ_tmp = ob_get_clean(); }
					if (($ʟ_loc[%var] ?? null) !== $ʟ_tmp) {
						echo $ʟ_loc[%2.var] = $ʟ_tmp;
					} else %line {
						%raw
					}


					XX,
				$this->line,
				$this->then->compile($compiler),
				$compiler->generateId(),
				$this->else->line,
				$this->else->compile($compiler),
			)
			: $compiler->write(
				<<<'XX'
					ob_start(fn() => '');
					try %line {
						%raw
					} finally { $ʟ_tmp = ob_get_clean(); }
					if (($ʟ_loc[%var] ?? null) !== $ʟ_tmp) {
						echo $ʟ_loc[%2.var] = $ʟ_tmp;
					}


					XX,
				$this->line,
				$this->then->compile($compiler),
				$compiler->generateId(),
			);
	}


	public function &getIterator(): \Generator
	{
		if ($this->condition) {
			yield $this->condition;
		}
		yield $this->then;
		if ($this->else) {
			yield $this->else;
		}
	}
}
