<?php

/**
 * Test: Latte\Parser::parseMacroTag().
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$parser = new Latte\Parser;


Assert::same(['=', '$var', '', false, false], $parser->parseMacroTag('$var'));
Assert::same(['=', '$var', '|noescape', false, false], $parser->parseMacroTag('$var|noescape'));
Assert::same(['=', '$var', '|noescape', true, false], $parser->parseMacroTag('$var|noescape/'));
Assert::same(['=', '$var||false', '', false, false], $parser->parseMacroTag('$var||false'));
Assert::same(['_', '"I love Nette"', '|noescape', false, false], $parser->parseMacroTag('_"I love Nette"|noescape'));
Assert::same(['=', '__("I love Nette")', '|noescape', false, false], $parser->parseMacroTag('__("I love Nette")|noescape'));
Assert::same(['_', '$var', '', false, false], $parser->parseMacroTag('_$var'));
Assert::same(['_', '$var', '', false, false], $parser->parseMacroTag('_ $var'));
Assert::same(['_', '', '', false, false], $parser->parseMacroTag('_'));
Assert::same(['_', '', '', false, true], $parser->parseMacroTag('/_'));
Assert::same(['=', '$var', '|noescape', false, false], $parser->parseMacroTag('=$var|noescape'));
Assert::same(['=', '$var', '', false, false], $parser->parseMacroTag('=$var'));
Assert::same(['=', '$var', '', false, false], $parser->parseMacroTag('= $var'));
Assert::same(['=', 'function()', '', false, false], $parser->parseMacroTag('function()'));
Assert::same(['=', 'md5()', '', false, false], $parser->parseMacroTag('md5()'));
Assert::same(['foo:bar', '', '', false, false], $parser->parseMacroTag('foo:bar'));
Assert::same(['foo-bar', '', '', false, false], $parser->parseMacroTag('foo-bar'));
Assert::same(['foo.bar', '', '', false, false], $parser->parseMacroTag('foo.bar'));
Assert::same(['=', ':bar', '', false, false], $parser->parseMacroTag(':bar'));
Assert::same(['=', '-bar', '', false, false], $parser->parseMacroTag('-bar'));
Assert::same(['=', '.bar', '', false, false], $parser->parseMacroTag('.bar'));
Assert::same(['foo', '..bar', '', false, false], $parser->parseMacroTag('foo..bar'));
Assert::same(['foo', '--bar', '', false, false], $parser->parseMacroTag('foo--bar'));
Assert::same(['=', 'class::member', '', false, false], $parser->parseMacroTag('class::member'));
Assert::same(['=', 'Namespace\Class::member()', '', false, false], $parser->parseMacroTag('Namespace\Class::member()'));
Assert::same(['Link', '$var', '', false, false], $parser->parseMacroTag('Link $var'));
Assert::same(['link', '$var', '', false, false], $parser->parseMacroTag('link $var'));
Assert::same(['link', '$var', '', false, false], $parser->parseMacroTag('link$var'));
Assert::same(['block', '#name', '', false, false], $parser->parseMacroTag('block #name'));
Assert::same(['block', '#name', '', false, false], $parser->parseMacroTag('block#name'));
Assert::same(['block', '', '', false, true], $parser->parseMacroTag('/block'));
Assert::same(['block', '#name', '', false, true], $parser->parseMacroTag('/block#name'));
Assert::same(['', '', '', false, true], $parser->parseMacroTag('/'));
Assert::same(['l', '', '', false, false], $parser->parseMacroTag('l'));
Assert::same(['=', '10', '', false, false], $parser->parseMacroTag('10'));
Assert::same(['=', "'str'", '', false, false], $parser->parseMacroTag("'str'"));
Assert::same(['=', '+10', '', false, false], $parser->parseMacroTag('+10'));
Assert::same(['=', '-10', '', false, false], $parser->parseMacroTag('-10'));

Assert::same(['=', '$var', "|mod:'\\':a:b:c':arg2 |mod2:|mod3", false, false], $parser->parseMacroTag("\$var |mod:'\\':a:b:c':arg2 |mod2:|mod3"));
Assert::same(['=', '$var', '|mod|mod2|noescape', false, false], $parser->parseMacroTag('$var|mod|mod2|noescape'));
Assert::same(['=', "'a|b\\'c'", '', false, false], $parser->parseMacroTag("'a|b\\'c'"));

Assert::same(['link', 'foo => ($var|mod)', '|mod2', false, false], $parser->parseMacroTag('link foo => ($var|mod)|mod2'));
Assert::same(['link', 'foo => ($var|mod)', '|mod2:(1|foo)', false, false], $parser->parseMacroTag('link foo => ($var|mod)|mod2:(1|foo)'));
Assert::same(['link', 'foo => "(aa"', '|mod2:"bb)"', false, false], $parser->parseMacroTag('link foo => "(aa"|mod2:"bb)"'));
