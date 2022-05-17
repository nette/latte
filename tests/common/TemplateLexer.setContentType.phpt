<?php

declare(strict_types=1);

use Latte\Compiler\Token;
use Latte\ContentType;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function tokenize($s, $contentType = null)
{
	$lexer = new Latte\Compiler\TemplateLexer;
	$tokens = $lexer->tokenize($s, $contentType ?: ContentType::Html);
	return array_map(
		fn(Token $token) => [$token->type, $token->text, $token->position->line . ':' . $token->position->column],
		iterator_to_array($tokens, false),
	);
}


Assert::same([
	[Token::Html_TagOpen, '<', '1:1'],
	[Token::Html_Name, 'script', '1:2'],
	[Token::Html_TagClose, '>', '1:8'],
	[Token::Text, ' <div /> ', '1:9'],
	[Token::Html_TagOpen, '<', '1:18'],
	[Token::Slash, '/', '1:19'],
	[Token::Html_Name, 'script', '1:20'],
	[Token::Html_TagClose, '>', '1:26'],
	[Token::End, '', '1:27'],
], tokenize('<script> <div /> </script>', ContentType::Html));

Assert::same([
	[Token::Latte_TagOpen, '{', '1:1'],
	[Token::Latte_Name, 'contentType', '1:2'],
	[Token::Latte_Args, ' html', '1:13'],
	[Token::Latte_TagClose, '}', '1:18'],
	[Token::Html_TagOpen, '<', '1:19'],
	[Token::Html_Name, 'script', '1:20'],
	[Token::Html_TagClose, '>', '1:26'],
	[Token::Text, ' <div /> ', '1:27'],
	[Token::Html_TagOpen, '<', '1:36'],
	[Token::Slash, '/', '1:37'],
	[Token::Html_Name, 'script', '1:38'],
	[Token::Html_TagClose, '>', '1:44'],
	[Token::End, '', '1:45'],
], tokenize('{contentType html}<script> <div /> </script>'));

Assert::same([
	[Token::Html_TagOpen, '<', '1:1'],
	[Token::Html_Name, 'script', '1:2'],
	[Token::Html_TagClose, '>', '1:8'],
	[Token::Text, ' ', '1:9'],
	[Token::Html_TagOpen, '<', '1:10'],
	[Token::Html_Name, 'div', '1:11'],
	[Token::Whitespace, ' ', '1:14'],
	[Token::Slash, '/', '1:15'],
	[Token::Html_TagClose, '>', '1:16'],
	[Token::Text, ' ', '1:17'],
	[Token::Html_TagOpen, '<', '1:18'],
	[Token::Slash, '/', '1:19'],
	[Token::Html_Name, 'script', '1:20'],
	[Token::Html_TagClose, '>', '1:26'],
	[Token::End, '', '1:27'],
], tokenize('<script> <div /> </script>', ContentType::Xml));

Assert::same([
	[Token::Text, '<script> <div /> </script>', '1:1'],
	[Token::End, '', '1:27'],
], tokenize('<script> <div /> </script>', ContentType::Text));

Assert::same([
	[Token::Text, '<script> <div /> </script>', '1:1'],
	[Token::End, '', '1:27'],
], tokenize('<script> <div /> </script>', ContentType::ICal));

Assert::same([
	[Token::Html_TagOpen, '<', '1:1'],
	[Token::Html_Name, 'script', '1:2'],
	[Token::Whitespace, ' ', '1:8'],
	[Token::Slash, '/', '1:9'],
	[Token::Html_TagClose, '>', '1:10'],
	[Token::Text, ' ', '1:11'],
	[Token::Html_TagOpen, '<', '1:12'],
	[Token::Html_Name, 'div', '1:13'],
	[Token::Whitespace, ' ', '1:16'],
	[Token::Slash, '/', '1:17'],
	[Token::Html_TagClose, '>', '1:18'],
	[Token::End, '', '1:19'],
], tokenize('<script /> <div />', ContentType::Html));
