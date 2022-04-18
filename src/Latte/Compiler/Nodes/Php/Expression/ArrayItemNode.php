<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expression;

use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\Nodes\Php\Scalar\StringNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class ArrayItemNode extends ExpressionNode
{
	public function __construct(
		public ExpressionNode $value,
		public ExpressionNode|IdentifierNode|null $key = null,
		public bool $byRef = false,
		public ?Position $position = null,
		public bool $unpack = false,
	) {
	}


	public function print(PrintContext $context): string
	{
		$key = match (true) {
			$this->key instanceof ExpressionNode => $this->key->print($context) . ' => ',
			$this->key instanceof IdentifierNode => $context->encodeString($this->key->name) . ' => ',
			$this->key === null => '',
		};
		return $key
			. ($this->byRef ? '&' : '')
			. ($this->unpack ? '...' : '')
			. $this->value->print($context);
	}


	public function printAsArgument(PrintContext $context): string
	{
		$key = match (true) {
			$this->key instanceof StringNode => $this->key->value . ': ',
			$this->key instanceof IdentifierNode => $this->key . ': ',
			default => '',
		};
		return $key
			. ($this->byRef ? '&' : '')
			. ($this->unpack ? '...' : '')
			. $this->value->print($context);
	}


	public function &getIterator(): \Generator
	{
		if ($this->key) {
			yield $this->key;
		}
		yield $this->value;
	}
}
