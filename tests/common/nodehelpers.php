<?php

declare(strict_types=1);

use Latte\Compiler\Node;
use Latte\Compiler\PrintContext;


class LeafNode extends Node
{
	public function print(PrintContext $context): string
	{
		return '';
	}


	public function &getIterator(): Generator
	{
		false && yield;
	}
}


class ArrayNode extends Node
{
	public function __construct(
		public array $items,
	) {
	}


	public function print(PrintContext $context): string
	{
		return '';
	}


	public function &getIterator(): Generator
	{
		foreach ($this->items as &$item) {
			yield $item;
		}
	}
}


class ParentNode extends Node
{
	public function __construct(
		public Node $child,
	) {
	}


	public function print(PrintContext $context): string
	{
		return '';
	}


	public function &getIterator(): Generator
	{
		yield $this->child;
	}
}


class TracingVisitor
{
	public $trace = [];


	public function enter(Node $node)
	{
		$this->trace[] = ['enter', $node];
	}


	public function leave(Node $node)
	{
		$this->trace[] = ['leave', $node];
	}
}


class ModifyingVisitor
{
	private array $returns;
	private int $pos = 0;


	public function __construct(array $returns = [])
	{
		$this->returns = $returns;
	}


	public function enter(Node $node)
	{
		return $this->res('enter', $node);
	}


	public function leave(Node $node)
	{
		return $this->res('leave', $node);
	}


	private function res(string $method, $param)
	{
		if ($this->pos >= count($this->returns)) {
			return null;
		}
		$currentReturn = $this->returns[$this->pos];
		if ($currentReturn[0] === $method && $currentReturn[1] === $param) {
			$this->pos++;
			return $currentReturn[2];
		}
	}


	public function __destruct()
	{
		if ($this->pos !== count($this->returns)) {
			throw new Exception('Expected event did not occur');
		}
	}
}
