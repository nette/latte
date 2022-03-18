<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$lexer = new Latte\Compiler\TemplateLexer;


Assert::same(['=', '$var', '', false, false], $lexer->parseMacroTag('$var'));
Assert::same(['=', '$var', '|noescape', false, false], $lexer->parseMacroTag('$var|noescape'));
Assert::same(['=', '$var', '|noescape', true, false], $lexer->parseMacroTag('$var|noescape/'));
Assert::same(['=', '$var||false', '', false, false], $lexer->parseMacroTag('$var||false'));
Assert::same(['_', '"I love Nette"', '|noescape', false, false], $lexer->parseMacroTag('_"I love Nette"|noescape'));
Assert::same(['=', '__("I love Nette")', '|noescape', false, false], $lexer->parseMacroTag('__("I love Nette")|noescape'));
Assert::same(['_', '$var', '', false, false], $lexer->parseMacroTag('_$var'));
Assert::same(['_', '$var', '', false, false], $lexer->parseMacroTag('_ $var'));
Assert::same(['_', '', '', false, false], $lexer->parseMacroTag('_'));
Assert::same(['_', '', '', false, true], $lexer->parseMacroTag('/_'));
Assert::same(['=', '$var', '|noescape', false, false], $lexer->parseMacroTag('=$var|noescape'));
Assert::same(['=', '$var', '', false, false], $lexer->parseMacroTag('=$var'));
Assert::same(['=', '$var', '', false, false], $lexer->parseMacroTag('= $var'));
Assert::same(['=', 'function()', '', false, false], $lexer->parseMacroTag('function()'));
Assert::same(['=', 'md5()', '', false, false], $lexer->parseMacroTag('md5()'));
Assert::same(['foo:bar', '', '', false, false], $lexer->parseMacroTag('foo:bar'));
Assert::same(['foo-bar', '', '', false, false], $lexer->parseMacroTag('foo-bar'));
Assert::same(['foo.bar', '', '', false, false], $lexer->parseMacroTag('foo.bar'));
Assert::same(['=', ':bar', '', false, false], $lexer->parseMacroTag(':bar'));
Assert::same(['=', '-bar', '', false, false], $lexer->parseMacroTag('-bar'));
Assert::same(['=', '.bar', '', false, false], $lexer->parseMacroTag('.bar'));
Assert::same(['foo', '..bar', '', false, false], $lexer->parseMacroTag('foo..bar'));
Assert::same(['foo', '--bar', '', false, false], $lexer->parseMacroTag('foo--bar'));
Assert::same(['=', 'class::member', '', false, false], $lexer->parseMacroTag('class::member'));
Assert::same(['=', 'Namespace\Class::member()', '', false, false], $lexer->parseMacroTag('Namespace\Class::member()'));
Assert::same(['Link', '$var', '', false, false], $lexer->parseMacroTag('Link $var'));
Assert::same(['link', '$var', '', false, false], $lexer->parseMacroTag('link $var'));
Assert::same(['link', '$var', '', false, false], $lexer->parseMacroTag('link$var'));
Assert::same(['block', '#name', '', false, false], $lexer->parseMacroTag('block #name'));
Assert::same(['block', '#name', '', false, false], $lexer->parseMacroTag('block#name'));
Assert::same(['block', '', '', false, true], $lexer->parseMacroTag('/block'));
Assert::same(['block', '#name', '', false, true], $lexer->parseMacroTag('/block#name'));
Assert::same(['', '', '', false, true], $lexer->parseMacroTag('/'));
Assert::same(['l', '', '', false, false], $lexer->parseMacroTag('l'));
Assert::same(['=', '10', '', false, false], $lexer->parseMacroTag('10'));
Assert::same(['=', "'str'", '', false, false], $lexer->parseMacroTag("'str'"));
Assert::same(['=', '+10', '', false, false], $lexer->parseMacroTag('+10'));
Assert::same(['=', '-10', '', false, false], $lexer->parseMacroTag('-10'));

Assert::same(['=', '$var', "|mod:'\\':a:b:c':arg2 |mod2:|mod3", false, false], $lexer->parseMacroTag("\$var |mod:'\\':a:b:c':arg2 |mod2:|mod3"));
Assert::same(['=', '$var', '|mod|mod2|noescape', false, false], $lexer->parseMacroTag('$var|mod|mod2|noescape'));
Assert::same(['=', "'a|b\\'c'", '', false, false], $lexer->parseMacroTag("'a|b\\'c'"));

Assert::same(['link', 'foo => ($var|mod)', '|mod2', false, false], $lexer->parseMacroTag('link foo => ($var|mod)|mod2'));
Assert::same(['link', 'foo => ($var|mod)', '|mod2:(1|foo)', false, false], $lexer->parseMacroTag('link foo => ($var|mod)|mod2:(1|foo)'));
Assert::same(['link', 'foo => "(aa"', '|mod2:"bb)"', false, false], $lexer->parseMacroTag('link foo => "(aa"|mod2:"bb)"'));
