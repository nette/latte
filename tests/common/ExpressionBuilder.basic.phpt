<?php

declare(strict_types=1);

use Latte\Compiler\ExpressionBuilder;
use Latte\Compiler\Nodes\Php\ArgumentNode;
use Latte\Compiler\Nodes\Php\Expression\FunctionCallNode;
use Latte\Compiler\Nodes\Php\Expression\MethodCallNode;
use Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode;
use Latte\Compiler\Nodes\Php\Expression\StaticCallNode;
use Latte\Compiler\Nodes\Php\Expression\VariableNode;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\Nodes\Php\NameNode;
use Latte\Compiler\Nodes\Php\Scalar\BooleanNode;
use Latte\Compiler\Nodes\Php\Scalar\IntegerNode;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

// variable()
Assert::equal(
	new VariableNode('var'),
	ExpressionBuilder::variable('$var')->build(),
);

Assert::equal(
	new VariableNode('var'),
	ExpressionBuilder::variable('var')->build(),
);

// class()
Assert::equal(
	new NameNode('Foo'),
	ExpressionBuilder::class('Foo')->build(),
);

// function()
Assert::equal(
	new FunctionCallNode(new NameNode('foo')),
	ExpressionBuilder::function('foo')->build(),
);

Assert::equal(
	new FunctionCallNode(new VariableNode('var')),
	ExpressionBuilder::function(new VariableNode('var'))->build(),
);

Assert::equal(
	new FunctionCallNode(new VariableNode('var')),
	ExpressionBuilder::function(ExpressionBuilder::variable('$var'))->build(),
);

Assert::equal(
	new FunctionCallNode(new NameNode('foo'), [new ArgumentNode(new BooleanNode(true)), new ArgumentNode(new IntegerNode(123))]),
	ExpressionBuilder::function('foo', [true, 123])->build(),
);

Assert::equal(
	new FunctionCallNode(new NameNode('foo'), [new ArgumentNode(new BooleanNode(true)), new ArgumentNode(new IntegerNode(123))]),
	ExpressionBuilder::function('foo', [new BooleanNode(true), new ArgumentNode(new IntegerNode(123))])->build(),
);

// property() & immutability
$var = ExpressionBuilder::variable('$this');
Assert::equal(
	new PropertyFetchNode(new VariableNode('this'), new IdentifierNode('foo')),
	$var->property('foo')->build(),
);

Assert::equal(
	new PropertyFetchNode(new PropertyFetchNode(new VariableNode('this'), new IdentifierNode('foo')), new IdentifierNode('bar')),
	$var->property('foo')->property('bar')->build(),
);

// method() & immutability
$var = ExpressionBuilder::variable('$this');
Assert::equal(
	new MethodCallNode(new VariableNode('this'), new IdentifierNode('foo')),
	$var->method('foo')->build(),
);

Assert::equal(
	new PropertyFetchNode(new MethodCallNode(new VariableNode('this'), new IdentifierNode('foo'), [new ArgumentNode(new IntegerNode(123))]), new IdentifierNode('bar')),
	$var->method('foo', [123])->property('bar')->build(),
);

// staticMethod() & immutability
$var = ExpressionBuilder::variable('$this');
Assert::equal(
	new StaticCallNode(new VariableNode('this'), new IdentifierNode('foo')),
	$var->staticMethod('foo')->build(),
);

Assert::equal(
	new PropertyFetchNode(new StaticCallNode(new VariableNode('this'), new IdentifierNode('foo'), [new ArgumentNode(new IntegerNode(123))]), new IdentifierNode('bar')),
	$var->staticMethod('foo', [123])->property('bar')->build(),
);

Assert::equal(
	new StaticCallNode(new NameNode('Foo'), new IdentifierNode('foo')),
	ExpressionBuilder::class('Foo')->staticMethod('foo')->build(),
);
