<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Extensions\Nodes;

use Latte\CompileException;
use Latte\Compiler\Compiler;
use Latte\Compiler\MacroTokens;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PhpWriter;
use Latte\Compiler\TagInfo;


/**
 * {var [type] $var = value, ...}
 * {default [type] $var = value, ...}
 */
class VarNode extends StatementNode
{
	public bool $default;
	public MacroTokens $expression;


	public static function parse(TagInfo $tag): self
	{
		if ($tag->modifiers) {
			$tag->setArgs($tag->args . $tag->modifiers);
			$tag->modifiers = '';
		}
		$tag->validate(true);

		$node = new self;
		$node->default = $tag->name === 'default';

		$var = true;
		$hasType = false;
		$tokens = $tag->tokenizer;
		$node->expression = $res = new MacroTokens;

		while ($tokens->nextToken()) {
			if ($var && !$hasType && $tokens->isCurrent($tokens::T_SYMBOL, '?', 'null', '\\')) { // type
				$tokens->nextToken();
				$tokens->nextAll($tokens::T_SYMBOL, '\\', '|', '[', ']', 'null');
				$hasType = true;

			} elseif ($var && $tokens->isCurrent($tokens::T_VARIABLE)) {
				if ($node->default) {
					$res->append("'" . ltrim($tokens->currentValue(), '$') . "'");
				} else {
					$res->append('$' . ltrim($tokens->currentValue(), '$'));
				}

				$var = null;

			} elseif ($var === null && $tokens->isCurrent('=')) {
				$res->append($node->default ? '=>' : '=');
				$var = false;

			} elseif (!$var && $tokens->isCurrent(',') && $tokens->depth === 0) {
				if ($var === null) {
					$res->append($node->default ? '=>null' : '=null');
				}

				$res->append($node->default ? ',' : ';');
				$var = true;
				$hasType = false;

			} elseif ($var === null && $node->default && !$tokens->isCurrent($tokens::T_WHITESPACE)) {
				throw new CompileException("Unexpected '{$tokens->currentValue()}' in {default $tag->args}");

			} else {
				$res->append($tokens->currentValue());
			}
		}

		if ($var === null) {
			$res->append($node->default ? '=>null' : '=null');
		} elseif ($var === true) {
			throw new CompileException("Unexpected end in {{$tag->name} {$tag->args}}");
		}

		return $node;
	}


	public function compile(Compiler $compiler): string
	{
		$writer = PhpWriter::using($compiler);
		$res = $writer->preprocess($this->expression);
		$out = $writer->quotingPass($res)->joinAll();
		return $compiler->write(
			$this->default
				? 'extract([%raw], EXTR_SKIP) %line;'
				: '%raw %line;',
			$out,
			$this->line,
		);
	}
}
