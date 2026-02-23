<?php declare(strict_types=1);

use Latte\Compiler\Nodes\Php\ArrayItemNode;
use Latte\Compiler\Nodes\Php\Expression\ArrayNode;
use Latte\Compiler\Nodes\Php\Expression\BinaryOpNode;
use Latte\Compiler\Nodes\Php\FilterNode;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\Nodes\Php\ModifierNode;
use Latte\Compiler\Nodes\Php\Scalar\IntegerNode;
use Latte\Compiler\Nodes\Php\Scalar\StringNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function format(...$args)
{
	return (new PrintContext)->format(...$args);
}


test('text', function () {
	Assert::same('', format(''));
	Assert::same('0', format('0'));
	Assert::same('%a %b', format('%a %b'));
	Assert::exception(fn() => @format('%foo %bar'), UnhandledMatchError::class);
});


test('order', function () {
	Assert::same(
		'test([], 2, 1, 2)',
		format('test(%2.dump, %1.dump, %dump, %dump)', 1, 2, []),
	);

	Assert::error(
		fn() => format('test(%dump, %dump, %dump)', 1, 2),
		E_WARNING,
	);

	Assert::error(
		fn() => format('test(%1.dump)', 1),
		E_WARNING,
	);
});


test('%dump', function () {
	Assert::same(
		'test(1, 2, [])',
		format('test(%dump, %dump, %dump)', 1, 2, []),
	);

	Assert::same(
		"test('hello')",
		format('test(%dump, %dump?, %dump?)', 'hello', [], null),
	);

	Assert::match(
		'test(%a?%Latte\Compiler\Nodes\Php\Scalar\StringNode::__set_state(%A%))',
		format('test(%dump)', new StringNode('PHP')),
	);
});


test('%node', function () {
	Assert::same(
		'test()',
		format('test(%node)', null),
	);

	Assert::same(
		"test(['PHP'])",
		format('test(%node)', new ArrayNode([new ArrayItemNode(new StringNode('PHP'))])),
	);

	Assert::same(
		'test(hello)',
		format('test(%raw, %node?)', 'hello', null),
	);

	Assert::same(
		'test(hello)',
		format('test(%raw, %node?)', 'hello', new ArrayNode),
	);

	Assert::same(
		'test(123)',
		format('test(%node? + 123)', null),
	);

	Assert::same( // the minimum context is assignment operator
		'(1 and 2)',
		format('%node', new BinaryOpNode(new IntegerNode(1), 'and', new IntegerNode(2))),
	);
});


test('%raw', function () {
	Assert::same(
		'test(hello %raw,)',
		format('test(%raw, %raw)', 'hello %raw', null),
	);

	Assert::same(
		'test(hello)',
		format('test(%raw, %raw?)', 'hello', null),
	);

	Assert::same(
		'test(hello, 123)',
		format('test(%raw, %raw? + 123)', 'hello', null),
	);
});


test('%args', function () {
	Assert::same(
		"test('PHP')",
		format('test(%args)', new ArrayNode([new ArrayItemNode(new StringNode('PHP'))])),
	);

	Assert::same(
		'test(hello)',
		format('test(%raw, %args?)', 'hello', []),
	);

	Assert::same(
		'test(hello)',
		format('test(%raw, %args?)', 'hello', new ArrayNode),
	);
});


test('%line', function () {
	Assert::same(
		'test();',
		format('test() %line;', null),
	);

	Assert::same(
		'test();',
		format('test() %line;', new Position(0, 0)),
	);

	Assert::same(
		'test() /* pos 1:1 */;',
		format('test() %line;', new Position(1, 1)),
	);
});


test('%modify', function () {
	Assert::same(
		'test1(test())',
		format('test1(%modify(test()))', new ModifierNode([])),
	);

	Assert::same(
		'test1(($this->filters->a)(($this->filters->b)(test())))',
		format('test1(%modify(test()))', new ModifierNode([new FilterNode(new IdentifierNode('b')), new FilterNode(new IdentifierNode('a'))])),
	);
});


test('%modifyContent', function () {
	Assert::same(
		'test1(test())',
		format('test1(%modifyContent(test()))', new ModifierNode([])),
	);

	Assert::same(
		'test1($this->filters->filterContent(\'a\', $ʟ_fi, $this->filters->filterContent(\'b\', $ʟ_fi, test())))',
		format('test1(%modifyContent(test()))', new ModifierNode([new FilterNode(new IdentifierNode('b')), new FilterNode(new IdentifierNode('a'))])),
	);
});


test('%escape', function () {
	Assert::same(
		'test1(LR\HtmlHelpers::escapeText(test()), 123)',
		format('test1(%escape(test()), %dump)', 123),
	);
});
