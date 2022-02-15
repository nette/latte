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
use Latte\Compiler\Nodes\TextNode;
use Latte\Compiler\Parser;
use Latte\Compiler\TagInfo;
use Latte\Helpers;


/**
 * {_ ...}
 */
class TranslateNode extends StatementNode
{
	public ?Node $content = null;
	public ?ExpressionNode $expression = null;


	/** @return \Generator<int, ?array, array{FragmentNode, ?TagInfo}, self> */
	public static function parse(TagInfo $tag, Parser $parser): \Generator
	{
		$tag->extractModifier();
		if (Helpers::removeFilter($tag->modifiers, 'noescape')) {
			$parser->checkFilterIsAllowed('noescape');
		} else {
			$tag->modifiers .= '|escape';
		}
		$node = new self;
		$node->expression = $tag->tokenizer;
		if ($tag->args === '') {
			if ($tag->empty) {
				return new NopNode;
			}
			[$node->content] = yield;
		} else {
			$node->replaced = true;
		}
		return $node;
	}


	public function compile(Compiler $compiler): string
	{
		return $this->content
			? $this->compileCapturing($compiler)
			: $this->compileExpression($compiler);
	}


	private function compileExpression(Compiler $compiler): string
	{
		return $compiler->write(
			'echo %modify(($this->filters->translate)(%args)) %line;',
			$this->expression->modifier,
			$this->expression,
			$this->line,
		);
	}


	private function compileCapturing(Compiler $compiler)
	{
		if (
			$this->content instanceof FragmentNode
			&& count($this->content->children) === 1
			&& $this->content->children[0] instanceof TextNode
		) {
			return $compiler->write(
				<<<'XX'
					$ʟ_fi = new LR\FilterInfo(%var);
					echo %modifyContent($this->filters->filterContent('translate', $ʟ_fi, %var)) %line;
					XX,
				$this->expression->modifier,
				implode('', $compiler->getContext()),
				$this->content->children[0]->content,
				$this->line,
			);

		} else {
			return $compiler->write(
				<<<'XX'
					ob_start(fn() => ''); try {
						%raw
					} finally {
						$ʟ_tmp = ob_get_clean();
					}
					$ʟ_fi = new LR\FilterInfo(%var);
					echo %modifyContent($this->filters->filterContent('translate', $ʟ_fi, $ʟ_tmp)) %line;
					XX,
				$this->expression->modifier,
				$this->content->compile($compiler),
				implode('', $compiler->getContext()),
				$this->line,
			);
		}
	}


	public function &getIterator(): \Generator
	{
		if ($this->content) {
			yield $this->content;
		}
		if ($this->expression) {
			yield $this->expression;
		}
	}
}
