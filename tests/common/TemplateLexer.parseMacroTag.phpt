<?php

declare(strict_types=1);

use Latte\Compiler\Tag;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$lexer = new Latte\Compiler\TemplateLexer;


$tag = new Tag('', "\$var |mod:'\\':a:b:c':arg2 |mod2:|mod3");
$tag->extractModifier();
Assert::same("|mod:'\\':a:b:c':arg2 |mod2:|mod3", $tag->parser->modifiers);

$tag = new Tag('', "'a|b\\'c'");
$tag->extractModifier();
Assert::same('', $tag->parser->modifiers);

$tag = new Tag('', 'foo => ($var|mod)|mod2');
$tag->extractModifier();
Assert::same('|mod2', $tag->parser->modifiers);

$tag = new Tag('', 'foo => ($var|mod)|mod2:(1|foo)');
$tag->extractModifier();
Assert::same('|mod2:(1|foo)', $tag->parser->modifiers);

$tag = new Tag('', 'foo => "(aa"|mod2:"bb)"');
$tag->extractModifier();
Assert::same('|mod2:"bb)"', $tag->parser->modifiers);
