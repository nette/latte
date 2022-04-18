<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php;

use Latte\CompileException;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\Php;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class FilterNode extends Node
{
	public const
		Escape = 'escape',
		NoEscape = 'noescape';


	public function __construct(
		public IdentifierNode $name,
		/** @var Php\ArgumentNode[] */
		public array $args = [],
		public ?Position $position = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		throw new \LogicException('Cannot directly print FilterNode');
	}


	public function printSimple(PrintContext $context, string $expr): string
	{
		if ($this->name->name === self::Escape) {
			return $context->escape($expr);
		} elseif ($this->name->name === self::NoEscape) {
			throw new CompileException('Filter |noescape is not expected at this place', $this->position);
		}

		return '($this->filters->' . $this->name . ')('
			. $expr
			. ($this->args ? ', ' . $context->implode($this->args) : '')
			. ')';
	}


	public function printContentAware(PrintContext $context, string $expr): string
	{
		if ($this->name->name === self::Escape) {
			return 'LR\Filters::convertTo($ʟ_fi, '
				. var_export(implode('', $context->getEscapingContext()), true) . ', '
				. $expr
				. ')';
		} elseif ($this->name->name === self::NoEscape) {
			throw new CompileException('Filter |noescape is not expected at this place', $this->position);
		}

		return '$this->filters->filterContent('
			. $context->encodeString($this->name->name)
			. ', $ʟ_fi, '
			. $expr
			. ($this->args ? ', ' . $context->implode($this->args) : '')
			. ')';
	}


	public function &getIterator(): \Generator
	{
		yield $this->name;
		foreach ($this->args as &$item) {
			yield $item;
		}
	}
}
