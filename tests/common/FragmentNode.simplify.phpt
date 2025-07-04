<?php

declare(strict_types=1);

use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\TextNode;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('simplify with allowsNull=true returns null for empty FragmentNode', function () {
	$fragment = new FragmentNode([]);
	Assert::null($fragment->simplify(allowsNull: true));
});


test('simplify with allowsNull=false returns self for empty FragmentNode', function () {
	$fragment = new FragmentNode([]);
	Assert::same($fragment, $fragment->simplify(allowsNull: false));
});


test('simplify with allowsNull=true returns single child for one-element FragmentNode', function () {
	$text = new TextNode('single');
	$fragment = new FragmentNode([$text]);
	Assert::same($text, $fragment->simplify(allowsNull: true));
});


test('simplify with allowsNull=false returns single child for one-element FragmentNode', function () {
	$text = new TextNode('single');
	$fragment = new FragmentNode([$text]);
	Assert::same($text, $fragment->simplify(allowsNull: false));
});


test('simplify with allowsNull=true returns self for multi-element FragmentNode', function () {
	$fragment = new FragmentNode([
		new TextNode('first'),
		new TextNode('second'),
	]);
	Assert::same($fragment, $fragment->simplify(allowsNull: true));
});


test('simplify with allowsNull=false returns self for multi-element FragmentNode', function () {
	$fragment = new FragmentNode([
		new TextNode('first'),
		new TextNode('second'),
	]);
	Assert::same($fragment, $fragment->simplify(allowsNull: false));
});
