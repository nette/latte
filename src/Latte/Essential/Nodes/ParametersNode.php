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
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {parameters [type] $var, ...}
 */
class ParametersNode extends StatementNode
{
	/** @var string[] */
	public array $parameters = [];


	public static function create(Tag $tag): static
	{
		if (!$tag->isInHead()) {
			throw new CompileException('{parameters} is allowed only in template header.', $tag->position);
		}
		$tag->expectArguments();
		$node = new static;
		$node->parameters = self::parseParameters($tag->parser);
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


	public function print(PrintContext $context): string
	{
		$context->paramsExtraction = $this->parameters;
		return '';
	}
}
