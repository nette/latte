<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\ExpressionNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {ifchanged [$var]} ... {else}
 */
class IfChangedNode extends StatementNode
{
	public ?ExpressionNode $condition = null;
	public AreaNode $then;
	public ?AreaNode $else = null;
	public ?Position $elseLine = null;


	/** @return \Generator<int, ?array, array{AreaNode, ?Tag}, static> */
	public static function create(Tag $tag): \Generator
	{
		$node = new static;
		$node->condition = $tag->parser->parseExpression();

		[$node->then, $nextTag] = yield ['else'];
		if ($nextTag?->name === 'else') {
			$nextTag->expectArguments(false);
			$node->elseLine = $nextTag->position;
			[$node->else] = yield;
		}

		return $node;
	}


	public function print(PrintContext $context): string
	{
		return $this->condition
			? $this->printExpression($context)
			: $this->printCapturing($context);
	}


	private function printExpression(PrintContext $context): string
	{
		return $this->else
			? $context->format(
				<<<'XX'
					if (($ʟ_loc[%dump] ?? null) !== ($ʟ_tmp = [%node])) {
						$ʟ_loc[%0.dump] = $ʟ_tmp;
						%node
					} else %line {
						%node
					}


					XX,
				$context->generateId(),
				$this->condition,
				$this->then,
				$this->elseLine,
				$this->else,
			)
			: $context->format(
				<<<'XX'
					if (($ʟ_loc[%dump] ?? null) !== ($ʟ_tmp = [%node])) {
						$ʟ_loc[%0.dump] = $ʟ_tmp;
						%2.node
					}


					XX,
				$context->generateId(),
				$this->condition,
				$this->then,
			);
	}


	private function printCapturing(PrintContext $context): string
	{
		return $this->else
			? $context->format(
				<<<'XX'
					ob_start(fn() => '');
					try %line {
						%node
					} finally { $ʟ_tmp = ob_get_clean(); }
					if (($ʟ_loc[%dump] ?? null) !== $ʟ_tmp) {
						echo $ʟ_loc[%2.dump] = $ʟ_tmp;
					} else %line {
						%node
					}


					XX,
				$this->position,
				$this->then,
				$context->generateId(),
				$this->elseLine,
				$this->else,
			)
			: $context->format(
				<<<'XX'
					ob_start(fn() => '');
					try %line {
						%node
					} finally { $ʟ_tmp = ob_get_clean(); }
					if (($ʟ_loc[%dump] ?? null) !== $ʟ_tmp) {
						echo $ʟ_loc[%2.dump] = $ʟ_tmp;
					}


					XX,
				$this->position,
				$this->then,
				$context->generateId(),
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
