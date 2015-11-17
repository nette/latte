<?php

/**
 * Test: Latte\Parser::parse()
 */

use Tester\Assert;
use Latte\Parser;
use Latte\Token;


require __DIR__ . '/../bootstrap.php';


test(function () {
	$parser = new Parser;
	$tokens = $parser->parse('<0>');
	Assert::same(Token::TEXT, $tokens[0]->type);
	Assert::same('<0>', $tokens[0]->text);
});
