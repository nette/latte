<?php

declare(strict_types=1);

use Latte\Compiler\Token;
use Latte\Engine;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function tokenize($s, $contentType = null)
{
	$lexer = new Latte\Compiler\TemplateLexer;
	$lexer->setContentType($contentType ?: Engine::CONTENT_HTML);
	return array_map(fn(Token $token) => [$token->type, $token->text], $lexer->tokenize($s));
}


Assert::same([
	['htmlTagBegin', '<script'],
	['htmlTagEnd', '>'],
	['text', ' <div /> '],
	['htmlTagBegin', '</script'],
	['htmlTagEnd', '>'],
], tokenize('<script> <div /> </script>', Engine::CONTENT_HTML));

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
], tokenize('<script> <div /> </script>', Engine::CONTENT_XML));

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
], tokenize('<script> <div /> </script>', Engine::CONTENT_TEXT));

Assert::same([
	['macroTag', '{contentType text}'],
	['text', '<script> <div /> </script>'],
], tokenize('{contentType text}<script> <div /> </script>'));

Assert::same([
	['text', '<script> <div /> </script>'],
], tokenize('<script> <div /> </script>', Engine::CONTENT_ICAL));

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
], tokenize('<script /> <div />', Engine::CONTENT_HTML));
