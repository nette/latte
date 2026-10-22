<?php declare(strict_types=1);

use Latte\Compiler\ExpressionBuilder;
use Latte\Compiler\Nodes\Php\{ArgumentNode, IdentifierNode, NameNode};
use Latte\Compiler\Nodes\Php\Expression\{FunctionCallNode, MethodCallNode, PropertyFetchNode, StaticMethodCallNode, VariableNode};
use Latte\Compiler\Nodes\Php\Scalar\{BooleanNode, IntegerNode};
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
	new StaticMethodCallNode(new VariableNode('this'), new IdentifierNode('foo')),
	$var->staticMethod('foo')->build(),
);

Assert::equal(
	new PropertyFetchNode(new StaticMethodCallNode(new VariableNode('this'), new IdentifierNode('foo'), [new ArgumentNode(new IntegerNode(123))]), new IdentifierNode('bar')),
	$var->staticMethod('foo', [123])->property('bar')->build(),
);

Assert::equal(
	new StaticMethodCallNode(new NameNode('Foo'), new IdentifierNode('foo')),
	ExpressionBuilder::class('Foo')->staticMethod('foo')->build(),
);
