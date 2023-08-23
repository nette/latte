<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expression;

use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\NameNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class ConstantFetchNode extends ExpressionNode
{
	public function __construct(
		public NameNode $name,
		public ?Position $position = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		if ($this->name->kind === NameNode::KindNormal) {
			return match ((string) $this->name) {
				'__LINE__' => (string) $this->position->line,
				'__FILE__' => '(is_file(self::Source) ? self::Source : null)',
				'__DIR__' => '(is_file(self::Source) ? dirname(self::Source) : null)',
				default => $this->name->print($context),
			};
		}
		return $this->name->print($context);
	}


	public function &getIterator(): \Generator
	{
		yield $this->name;
	}
}
