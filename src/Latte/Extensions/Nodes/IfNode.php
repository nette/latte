<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Extensions\Nodes;

use Latte\CompileException;
use Latte\Compiler\Compiler;
use Latte\Compiler\MacroTokens;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\ExpressionNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\NopNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\Parser;
use Latte\Compiler\TagInfo;


/**
 * {if $cond} & {elseif $cond} & {else}
 * {if} & {/if $cond}
 * {ifset $var} & {elseifset $var}
 * {ifset block} & {elseifset block}
 */
class IfNode extends StatementNode
{
	public ExpressionNode $condition;
	public Node $then;
	public ?Node $else = null;
	public bool $capture = false;
	public bool $ifset = false;


	/** @return \Generator<int, ?array, array{FragmentNode, ?TagInfo}, self> */
	public static function parse(TagInfo $tag, Parser $parser): \Generator
	{
		$tag->validate(null);

		$node = new self;
		$node->condition = $tag->tokenizer;
		$node->line = $tag->line;
		$node->ifset = in_array($tag->name, ['ifset', 'elseifset'], true);
		$node->capture = $tag->args === '';
		if ($tag->empty) {
			$node->then = new NopNode;
			return $node;
		}

		[$node->then, $nextTag] = yield $node->capture ? ['else'] : ['else', 'elseif', 'elseifset'];

		if ($nextTag?->name === 'else') {
			if ($nextTag->args !== '' && str_starts_with($nextTag->args, 'if')) {
				throw new CompileException('Arguments are not allowed in {else}, did you mean {elseif}?');
			}
			$nextTag->validate(false);
			[$node->else, $nextTag] = yield;

		} elseif ($nextTag?->name === 'elseif' || $nextTag?->name === 'elseifset') {
			$nextTag->validate('condition');
			$node->else = yield from self::parse($nextTag, $parser);
		}

		if ($node->capture) {
			$nextTag->validate(true);
			$node->condition = $nextTag->tokenizer;
		}

		return $node;
	}


	public function compile(Compiler $compiler): string
	{
		return $this->capture
			? $this->compileCapturing($compiler)
			: $this->compileCommon($compiler);
	}


	private function compileCommon(Compiler $compiler): string
	{
		if ($this->else) {
			return $compiler->write(
				($this->else instanceof self
					? "if (%raw) %line { %raw } else%raw\n"
					: "if (%raw) %line { %raw } else %4.line { %3.raw }\n"),
				$this->buildConds($compiler),
				$this->condition->line,
				$this->then->compile($compiler),
				$this->else->compile($compiler),
				$this->else->line,
			);
		}
		return $compiler->write(
			"if (%raw) %line { %raw }\n",
			$this->buildConds($compiler),
			$this->condition->line,
			$this->then->compile($compiler),
		);
	}


	private function compileCapturing(Compiler $compiler): string
	{
		if ($this->else) {
			return $compiler->write(
				<<<'XX'
					ob_start(fn() => '') %line;
					try {
						%raw
						ob_start(fn() => '') %line;
						try {
							%raw
						} finally {
							$ʟ_ifB = ob_get_clean();
						}
					} finally {
						$ʟ_ifA = ob_get_clean();
					}
					echo (%raw) ? $ʟ_ifA : $ʟ_ifB %0.line;


					XX,
				$this->condition->line,
				$this->then->compile($compiler),
				$this->else->line,
				$this->else->compile($compiler),
				$this->buildConds($compiler),
			);
		}

		return $compiler->write(
			<<<'XX'
				ob_start(fn() => '') %line;
				try {
					%raw
				} finally {
					$ʟ_ifA = ob_get_clean();
				}
				if (%raw) %0.line { echo $ʟ_ifA; }

				XX,
			$this->condition->line,
			$this->then->compile($compiler),
			$this->buildConds($compiler),
		);
	}


	private function buildConds(Compiler $compiler): string
	{
		if ($this->ifset) {
			while ([$name, $block] = $this->condition->fetchWordWithModifier('block')) {
				$list[] = $block || preg_match('~#|\w[\w-]*$~DA', $name)
					? $compiler->write('$this->hasBlock(%word)', ltrim($name, '#'))
					: $compiler->write('isset(%args)', new MacroTokens($name));
			}

			return implode(' && ', $list);
		} else {
			return $this->condition->compile($compiler);
		}
	}


	public function &getIterator(): \Generator
	{
		yield $this->condition;
		yield $this->then;
		if ($this->else) {
			yield $this->else;
		}
	}
}
