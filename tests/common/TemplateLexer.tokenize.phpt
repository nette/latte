<?php

declare(strict_types=1);

use Latte\Compiler\Token;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function tokenize($s)
{
	$lexer = new Latte\Compiler\TemplateLexer;
	return array_map(
		fn(Token $token) => [$token->type, $token->text, $token->position->line . ':' . $token->position->column],
		iterator_to_array($lexer->tokenize($s), false),
	);
}


Assert::same([
	['text', '<0>', '1:1'],
	['end', '', '1:4'],
], tokenize('<0>'));

Assert::same([
	['htmlTagBegin', '<x:-._', '1:1'],
	['htmlTagEnd', '>', '1:7'],
	['end', '', '1:8'],
], tokenize('<x:-._>'));

Assert::same([
	['htmlTagBegin', '<?', '1:1'],
	['text', 'xml encoding="', '1:3'],
	['macroTag', '{$enc}', '1:17'],
	['text', '" ?', '1:23'],
	['htmlTagEnd', '>', '1:26'],
	['text', 'text', '1:27'],
	['end', '', '1:31'],
], tokenize('<?xml encoding="{$enc}" ?>text'));

Assert::same([
	['htmlTagBegin', '<?', '1:1'],
	['text', 'php $abc ?', '1:3'],
	['htmlTagEnd', '>', '1:13'],
	['text', 'text', '1:14'],
	['end', '', '1:18'],
], tokenize('<?php $abc ?>text'));

Assert::same([
	['htmlTagBegin', '<?', '1:1'],
	['text', '= $abc ?', '1:3'],
	['htmlTagEnd', '>', '1:11'],
	['text', 'text', '1:12'],
	['end', '', '1:16'],
], tokenize('<?= $abc ?>text'));

Assert::same([
	['htmlTagBegin', '<?', '1:1'],
	['text', 'bogus', '1:3'],
	['htmlTagEnd', '>', '1:8'],
	['text', 'text', '1:9'],
	['end', '', '1:13'],
], tokenize('<?bogus>text'));

Assert::same([
	['htmlTagBegin', '<!', '1:1'],
	['text', 'doctype html', '1:3'],
	['htmlTagEnd', '>', '1:15'],
	['text', 'text', '1:16'],
	['end', '', '1:20'],
], tokenize('<!doctype html>text'));

Assert::same([
	['htmlTagBegin', '<!', '1:1'],
	['text', '--', '1:3'],
	['htmlTagEnd', '>', '1:5'],
	['text', ' text> --> text', '1:6'],
	['end', '', '1:21'],
], tokenize('<!--> text> --> text'));

Assert::same([
	['htmlTagBegin', '<!--', '1:1'],
	['text', ' text> ', '1:5'],
	['htmlTagEnd', '-->', '1:12'],
	['text', ' text', '1:15'],
	['end', '', '1:20'],
], tokenize('<!-- text> --> text'));

Assert::same([
	['htmlTagBegin', '<!', '1:1'],
	['text', 'bogus', '1:3'],
	['htmlTagEnd', '>', '1:8'],
	['text', 'text', '1:9'],
	['end', '', '1:13'],
], tokenize('<!bogus>text'));

// html attributes
Assert::same([
	['htmlTagBegin', '<div', '1:1'],
	['htmlAttributeBegin', ' a', '1:5'],
	['htmlAttributeBegin', ' b', '1:7'],
	['htmlAttributeBegin', ' c = d', '1:9'],
	['htmlAttributeBegin', ' e = "', '1:15'],
	['text', 'f', '1:21'],
	['htmlAttributeEnd', '"', '1:22'],
	['htmlAttributeBegin', ' g', '1:23'],
	['htmlTagEnd', '>', '1:25'],
	['htmlTagBegin', '</div', '1:26'],
	['htmlTagEnd', '>', '1:31'],
	['end', '', '1:32'],
], tokenize('<div a b c = d e = "f" g></div>'));

Assert::same([
	['htmlTagBegin', '<div', '1:1'],
	['htmlAttributeBegin', ' a', '1:5'],
	['text', ' ', '1:7'],
	['macroTag', '{b}', '1:8'],
	['htmlAttributeBegin', ' c', '1:11'],
	['text', ' = ', '1:13'],
	['macroTag', '{d}', '1:16'],
	['htmlAttributeBegin', ' e = a', '1:19'],
	['macroTag', '{b}', '1:25'],
	['htmlAttributeBegin', 'c', '1:28'],
	['htmlAttributeBegin', ' f = "', '1:29'],
	['text', 'a', '1:35'],
	['macroTag', '{b}', '1:36'],
	['text', 'c', '1:39'],
	['htmlAttributeEnd', '"', '1:40'],
	['htmlTagEnd', '>', '1:41'],
	['htmlTagBegin', '</div', '1:42'],
	['htmlTagEnd', '>', '1:47'],
	['end', '', '1:48'],
], tokenize('<div a {b} c = {d} e = a{b}c f = "a{b}c"></div>'));

// macro attributes
Assert::same([
	['htmlTagBegin', '<div', '1:1'],
	['htmlAttributeBegin', ' n:a', '1:5'],
	['htmlAttributeBegin', ' n:b', '1:9'],
	['htmlAttributeBegin', ' n:c = d', '1:13'],
	['htmlAttributeBegin', ' n:e = "f"', '1:21'],
	['htmlAttributeBegin', ' n:g', '1:31'],
	['htmlTagEnd', '>', '1:35'],
	['htmlTagBegin', '</div', '1:36'],
	['htmlTagEnd', '>', '1:41'],
	['end', '', '1:42'],
], tokenize('<div n:a n:b n:c = d n:e = "f" n:g></div>'));
