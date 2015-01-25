<?php

/**
 * Test: Latte\MacroTokens::append()
 */

use Latte\MacroTokens,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test(function() { // constructor
	$tokenizer = new MacroTokens('hello world');
	Assert::count( 3, $tokenizer->tokens );

	$tokenizer2 = new MacroTokens($tokenizer->tokens);
	Assert::same( $tokenizer2->tokens, $tokenizer->tokens );

	$tokenizer3 = new MacroTokens(NULL);
	Assert::count( 0, $tokenizer3->tokens );
});


test(function() { // append
	$tokenizer = new MacroTokens('hello ');

	$res = $tokenizer->append('world!');
	Assert::same( $tokenizer, $res );
	Assert::same( 'hello world!', $tokenizer->joinAll() );
	Assert::count( 4, $tokenizer->tokens );

	$res = $tokenizer->append($tokenizer->tokens[0]);
	Assert::same( 'hello world!hello', $tokenizer->reset()->joinAll() );
	Assert::count( 5, $tokenizer->tokens );

	$res = $tokenizer->append(NULL);
	Assert::count( 5, $tokenizer->tokens );

	$res = $tokenizer->append('');
	Assert::count( 5, $tokenizer->tokens );

	$res = $tokenizer->append(array());
	Assert::count( 5, $tokenizer->tokens );

	$res = $tokenizer->append(FALSE);
	Assert::count( 5, $tokenizer->tokens );
});


test(function() { // append with position
	$tokenizer = new MacroTokens('world');

	$res = $tokenizer->append('!', NULL);
	Assert::same( 'world!', $tokenizer->joinAll() );

	$res = $tokenizer->append('hello', 0);
	Assert::same( 'helloworld!', $tokenizer->reset()->joinAll() );

	$res = $tokenizer->append(' ', 1);
	Assert::same( 'hello world!', $tokenizer->reset()->joinAll() );

	$res = $tokenizer->append('*', -1);
	Assert::same( 'hello world*!', $tokenizer->reset()->joinAll() );

	$res = $tokenizer->append('false', FALSE);
	Assert::same( 'falsehello world*!', $tokenizer->reset()->joinAll() );
});


test(function() { // prepend
	$tokenizer = new MacroTokens('world!');

	$res = $tokenizer->prepend('hello ');
	Assert::same( $tokenizer, $res );
	Assert::same( 'hello world!', $tokenizer->joinAll() );
	Assert::count( 4, $tokenizer->tokens );

	$res = $tokenizer->prepend($tokenizer->tokens[2]);
	Assert::same( 'worldhello world!', $tokenizer->reset()->joinAll() );
	Assert::count( 5, $tokenizer->tokens );

	$res = $tokenizer->prepend(NULL);
	Assert::count( 5, $tokenizer->tokens );

	$res = $tokenizer->prepend('');
	Assert::count( 5, $tokenizer->tokens );

	$res = $tokenizer->prepend(array());
	Assert::count( 5, $tokenizer->tokens );

	$res = $tokenizer->prepend(FALSE);
	Assert::count( 5, $tokenizer->tokens );
});
