<?php declare(strict_types=1);

use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\Html\AttributeNode;
use Latte\Compiler\Nodes\Html\ElementNode;
use Latte\Compiler\Nodes\Html\ExpressionAttributeNode;
use Latte\Compiler\Nodes\Php\Expression\VariableNode;
use Latte\Compiler\Nodes\Php\ModifierNode;
use Latte\Compiler\Nodes\TextNode;
use Latte\ContentType;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('ElementNode::is() - HTML content type case insensitive', function () {
	$element = new ElementNode('DIV', contentType: ContentType::Html);

	Assert::true($element->is('div'));
	Assert::true($element->is('DIV'));
	Assert::true($element->is('Div'));
	Assert::false($element->is('span'));
});


test('ElementNode::is() - non-HTML content type case sensitive', function () {
	$element = new ElementNode('custom', contentType: ContentType::Xml);

	Assert::true($element->is('custom'));
	Assert::false($element->is('CUSTOM'));
	Assert::false($element->is('Custom'));
});


test('ElementNode::getAttribute() - no attributes', function () {
	$element = new ElementNode('div');
	Assert::null($element->getAttribute('class'));
});


test('ElementNode::getAttribute() - existing attribute with text value', function () {
	$element = new ElementNode('div');
	$element->attributes->children = [
		new AttributeNode(
			new TextNode('class'),
			new TextNode('container'),
		),
		new AttributeNode(
			new TextNode('id'),
			new TextNode('main'),
		),
	];

	Assert::same('container', $element->getAttribute('class'));
	Assert::same('main', $element->getAttribute('id'));
});


test('ElementNode::getAttribute() - HTML content type case insensitive attribute names', function () {
	$element = new ElementNode('div', contentType: ContentType::Html);
	$element->attributes->children = [
		new AttributeNode(
			new TextNode('CLASS'),
			new TextNode('container'),
		),
	];

	Assert::same('container', $element->getAttribute('class'));
	Assert::same('container', $element->getAttribute('CLASS'));
	Assert::same('container', $element->getAttribute('Class'));
});


test('ElementNode::getAttribute() - non-HTML content type case sensitive attribute names', function () {
	$element = new ElementNode('element', contentType: ContentType::Xml);
	$element->attributes->children = [
		new AttributeNode(
			new TextNode('CLASS'),
			new TextNode('container'),
		),
		new AttributeNode(
			new TextNode('class'),
			new TextNode('other'),
		),
	];

	Assert::same('container', $element->getAttribute('CLASS'));
	Assert::same('other', $element->getAttribute('class'));
	Assert::null($element->getAttribute('Class'));
});


test('ElementNode::getAttribute() - boolean attribute (no value)', function () {
	$element = new ElementNode('input');
	$element->attributes->children = [
		new AttributeNode(
			new TextNode('disabled'),
		),
	];

	Assert::same(true, $element->getAttribute('disabled'));
});


test('ElementNode::getAttribute() - non-existent attribute', function () {
	$element = new ElementNode('div');
	$element->attributes->children = [
		new AttributeNode(
			new TextNode('class'),
			new TextNode('container'),
		),
	];

	Assert::null($element->getAttribute('id'));
	Assert::null($element->getAttribute('nonexistent'));
});


test('ElementNode::getAttribute() - ExpressionAttributeNode HTML case insensitive', function () {
	$element = new ElementNode('div', contentType: ContentType::Html);
	$element->attributes = new FragmentNode([
		new ExpressionAttributeNode(
			'CLASS',
			new VariableNode('foo'),
			new ModifierNode([]),
		),
	]);

	Assert::same(true, $element->getAttribute('class'));
	Assert::same(true, $element->getAttribute('CLASS'));
	Assert::same(true, $element->getAttribute('Class'));
});


test('ElementNode::getAttribute() - ExpressionAttributeNode non-HTML case sensitive', function () {
	$element = new ElementNode('element', contentType: ContentType::Xml);
	$element->attributes = new FragmentNode([
		new ExpressionAttributeNode(
			'CLASS',
			new VariableNode('foo'),
			new ModifierNode([]),
		),
	]);

	Assert::same(true, $element->getAttribute('CLASS'));
	Assert::null($element->getAttribute('class'));
	Assert::null($element->getAttribute('Class'));
});
