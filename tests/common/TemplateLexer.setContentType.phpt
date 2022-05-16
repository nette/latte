<?php

declare(strict_types=1);

use Latte\Compiler\Token;
use Latte\ContentType;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function tokenize($s, $contentType = null)
{
	$lexer = new Latte\Compiler\TemplateLexer;
	return array_map(
		fn(Token $token) => [$token->type, $token->text, $token->position->line . ':' . $token->position->column],
		$lexer->tokenize($s, $contentType ?: ContentType::Html),
	);
}


Assert::same([
	['htmlTagBegin', '<script', '1:1'],
	['htmlTagEnd', '>', '1:8'],
	['text', ' <div /> ', '1:9'],
	['htmlTagBegin', '</script', '1:18'],
	['htmlTagEnd', '>', '1:26'],
	['end', '', '1:27'],
], tokenize('<script> <div /> </script>', ContentType::Html));

Assert::same([
	['macroTag', '{contentType html}', '1:1'],
	['htmlTagBegin', '<script', '1:19'],
	['htmlTagEnd', '>', '1:26'],
	['text', ' <div /> ', '1:27'],
	['htmlTagBegin', '</script', '1:36'],
	['htmlTagEnd', '>', '1:44'],
	['end', '', '1:45'],
], tokenize('{contentType html}<script> <div /> </script>'));

Assert::same([
	['htmlTagBegin', '<script', '1:1'],
	['htmlTagEnd', '>', '1:8'],
	['text', ' ', '1:9'],
	['htmlTagBegin', '<div', '1:10'],
	['text', ' ', '1:14'],
	['htmlTagEnd', '/>', '1:15'],
	['text', ' ', '1:17'],
	['htmlTagBegin', '</script', '1:18'],
	['htmlTagEnd', '>', '1:26'],
	['end', '', '1:27'],
], tokenize('<script> <div /> </script>', ContentType::Xml));

Assert::same([
	['text', '<script> <div /> </script>', '1:1'],
	['end', '', '1:27'],
], tokenize('<script> <div /> </script>', ContentType::Text));

Assert::same([
	['text', '<script> <div /> </script>', '1:1'],
	['end', '', '1:27'],
], tokenize('<script> <div /> </script>', ContentType::ICal));

Assert::same([
	['htmlTagBegin', '<script', '1:1'],
	['text', ' ', '1:8'],
	['htmlTagEnd', '/>', '1:9'],
	['text', ' ', '1:11'],
	['htmlTagBegin', '<div', '1:12'],
	['text', ' ', '1:16'],
	['htmlTagEnd', '/>', '1:17'],
	['end', '', '1:19'],
], tokenize('<script /> <div />', ContentType::Html));
