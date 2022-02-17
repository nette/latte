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
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\Parser;
use Latte\Compiler\TagInfo;
use Latte\Helpers;


/**
 * {= ...}
 */
class PrintNode extends StatementNode
{
	public Node $expression;
	public bool $replaced = true;


	public static function parse(TagInfo $tag, Parser $parser): self
	{
		$stream = $parser->getStream();
		if (
			$tag->isInText()
			&& $parser->getContentType() === Compiler::CONTENT_HTML
			&& $tag->htmlElement?->startTag->getName() === 'script'
			&& ($token = $stream->peek(-2))
			&& preg_match('#["\']$#D', $token->text)
		) {
			throw new CompileException("Do not place {$stream->peek(-1)->text} inside quotes in JavaScript.");
		}

		if (Helpers::removeFilter($tag->modifiers, 'noescape')) {
			$parser->checkFilterIsAllowed('noescape');
		} else {
			$tag->modifiers .= '|escape';
		}
		$tag->validate(true, [], true);
		$node = new self;
		$node->expression = $tag->tokenizer;
		return $node;
	}


	public function compile(Compiler $compiler): string
	{
		return $compiler->write(
			"echo %raw %line;\n",
			$this->expression->compile($compiler),
			$this->line,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->expression;
	}
}
