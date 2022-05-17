<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Escaper;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\ExpressionNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {capture $variable}
 */
class CaptureNode extends StatementNode
{
	public ExpressionNode $variable;
	public string $modifier;
	public AreaNode $content;


	/** @return \Generator<int, ?array, array{AreaNode, ?Tag}, static> */
	public static function create(Tag $tag): \Generator
	{
		$tag->extractModifier();
		$tag->expectArguments();
		if (!str_starts_with($tag->args, '$')) {
			throw new CompileException("Invalid capture block variable '$tag->args'", $tag->position);
		}
		$node = new static;
		$node->variable = $tag->parser->parseExpression();
		$node->modifier = $tag->parser->modifiers;
		[$node->content] = yield;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		$escaper = $context->getEscaper();
		return $context->format(
			<<<'XX'
				ob_start(fn() => '') %line;
				try {
					%node
				} finally {
					$ʟ_tmp = %raw;
				}
				$ʟ_fi = new LR\FilterInfo(%dump); %args = %modifyContent($ʟ_tmp);


				XX,
			$this->position,
			$this->content,
			$escaper->getState() === Escaper::HtmlText
				? 'ob_get_length() ? new LR\Html(ob_get_clean()) : ob_get_clean()'
				: 'ob_get_clean()',
			$escaper->export(),
			$this->variable,
			$this->modifier,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->variable;
		yield $this->content;
	}
}
