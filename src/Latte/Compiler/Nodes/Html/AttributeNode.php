<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Html;

use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class AttributeNode extends AreaNode
{
	public function __construct(
		public string $name,
		public string $text,
		public ?AreaNode $value = null,
		public ?string $quote = null,
		public ?Position $position = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		$res = 'echo ' . var_export($this->text, true) . ';';
		$context->restoreEscape();
		$context->beginEscape()->enterHtmlAttribute($this->name, $this->quote);
		if ($this->value) {
			$res .= $this->value->print($context);
		}
		return $res;
	}


	public function &getIterator(): \Generator
	{
		if ($this->value) {
			yield $this->value;
		}
	}
}
