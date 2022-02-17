<?php

/**
 * Test: Latte\Lexer::parseMacroTag().
 */

declare(strict_types=1);

use Latte\Compiler\TagInfo;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$lexer = new Latte\Compiler\Lexer;


Assert::same(['=', '$var', false, false], $lexer->parseMacroTag('$var'));
Assert::same(['_', '"I love Nette"', false, false], $lexer->parseMacroTag('_"I love Nette"'));
Assert::same(['=', '__("I love Nette")|noescape', false, false], $lexer->parseMacroTag('__("I love Nette")|noescape'));
Assert::same(['_', '$var', false, false], $lexer->parseMacroTag('_$var'));
Assert::same(['_', '$var', false, false], $lexer->parseMacroTag('_ $var'));
Assert::same(['_', '', false, false], $lexer->parseMacroTag('_'));
Assert::same(['_', '', false, true], $lexer->parseMacroTag('/_'));
Assert::same(['=', '$var', false, false], $lexer->parseMacroTag('=$var'));
Assert::same(['=', '$var', false, false], $lexer->parseMacroTag('= $var'));
Assert::same(['=', 'function()', false, false], $lexer->parseMacroTag('function()'));
Assert::same(['=', 'md5()', false, false], $lexer->parseMacroTag('md5()'));
Assert::same(['foo:bar', '', false, false], $lexer->parseMacroTag('foo:bar'));
Assert::same(['foo-bar', '', false, false], $lexer->parseMacroTag('foo-bar'));
Assert::same(['foo.bar', '', false, false], $lexer->parseMacroTag('foo.bar'));
Assert::same(['=', ':bar', false, false], $lexer->parseMacroTag(':bar'));
Assert::same(['=', '-bar', false, false], $lexer->parseMacroTag('-bar'));
Assert::same(['=', '.bar', false, false], $lexer->parseMacroTag('.bar'));
Assert::same(['foo', '..bar', false, false], $lexer->parseMacroTag('foo..bar'));
Assert::same(['foo', '--bar', false, false], $lexer->parseMacroTag('foo--bar'));
Assert::same(['=', 'class::member', false, false], $lexer->parseMacroTag('class::member'));
Assert::same(['=', 'Namespace\Class::member()', false, false], $lexer->parseMacroTag('Namespace\Class::member()'));
Assert::same(['Link', '$var', false, false], $lexer->parseMacroTag('Link $var'));
Assert::same(['link', '$var', false, false], $lexer->parseMacroTag('link $var'));
Assert::same(['link', '$var', false, false], $lexer->parseMacroTag('link$var'));
Assert::same(['block', '#name', false, false], $lexer->parseMacroTag('block #name'));
Assert::same(['block', '#name', false, false], $lexer->parseMacroTag('block#name'));
Assert::same(['block', '', false, true], $lexer->parseMacroTag('/block'));
Assert::same(['block', '#name', false, true], $lexer->parseMacroTag('/block#name'));
Assert::same(['', '', false, true], $lexer->parseMacroTag('/'));
Assert::same(['l', '', false, false], $lexer->parseMacroTag('l'));
Assert::same(['=', '10', false, false], $lexer->parseMacroTag('10'));
Assert::same(['=', "'str'", false, false], $lexer->parseMacroTag("'str'"));
Assert::same(['=', '+10', false, false], $lexer->parseMacroTag('+10'));
Assert::same(['=', '-10', false, false], $lexer->parseMacroTag('-10'));

$tag = new TagInfo('', "\$var |mod:'\\':a:b:c':arg2 |mod2:|mod3");
$tag->extractModifier();
Assert::same("|mod:'\\':a:b:c':arg2 |mod2:|mod3", $tag->modifiers);

$tag = new TagInfo('', "'a|b\\'c'");
$tag->extractModifier();
Assert::same('', $tag->modifiers);

$tag = new TagInfo('', 'foo => ($var|mod)|mod2');
$tag->extractModifier();
Assert::same('|mod2', $tag->modifiers);

$tag = new TagInfo('', 'foo => ($var|mod)|mod2:(1|foo)');
$tag->extractModifier();
Assert::same('|mod2:(1|foo)', $tag->modifiers);

$tag = new TagInfo('', 'foo => "(aa"|mod2:"bb)"');
$tag->extractModifier();
Assert::same('|mod2:"bb)"', $tag->modifiers);
