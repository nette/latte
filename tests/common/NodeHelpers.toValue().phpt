<?php

declare(strict_types=1);

use Latte\Compiler\NodeHelpers;
use Latte\Compiler\TagLexer;
use Latte\Compiler\TagParser;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function parse(string $str): Latte\Compiler\Nodes\Php\Expression\ArrayNode
{
	$tokens = (new TagLexer)->tokenize($str);
	$parser = new TagParser($tokens);
	return $parser->parseArguments();
}


$node = parse('10, true, false, null, 5.3, "hello"');
Assert::equal(
	[10, true, false, null, 5.3, 'hello'],
	NodeHelpers::toValue($node),
);


$node = parse('name: 10, [1 => a, "key" => b]');
Assert::equal(
	['name' => 10, [1 => 'a', 'key' => 'b']],
	NodeHelpers::toValue($node),
);


$node = parse('[1, 2, ...[4, 5]]');
Assert::equal(
	[[1, 2, 4, 5]],
	NodeHelpers::toValue($node),
);


$node = parse('[1, 2, ...[$a, 5]]');
Assert::exception(
	fn() => NodeHelpers::toValue($node),
	InvalidArgumentException::class,
);


// constant
const FOO = 123;

$node = parse('FOO');
Assert::exception(
	fn() => NodeHelpers::toValue($node),
	InvalidArgumentException::class,
);

Assert::equal(
	[FOO],
	NodeHelpers::toValue($node, constants: true),
);

$node = parse('BAR');
Assert::exception(
	fn() => NodeHelpers::toValue($node, constants: true),
	InvalidArgumentException::class,
);


// class constant
class ClassA
{
	public const FOO = 456;
}

$node = parse('ClassA::FOO');
Assert::exception(
	fn() => NodeHelpers::toValue($node),
	InvalidArgumentException::class,
);

Assert::equal(
	[ClassA::FOO],
	NodeHelpers::toValue($node, constants: true),
);

$node = parse('ClassA::BAR');
Assert::exception(
	fn() => NodeHelpers::toValue($node, constants: true),
	InvalidArgumentException::class,
);

$node = parse('ClassB::FOO');
Assert::exception(
	fn() => NodeHelpers::toValue($node, constants: true),
	InvalidArgumentException::class,
);
