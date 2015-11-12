<?php

/**
 * Test: Latte\Parser
 */

use Tester\Assert;
use Latte\Parser;
use Latte\Token;


require __DIR__ . '/../bootstrap.php';


$parser = new Parser;

$tokens = $parser->parse("{if true}<br>{/if}");
Assert::count(4, $tokens);
Assert::same($tokens[0]->type, Token::MACRO_TAG);
Assert::same($tokens[3]->type, Token::MACRO_TAG);

$tokens = $parser->parse("{if true}<0>{/if}");
Assert::count(4, $tokens);
Assert::same($tokens[0]->type, Token::MACRO_TAG);
Assert::same($tokens[3]->type, Token::MACRO_TAG);
