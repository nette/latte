<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Extensions\Nodes;

use Latte\Compiler\Compiler;
use Latte\Compiler\Nodes\ExpressionNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\TagInfo;


/**
 * {sandbox "file" [,] [params]}
 */
class SandboxNode extends StatementNode
{
	public ExpressionNode $file;


	public static function parse(TagInfo $tag): self
	{
		$tag->validate(true);
		$node = new self;
		$node->file = $tag->tokenizer;
		return $node;
	}


	public function compile(Compiler $compiler): string
	{
		return $compiler->write(
			<<<'XX'
				ob_start(fn() => '');
				try {
					$this->createTemplate(%word, %array, 'sandbox')->renderToContentType(%var) %line;
					echo ob_get_clean();
				} catch (\Throwable $ʟ_e) {
					if (isset($this->global->coreExceptionHandler)) {
						ob_end_clean();
						($this->global->coreExceptionHandler)($ʟ_e, $this);
					} else {
						echo ob_get_clean();
						throw $ʟ_e;
					}
				}


				XX,
			$this->file->fetchWord(),
			$this->file,
			implode('', $compiler->getContext()),
			$this->line,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->file;
	}
}
