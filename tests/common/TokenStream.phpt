<?php

declare(strict_types=1);

use Latte\CompileException;
use Latte\Compiler\Token;
use Latte\Compiler\TokenStream;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('end', function () {
	$eof = new Token(Token::END, '');
	$stream = new TokenStream([$eof]);
	Assert::false($stream->is());
	Assert::same($eof, $stream->peek());
	Assert::null($stream->peek(1));
	Assert::same($eof, $stream->consume());
	Assert::same($eof, $stream->consume());
	Assert::same(0, $stream->getIndex());
});


test('is()', function () {
	$token = new Token(Token::TEXT, 'foo');
	$eof = new Token(Token::END, '');
	$stream = new TokenStream([$token, $eof]);

	Assert::false($stream->is());
	Assert::true($stream->is('foo'));
	Assert::false($stream->is(''));
	Assert::true($stream->is('', 'foo'));
	Assert::true($stream->is(Token::TEXT));

	Assert::same($token, $stream->consume());
	Assert::false($stream->is('foo'));
});


test('peek()', function () {
	$token1 = new Token('', '');
	$token2 = new Token('', '');
	$eof = new Token(Token::END, '');
	$stream = new TokenStream([$token1, $token2, $eof]);

	Assert::null($stream->peek(-1));
	Assert::same(0, $stream->getIndex());
	Assert::same($token1, $stream->peek());
	Assert::same($token2, $stream->peek(1));
	Assert::same($eof, $stream->peek(2));
	Assert::null($stream->peek(3));
	Assert::same(0, $stream->getIndex());

	$stream->consume();
	Assert::null($stream->peek(-2));
	Assert::same($token1, $stream->peek(-1));
	Assert::same($token2, $stream->peek());
	Assert::same($eof, $stream->peek(1));
	Assert::null($stream->peek(2));

	$stream->consume();
	Assert::null($stream->peek(-3));
	Assert::same($token1, $stream->peek(-2));
	Assert::same($token2, $stream->peek(-1));
	Assert::same($eof, $stream->peek());
	Assert::null($stream->peek(1));
});


test('peek() jump forward', function () {
	$token1 = new Token('', '');
	$token2 = new Token('', '');
	$token3 = new Token('', '');
	$eof = new Token(Token::END, '');
	$stream = new TokenStream([$token1, $token2, $token3, $eof]);

	Assert::same($token3, $stream->peek(2));
});


test('consume() any token', function () {
	$token = new Token('', '');
	$eof = new Token(Token::END, '');
	$stream = new TokenStream([$token, $eof]);

	Assert::same($token, $stream->consume());
	Assert::same(1, $stream->getIndex());
	Assert::same($eof, $stream->consume());
	Assert::same(1, $stream->getIndex());
});


test('consume() kind of token', function () {
	$token = new Token(Token::TEXT, 'foo');
	$eof = new Token(Token::END, '');
	$stream = new TokenStream([$token, $eof]);

	Assert::exception(
		fn() => $stream->consume('bar'),
		CompileException::class,
		"Unexpected 'foo', expecting 'bar'",
	);
	Assert::same(0, $stream->getIndex());
	Assert::same($token, $stream->consume('foo'));
	Assert::same(1, $stream->getIndex());
});


test('tryConsume() kind of token', function () {
	$token = new Token(Token::TEXT, 'foo');
	$eof = new Token(Token::END, '');
	$stream = new TokenStream([$token, $eof]);

	Assert::null($stream->tryConsume('bar'));
	Assert::same(0, $stream->getIndex());
	Assert::same($token, $stream->tryConsume('foo'));
	Assert::same(1, $stream->getIndex());
});


test('seek()', function () {
	$token = new Token('', '');
	$eof = new Token(Token::END, '');
	$stream = new TokenStream([$token, $eof]);

	Assert::noError(fn() => $stream->seek(1));
	Assert::exception(
		fn() => $stream->seek(2),
		InvalidArgumentException::class,
		'The position is out of range.',
	);
	Assert::exception(
		fn() => $stream->seek(-1),
		InvalidArgumentException::class,
		'The position is out of range.',
	);
});
