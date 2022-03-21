<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Sandbox;

use Latte;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\Php;
use Latte\Compiler\Nodes\Php\Expression;
use Latte\Compiler\Nodes\TemplateNode;
use Latte\Compiler\NodeTraverser;
use Latte\SecurityViolationException;


/**
 * Security protection for the sandbox.
 */
final class SandboxExtension extends Latte\Extension
{
	private ?Latte\Policy $policy;


	public function beforeCompile(Latte\Engine $engine): void
	{
		$this->policy = $engine->getPolicy(effective: true);
	}


	public function getTags(): array
	{
		return [
			'sandbox' => [Nodes\SandboxNode::class, 'create'],
		];
	}


	public function getPasses(): array
	{
		return $this->policy
			? [-10 => [$this, 'processPass']]
			: [];
	}


	public function beforeRender(Latte\Engine $engine): void
	{
		if ($policy = $engine->getPolicy()) {
			$engine->addProvider('sandbox', new RuntimeChecker($policy));
		}
	}


	public function processPass(TemplateNode $node): void
	{
		(new NodeTraverser)->traverse($node, leave: \Closure::fromCallable([$this, 'sandboxVisitor']));
	}


	private function sandboxVisitor(Node $node): Node
	{
		if ($node instanceof Expression\VariableNode) {
			if ($node->name === 'this') {
				throw new SecurityViolationException("Forbidden variable \${$node->name}.");
			} elseif (!is_string($node->name)) {
				throw new SecurityViolationException('Forbidden variable variables.');
			}
			return $node;

		} elseif ($node instanceof Expression\NewNode) {
			throw new SecurityViolationException("Forbidden keyword 'new'");

		} elseif ($node instanceof Expression\FunctionCallNode && $node->name instanceof Php\NameNode) {
			if (!$this->policy->isFunctionAllowed((string) $node->name)) {
				throw new SecurityViolationException("Function $node->name() is not allowed.");
			}
			return $node;

		} elseif ($node instanceof Php\FilterNode) {
			$name = (string) $node->name;
			if (!$this->policy->isFilterAllowed($name)) {
				throw new SecurityViolationException("Filter |$name is not allowed.");
			}
			return $node;

		} elseif ($node instanceof Expression\PropertyFetchNode
			|| $node instanceof Expression\StaticPropertyFetchNode
			|| $node instanceof Expression\NullsafePropertyFetchNode
			|| $node instanceof Expression\UndefinedsafePropertyFetchNode
			|| $node instanceof Expression\FunctionCallNode
			|| $node instanceof Expression\MethodCallNode
			|| $node instanceof Expression\StaticCallNode
			|| $node instanceof Expression\NullsafeMethodCallNode
			|| $node instanceof Expression\UndefinedsafeMethodCallNode
		) {
			$class = namespace\Nodes::class . strrchr($node::class, '\\');
			return new $class($node);

		} else {
			return $node;
		}
	}
}
