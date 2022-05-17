<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\MacroTokens;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PhpWriter;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {var [type] $var = value, ...}
 * {default [type] $var = value, ...}
 */
class VarNode extends StatementNode
{
	public bool $default;
	public MacroTokens $assignments;


	public static function create(Tag $tag): static
	{
		$tag->expectArguments();

		$node = new static;
		$node->default = $tag->name === 'default';
		$node->assignments = self::parseAssignments($tag, $node->default);
		return $node;
	}


	private static function parseAssignments(Tag $tag, bool $default): MacroTokens
	{
		$var = true;
		$hasType = false;
		$tokens = $tag->parser;
		$res = new MacroTokens;

		while ($tokens->nextToken()) {
			if ($var && !$hasType && $tokens->isCurrent($tokens::T_SYMBOL, '?', 'null', '\\')) { // type
				$tokens->nextToken();
				$tokens->nextAll($tokens::T_SYMBOL, '\\', '|', '[', ']', 'null');
				$hasType = true;

			} elseif ($var && $tokens->isCurrent($tokens::T_VARIABLE)) {
				if ($default) {
					$res->append("'" . ltrim($tokens->currentValue(), '$') . "'");
				} else {
					$res->append('$' . ltrim($tokens->currentValue(), '$'));
				}

				$var = null;

			} elseif ($var === null && $tokens->isCurrent('=')) {
				$res->append($default ? '=>' : '=');
				$var = false;

			} elseif (!$var && $tokens->isCurrent(',') && $tokens->depth === 0) {
				if ($var === null) {
					$res->append($default ? '=>null' : '=null');
				}

				$res->append($default ? ',' : ';');
				$var = true;
				$hasType = false;

			} elseif ($var === null && $default && !$tokens->isCurrent($tokens::T_WHITESPACE)) {
				throw new CompileException("Unexpected '{$tokens->currentValue()}' in {default $tag->args}", $tag->position);

			} else {
				$res->append($tokens->currentValue());
			}
		}

		if ($var === null) {
			$res->append($default ? '=>null' : '=null');
		} elseif ($var === true) {
			throw new CompileException("Unexpected end in {{$tag->name} {$tag->args}}", $tag->position);
		}

		return $res;
	}


	public function print(PrintContext $context): string
	{
		$writer = PhpWriter::using($context);
		$res = $writer->preprocess($this->assignments);
		$out = $writer->quotingPass($res)->joinAll();
		return $context->format(
			$this->default
				? 'extract([%raw], EXTR_SKIP) %line;'
				: '%raw %line;',
			$out,
			$this->position,
		);
	}
}
