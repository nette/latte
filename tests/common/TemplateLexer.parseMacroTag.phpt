<?php

declare(strict_types=1);

use Latte\Compiler\Tag;
use Latte\Compiler\Token;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$lexer = new Latte\Compiler\TemplateLexer;


$tag = new Tag('', [new Token(0, "\$var |mod:'\\':a:b:c':arg2 |mod2:|mod3")]);
$tag->extractModifier();
Assert::same("|mod:'\\':a:b:c':arg2 |mod2:|mod3", $tag->parser->modifiers);

$tag = new Tag('', [new Token(0, "'a|b\\'c'")]);
$tag->extractModifier();
Assert::same('', $tag->parser->modifiers);

$tag = new Tag('', [new Token(0, 'foo => ($var|mod)|mod2')]);
$tag->extractModifier();
Assert::same('|mod2', $tag->parser->modifiers);

$tag = new Tag('', [new Token(0, 'foo => ($var|mod)|mod2:(1|foo)')]);
$tag->extractModifier();
Assert::same('|mod2:(1|foo)', $tag->parser->modifiers);

$tag = new Tag('', [new Token(0, 'foo => "(aa"|mod2:"bb)"')]);
$tag->extractModifier();
Assert::same('|mod2:"bb)"', $tag->parser->modifiers);
