<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Html;

use Latte\Compiler\Compiler;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\CallableNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\TagInfo;


/**
 * HTML element node.
 */
class ElementNode extends Node
{
	public TagNode $startTag;
	public ?TagNode $endTag = null;
	public ?Node $content = null;
	public ?Node $startStmt = null;
	public ?Node $endStmt = null;

	/** @var TagInfo[] */
	public array $nAttrs = [];

	private static $counter = 0;


	public function __construct(
		public ?self $parent = null,
	) {
	}


	public function compile(Compiler $compiler): string
	{
		$res = ($this->startStmt ?? $this->startTag)->compile($compiler);
		$res .= $this->content?->compile($compiler);
		$res .= ($this->endStmt ?? $this->endTag)?->compile($compiler);
		return $res;
	}


	public function memoizeEndTag(): void
	{
		if ($this->startStmt) {
			return;
		}

		$var = '$ʟ_endtag[' . self::$counter++ . ']';
		$this->startStmt = new FragmentNode([
			$this->startTag,
			new CallableNode(
				fn(Compiler $compiler) => "ob_start(fn() => '');"
					. $this->endTag->compile($compiler)
					. "$var = ob_get_clean() . ($var ?? '');\n",
			),
		]);
		$this->endStmt = new CallableNode(fn() => "echo $var ?? ''; $var = null;");
	}
}
