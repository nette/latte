<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte;


final class VariableScope
{
	use Latte\Strict;

	/** @var string[] */
	public array $types = [];


	public function addVariable(string $name, ?string $type): string
	{
		return $this->types[$name] = $this->printComment($name, $type);
	}


	public function addExpression(Nodes\Php\ExpressionNode $expr, ?Nodes\Php\SuperiorTypeNode $type): string
	{
		return $expr instanceof Nodes\Php\Expression\VariableNode && is_string($expr->name)
			? $this->addVariable($expr->name, $type?->type)
			: '';
	}


	public static function printComment(string $name, ?string $type): string
	{
		if (!$type) {
			return '';
		}
		$str = '@var ' . $type . ' $' . $name;
		return '/** ' . str_replace('*/', '* /', $str) . ' */';
	}


	public function extractTypes(): string
	{
		return implode("\n", array_filter($this->types));
	}
}
