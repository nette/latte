<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Essential\Nodes;

use Latte;
use Latte\Compiler\Escaper;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\ContentType;
use function in_array;


/**
 * {spaceless} ... {/spaceless}
 * Removes whitespace between HTML tags.
 */
class SpacelessNode extends StatementNode
{
	public AreaNode $content;


	/** @return \Generator<int, ?list<string>, array{AreaNode, ?Tag}, static> */
	public static function create(Tag $tag): \Generator
	{
		$node = $tag->node = new static;
		[$node->content] = yield;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		$escaper = $context->getEscaper();
		$allowed = [Escaper::HtmlText, Escaper::Text, Escaper::JavaScript, Escaper::Css, Escaper::ICal, ContentType::Xml];
		if (!in_array($escaper->getState(), $allowed, strict: true)) {
			throw new Latte\CompileException('{spaceless} cannot be used in this context.', $this->position);
		}

		return $context->format(
			<<<'XX'
				Latte\Essential\WhitespaceMinifier::start(%dump) %line;
				try {
					%node
				} finally {
					Latte\Essential\WhitespaceMinifier::end();
				}


				XX,
			$escaper->getContentType(),
			$this->position,
			$this->content,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->content;
	}
}
