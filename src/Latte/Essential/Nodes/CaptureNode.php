<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\LegacyExprNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Context;


/**
 * {capture $variable}
 */
class CaptureNode extends StatementNode
{
	public LegacyExprNode $variable;
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
		$node->variable = $tag->getArgs();
		$node->modifier = $tag->modifiers;
		[$node->content] = yield;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		$escapingContext = implode('', $context->getEscapingContext());
		return $context->format(
			<<<'XX'
				ob_start(fn() => '') %line;
				try {
					%raw
				} finally {
					$ʟ_tmp = %raw;
				}
				$ʟ_fi = new LR\FilterInfo(%dump); %args = %modifyContent($ʟ_tmp);


				XX,
			$this->modifier,
			$this->position,
			$this->content,
			$escapingContext === Context::Html
				? 'ob_get_length() ? new LR\Html(ob_get_clean()) : ob_get_clean()'
				: 'ob_get_clean()',
			$escapingContext,
			$this->variable,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->variable;
		yield $this->content;
	}
}
