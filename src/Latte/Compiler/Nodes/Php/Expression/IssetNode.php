<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expression;

use Latte\CompileException;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class IssetNode extends ExpressionNode
{
	public function __construct(
		/** @var ExpressionNode[] */
		public array $vars,
		public ?Position $position = null,
	) {
		$this->validate();
	}


	public function print(PrintContext $context): string
	{
		$this->validate();
		return 'isset(' . $context->implode($this->vars) . ')';
	}


	public function validate(): void
	{
		foreach ($this->vars as $var) {
			if (!$var instanceof ExpressionNode) {
				throw new \TypeError('Variable must be ExpressionNode, ' . get_debug_type($var) . ' given.');
			} elseif (!$var->isVariable()) {
				throw new CompileException('Cannot use isset() on expression: ' . $var->print(new PrintContext), $var->position);
			}
		}
	}


	public function &getIterator(): \Generator
	{
		foreach ($this->vars as &$item) {
			yield $item;
		}
	}
}
