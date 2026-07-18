<?php declare(strict_types=1);

use Latte\Compiler\Nodes\TextNode;
use Latte\Compiler\Position;
use Latte\Compiler\Range;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('TextNode has start and end', function () {
	$engine = new Latte\Engine;
	$ast = $engine->parse('Hello World');

	$textNode = $ast->main->children[0];
	Assert::type(TextNode::class, $textNode);
	Assert::type(Position::class, $textNode->position);
	Assert::false($textNode->position instanceof Range); // start is a plain point
	Assert::same(0, $textNode->position->offset);
	Assert::same(11, $textNode->end->offset);
});


test('Plain text with newline is single node', function () {
	$engine = new Latte\Engine;
	$ast = $engine->parse("Hello\nWorld");

	// In plain text mode, the whole content is a single TextNode
	$node = $ast->main->children[0];
	Assert::type(TextNode::class, $node);
	Assert::same(0, $node->position->offset);
	Assert::same(11, $node->end->offset); // "Hello\nWorld"
	Assert::same(2, $node->end->line);
});


test('HTML text has correct extent', function () {
	$engine = new Latte\Engine;
	$ast = $engine->parse('<p>Hello</p>');

	// Find the TextNode inside the element
	$element = $ast->main->children[0];
	$textNode = $element->content->children[0];
	Assert::type(TextNode::class, $textNode);
	Assert::same('Hello', $textNode->content);
	Assert::same(3, $textNode->position->offset);
	Assert::same(8, $textNode->end->offset);
});


test('PrintNode has correct extent', function () {
	$engine = new Latte\Engine;
	$ast = $engine->parse('{=$var}');

	$node = $ast->main->children[0];
	Assert::type(Latte\Compiler\Nodes\PrintNode::class, $node);
	Assert::same(0, $node->position->offset);
	Assert::same(7, $node->end->offset); // {=$var} = 7 chars
});


test('Paired tag spans up to the closing tag', function () {
	$engine = new Latte\Engine;
	$ast = $engine->parse('{if $cond}text{/if}');

	$node = $ast->main->children[0];
	Assert::type(Latte\Essential\Nodes\IfNode::class, $node);
	Assert::same(0, $node->position->offset);
	Assert::same(19, $node->end->offset); // {if $cond}text{/if} = 19 chars
});


test('Nested paired tags have correct extents', function () {
	$engine = new Latte\Engine;
	$ast = $engine->parse('{if $a}{if $b}x{/if}{/if}');

	// Outer if: {if $a}{if $b}x{/if}{/if} = 25 chars
	$outer = $ast->main->children[0];
	Assert::type(Latte\Essential\Nodes\IfNode::class, $outer);
	Assert::same(0, $outer->position->offset);
	Assert::same(25, $outer->end->offset);

	// Inner if: {if $b}x{/if} = 13 chars, starts at offset 7
	$inner = $outer->then->children[0];
	Assert::type(Latte\Essential\Nodes\IfNode::class, $inner);
	Assert::same(7, $inner->position->offset);
	Assert::same(20, $inner->end->offset);
});


test('Fragment takes start from first and end from last child', function () {
	$engine = new Latte\Engine;
	$ast = $engine->parse('{if $a}one{$b}two{/if}');

	$fragment = $ast->main->children[0]->then;
	Assert::type(Latte\Compiler\Nodes\FragmentNode::class, $fragment);
	Assert::same(7, $fragment->position->offset);  // 'one'
	Assert::same(17, $fragment->end->offset);      // end of 'two'
});


test('HTML element has correct extent', function () {
	$engine = new Latte\Engine;
	$ast = $engine->parse('<p>Hello</p>');

	$elem = $ast->main->children[0];
	Assert::type(Latte\Compiler\Nodes\Html\ElementNode::class, $elem);
	Assert::same(0, $elem->position->offset);
	Assert::same(12, $elem->end->offset); // <p>Hello</p> = 12
});


test('Void element has correct extent', function () {
	$engine = new Latte\Engine;
	$ast = $engine->parse('<br>');

	$elem = $ast->main->children[0];
	Assert::type(Latte\Compiler\Nodes\Html\ElementNode::class, $elem);
	Assert::same(0, $elem->position->offset);
	Assert::same(4, $elem->end->offset); // <br> = 4
});


test('HTML attribute has correct extent', function () {
	$engine = new Latte\Engine;
	$ast = $engine->parse('<a href="url">x</a>');

	$elem = $ast->main->children[0];
	$attr = $elem->attributes->children[1]; // [0] is whitespace
	Assert::type(Latte\Compiler\Nodes\Html\AttributeNode::class, $attr);
	Assert::same(3, $attr->position->offset);
	Assert::same(13, $attr->end->offset); // href="url" = 10 chars
});
