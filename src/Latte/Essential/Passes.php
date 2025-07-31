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
use function array_combine, array_keys, array_map, in_array, is_string, str_starts_with, strtolower;


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
		(new NodeTraverser)->traverse($node, function (Node $node) {
			if ($node instanceof VariableNode
				&& is_string($node->name)
				&& (preg_match('/ʟ_|GLOBALS$|this$/A', $node->name))
			) {
				if ($node->name === 'this' && !$this->engine->isStrictParsing()) {
					trigger_error("Using the \$$node->name variable in the template is deprecated ($node->position)", E_USER_DEPRECATED);
					return;
				}
				throw new CompileException("Forbidden variable \$$node->name.", $node->position);
			}
		});
	}
}
