<?php

declare(strict_types=1);

use Latte\Compiler\Token;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function tokenize($s)
{
	$parser = new Latte\Compiler\TemplateParser;
	$parser->addTags((new Latte\Essential\CoreExtension)->getTags());
	$parser->parse($s);
	$tokens = Assert::with($parser->getStream(), fn() => $this->tokens);
	return array_map(
		fn(Token $token) => [$token->type, $token->text, $token->position->line . ':' . $token->position->column],
		$tokens,
	);
}


Assert::same([
	[Token::Text, '<0>', '1:1'],
	[Token::End, '', '1:4'],
], tokenize('<0>'));

Assert::same([
	[Token::Html_TagOpen, '<', '1:1'],
	[Token::Html_Name, 'x:-._', '1:2'],
	[Token::Html_TagClose, '>', '1:7'],
	[Token::End, '', '1:8'],
], tokenize('<x:-._>'));

Assert::same([
	[Token::Text, '   ', '1:1'],
	[Token::Html_BogusOpen, '<?', '1:4'],
	[Token::Text, 'xml encoding="', '1:6'],
	[Token::Latte_TagOpen, '{', '1:20'],
	[Token::Php_Variable, '$enc', '1:21'],
	[Token::Latte_TagClose, '}', '1:25'],
	[Token::Text, '" ?', '1:26'],
	[Token::Html_TagClose, '>', '1:29'],
	[Token::Text, 'text', '1:30'],
	[Token::End, '', '1:34'],
], tokenize('   <?xml encoding="{$enc}" ?>text'));

Assert::same([
	[Token::Text, 'x ', '1:1'],
	[Token::Html_BogusOpen, '<?', '1:3'],
	[Token::Text, '= $abc ?', '1:5'],
	[Token::Html_TagClose, '>', '1:13'],
	[Token::Text, 'text', '1:14'],
	[Token::End, '', '1:18'],
], tokenize('x <?= $abc ?>text'));

Assert::same([
	[Token::Text, "\n ", '1:1'],
	[Token::Html_BogusOpen, '<?', '2:2'],
	[Token::Text, 'bogus', '2:4'],
	[Token::Html_TagClose, '>', '2:9'],
	[Token::Text, "\ntext", '2:10'],
	[Token::End, '', '3:5'],
], tokenize("\n <?bogus>\ntext"));

Assert::same([
	[Token::Html_BogusOpen, '<!', '1:1'],
	[Token::Text, 'doctype html', '1:3'],
	[Token::Html_TagClose, '>', '1:15'],
	[Token::Text, 'text', '1:16'],
	[Token::End, '', '1:20'],
], tokenize('<!doctype html>text'));

Assert::same([
	[Token::Text, '  ', '1:1'],
	[Token::Html_BogusOpen, '<!', '1:3'],
	[Token::Text, '--', '1:5'],
	[Token::Html_TagClose, '>', '1:7'],
	[Token::Text, ' text> --> text', '1:8'],
	[Token::End, '', '1:23'],
], tokenize('  <!--> text> --> text'));

Assert::same([
	[Token::Text, '  ', '1:1'],
	[Token::Html_CommentOpen, '<!--', '1:3'],
	[Token::Text, ' text> ', '1:7'],
	[Token::Html_CommentClose, '-->', '1:14'],
	[Token::Text, ' text', '1:17'],
	[Token::End, '', '1:22'],
], tokenize('  <!-- text> --> text'));

Assert::same([
	[Token::Html_BogusOpen, '<!', '1:1'],
	[Token::Text, 'bogus', '1:3'],
	[Token::Html_TagClose, '>', '1:8'],
	[Token::Text, 'text', '1:9'],
	[Token::End, '', '1:13'],
], tokenize('<!bogus>text'));


// html
Assert::same([
	[Token::Indentation, '  ', '1:1'],
	[Token::Html_TagOpen, '<', '1:3'],
	[Token::Html_Name, 'div', '1:4'],
	[Token::Html_TagClose, '>', '1:7'],
	[Token::Newline, "\n", '1:8'],
	[Token::Text, "\nx  ", '2:1'],
	[Token::Html_TagOpen, '<', '3:4'],
	[Token::Slash, '/', '3:5'],
	[Token::Html_Name, 'div', '3:6'],
	[Token::Html_TagClose, '>', '3:9'],
	[Token::Newline, "\n", '3:10'],
	[Token::Text, "\n", '4:1'],
	[Token::End, '', '5:1'],
], tokenize("  <div>\n\nx  </div>\n\n"));

Assert::same([
	[Token::Html_TagOpen, '<', '1:1'],
	[Token::Html_Name, 'a', '1:2'],
	[Token::Whitespace, ' ', '1:3'],
	[Token::Html_Name, 'href', '1:4'],
	[Token::Equals, '=', '1:8'],
	[Token::Html_Name, '/a/', '1:9'],
	[Token::Html_TagClose, '>', '1:12'],
	[Token::End, '', '1:13'],
], tokenize('<a href=/a/>'));

Assert::same([
	[Token::Html_TagOpen, '<', '1:1'],
	[Token::Html_Name, 'a', '1:2'],
	[Token::Whitespace, ' ', '1:3'],
	[Token::Html_Name, 'title', '1:4'],
	[Token::Slash, '/', '1:9'],
	[Token::Html_TagClose, '>', '1:10'],
	[Token::End, '', '1:11'],
], tokenize('<a title/>'));

