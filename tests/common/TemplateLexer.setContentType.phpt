<?php

declare(strict_types=1);

use Latte\Compiler\LegacyToken;
use Latte\Context;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function tokenize($s, $contentType = null)
{
	$lexer = new Latte\Compiler\TemplateLexer;
	$lexer->setContentType($contentType ?: Context::Html);
	return array_map(fn(LegacyToken $token) => [$token->type, $token->text], $lexer->tokenize($s));
}


Assert::same([
	['htmlTagBegin', '<script'],
	['htmlTagEnd', '>'],
	['text', ' <div /> '],
	['htmlTagBegin', '</script'],
	['htmlTagEnd', '>'],
], tokenize('<script> <div /> </script>', Context::Html));

Assert::same([
	['macroTag', '{contentType html}'],
	['htmlTagBegin', '<script'],
	['htmlTagEnd', '>'],
	['text', ' <div /> '],
	['htmlTagBegin', '</script'],
	['htmlTagEnd', '>'],
], tokenize('{contentType html}<script> <div /> </script>'));

Assert::same([
	['htmlTagBegin', '<script'],
	['htmlTagEnd', '>'],
	['text', ' '],
	['htmlTagBegin', '<div'],
	['htmlTagEnd', ' />'],
	['text', ' '],
	['htmlTagBegin', '</script'],
	['htmlTagEnd', '>'],
], tokenize('<script> <div /> </script>', Context::Xml));

Assert::same([
	['macroTag', '{contentType xml}'],
	['htmlTagBegin', '<script'],
	['htmlTagEnd', '>'],
	['text', ' '],
	['htmlTagBegin', '<div'],
	['htmlTagEnd', ' />'],
	['text', ' '],
	['htmlTagBegin', '</script'],
	['htmlTagEnd', '>'],
], tokenize('{contentType xml}<script> <div /> </script>'));

Assert::same([
	['text', '<script> <div /> </script>'],
], tokenize('<script> <div /> </script>', Context::Text));

Assert::same([
	['macroTag', '{contentType text}'],
	['text', '<script> <div /> </script>'],
], tokenize('{contentType text}<script> <div /> </script>'));

Assert::same([
	['text', '<script> <div /> </script>'],
], tokenize('<script> <div /> </script>', Context::ICal));

Assert::same([
	['macroTag', '{contentType ical}'],
	['text', '<script> <div /> </script>'],
], tokenize('{contentType ical}<script> <div /> </script>'));

Assert::same([
	['htmlTagBegin', '<script'],
	['htmlTagEnd', ' />'],
	['text', ' '],
	['htmlTagBegin', '<div'],
	['htmlTagEnd', ' />'],
], tokenize('<script /> <div />', Context::Html));
