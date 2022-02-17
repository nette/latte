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
 * {templatePrint [ClassName]}
 */
class TemplatePrintNode extends StatementNode
{
	public bool $allowedInHead = true;
	public ?string $template;


	public static function parse(TagInfo $tag): self
	{
		$node = new self;
		$node->template = $tag->tokenizer->fetchWord() ?: null;
		return $node;
	}


	public function compile(Compiler $compiler): string
	{
		$compiler->addPrepare('(new Latte\Extensions\Blueprint)->printClass($this, ' . var_export($this->template, true) . '); exit;');
		return '';
	}
}
