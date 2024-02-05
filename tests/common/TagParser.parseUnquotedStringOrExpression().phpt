<?php

declare(strict_types=1);

use Latte\Compiler\PrintContext;
use Latte\Compiler\TagLexer;
use Latte\Compiler\TagParser;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function format(string $str, bool $acceptColon = true)
{
	$tokens = (new TagLexer)->tokenize($str);
	$parser = new TagParser($tokens);
	$node = $parser->parseUnquotedStringOrExpression($acceptColon);
	return $node->print(new PrintContext);
}


Assert::exception(
	fn() => format(''),
	Latte\CompileException::class,
	'Unexpected end (on line 1 at column 1)',
);

// non-unquoted strings
Assert::same('0', format('0'));
Assert::same('-0.0', format('-0.0'));
Assert::same("'symbol'", format('symbol'));
Assert::same('$var', format('$var'));
Assert::same("'symbol'", format('symbol$var')); // bc break
Assert::same("'var'", format("'var'"));
Assert::same("'var'", format('"var"'));
Assert::same("'v\"ar'", format('"v\\"ar"'));
Assert::same("'var' . 'var'", format("var.'var'"));
Assert::same("\$var['var']", format('$var[var]'));
Assert::same("\$x['[x]']", format('$x["[x]"]'));
Assert::same("'item' - \$x", format('item-$x')); // bc break
Assert::same("'Foo:CONST'", format('Foo:CONST'));
Assert::same('Foo::CONST', format('Foo::CONST'));
Assert::same('Foo::Abc1', format('Foo::Abc1'));
Assert::same('\Namespace0\Class_1::CONST_X', format('\Namespace0\Class_1::CONST_X'));
Assert::same("'symbol'", format('(symbol)'));

// unquoted strings
Assert::same("'../'", format('../'));
Assert::same('("item-" . ($x()) . "")', format('item-{$x()}'));
Assert::same('"{$x}-item"', format('{$x}-item'));
Assert::same("'null'", format('null')); // bc breaks
Assert::same("'NULL'", format('NULL'));
Assert::same("'true'", format('true'));
Assert::same("'TRUE'", format('TRUE'));
Assert::same("'false'", format('false'));
Assert::same("'FALSE'", format('FALSE'));
Assert::same("'Null'", format('Null'));
Assert::same("'True'", format('True'));
Assert::same("'False'", format('False'));

// unquoted & following chars
Assert::same("'fo:o'", format('fo:o,'));
Assert::same("'fo:o'", format('fo:o ,'));
Assert::same("'fo:o'", format('fo:o foo'));
Assert::same("'fo:o'", format('fo:o ()'));
Assert::same("'fo:o'", format('fo:o ""'));
Assert::same("'fo:o'", format('fo:o \foo'));

// non-unquoted & following chars
Assert::same('true ? false : null', format('true ? false'));
Assert::same('true . false', format('true . false'));

// no colon
Assert::same("'Foo'", format('Foo:CONST', false));
Assert::same("'../'", format('../:xx', false));
