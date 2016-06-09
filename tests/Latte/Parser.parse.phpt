<?php

/**
 * Test: Latte\Parser::parse()
 */

use Tester\Assert;
use Latte\Parser;
use Latte\Token;


require __DIR__ . '/../bootstrap.php';


function parse($s)
{
	$parser = new Latte\Parser;
	return array_map(function (Token $token) {
		return [$token->type, $token->text];
	}, $parser->parse($s));
}

Assert::same([
	['text', '<0>']
], parse('<0>'));

Assert::same([
	['htmlTagBegin', '<?'],
	['text', 'xml encoding="'],
	['macroTag', '{$enc}'],
	['text', '" ?'],
	['htmlTagEnd', '>'],
	['text', 'text'],
], parse('<?xml encoding="{$enc}" ?>text'));

Assert::same([
	['text', '<?php $abc ?>text'],
], parse('<?php $abc ?>text'));

Assert::same([
	['text', '<?= $abc ?>text'],
], parse('<?= $abc ?>text'));

Assert::same([
	['htmlTagBegin', '<?'],
	['text', 'bogus'],
	['htmlTagEnd', '>'],
	['text', 'text'],
], parse('<?bogus>text'));

Assert::same([
	['macroTag', '{contentType xml}'],
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
	['htmlTagBegin', '<div'],
	['comment', ' n:syntax="off"'],
	['htmlTagEnd', '>'],
	['htmlTagBegin', '<div'],
	['htmlTagEnd', '>'],
	['text', '{foo}'],
	['htmlTagBegin', '</div'],
	['htmlTagEnd', '>'],
	['text', '{bar}'],
	['htmlTagBegin', '</div'],
	['htmlTagEnd', '>'],
	['macroTag', '{lorem}'],
], parse('<div n:syntax="off"><div>{foo}</div>{bar}</div>{lorem}'));
