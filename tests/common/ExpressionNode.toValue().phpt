<?php declare(strict_types=1);

use Latte\Compiler\{NodeHelpers, TagLexer, TagParser};
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
	$node->toValue(),
);


$node = parse('name: 10, [1 => a, "key" => b]');
Assert::equal(
	['name' => 10, [1 => 'a', 'key' => 'b']],
	$node->toValue(),
);


$node = parse('[1, 2, ...[4, 5]]');
Assert::equal(
	[[1, 2, 4, 5]],
	$node->toValue(),
);


$node = parse('[1, 2, ...[$a, 5]]');
Assert::exception(
	fn() => $node->toValue(),
	InvalidArgumentException::class,
);


// constant
const FOO = 123;

$node = parse('\FOO');
Assert::exception(
	fn() => $node->toValue(),
	InvalidArgumentException::class,
);

Assert::equal(
	[FOO],
	$node->toValue(constants: true),
);

$node = parse('\BAR');
Assert::exception(
	fn() => $node->toValue(constants: true),
	InvalidArgumentException::class,
);


// class constant
class ClassA
{
	public const FOO = 456;
}

$node = parse('ClassA::FOO');
Assert::exception(
	fn() => $node->toValue(),
	InvalidArgumentException::class,
);

Assert::equal(
	[ClassA::FOO],
	$node->toValue(constants: true),
);

$node = parse('ClassA::BAR');
Assert::exception(
	fn() => $node->toValue(constants: true),
	InvalidArgumentException::class,
);

$node = parse('ClassB::FOO');
Assert::exception(
	fn() => $node->toValue(constants: true),
	InvalidArgumentException::class,
);


// hasValue()
Assert::true(parse('[1, "a" => 2]')->hasValue());
Assert::false(parse('[1, $a]')->hasValue());
Assert::false(parse('[...5]')->hasValue());
Assert::false(parse('\FOO')->hasValue());
Assert::true(parse('\FOO')->hasValue(constants: true));


// NodeHelpers::toValue() is the same
Assert::same([FOO], NodeHelpers::toValue(parse('\FOO'), constants: true));
