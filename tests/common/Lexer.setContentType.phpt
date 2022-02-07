<?php

declare(strict_types=1);

use Latte\Compiler\LegacyToken;
use Latte\Engine;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


function parse($s, $contentType = null)
{
	$lexer = new Latte\Compiler\Lexer;
	$lexer->setContentType($contentType ?: Engine::CONTENT_HTML);
	return array_map(fn(LegacyToken $token) => [$token->type, $token->text], $lexer->tokenize($s)->getTokens());
}


Assert::same([
	['htmlTagBegin', '<script'],
	['htmlTagEnd', '>'],
	['text', ' <div /> '],
	['htmlTagBegin', '</script'],
	['htmlTagEnd', '>'],
], parse('<script> <div /> </script>', Engine::CONTENT_HTML));

Assert::same([
	['macroTag', '{contentType html}'],
	['htmlTagBegin', '<script'],
	['htmlTagEnd', '>'],
	['text', ' <div /> '],
	['htmlTagBegin', '</script'],
	['htmlTagEnd', '>'],
], parse('{contentType html}<script> <div /> </script>'));

Assert::same([
	['htmlTagBegin', '<script'],
	['htmlTagEnd', '>'],
	['text', ' '],
	['htmlTagBegin', '<div'],
	['htmlTagEnd', ' />'],
	['text', ' '],
	['htmlTagBegin', '</script'],
	['htmlTagEnd', '>'],
], parse('<script> <div /> </script>', Engine::CONTENT_XML));

Assert::same([
	['text', '<script> <div /> </script>'],
], parse('<script> <div /> </script>', Engine::CONTENT_TEXT));

Assert::same([
	['text', '<script> <div /> </script>'],
], parse('<script> <div /> </script>', Engine::CONTENT_ICAL));

Assert::same([
	['htmlTagBegin', '<script'],
	['htmlTagEnd', ' />'],
	['text', ' '],
	['htmlTagBegin', '<div'],
	['htmlTagEnd', ' />'],
], parse('<script /> <div />', Engine::CONTENT_HTML));
