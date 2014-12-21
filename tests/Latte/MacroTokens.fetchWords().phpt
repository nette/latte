<?php

/**
 * Test: Latte\MacroTokens::fetchWords()
 */

use Latte\MacroTokens,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test(function() {
	$tokenizer = new MacroTokens('');
	Assert::same( array(),  $tokenizer->fetchWords() );
	Assert::same( '',  $tokenizer->joinAll() );
});


test(function() {
	$tokenizer = new MacroTokens('$1d-,a');
	Assert::same( array('$1d-'),  $tokenizer->fetchWords() );
	Assert::same( 'a',  $tokenizer->joinAll() );
});


test(function() {
	$tokenizer = new MacroTokens('"a:":$b" c" ,');
	Assert::same( array('"a:"', '$b" c"'),  $tokenizer->fetchWords() );
	Assert::same( '',  $tokenizer->joinAll() );
});


test(function() {
	$tokenizer = new MacroTokens('a. b');
	Assert::same( array('a. b'),  $tokenizer->fetchWords() );
	Assert::same( '',  $tokenizer->joinAll() );
});


test(function() {
	$tokenizer = new MacroTokens('a . b');
	Assert::same( array('a . b'),  $tokenizer->fetchWords() );
	Assert::same( '',  $tokenizer->joinAll() );
});


test(function() {
	$tokenizer = new MacroTokens('a .b');
	Assert::same( array('a .b'),  $tokenizer->fetchWords() );
	Assert::same( '',  $tokenizer->joinAll() );
});


test(function() {
	$tokenizer = new MacroTokens('a . b:x,');
	Assert::same( array('a . b:x'),  $tokenizer->fetchWords() );
	Assert::same( '',  $tokenizer->joinAll() );
});
