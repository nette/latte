<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Macros;

use Latte;
use Latte\CompileException;
use Latte\Compiler\Compiler;
use Latte\Compiler\Tag;


/**
 * Base Macro implementation. Allows add multiple macros.
 */
class MacroSet extends Latte\Extension
{
	use Latte\Strict;

	private Compiler $compiler;

	/** @var array<string, array{string|callable|null, string|callable|null, string|callable|null}> */
	private array $macros;


	public function __construct(Compiler $compiler = null)
	{
		if ($compiler) {
			$this->compiler = $compiler;
		}
	}


	public function addMacro(
		string $name,
		string|callable|null $begin,
		string|callable|null $end = null,
		string|callable|null $attr = null,
		?int $flags = null,
	): self {
		if (!$begin && !$end && !$attr) {
			throw new \InvalidArgumentException("At least one argument must be specified for tag '$name'.");
		}

		foreach ([$begin, $end, $attr] as $arg) {
			if ($arg && !is_string($arg)) {
				Latte\Helpers::checkCallback($arg);
			}
		}

		$this->macros[$name] = [$begin, $end, $attr];
		$this->compiler->addMacro($name, $this, $flags);
		return $this;
	}


	public function finalize()
	{
		return null;
	}


	/**
	 * New node is found.
	 * @return bool|null
	 */
	public function nodeOpened(Tag $node)
	{
		[$begin, $end, $attr] = $this->macros[$node->name];
		$node->empty = !$end;

		if (
			$node->modifiers
			&& (!$begin || (is_string($begin) && !str_contains($begin, '%modify')))
			&& (!$end || (is_string($end) && !str_contains($end, '%modify')))
			&& (!$attr || (is_string($attr) && !str_contains($attr, '%modify')))
		) {
			throw new CompileException('Filters are not allowed in ' . $node->getNotation());
		}

		if (
			$node->args !== ''
			&& (!$begin || (is_string($begin) && !str_contains($begin, '%node')))
			&& (!$end || (is_string($end) && !str_contains($end, '%node')))
			&& (!$attr || (is_string($attr) && !str_contains($attr, '%node')))
		) {
			throw new CompileException('Arguments are not allowed in ' . $node->getNotation());
		}

		if ($attr && $node->prefix === $node::PREFIX_NONE) {
			$node->empty = true;
			$node->context[1] = Compiler::CONTEXT_HTML_ATTRIBUTE;
			$res = $this->compile($node, $attr);
			if ($res === false) {
				return false;
			} elseif (!$node->attrCode) {
				$node->attrCode = "<?php $res ?>";
			}

			$node->context[1] = Compiler::CONTEXT_HTML_TEXT;

		} elseif ($node->empty && $node->prefix) {
			return false;

		} elseif ($begin) {
			$res = $this->compile($node, $begin);
			if ($res === false) {
				return false;
			} elseif (!$node->openingCode && is_string($res) && $res !== '') {
				$node->openingCode = "<?php $res ?>";
			}
		} elseif (!$end) {
			return false;
		}

		return null;
	}


	/**
	 * Node is closed.
	 * @return void
	 */
	public function nodeClosed(Tag $node)
	{
		if (isset($this->macros[$node->name][1])) {
			$res = $this->compile($node, $this->macros[$node->name][1]);
			if (!$node->closingCode && is_string($res) && $res !== '') {
				$node->closingCode = "<?php $res ?>";
			}
		}
	}


	/**
	 * Generates code.
	 */
	private function compile(Tag $node, string|callable $def): string|bool|null
	{
		$node->tokenizer->reset();
		$writer = Latte\Compiler\PhpWriter::using($node, $this->compiler);
		return is_string($def)
			? $writer->write($def)
			: $def($node, $writer);
	}


	public function getCompiler(): Compiler
	{
		return $this->compiler;
	}


	/** @internal */
	protected function checkExtraArgs(Tag $node): void
	{
		if ($node->tokenizer->isNext(...$node->tokenizer::SIGNIFICANT)) {
			$args = Latte\Essential\Filters::truncate($node->tokenizer->joinAll(), 20);
			throw new CompileException("Unexpected arguments '$args' in " . $node->getNotation());
		}
	}
}
