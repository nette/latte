<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Tools;

use Latte;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\Php;
use Latte\Compiler\Nodes\Php\Expression;
use Latte\Compiler\Nodes\TemplateNode;
use Latte\Compiler\NodeTraverser;
use function defined;


/**
 * Linter extension for validating filters, functions, classes, methods, constants and more.
 */
final class LinterExtension extends Latte\Extension
{
	private ?Latte\Engine $engine = null;


	public function beforeCompile(Latte\Engine $engine): void
	{
		$this->engine = $engine;
	}


	public function getPasses(): array
	{
		return [
			'linter' => $this->linterPass(...),
		];
	}


	private function linterPass(TemplateNode $node): void
	{
		(new NodeTraverser)->traverse($node, function (Node $node) {
			if ($node instanceof Php\FilterNode) {
				$this->validateFilter($node);

			} elseif ($node instanceof Expression\FunctionCallNode && $node->name instanceof Php\NameNode) {
				$this->validateFunction($node);

			} elseif ($node instanceof Expression\NewNode && $node->class instanceof Php\NameNode) {
				$this->validateClass($node);

			} elseif ($node instanceof Expression\StaticMethodCallNode
				&& $node->class instanceof Php\NameNode
				&& $node->name instanceof Php\IdentifierNode
			) {
				$this->validateStaticMethod($node);

			} elseif ($node instanceof Expression\ClassConstantFetchNode
				&& $node->class instanceof Php\NameNode
				&& $node->name instanceof Php\IdentifierNode
			) {
				$this->validateClassConstant($node);

			} elseif ($node instanceof Expression\ConstantFetchNode) {
				$this->validateConstant($node);

			} elseif ($node instanceof Expression\InstanceofNode && $node->class instanceof Php\NameNode) {
				$this->validateInstanceof($node);

			} elseif ($node instanceof Expression\StaticPropertyFetchNode
				&& $node->class instanceof Php\NameNode
				&& $node->name instanceof Php\VarLikeIdentifierNode
			) {
				$this->validateStaticProperty($node);
			}
		});
	}


	private function validateFilter(Php\FilterNode $node): void
	{
		$name = $node->name->name;
		$filters = $this->engine->getFilters();
		if (!isset($filters[$name])) {
			trigger_error("Unknown filter |$name $node->position", E_USER_WARNING);
		}
	}


	private function validateFunction(Expression\FunctionCallNode $node): void
	{
		$name = (string) $node->name;
		if (!function_exists($name)) {
			trigger_error("Unknown function $name() $node->position", E_USER_WARNING);
		}
	}


	private function validateClass(Expression\NewNode $node): void
	{
		$className = (string) $node->class;
		if (!class_exists($className) && !interface_exists($className)) {
			trigger_error("Unknown class $className $node->position", E_USER_WARNING);
		}
	}


	private function validateStaticMethod(Expression\StaticMethodCallNode $node): void
	{
		$className = (string) $node->class;
		$methodName = $node->name->name;
		if (!method_exists($className, $methodName)) {
			trigger_error("Unknown method $className::$methodName() $node->position", E_USER_WARNING);
		}
	}


	private function validateClassConstant(Expression\ClassConstantFetchNode $node): void
	{
		$name = "{$node->class}::{$node->name->name}";
		if (!defined($name)) {
			trigger_error("Unknown class constant $name $node->position", E_USER_WARNING);
		}
	}


	private function validateConstant(Expression\ConstantFetchNode $node): void
	{
		$magic = ['__LINE__' => 1, '__FILE__' => 1, '__DIR__' => 1];
		$name = (string) $node->name;
		if (!defined($name) && !isset($magic[$name])) {
			trigger_error("Unknown constant $name $node->position", E_USER_WARNING);
		}
	}


	private function validateInstanceof(Expression\InstanceofNode $node): void
	{
		$className = (string) $node->class;
		if (!class_exists($className) && !interface_exists($className)) {
			trigger_error("Unknown class $className in instanceof $node->position", E_USER_WARNING);
		}
	}


	private function validateStaticProperty(Expression\StaticPropertyFetchNode $node): void
	{
		$className = (string) $node->class;
		$propertyName = $node->name->name;
		if (!property_exists($className, $propertyName)) {
			trigger_error("Unknown static property $className::\$$propertyName $node->position", E_USER_WARNING);
		}
	}
}
