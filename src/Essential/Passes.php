<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential;

use Latte\CompileException;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\Php\Expression;
use Latte\Compiler\Nodes\Php\Expression\VariableNode;
use Latte\Compiler\Nodes\Php\NameNode;
use Latte\Compiler\Nodes\TemplateNode;
use Latte\Compiler\NodeTraverser;
use Latte\Compiler\PrintContext;
use Latte\Engine;


final class Passes
{
	public function __construct(
		private Engine $engine,
	) {
	}


	/**
	 * Enable custom functions.
	 */
	public function customFunctionsPass(TemplateNode $node): void
	{
		$functions = $this->engine->getFunctions();
		$names = array_keys($functions);
		$names = array_combine(array_map('strtolower', $names), $names);

		(new NodeTraverser)->traverse($node, function (Node $node) use ($names) {
			if (($node instanceof Expression\FunctionCallNode || $node instanceof Expression\FunctionCallableNode)
				&& $node->name instanceof NameNode
				&& ($orig = $names[strtolower((string) $node->name)] ?? null)
			) {
				if ((string) $node->name !== $orig) {
					trigger_error("Case mismatch on function name '{$node->name}', correct name is '$orig'.", E_USER_WARNING);
				}

				return new Expression\AuxiliaryNode(
					fn(PrintContext $context, ...$args) => '($this->global->fn->' . $orig . ')($this, ' . $context->implode($args) . ')',
					$node->args,
				);
			}
		});
	}


	/**
	 * $ʟ_xxx, $GLOBALS and $this are forbidden
	 */
	public function forbiddenVariablesPass(TemplateNode $node): void
	{
		$forbidden = $this->engine->isStrictParsing() ? ['GLOBALS', 'this'] : ['GLOBALS'];
		(new NodeTraverser)->traverse($node, function (Node $node) use ($forbidden) {
			if ($node instanceof VariableNode
				&& is_string($node->name)
				&& (str_starts_with($node->name, 'ʟ_') || in_array($node->name, $forbidden, true))
			) {
				throw new CompileException("Forbidden variable \$$node->name.", $node->position);
			}
		});
	}
}
