<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler\Nodes\Php\Expression;

use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\OperatorNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;
use function count, in_array, strtolower;


/**
 * Binary operation: arithmetic, logical, bitwise, comparison, null-coalescing, or pipe.
 */
class BinaryOpNode extends ExpressionNode implements OperatorNode
{
	private const Ops = ['||', '&&', 'or', 'and', 'xor', '|', '&', '^', '.', '+', '-', '*', '/', '%', '<<', '>>', '**',
		'==', '!=', '===', '!==', '<=>', '<', '<=', '>', '>=', '??', '|>'];


	public function __construct(
		public ExpressionNode $left,
		public string $operator,
		public ExpressionNode $right,
		public ?Position $position = null,
	) {
		if (!in_array(strtolower($this->operator), self::Ops, strict: true)) {
			throw new \InvalidArgumentException("Unexpected operator '$this->operator'");
		}
	}


	/**
	 * Creates nested BinaryOp nodes from a list of expressions.
	 */
	public static function nest(string $operator, ExpressionNode ...$exprs): ExpressionNode
	{
		$count = count($exprs);
		if ($count < 2) {
			return $exprs[0];
		}

		$last = $exprs[0];
		for ($i = 1; $i < $count; $i++) {
			$last = new static($last, $operator, $exprs[$i]);
		}

		return $last;
	}


	public function print(PrintContext $context): string
	{
		return $context->parenthesize($this, $this->left, self::AssocLeft)
			. ' ' . $this->operator . ' '
			. $context->parenthesize($this, $this->right, self::AssocRight);
	}


	public function getOperatorPrecedence(): array
	{
		return match (strtolower($this->operator)) {
			'**' => [250, self::AssocRight],
			'*', '/', '%' => [210, self::AssocLeft],
			'+', '-' => [200, self::AssocLeft],
			'<<', '>>' => [190, self::AssocLeft],
			'.' => [185, self::AssocLeft],
			'|>' => [183, self::AssocLeft],
			'<', '<=', '>', '>=', '<=>' => [180, self::AssocNone],
			'==', '!=', '===', '!==' => [170, self::AssocNone],
			'&' => [160, self::AssocLeft],
			'^' => [150, self::AssocLeft],
			'|' => [140, self::AssocLeft],
			'&&' => [130, self::AssocLeft],
			'||' => [120, self::AssocLeft],
			'??' => [110, self::AssocRight],
			'and' => [50, self::AssocLeft],
			'xor' => [40, self::AssocLeft],
			'or' => [30, self::AssocLeft],
		};
	}


	public function &getIterator(): \Generator
	{
		yield $this->left;
		yield $this->right;
	}
}
