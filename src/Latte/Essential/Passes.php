<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential;

use Latte;
use Latte\CompileException;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes;
use Latte\Compiler\Nodes\Html;
use Latte\Compiler\Nodes\Php;
use Latte\Compiler\Nodes\Php\Expression;
use Latte\Compiler\Nodes\Php\Expression\VariableNode;
use Latte\Compiler\Nodes\TemplateNode;
use Latte\Compiler\NodeTraverser;
use Latte\Compiler\PrintContext;
use Latte\ContentType;
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
				&& $node->name instanceof Php\NameNode
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


	/**
	 * Validates and secures potentially dangerous URLs attributes in HTML elements.
	 */
	public function checkUrlsPass(TemplateNode $node): void
	{
		if ($node->contentType !== ContentType::Html) {
			return;
		}

		$elem = null;
		(new NodeTraverser)->traverse($node, function (Node $node) use (&$elem) {
			if ($node instanceof Html\ElementNode) {
				$elem = $node;

			} elseif ($node instanceof Html\AttributeNode) {
				if (
					$node->name instanceof Nodes\TextNode
					&& Latte\Runtime\AttributeHandler::isUrlAttribute($elem->name, $node->name->content)
				) {
					$value = $node->value instanceof Nodes\FragmentNode && $node->value->children
						? $node->value->children[0]
						: $node->value;

					(new NodeTraverser)->traverse($value, function (Node $node) {
						if ($node instanceof Nodes\PrintNode) {
							$check = true;
							foreach ($node->modifier->filters as $i => $filter) {
								match ($filter->name->name) {
									'nocheck', 'noCheck' => array_splice($node->modifier->filters, $i, 1) && $check = false,
									'datastream', 'dataStream' => $check = false,
									default => null,
								};
							}

							if ($check) {
								$node->modifier->filters[] = new Php\FilterNode(new Php\IdentifierNode('checkUrl'));
							}
						}
					});
				}
			}
		});
	}
}
