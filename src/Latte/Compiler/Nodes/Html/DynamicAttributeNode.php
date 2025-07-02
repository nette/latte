<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Html;

use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;
use Latte\ContentType;


class DynamicAttributeNode extends AreaNode
{
	public function __construct(
		public string $name,
		public ExpressionNode $value,
		public ?Position $position = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		return $context->format(
			'echo LR\%raw::formatAttribute(%dump, %node) %line;',
			$context->getEscaper()->getContentType() === ContentType::Html ? 'HtmlHelpers' : 'XmlHelpers',
			$this->name,
			$this->value,
			$this->position,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->value;
	}
}
