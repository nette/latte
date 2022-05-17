<?php

declare(strict_types=1);

use Latte\CompileException;
use Latte\Compiler\Token;
use Latte\Compiler\TokenStream;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('end', function () {
	$eof = new Token(Token::End, '');
	$stream = new TokenStream(new ArrayIterator([$eof]));
	Assert::false($stream->is());
	Assert::same($eof, $stream->peek());
	Assert::null($stream->peek(1));
	Assert::same($eof, $stream->consume());
	Assert::same($eof, $stream->consume());
	Assert::same(0, $stream->getIndex());
});


test('is()', function () {
	$token = new Token(Token::Text, 'foo');
	$eof = new Token(Token::End, '');
	$stream = new TokenStream(new ArrayIterator([$token, $eof]));

	Assert::false($stream->is());
	Assert::true($stream->is('foo'));
	Assert::false($stream->is(''));
	Assert::true($stream->is('', 'foo'));
	Assert::true($stream->is(Token::Text));

	Assert::same($token, $stream->consume());
	Assert::false($stream->is('foo'));
});


test('peek()', function () {
	$token1 = new Token(1, '');
	$token2 = new Token(2, '');
	$eof = new Token(Token::End, '');
	$stream = new TokenStream(new ArrayIterator([$token1, $token2, $eof]));

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
	$token1 = new Token(1, '');
	$token2 = new Token(2, '');
	$token3 = new Token(3, '');
	$eof = new Token(Token::End, '');
	$stream = new TokenStream(new ArrayIterator([$token1, $token2, $token3, $eof]));

	Assert::same($token3, $stream->peek(2));
});


test('consume() any token', function () {
	$token = new Token(1, '');
	$eof = new Token(Token::End, '');
	$stream = new TokenStream(new ArrayIterator([$token, $eof]));

	Assert::same($token, $stream->consume());
	Assert::same(1, $stream->getIndex());
	Assert::same($eof, $stream->consume());
	Assert::same(1, $stream->getIndex());
});


test('consume() kind of token', function () {
	$token = new Token(Token::Text, 'foo');
	$eof = new Token(Token::End, '');
	$stream = new TokenStream(new ArrayIterator([$token, $eof]));

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
	$token = new Token(Token::Text, 'foo');
	$eof = new Token(Token::End, '');
	$stream = new TokenStream(new ArrayIterator([$token, $eof]));

	Assert::null($stream->tryConsume('bar'));
	Assert::same(0, $stream->getIndex());
	Assert::same($token, $stream->tryConsume('foo'));
	Assert::same(1, $stream->getIndex());
});


test('seek()', function () {
	$token = new Token(1, '');
	$eof = new Token(Token::End, '');
	$stream = new TokenStream(new ArrayIterator([$token, $eof]));

	Assert::exception(
		fn() => $stream->seek(0),
		InvalidArgumentException::class,
		'The position is out of range.',
	);
	$stream->consume();
	Assert::noError(fn() => $stream->seek(0));
	Assert::exception(
		fn() => $stream->seek(-1),
		InvalidArgumentException::class,
		'The position is out of range.',
	);
});


test('generator is read on the first usage', function () {
	$generator = function () {
		throw new Exception('Generator');
		yield null;
	};
	$stream = new TokenStream($generator());
	Assert::exception(
		fn() => $stream->peek(),
		Throwable::class,
		'Generator',
	);
});


test('generator is read continually', function () {
	$generator = function () {
		yield new Token(1, '');
		throw new Exception('Generator');
	};
	$stream = new TokenStream($generator());
	$stream->consume();
	Assert::exception(
		fn() => $stream->peek(),
		Throwable::class,
		'Generator',
	);
});
