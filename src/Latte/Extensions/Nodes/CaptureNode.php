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
use Latte\Engine;


/**
 * {capture $variable}
 */
class CaptureNode extends StatementNode
{
	public ExpressionNode $expression;
	public Node $content;


	/** @return \Generator<int, ?array, array{FragmentNode, ?TagInfo}, self> */
	public static function parse(TagInfo $tag): \Generator
	{
		$tag->extractModifier();
		$tag->validate(true);
		if (!str_starts_with($tag->args, '$')) {
			throw new CompileException("Invalid capture block variable '$tag->args'");
		}
		$node = new self;
		$node->expression = $tag->tokenizer;
		[$node->content] = $tag->empty
			? [new NopNode]
			: yield;
		return $node;
	}


	public function compile(Compiler $compiler): string
	{
		$context = implode('', $compiler->getContext());
		$body = $context === Engine::CONTENT_HTML
			? 'ob_get_length() ? new LR\\Html(ob_get_clean()) : ob_get_clean()'
			: 'ob_get_clean()';

		return $compiler->write(
			<<<'XX'
				ob_start(fn() => '') %line;
				try {
					%raw
				} finally {
					$ʟ_tmp = %raw;
				}
				$ʟ_fi = new LR\FilterInfo(%var); %args = %modifyContent($ʟ_tmp);


				XX,
			$this->expression->modifier,
			$this->line,
			$this->content->compile($compiler),
			$body,
			$context,
			$this->expression,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->expression;
		yield $this->content;
	}
}
