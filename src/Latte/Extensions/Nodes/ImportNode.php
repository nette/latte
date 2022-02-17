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
 * {import "file"}
 */
class ImportNode extends StatementNode
{
	public bool $allowedInHead = true;
	public string $file;
	public bool $global = false;


	public static function parse(TagInfo $tag): self
	{
		$tag->validate(true);
		$node = new self;
		$node->file = $tag->tokenizer->fetchWord();
		$node->global = $tag->isInHead();
		$tag->checkExtraArgs();
		return $node;
	}


	public function compile(Compiler $compiler): string
	{
		$code = $compiler->write(
			'$this->createTemplate(%word, $this->params, "import")->render() %line;',
			$this->file,
			$this->line,
		);
		if ($this->global) {
			$compiler->addPrepare($code);
			return '';
		}
		return $code;
	}
}
