<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Essential;

use Latte\{CompileException, ContentType, Engine, Feature};
use Latte\Compiler\{Node, NodeTraverser};
use Latte\Compiler\Nodes\Html\{ElementNode, ExpressionAttributeNode};
use Latte\Compiler\Nodes\{Php, PrintNode, TemplateNode, TextNode};
use Latte\Compiler\Nodes\Php\Expression;
use Latte\Compiler\Nodes\Php\Expression\VariableNode;
use Latte\Runtime\HtmlHelpers;
use function is_string;
use const PHP_VERSION_ID;


/**
 * Built-in compiler passes.
 */
final readonly class Passes
{
	public function __construct(
		private Engine $engine,
	) {
	}


	/**
	 * Replaces calls to registered functions with CustomFunctionCallNode wrappers.
	 */
	public function customFunctionsPass(TemplateNode $node): void
	{
		$functions = $this->engine->getFunctions();
		(new NodeTraverser)->traverse($node, function (Node $node) use ($functions) {
			if (($node instanceof Expression\FunctionCallNode)
				&& $node->name instanceof Php\NameNode
				&& isset($functions[$node->name->name])
			) {
				if ($node->isPartialApplication() && PHP_VERSION_ID < 80600) {
					throw new CompileException(
						"Partial application of custom function '{$node->name->name}' requires PHP 8.6.",
						$node->position,
					);
				}

				return new Nodes\CustomFunctionCallNode($node->name, $node->args, $node->position);
			}
		});
	}


	/**
	 * Forbids use of internal Latte variables ($ʟ_xxx), $GLOBALS, and $this in templates.
	 */
	public function forbiddenVariablesPass(TemplateNode $node): void
	{
		(new NodeTraverser)->traverse($node, function (Node $node) {
			if ($node instanceof VariableNode
				&& is_string($node->name)
				&& (preg_match('/ʟ_|__|GLOBALS$|this$/A', $node->name))
			) {
				if (preg_match('/__|this$/A', $node->name) && !$this->engine->hasFeature(Feature::StrictParsing)) {
					trigger_error("Using the \$$node->name variable in the template is deprecated ($node->position)", E_USER_DEPRECATED);
					return;
				}
				throw new CompileException("Forbidden variable \$$node->name.", $node->position);
			}
		});
	}


	/**
	 * Detects print statements immediately followed by quotes inside <script> and throws an error.
	 */
	public function scriptTagQuotesPass(TemplateNode $node): void
	{
		if ($node->contentType !== ContentType::Html) {
			return;
		}
		(new NodeTraverser)->traverse($node, function (Node $node) {
			if ($node instanceof ElementNode && $node->is('script')) {
				$type = $node->getAttribute('type');
				if ((is_string($type) || $type === null)
					&& HtmlHelpers::classifyScriptType((string) $type) === ContentType::JavaScript
				) {
					$prev = null;
					foreach ($node->content ?? [] as $child) {
						if ($prev instanceof PrintNode && $child instanceof TextNode) {
							if (preg_match('/^["\']/', $child->content)) {
								throw new CompileException(
									'Do not place print statement {...} inside quotes in JavaScript.',
									$prev->position,
								);
							}
						}
						$prev = $child;
					}
				}
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
			if ($node instanceof ElementNode) {
				$elem = $node;

			} elseif ($node instanceof ExpressionAttributeNode
				&& $elem && HtmlHelpers::isUrlAttribute($elem->name, $node->name)
				&& !$node->modifier->removeFilter('nocheck') && !$node->modifier->removeFilter('noCheck')
				&& !$node->modifier->hasFilter('datastream') && !$node->modifier->hasFilter('dataStream')
			) {
				$node->modifier->filters[] = new Php\FilterNode(new Php\IdentifierNode('checkUrl'), nullsafe: true); // null drops the attribute
			}
		});
	}
}
