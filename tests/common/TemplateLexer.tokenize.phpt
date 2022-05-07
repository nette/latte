<?php

declare(strict_types=1);

use Latte\Compiler\Token;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function tokenize($s)
{
	$lexer = new Latte\Compiler\TemplateLexer;
	return array_map(
		fn(Token $token) => array_filter([$token->type, $token->text, $token->name ?? null, $token->value ?? null]),
		$lexer->tokenize($s),
	);
}


Assert::same([
	['text', '<0>'],
], tokenize('<0>'));

Assert::same([
	['htmlTagBegin', '<x:-._', 'x:-._'],
	['htmlTagEnd', '>'],
], tokenize('<x:-._>'));

Assert::same([
	['htmlTagBegin', '<?'],
	['text', 'xml encoding="'],
	['macroTag', '{$enc}', '=', '$enc'],
	['text', '" ?'],
	['htmlTagEnd', '>'],
	['text', 'text'],
], tokenize('<?xml encoding="{$enc}" ?>text'));

Assert::same([
	['htmlTagBegin', '<?'],
	['text', 'php $abc ?'],
	['htmlTagEnd', '>'],
	['text', 'text'],
], tokenize('<?php $abc ?>text'));

Assert::same([
	['htmlTagBegin', '<?'],
	['text', '= $abc ?'],
	['htmlTagEnd', '>'],
	['text', 'text'],
], tokenize('<?= $abc ?>text'));

Assert::same([
	['htmlTagBegin', '<?'],
	['text', 'bogus'],
	['htmlTagEnd', '>'],
	['text', 'text'],
], tokenize('<?bogus>text'));

Assert::same([
	['macroTag', '{contentType xml}', 'contentType', 'xml'],
	['htmlTagBegin', '<?'],
	['text', 'bogus>text'],
], tokenize('{contentType xml}<?bogus>text'));

Assert::same([
	['htmlTagBegin', '<!'],
	['text', 'doctype html'],
	['htmlTagEnd', '>'],
	['text', 'text'],
], tokenize('<!doctype html>text'));

Assert::same([
	['htmlTagBegin', '<!'],
	['text', '--'],
	['htmlTagEnd', '>'],
	['text', ' text> --> text'],
], tokenize('<!--> text> --> text'));

Assert::same([
	['htmlTagBegin', '<!--'],
	['text', ' text> '],
	['htmlTagEnd', '-->'],
	['text', ' text'],
], tokenize('<!-- text> --> text'));

Assert::same([
	['htmlTagBegin', '<!'],
	['text', 'bogus'],
	['htmlTagEnd', '>'],
	['text', 'text'],
], tokenize('<!bogus>text'));

Assert::same([
	['htmlTagBegin', '<div', 'div'],
	['comment', ' n:syntax="off"', 'n:syntax', 'off'],
	['htmlTagEnd', '>'],
	['htmlTagBegin', '<div', 'div'],
	['htmlTagEnd', '>'],
	['text', '{foo}'],
	['htmlTagBegin', '</div', 'div'],
	['htmlTagEnd', '>'],
	['text', '{bar}'],
	['htmlTagBegin', '</div', 'div'],
	['htmlTagEnd', '>'],
	['macroTag', '{lorem}', 'lorem'],
], tokenize('<div n:syntax="off"><div>{foo}</div>{bar}</div>{lorem}'));

// html attributes
Assert::same([
	['htmlTagBegin', '<div', 'div'],
	['htmlAttributeBegin', ' a', 'a'],
	['htmlAttributeBegin', ' b', 'b'],
	['htmlAttributeBegin', ' c = d', 'c', 'd'],
	['htmlAttributeBegin', ' e = "', 'e', '"'],
	['text', 'f'],
	['htmlAttributeEnd', '"'],
	['htmlAttributeBegin', ' g', 'g'],
	['htmlTagEnd', '>'],
	['htmlTagBegin', '</div', 'div'],
	['htmlTagEnd', '>'],
], tokenize('<div a b c = d e = "f" g></div>'));

Assert::same([
	['htmlTagBegin', '<div', 'div'],
	['htmlAttributeBegin', ' a', 'a'],
	['text', ' '],
	['macroTag', '{b}', 'b'],
	['htmlAttributeBegin', ' c', 'c'],
	['text', ' = '],
	['macroTag', '{d}', 'd'],
	['htmlAttributeBegin', ' e = a', 'e', 'a'],
	['macroTag', '{b}', 'b'],
	['htmlAttributeBegin', 'c', 'c'],
	['htmlAttributeBegin', ' f = "', 'f', '"'],
	['text', 'a'],
	['macroTag', '{b}', 'b'],
	['text', 'c'],
	['htmlAttributeEnd', '"'],
	['htmlTagEnd', '>'],
	['htmlTagBegin', '</div', 'div'],
	['htmlTagEnd', '>'],
], tokenize('<div a {b} c = {d} e = a{b}c f = "a{b}c"></div>'));

// macro attributes
Assert::same([
	['htmlTagBegin', '<div', 'div'],
	['htmlAttributeBegin', ' n:a', 'n:a'],
	['htmlAttributeBegin', ' n:b', 'n:b'],
	['htmlAttributeBegin', ' n:c = d', 'n:c', 'd'],
	['htmlAttributeBegin', ' n:e = "f"', 'n:e', 'f'],
	['htmlAttributeBegin', ' n:g', 'n:g'],
	['htmlTagEnd', '>'],
	['htmlTagBegin', '</div', 'div'],
	['htmlTagEnd', '>'],
], tokenize('<div n:a n:b n:c = d n:e = "f" n:g></div>'));
