<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Extensions\Nodes;

use Latte\Compiler\Compiler;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\CallableNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\Html\ElementNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\Parser;
use Latte\Compiler\TagInfo;


/**
 * n:ifcontent
 */
class IfContentNode extends StatementNode
{
	public Node $content;
	public int $id;
	public ElementNode $htmlEl;


	/** @return \Generator<int, ?array, array{FragmentNode, ?TagInfo}, self> */
	public static function parse(TagInfo $tag, Parser $parser): \Generator
	{
		$tag->validate(false);
		$node = new self;
		$node->id = $parser->generateId();
		[$node->content] = yield;
		$node->htmlEl = $tag->htmlElement;
		if (!$node->htmlEl->content) { // is empty/void
			// TODO: throw
		}
		return $node;
	}


	public function compile(Compiler $compiler): string
	{
		try {
			$saved = $this->htmlEl->content;
			$this->htmlEl->content = new CallableNode(fn() => <<<XX
				ob_start();
				try {
					{$saved->compile($compiler)}
				} finally {
					\$ʟ_ifc[$this->id] = rtrim(ob_get_flush()) === '';
				}

				XX);
			return <<<XX
				ob_start(fn() => '');
				try {
					{$this->content->compile($compiler)}
				} finally {
					if (\$ʟ_ifc[$this->id] ?? null) {
						ob_end_clean();
					} else {
						echo ob_get_clean();
					}
				}

				XX;
		} finally {
			$this->htmlEl->content = $saved;
		}
	}


	public function &getIterator(): \Generator
	{
		yield $this->content;
	}
}
