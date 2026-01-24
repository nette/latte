<?php

declare(strict_types=1);

use Latte\Essential\Nodes\ForeachNode;
use Latte\Essential\Nodes\IfNode;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('simple tag has one tagPosition', function () {
	$engine = new Latte\Engine;
	$ast = $engine->parse('{=$var}');

	$node = $ast->main->children[0];
	Assert::type(Latte\Compiler\Nodes\PrintNode::class, $node);
	Assert::count(1, $node->tagRanges);
	Assert::same(0, $node->tagRanges[0]->offset);
	Assert::same(7, $node->tagRanges[0]->length);
});


test('paired tag has two tagRanges', function () {
	$engine = new Latte\Engine;
	$ast = $engine->parse('{if $cond}text{/if}');

	$node = $ast->main->children[0];
	Assert::type(IfNode::class, $node);
	Assert::count(2, $node->tagRanges);

	// opening tag {if $cond}
	Assert::same(0, $node->tagRanges[0]->offset);
	Assert::same(10, $node->tagRanges[0]->length);

	// closing tag {/if}
	Assert::same(14, $node->tagRanges[1]->offset);
	Assert::same(5, $node->tagRanges[1]->length);
});


test('if/else has three tagRanges', function () {
	$engine = new Latte\Engine;
	$ast = $engine->parse('{if $a}text{else}more{/if}');

	$node = $ast->main->children[0];
	Assert::type(IfNode::class, $node);
	Assert::count(3, $node->tagRanges);

	// {if $a}
	Assert::same(0, $node->tagRanges[0]->offset);
	Assert::same(7, $node->tagRanges[0]->length);

	// {else}
	Assert::same(11, $node->tagRanges[1]->offset);
	Assert::same(6, $node->tagRanges[1]->length);

	// {/if}
	Assert::same(21, $node->tagRanges[2]->offset);
	Assert::same(5, $node->tagRanges[2]->length);
});


test('if/elseif/else has four tagRanges', function () {
	$engine = new Latte\Engine;
	$ast = $engine->parse('{if $a}A{elseif $b}B{else}C{/if}');

	$node = $ast->main->children[0];
	Assert::type(IfNode::class, $node);
	Assert::count(4, $node->tagRanges);

	// {if $a}
	Assert::same(0, $node->tagRanges[0]->offset);
	Assert::same(7, $node->tagRanges[0]->length);

	// {elseif $b}
	Assert::same(8, $node->tagRanges[1]->offset);
	Assert::same(11, $node->tagRanges[1]->length);

	// {else}
	Assert::same(20, $node->tagRanges[2]->offset);
	Assert::same(6, $node->tagRanges[2]->length);

	// {/if}
	Assert::same(27, $node->tagRanges[3]->offset);
	Assert::same(5, $node->tagRanges[3]->length);
});


test('foreach has two tagRanges', function () {
	$engine = new Latte\Engine;
	$ast = $engine->parse('{foreach $items as $item}x{/foreach}');

	$node = $ast->main->children[0];
	Assert::type(ForeachNode::class, $node);
	Assert::count(2, $node->tagRanges);

	// {foreach $items as $item}
	Assert::same(0, $node->tagRanges[0]->offset);
	Assert::same(25, $node->tagRanges[0]->length);

	// {/foreach}
	Assert::same(26, $node->tagRanges[1]->offset);
	Assert::same(10, $node->tagRanges[1]->length);
});


test('self-closing tag has one tagPosition', function () {
	$engine = new Latte\Engine;
	$ast = $engine->parse('x{foreach $items as $item /}');

	$node = $ast->main->children[1];
	Assert::type(ForeachNode::class, $node);
	Assert::count(1, $node->tagRanges);

	// {foreach $items as $item /}
	Assert::same(1, $node->tagRanges[0]->offset);
	Assert::same(27, $node->tagRanges[0]->length);
});


test('foreach/else has three tagRanges', function () {
	$engine = new Latte\Engine;
	$ast = $engine->parse('{foreach $items as $item}x{else}empty{/foreach}');

	$node = $ast->main->children[0];
	Assert::type(ForeachNode::class, $node);
	Assert::count(3, $node->tagRanges);

	// {foreach $items as $item}
	Assert::same(0, $node->tagRanges[0]->offset);
	Assert::same(25, $node->tagRanges[0]->length);

	// {else}
	Assert::same(26, $node->tagRanges[1]->offset);
	Assert::same(6, $node->tagRanges[1]->length);

	// {/foreach}
	Assert::same(37, $node->tagRanges[2]->offset);
	Assert::same(10, $node->tagRanges[2]->length);
});


test('nested if tags have correct tagRanges', function () {
	$engine = new Latte\Engine;
	$ast = $engine->parse('{if $a}{if $b}x{/if}{/if}');

	// Outer if
	$outer = $ast->main->children[0];
	Assert::type(IfNode::class, $outer);
	Assert::count(2, $outer->tagRanges);
	Assert::same(0, $outer->tagRanges[0]->offset);  // {if $a}
	Assert::same(20, $outer->tagRanges[1]->offset); // {/if}

	// Inner if
	$inner = $outer->then->children[0];
	Assert::type(IfNode::class, $inner);
	Assert::count(2, $inner->tagRanges);
	Assert::same(7, $inner->tagRanges[0]->offset);  // {if $b}
	Assert::same(15, $inner->tagRanges[1]->offset); // {/if}
});


test('unpaired tag has one tagPosition', function () {
	$engine = new Latte\Engine;
	$ast = $engine->parse('{var $x = 1}');

	// {var} goes to head, not main
	$node = $ast->head->children[0];
	Assert::count(1, $node->tagRanges);
	Assert::same(0, $node->tagRanges[0]->offset);
	Assert::same(12, $node->tagRanges[0]->length);
});
