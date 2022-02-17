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
use Latte\Compiler\TagInfo;


/**
 * {parameters [type] $var, ...}
 */
class ParametersNode extends StatementNode
{
	public bool $allowedInHead = true;
	public array $parameters = [];


	public static function parse(TagInfo $tag): self
	{
		if (!$tag->isInHead()) {
			throw new CompileException('{parameters} is allowed only in template header.');
		}
		if ($tag->modifiers) {
			$tag->setArgs($tag->args . $tag->modifiers);
			$tag->modifiers = '';
		}
		$tag->validate(true);
		$node = new self;
		$node->parameters = self::parseParameters($tag->tokenizer);
		return $node;
	}


	private static function parseParameters(MacroTokens $tokens): array
	{
		$params = [];
		while ($tokens->isNext(...$tokens::SIGNIFICANT)) {
			if ($tokens->nextValue($tokens::T_SYMBOL, '?', 'null', '\\')) { // type
				$tokens->nextAll($tokens::T_SYMBOL, '\\', '|', '[', ']', 'null');
			}

			$param = $tokens->consumeValue($tokens::T_VARIABLE);
			$default = $tokens->nextValue('=')
				? $tokens->joinUntilSameDepth(',')
				: 'null';
			$params[] = sprintf(
				'%s = $this->params[%s] ?? $this->params[%s] ?? %s;',
				$param,
				count($params),
				var_export(substr($param, 1), true),
				$default,
			);

			if ($tokens->isNext(...$tokens::SIGNIFICANT)) {
				$tokens->consumeValue(',');
			}
		}

		return $params;
	}


	public function compile(Compiler $compiler): string
	{
		$compiler->paramsExtraction = implode('', $this->parameters);
		return '';
	}
}
