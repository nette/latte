<?php

/**
 * Test: Latte\Parser::parse()
 */

declare(strict_types=1);

use Latte\Token;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function parse($s)
{
	$parser = new Latte\Parser;
	return array_map(
		fn(Token $token) => array_filter([$token->type, $token->text, $token->name ?? null, $token->value ?? null]),
		$parser->parse($s),
	);
}


Assert::same([
	['text', '<0>'],
], parse('<0>'));

Assert::same([
	['htmlTagBegin', '<x:-._', 'x:-._'],
	['htmlTagEnd', '>'],
], parse('<x:-._>'));

Assert::same([
	['htmlTagBegin', '<?'],
	['text', 'xml encoding="'],
	['macroTag', '{$enc}', '=', '$enc'],
	['text', '" ?'],
	['htmlTagEnd', '>'],
	['text', 'text'],
], parse('<?xml encoding="{$enc}" ?>text'));

Assert::same([
	['htmlTagBegin', '<?'],
	['text', 'php $abc ?'],
	['htmlTagEnd', '>'],
	['text', 'text'],
], parse('<?php $abc ?>text'));

Assert::same([
	['htmlTagBegin', '<?'],
	['text', '= $abc ?'],
	['htmlTagEnd', '>'],
	['text', 'text'],
], parse('<?= $abc ?>text'));

Assert::same([
	['htmlTagBegin', '<?'],
	['text', 'bogus'],
	['htmlTagEnd', '>'],
	['text', 'text'],
], parse('<?bogus>text'));

Assert::same([
	['macroTag', '{contentType xml}', 'contentType', 'xml'],
	['htmlTagBegin', '<?'],
	['text', 'bogus>text'],
], parse('{contentType xml}<?bogus>text'));

Assert::same([
	['htmlTagBegin', '<!'],
	['text', 'doctype html'],
	['htmlTagEnd', '>'],
	['text', 'text'],
], parse('<!doctype html>text'));

Assert::same([
	['htmlTagBegin', '<!'],
	['text', '--'],
	['htmlTagEnd', '>'],
	['text', ' text> --> text'],
], parse('<!--> text> --> text'));

Assert::same([
	['htmlTagBegin', '<!--'],
	['text', ' text> '],
	['htmlTagEnd', '-->'],
	['text', ' text'],
], parse('<!-- text> --> text'));

Assert::same([
	['htmlTagBegin', '<!'],
	['text', 'bogus'],
	['htmlTagEnd', '>'],
	['text', 'text'],
], parse('<!bogus>text'));

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
], parse('<div n:syntax="off"><div>{foo}</div>{bar}</div>{lorem}'));

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
], parse('<div a b c = d e = "f" g></div>'));

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
], parse('<div a {b} c = {d} e = a{b}c f = "a{b}c"></div>'));

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
], parse('<div n:a n:b n:c = d n:e = "f" n:g></div>'));
