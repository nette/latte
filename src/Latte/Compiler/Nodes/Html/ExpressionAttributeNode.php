<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Html;

use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\ModifierNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class ExpressionAttributeNode extends AreaNode
{
	public function __construct(
		public string $name,
		public ExpressionNode $value,
		public ModifierNode $modifier,
		public ?string $indentation = null,
		public ?Position $position = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		$escaper = $context->beginEscape();
		$escaper->enterHtmlAttribute($this->name);
		$res = $context->format(
			'echo %dump; echo %modify(%node) %line; echo \'"\';',
			$this->indentation . $this->name . '="',
			$this->modifier,
			$this->value,
			$this->value->position,
		);
		$context->restoreEscape();
		return $res;
	}


	public function &getIterator(): \Generator
	{
		yield $this->value;
		yield $this->modifier;
	}
}
