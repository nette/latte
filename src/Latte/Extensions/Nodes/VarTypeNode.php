<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Extensions\Nodes;

use Latte\CompileException;
use Latte\Compiler\Compiler;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\TagInfo;


/**
 * {varType type $var}
 */
class VarTypeNode extends StatementNode
{
	public bool $allowedInHead = true;


	public static function parse(TagInfo $tag): self
	{
		if ($tag->modifiers) {
			$tag->setArgs($tag->args . $tag->modifiers);
			$tag->modifiers = '';
		}
		$tag->validate(true);

		$type = trim($tag->tokenizer->joinUntil($tag->tokenizer::T_VARIABLE));
		$variable = $tag->tokenizer->nextValue($tag->tokenizer::T_VARIABLE);
		if (!$type || !$variable) {
			throw new CompileException('Unexpected content, expecting {varType type $var}.');
		}

		return new self;
	}


	public function compile(Compiler $compiler): string
	{
		return '';
	}
}
