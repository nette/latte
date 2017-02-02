<?php

/**
 * Test: Latte\PhpWriter::formatWord()
 */

declare(strict_types=1);

use Latte\PhpWriter;
use Latte\MacroTokens;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$writer = new PhpWriter(new MacroTokens(''));


Assert::same('""',  $writer->formatWord(''));
Assert::same('" "',  $writer->formatWord(' '));
Assert::same('0',  $writer->formatWord('0'));
Assert::same('-0.0',  $writer->formatWord('-0.0'));
Assert::same('"symbol"',  $writer->formatWord('symbol'));
Assert::same("\$var",  $writer->formatWord('$var'));
Assert::same('"symbol$var"',  $writer->formatWord('symbol$var'));
Assert::same("'var'",  $writer->formatWord("'var'"));
Assert::same('"var"',  $writer->formatWord('"var"'));
Assert::same('"v\\"ar"',  $writer->formatWord('"v\\"ar"'));
Assert::same("'var'.'var'",  $writer->formatWord("var.'var'"));
Assert::same("\$var['var']",  $writer->formatWord('$var[var]'));
Assert::same('$x["[x]"]',  $writer->formatWord('$x["[x]"]'));
Assert::same('null',  $writer->formatWord('null'));
Assert::same('NULL',  $writer->formatWord('NULL'));
Assert::same('true',  $writer->formatWord('true'));
Assert::same('TRUE',  $writer->formatWord('TRUE'));
Assert::same('false',  $writer->formatWord('false'));
Assert::same('FALSE',  $writer->formatWord('FALSE'));
Assert::same('"Null"',  $writer->formatWord('Null'));
Assert::same('"True"',  $writer->formatWord('True'));
Assert::same('"False"',  $writer->formatWord('False'));
Assert::same('Class::CONST',  $writer->formatWord('Class::CONST'));
Assert::same('\Namespace0\Class_1::CONST_X',  $writer->formatWord('\Namespace0\Class_1::CONST_X'));


Assert::exception(function () use ($writer) {
	$writer->formatWord("'var\"");
}, Latte\CompileException::class, "Unexpected ''var\"' on line 1, column 1.");
