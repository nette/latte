<?php

/**
 * Test: Latte\Parser::parseMacroTag().
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$parser = new Latte\Parser;


Assert::same(['?', 'echo', '', FALSE, FALSE], $parser->parseMacroTag('? echo'));
Assert::same(['?', 'echo', '', FALSE, FALSE], $parser->parseMacroTag('?echo'));
Assert::same(['?', 'echo', '', TRUE, FALSE], $parser->parseMacroTag('?echo/'));
Assert::same(['?', '', '', FALSE, FALSE], $parser->parseMacroTag('?'));
Assert::same(['?', '', '', TRUE, FALSE], $parser->parseMacroTag('?/'));
Assert::same(['?', '', '', TRUE, FALSE], $parser->parseMacroTag('? /'));
Assert::same(['?', '/', '', FALSE, FALSE], $parser->parseMacroTag('? / '));
Assert::same(['=', '$var', '', FALSE, FALSE], $parser->parseMacroTag('$var'));
Assert::same(['=', '$var', '|noescape', FALSE, FALSE], $parser->parseMacroTag('$var|noescape'));
Assert::same(['=', '$var', '|noescape', TRUE, FALSE], $parser->parseMacroTag('$var|noescape/'));
Assert::same(['=', '$var||false', '', FALSE, FALSE], $parser->parseMacroTag('$var||false'));
Assert::same(['_', '"I love Nette"', '|noescape', FALSE, FALSE], $parser->parseMacroTag('_"I love Nette"|noescape'));
Assert::same(['_', '$var', '', FALSE, FALSE], $parser->parseMacroTag('_$var'));
Assert::same(['_', '$var', '', FALSE, FALSE], $parser->parseMacroTag('_ $var'));
Assert::same(['_', '', '', FALSE, FALSE], $parser->parseMacroTag('_'));
Assert::same(['_', '', '', FALSE, TRUE], $parser->parseMacroTag('/_'));
Assert::same(['=', '$var', '|noescape', FALSE, FALSE], $parser->parseMacroTag('=$var|noescape'));
Assert::same(['=', '$var', '', FALSE, FALSE], $parser->parseMacroTag('=$var'));
Assert::same(['=', '$var', '', FALSE, FALSE], $parser->parseMacroTag('= $var'));
Assert::same(['=', 'function()', '', FALSE, FALSE], $parser->parseMacroTag('function()'));
Assert::same(['=', 'md5()', '', FALSE, FALSE], $parser->parseMacroTag('md5()'));
Assert::same(['foo:bar', '', '', FALSE, FALSE], $parser->parseMacroTag('foo:bar'));
Assert::same(['=', ':bar', '', FALSE, FALSE], $parser->parseMacroTag(':bar'));
Assert::same(['=', 'class::member', '', FALSE, FALSE], $parser->parseMacroTag('class::member'));
Assert::same(['=', 'Namespace\Class::member()', '', FALSE, FALSE], $parser->parseMacroTag('Namespace\Class::member()'));
Assert::same(['Link', '$var', '', FALSE, FALSE], $parser->parseMacroTag('Link $var'));
Assert::same(['link', '$var', '', FALSE, FALSE], $parser->parseMacroTag('link $var'));
Assert::same(['link', '$var', '', FALSE, FALSE], $parser->parseMacroTag('link$var'));
Assert::same(['block', '#name', '', FALSE, FALSE], $parser->parseMacroTag('block #name'));
Assert::same(['block', '#name', '', FALSE, FALSE], $parser->parseMacroTag('block#name'));
Assert::same(['block', '', '', FALSE, TRUE], $parser->parseMacroTag('/block'));
Assert::same(['block', '#name', '', FALSE, TRUE], $parser->parseMacroTag('/block#name'));
Assert::same(['', '', '', FALSE, TRUE], $parser->parseMacroTag('/'));
Assert::same(['l', '', '', FALSE, FALSE], $parser->parseMacroTag('l'));
Assert::same(['=', '10', '', FALSE, FALSE], $parser->parseMacroTag('10'));
Assert::same(['=', "'str'", '', FALSE, FALSE], $parser->parseMacroTag("'str'"));
Assert::same(['=', '+10', '', FALSE, FALSE], $parser->parseMacroTag('+10'));
Assert::same(['=', '-10', '', FALSE, FALSE], $parser->parseMacroTag('-10'));

Assert::same(['=', '$var', "|mod:'\\':a:b:c':arg2 |mod2:|mod3", FALSE, FALSE], $parser->parseMacroTag("\$var |mod:'\\':a:b:c':arg2 |mod2:|mod3"));
Assert::same(['=', '$var', '|mod|mod2|noescape', FALSE, FALSE], $parser->parseMacroTag('$var|mod|mod2|noescape'));
Assert::same(['=', "'a|b\\'c'", '', FALSE, FALSE], $parser->parseMacroTag("'a|b\\'c'"));

Assert::same(['link', 'foo => ($var|mod)', '|mod2', FALSE, FALSE], $parser->parseMacroTag('link foo => ($var|mod)|mod2'));
Assert::same(['link', 'foo => ($var|mod)', '|mod2:(1|foo)', FALSE, FALSE], $parser->parseMacroTag('link foo => ($var|mod)|mod2:(1|foo)'));
Assert::same(['link', 'foo => "(aa"', '|mod2:"bb)"', FALSE, FALSE], $parser->parseMacroTag('link foo => "(aa"|mod2:"bb)"'));
