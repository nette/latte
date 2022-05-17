<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PhpHelpers;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {templatePrint [ClassName]}
 */
class TemplatePrintNode extends StatementNode
{
	public ?string $template;


	public static function create(Tag $tag): static
	{
		$node = new static;
		$node->template = $tag->parser->fetchWord() ?: null;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		$context->initialization .= '(new Latte\Essential\Blueprint)->printClass($this, ' . PhpHelpers::dump($this->template) . '); exit;';
		return '';
	}
}
