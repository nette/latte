<?php

/**
 * Test: Latte\Parser::parse()
 */

declare(strict_types=1);

use Latte\Engine;
use Latte\Token;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function parse($s, $contentType = null)
{
	$parser = new Latte\Parser;
	$parser->setContentType($contentType ?: Engine::CONTENT_HTML);
	return array_map(fn(Token $token) => [$token->type, $token->text], $parser->parse($s));
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
	['macroTag', '{contentType xml}'],
	['htmlTagBegin', '<script'],
	['htmlTagEnd', '>'],
	['text', ' '],
	['htmlTagBegin', '<div'],
	['htmlTagEnd', ' />'],
	['text', ' '],
	['htmlTagBegin', '</script'],
	['htmlTagEnd', '>'],
], parse('{contentType xml}<script> <div /> </script>'));

Assert::same([
	['text', '<script> <div /> </script>'],
], parse('<script> <div /> </script>', Engine::CONTENT_TEXT));

Assert::same([
	['macroTag', '{contentType text}'],
	['text', '<script> <div /> </script>'],
], parse('{contentType text}<script> <div /> </script>'));

Assert::same([
	['text', '<script> <div /> </script>'],
], parse('<script> <div /> </script>', Engine::CONTENT_ICAL));

Assert::same([
	['macroTag', '{contentType ical}'],
	['text', '<script> <div /> </script>'],
], parse('{contentType ical}<script> <div /> </script>'));

Assert::same([
	['htmlTagBegin', '<script'],
	['htmlTagEnd', ' />'],
	['text', ' '],
	['htmlTagBegin', '<div'],
	['htmlTagEnd', ' />'],
], parse('<script /> <div />', Engine::CONTENT_HTML));
