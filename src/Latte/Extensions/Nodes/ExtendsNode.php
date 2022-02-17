<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Extensions\Nodes;

use Latte\CompileException;
use Latte\Compiler\Compiler;
use Latte\Compiler\Nodes\ExpressionNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\TagInfo;


/**
 * {extends none | $var | "file"}
 * {layout none | $var | "file"}
 */
class ExtendsNode extends StatementNode
{
	public bool $allowedInHead = true;
	public ?ExpressionNode $extends;


	public static function parse(TagInfo $tag): self
	{
		$tag->validate(true);
		$node = new self;
		if (!$tag->isInHead()) {
			throw new CompileException("{{$tag->name}} must be placed in template head.");
		} elseif (isset($tag->data->extends)) {
			throw new CompileException("Multiple {{$tag->name}} declarations are not allowed.");
		} elseif ($tag->args === 'none') {
			$node->extends = null;
		} else {
			$node->extends = $tag->tokenizer;
		}
		$tag->data->extends = true;
		return $node;
	}


	public function compile(Compiler $compiler): string
	{
		$compiler->addPrepare($this->extends
			? $compiler->write(
				'$this->parentName = %word%args;',
				$this->extends->fetchWord(),
				$this->extends,
			)
			: '$this->parentName = false;');
		return '';
	}
}
