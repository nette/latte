<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Extensions\Nodes;

use Latte\Compiler\Compiler;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\TagInfo;


/**
 * {varPrint [all]}
 */
class VarPrintNode extends StatementNode
{
	public bool $allowedInHead = true;
	public bool $all;


	public static function parse(TagInfo $tag): self
	{
		$node = new self;
		$node->all = $tag->tokenizer->fetchWord() === 'all';
		return $node;
	}


	public function compile(Compiler $compiler): string
	{
		$vars = $this->all ? 'get_defined_vars()'
			: 'array_diff_key(get_defined_vars(), $this->getParameters())';
		return "(new Latte\\Extensions\\Blueprint)->printVars($vars); exit;";
	}
}
