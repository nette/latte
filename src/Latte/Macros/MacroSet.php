<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Macros;

use Latte;
use Latte\MacroNode;


/**
 * Base IMacro implementation. Allows add multiple macros.
 */
class MacroSet extends Latte\Object implements Latte\IMacro
{
	/** @var Latte\Compiler */
	private $compiler;

	/** @var array */
	private $macros;


	public function __construct(Latte\Compiler $compiler)
	{
		$this->compiler = $compiler;
	}


	public function addMacro($name, $begin, $end = NULL, $attr = NULL)
	{
		if (!$begin && !$end && !$attr) {
			throw new \InvalidArgumentException("At least one argument must be specified for macro '$name'.");
		}
		foreach (array($begin, $end, $attr) as $arg) {
			if ($arg && !is_string($arg)) {
				Latte\Helpers::checkCallback($arg);
			}
		}

		$this->macros[$name] = array($begin, $end, $attr);
		$this->compiler->addMacro($name, $this);
		return $this;
	}


	/**
	 * Initializes before template parsing.
	 * @return void
	 */
	public function initialize()
	{
	}


	/**
	 * Finishes template parsing.
	 * @return array(prolog, epilog)
	 */
	public function finalize()
	{
	}


	/**
	 * New node is found.
	 * @return bool
	 */
	public function nodeOpened(MacroNode $node)
	{
		list($begin, $end, $attr) = $this->macros[$node->name];
		$node->isEmpty = !$end;

		if ($node->modifiers
			&& (!$begin || (is_string($begin) && strpos($begin, '%modify') === FALSE))
			&& (!$end || (is_string($end) && strpos($end, '%modify') === FALSE))
			&& (!$attr || (is_string($attr) && strpos($attr, '%modify') === FALSE))
		) {
			trigger_error("Modifiers are not allowed in {{$node->name}}", E_USER_WARNING);
		}

		if ($node->args
			&& (!$begin || (is_string($begin) && strpos($begin, '%node') === FALSE))
			&& (!$end || (is_string($end) && strpos($end, '%node') === FALSE))
			&& (!$attr || (is_string($attr) && strpos($attr, '%node') === FALSE))
		) {
			trigger_error("Arguments are not allowed in {{$node->name}}", E_USER_WARNING);
		}

		if ($attr && $node->prefix === $node::PREFIX_NONE) {
			$node->isEmpty = TRUE;
			$this->compiler->setContext(Latte\Compiler::CONTEXT_DOUBLE_QUOTED_ATTR);
			$res = $this->compile($node, $attr);
			if ($res === FALSE) {
				return FALSE;
			} elseif (!$node->attrCode) {
				$node->attrCode = "<?php $res ?>";
			}
			$this->compiler->setContext(NULL);

		} elseif ($begin) {
			$res = $this->compile($node, $begin);
			if ($res === FALSE || ($node->isEmpty && $node->prefix)) {
				return FALSE;
			} elseif (!$node->openingCode) {
				$node->openingCode = "<?php $res ?>";
			}

		} elseif (!$end) {
			return FALSE;
		}
	}


	/**
	 * Node is closed.
	 * @return void
	 */
	public function nodeClosed(MacroNode $node)
	{
		if (isset($this->macros[$node->name][1])) {
			$res = $this->compile($node, $this->macros[$node->name][1]);
			if (!$node->closingCode) {
				$node->closingCode = "<?php $res ?>";
			}
		}
	}


	/**
	 * Generates code.
	 * @return string
	 */
	private function compile(MacroNode $node, $def)
	{
		$node->tokenizer->reset();
		$writer = Latte\PhpWriter::using($node, $this->compiler);
		if (is_string($def)) {
			return $writer->write($def);
		} else {
			return call_user_func($def, $node, $writer);
		}
	}


	/**
	 * @return Latte\Compiler
	 */
	public function getCompiler()
	{
		return $this->compiler;
	}

}