Assert::same([
	[Token::Html_TagOpen, '<', '1:1'],
	[Token::Html_Name, 'meta', '1:2'],
	[Token::Whitespace, ' ', '1:6'],
	[Token::Html_Name, 'a', '1:7'],
	[Token::Whitespace, ' ', '1:8'],
	[Token::Html_Name, 'b', '1:9'],
	[Token::Whitespace, ' ', '1:10'],
	[Token::Html_Name, 'c', '1:11'],
	[Token::Whitespace, ' ', '1:12'],
	[Token::Equals, '=', '1:13'],
	[Token::Whitespace, ' ', '1:14'],
	[Token::Html_Name, 'd', '1:15'],
	[Token::Whitespace, ' ', '1:16'],
	[Token::Html_Name, 'n:if', '1:17'],
	[Token::Whitespace, ' ', '1:21'],
	[Token::Equals, '=', '1:22'],
	[Token::Whitespace, ' ', '1:23'],
	[Token::Quote, '\'', '1:24'],
	[Token::Text, 'f', '1:25'],
	[Token::Quote, '\'', '1:26'],
	[Token::Whitespace, ' ', '1:27'],
	[Token::Html_Name, 'g', '1:28'],
	[Token::Equals, '=', '1:29'],
	[Token::Quote, '"', '1:30'],
	[Token::Text, 'h', '1:31'],
	[Token::Quote, '"', '1:32'],
	[Token::Whitespace, ' ', '1:33'],
	[Token::Html_Name, 'i', '1:34'],
	[Token::Html_TagClose, '>', '1:35'],
	[Token::End, '', '1:36'],
], tokenize('<meta a b c = d n:if = \'f\' g="h" i>'));

Assert::same([
	[Token::Html_TagOpen, '<', '1:1'],
	[Token::Html_Name, 'div', '1:2'],
	[Token::Whitespace, ' ', '1:5'],
	[Token::Html_Name, 'a', '1:6'],
	[Token::Whitespace, ' ', '1:7'],
	[Token::Latte_TagOpen, '{', '1:8'],
	[Token::Latte_Name, '=', '1:9'],
	[Token::Php_Integer, '0', '1:10'],
	[Token::Latte_TagClose, '}', '1:11'],
	[Token::Whitespace, ' ', '1:12'],
	[Token::Html_Name, 'c', '1:13'],
	[Token::Whitespace, ' ', '1:14'],
	[Token::Equals, '=', '1:15'],
	[Token::Whitespace, ' ', '1:16'],
	[Token::Latte_TagOpen, '{', '1:17'],
	[Token::Latte_Name, '=', '1:18'],
	[Token::Php_Integer, '0', '1:19'],
	[Token::Latte_TagClose, '}', '1:20'],
	[Token::Whitespace, ' ', '1:21'],
	[Token::Html_Name, 'e', '1:22'],
	[Token::Whitespace, ' ', '1:23'],
	[Token::Equals, '=', '1:24'],
	[Token::Whitespace, ' ', '1:25'],
	[Token::Html_Name, 'a', '1:26'],
	[Token::Latte_TagOpen, '{', '1:27'],
	[Token::Latte_Name, '=', '1:28'],
	[Token::Php_Integer, '0', '1:29'],
	[Token::Latte_TagClose, '}', '1:30'],
	[Token::Html_Name, 'c', '1:31'],
	[Token::Whitespace, ' ', '1:32'],
	[Token::Html_Name, 'f', '1:33'],
	[Token::Whitespace, ' ', '1:34'],
	[Token::Equals, '=', '1:35'],
	[Token::Whitespace, ' ', '1:36'],
	[Token::Quote, '"', '1:37'],
	[Token::Text, 'a', '1:38'],
	[Token::Latte_TagOpen, '{', '1:39'],
	[Token::Latte_Name, '=', '1:40'],
	[Token::Php_Integer, '0', '1:41'],
	[Token::Latte_TagClose, '}', '1:42'],
	[Token::Text, 'c', '1:43'],
	[Token::Quote, '"', '1:44'],
	[Token::Html_TagClose, '>', '1:45'],
	[Token::End, '', '1:46'],
], tokenize('<div a {=0} c = {=0} e = a{=0}c f = "a{=0}c">'));

Assert::same([
	[Token::Html_TagOpen, '<', '1:1'],
	[Token::Html_Name, 'script', '1:2'],
	[Token::Html_TagClose, '>', '1:8'],
	[Token::Text, ' hello<a> ', '1:9'],
	[Token::Html_TagOpen, '<', '1:19'],
	[Token::Slash, '/', '1:20'],
	[Token::Html_Name, 'script', '1:21'],
	[Token::Html_TagClose, '>', '1:27'],
	[Token::End, '', '1:28'],
], tokenize('<script> hello<a> </script>'));

// latte elements
Assert::same([
	[Token::Indentation, ' ', '1:1'],
	[Token::Latte_TagOpen, '{', '1:2'],
	[Token::Latte_Name, 'if', '1:3'],
	[Token::Php_Whitespace, ' ', '1:5'],
	[Token::Php_Identifier, 'bar', '1:6'],
	[Token::Latte_TagClose, '}', '1:9'],
	[Token::Text, ' ... ', '1:10'],
	[Token::Latte_TagOpen, '{', '1:15'],
	[Token::Slash, '/', '1:16'],
	[Token::Latte_Name, 'if', '1:17'],
	[Token::Latte_TagClose, '}', '1:19'],
	[Token::Newline, "\n", '1:20'],
	[Token::Text, "\n ", '2:1'],
	[Token::End, '', '3:2'],
], tokenize(" {if bar} ... {/if}\n\n "));

Assert::same([
	[Token::Indentation, ' ', '1:1'],
	[Token::Latte_CommentOpen, '{*', '1:2'],
	[Token::Text, ' comment ', '1:4'],
	[Token::Latte_CommentClose, '*}', '1:13'],
	[Token::Newline, "\n\n", '1:15'],
	[Token::Text, "\n ", '3:1'],
	[Token::End, '', '4:2'],
], tokenize(" {* comment *}\n\n\n "));
