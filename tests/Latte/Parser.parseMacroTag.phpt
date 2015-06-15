<?php

/**
 * Test: Latte\Parser::parseMacroTag().
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$parser = new Latte\Parser;


Assert::same(['?', 'echo', '', FALSE], $parser->parseMacroTag('? echo'));
Assert::same(['?', 'echo', '', FALSE], $parser->parseMacroTag('?echo'));
Assert::same(['?', 'echo', '', TRUE], $parser->parseMacroTag('?echo/'));
Assert::same(['?', '', '', FALSE], $parser->parseMacroTag('?'));
Assert::same(['?', '', '', TRUE], $parser->parseMacroTag('?/'));
Assert::same(['?', '', '', TRUE], $parser->parseMacroTag('? /'));
Assert::same(['?', '/', '', FALSE], $parser->parseMacroTag('? / '));
Assert::same(['=', '$var', '', FALSE], $parser->parseMacroTag('$var'));
Assert::same(['=', '$var', '|noescape', FALSE], $parser->parseMacroTag('$var|noescape'));
Assert::same(['=', '$var', '|noescape', TRUE], $parser->parseMacroTag('$var|noescape/'));
Assert::same(['_', '"I love Nette"', '|noescape', FALSE], $parser->parseMacroTag('_"I love Nette"|noescape'));
Assert::same(['_', '$var', '', FALSE], $parser->parseMacroTag('_$var'));
Assert::same(['_', '$var', '', FALSE], $parser->parseMacroTag('_ $var'));
Assert::same(['_', '', '', FALSE], $parser->parseMacroTag('_'));
Assert::same(['/_', '', '', FALSE], $parser->parseMacroTag('/_'));
Assert::same(['=', '$var', '|noescape', FALSE], $parser->parseMacroTag('=$var|noescape'));
Assert::same(['=', '$var', '', FALSE], $parser->parseMacroTag('=$var'));
Assert::same(['=', '$var', '', FALSE], $parser->parseMacroTag('= $var'));
Assert::same(['=', 'function()', '', FALSE], $parser->parseMacroTag('function()'));
Assert::same(['=', 'md5()', '', FALSE], $parser->parseMacroTag('md5()'));
Assert::same(['foo:bar', '', '', FALSE], $parser->parseMacroTag('foo:bar'));
Assert::same(['=', ':bar', '', FALSE], $parser->parseMacroTag(':bar'));
Assert::same(['=', 'class::member', '', FALSE], $parser->parseMacroTag('class::member'));
Assert::same(['=', 'Namespace\Class::member()', '', FALSE], $parser->parseMacroTag('Namespace\Class::member()'));
Assert::same(['Link', '$var', '', FALSE], $parser->parseMacroTag('Link $var'));
Assert::same(['link', '$var', '', FALSE], $parser->parseMacroTag('link $var'));
Assert::same(['link', '$var', '', FALSE], $parser->parseMacroTag('link$var'));
Assert::same(['block', '#name', '', FALSE], $parser->parseMacroTag('block #name'));
Assert::same(['block', '#name', '', FALSE], $parser->parseMacroTag('block#name'));
Assert::same(['/block', '', '', FALSE], $parser->parseMacroTag('/block'));
Assert::same(['/block', '#name', '', FALSE], $parser->parseMacroTag('/block#name'));
Assert::same(['/', '', '', FALSE], $parser->parseMacroTag('/'));
Assert::same(['l', '', '', FALSE], $parser->parseMacroTag('l'));
Assert::same(['=', '10', '', FALSE], $parser->parseMacroTag('10'));
Assert::same(['=', "'str'", '', FALSE], $parser->parseMacroTag("'str'"));
Assert::same(['=', '+10', '', FALSE], $parser->parseMacroTag('+10'));
Assert::same(['=', '-10', '', FALSE], $parser->parseMacroTag('-10'));

Assert::same(['=', '$var', "|mod:'\\':a:b:c':arg2 |mod2:|mod3", FALSE], $parser->parseMacroTag("\$var |mod:'\\':a:b:c':arg2 |mod2:|mod3"));
Assert::same(['=', '$var', '|mod|mod2|noescape', FALSE], $parser->parseMacroTag('$var|mod|mod2|noescape'));
