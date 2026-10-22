<?php declare(strict_types=1);

// Interpolated strings

use Latte\Compiler\Nodes\Php\Expression\{FunctionCallNode, PropertyFetchNode, VariableNode};
use Latte\Compiler\Nodes\Php\{IdentifierNode, InterpolatedStringPartNode, NameNode};
use Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode;
use Latte\Compiler\PrintContext;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$parts = [];
$parts[] = new PropertyFetchNode(new VariableNode('foo'), new IdentifierNode('bar'));
$parts[] = new InterpolatedStringPartNode(' ');
$node = new InterpolatedStringNode($parts);

Assert::same(
	'"{$foo->bar} "',
	$node->print(new PrintContext),
);

$parts[] = new PropertyFetchNode(new FunctionCallNode(new NameNode('foo')), new IdentifierNode('bar'));
$node = new InterpolatedStringNode($parts);

Assert::same(
	'("{$foo->bar} " . (foo()->bar) . "")',
	$node->print(new PrintContext),
);
