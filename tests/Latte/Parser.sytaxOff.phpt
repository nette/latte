<?php

/**
 * Test: Latte\Parser and syntax=off
 */

use Latte\Token;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';

$parser = new \Latte\Parser();
$result = $parser->parse('<div n:syntax="off"><div>{foo}</div>{bar}</div>{lorem}');

$types = array_map(function (Token $token) {
	return $token->type;
}, $result);

Assert::same([
	Token::HTML_TAG_BEGIN,
	Token::COMMENT,
	Token::HTML_TAG_END,
	Token::HTML_TAG_BEGIN,
	Token::HTML_TAG_END,
	Token::TEXT,
	Token::HTML_TAG_BEGIN,
	Token::HTML_TAG_END,
	Token::TEXT,
	Token::HTML_TAG_BEGIN,
	Token::HTML_TAG_END,
	Token::MACRO_TAG,
], $types);
