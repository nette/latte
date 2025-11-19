<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential;

use Latte\CompileException;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\Html\ElementNode;
use Latte\Compiler\Nodes\Html\ExpressionAttributeNode;
use Latte\Compiler\Nodes\Php;
use Latte\Compiler\Nodes\Php\Expression;
use Latte\Compiler\Nodes\Php\Expression\VariableNode;
use Latte\Compiler\Nodes\PrintNode;
use Latte\Compiler\Nodes\TemplateNode;
use Latte\Compiler\Nodes\TextNode;
use Latte\Compiler\NodeTraverser;
use Latte\Compiler\PrintContext;
use Latte\ContentType;
use Latte\Engine;
use Latte\Runtime\HtmlHelpers;
use function is_string;


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
		(new NodeTraverser)->traverse($node, function (Node $node) use ($functions) {
			if (($node instanceof Expression\FunctionCallNode || $node instanceof Expression\FunctionCallableNode)
				&& $node->name instanceof Php\NameNode
				&& isset($functions[$node->name->name])
			) {
				return new Expression\AuxiliaryNode(
					fn(PrintContext $context, ...$args) => '($this->global->fn->' . $node->name . ')($this, ' . $context->implode($args) . ')',
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
				&& (preg_match('/ʟ_|__|GLOBALS$|this$/A', $node->name))
			) {
				if (preg_match('/__|this$/A', $node->name) && !$this->engine->isStrictParsing()) {
					trigger_error("Using the \$$node->name variable in the template is deprecated ($node->position)", E_USER_DEPRECATED);
					return;
				}
				throw new CompileException("Forbidden variable \$$node->name.", $node->position);
			}
		});
	}


	/**
	 * Validate PrintNode inside <script> - prevent quotes after PrintNode
	 */
	public function scriptTagQuotesPass(TemplateNode $node): void
	{
		if ($node->contentType !== ContentType::Html) {
			return;
		}
		(new NodeTraverser)->traverse($node, function (Node $node) {
			if ($node instanceof ElementNode && $node->is('script')
				&& HtmlHelpers::classifyScriptType((string) $node->getAttribute('type')) === ContentType::JavaScript
			) {
				$prev = null;
				foreach ($node->content ?? [] as $child) {
					if ($prev instanceof PrintNode && $child instanceof TextNode) {
						if (preg_match('/^["\']/', $child->content)) {
							throw new CompileException('Do not place print statement {...} inside quotes in JavaScript.', $prev->position);
						}
					}
					$prev = $child;
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
				&& HtmlHelpers::isUrlAttribute($elem->name, $node->name)
				&& !$node->modifier->removeFilter('nocheck') && !$node->modifier->removeFilter('noCheck')
				&& !$node->modifier->hasFilter('datastream') && !$node->modifier->hasFilter('dataStream')
			) {
				$node->modifier->filters[] = new Php\FilterNode(new Php\IdentifierNode('checkUrl'));
			}
		});
	}
}
