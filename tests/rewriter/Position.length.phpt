<?php

declare(strict_types=1);

use Latte\Compiler\Nodes\TextNode;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('TextNode has length set', function () {
	$engine = new Latte\Engine;
	$ast = $engine->parse('Hello World');

	$textNode = $ast->main->children[0];
	Assert::type(TextNode::class, $textNode);
	Assert::same(0, $textNode->position->offset);
	Assert::same(11, $textNode->position->length);
});


test('Plain text with newline is single node', function () {
	$engine = new Latte\Engine;
	$ast = $engine->parse("Hello\nWorld");

	// In plain text mode, the whole content is a single TextNode
	$node = $ast->main->children[0];
	Assert::type(TextNode::class, $node);
	Assert::same(0, $node->position->offset);
	Assert::same(11, $node->position->length); // "Hello\nWorld"
});


test('HTML text has correct length', function () {
	$engine = new Latte\Engine;
	$ast = $engine->parse('<p>Hello</p>');

	// Find the TextNode inside the element
	$element = $ast->main->children[0];
	$textNode = $element->content->children[0];
	Assert::type(TextNode::class, $textNode);
	Assert::same('Hello', $textNode->content);
	Assert::same(5, $textNode->position->length);
});


test('PrintNode has correct length', function () {
	$engine = new Latte\Engine;
	$ast = $engine->parse('{=$var}');

	$node = $ast->main->children[0];
	Assert::type(Latte\Compiler\Nodes\PrintNode::class, $node);
	Assert::same(0, $node->position->offset);
	Assert::same(7, $node->position->length); // {=$var} = 7 chars
});


test('Paired tag has full length', function () {
	$engine = new Latte\Engine;
	$ast = $engine->parse('{if $cond}text{/if}');

	$node = $ast->main->children[0];
	Assert::type(Latte\Essential\Nodes\IfNode::class, $node);
	Assert::same(0, $node->position->offset);
	Assert::same(19, $node->position->length); // {if $cond}text{/if} = 19 chars
});


test('Nested paired tags have correct lengths', function () {
	$engine = new Latte\Engine;
	$ast = $engine->parse('{if $a}{if $b}x{/if}{/if}');

	// Outer if: {if $a}{if $b}x{/if}{/if} = 25 chars
	$outer = $ast->main->children[0];
	Assert::type(Latte\Essential\Nodes\IfNode::class, $outer);
	Assert::same(0, $outer->position->offset);
	Assert::same(25, $outer->position->length);

	// Inner if: {if $b}x{/if} = 13 chars, starts at offset 7
	$inner = $outer->then->children[0];
	Assert::type(Latte\Essential\Nodes\IfNode::class, $inner);
	Assert::same(7, $inner->position->offset);
	Assert::same(13, $inner->position->length);
});


test('HTML element has correct length', function () {
	$engine = new Latte\Engine;
	$ast = $engine->parse('<p>Hello</p>');

	$elem = $ast->main->children[0];
	Assert::type(Latte\Compiler\Nodes\Html\ElementNode::class, $elem);
	Assert::same(0, $elem->position->offset);
	Assert::same(12, $elem->position->length); // <p>Hello</p> = 12
});


test('Void element has correct length', function () {
	$engine = new Latte\Engine;
	$ast = $engine->parse('<br>');

	$elem = $ast->main->children[0];
	Assert::type(Latte\Compiler\Nodes\Html\ElementNode::class, $elem);
	Assert::same(0, $elem->position->offset);
	Assert::same(4, $elem->position->length); // <br> = 4
});


test('HTML attribute has correct length', function () {
	$engine = new Latte\Engine;
	$ast = $engine->parse('<a href="url">x</a>');

	$elem = $ast->main->children[0];
	$attr = $elem->attributes->children[1]; // [0] is whitespace
	Assert::type(Latte\Compiler\Nodes\Html\AttributeNode::class, $attr);
	Assert::same(3, $attr->position->offset);
	Assert::same(10, $attr->position->length); // href="url" = 10
});
