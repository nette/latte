<?php

/**
 * Test: {include block}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(
	' before [block 1] after',
	$latte->renderToString('{define block}[block {$var}]{/} before {include block, var => 1} after'),
);

Assert::match(
	' before [block 1] after',
	$latte->renderToString('{define block}[block {$var}]{/} before {include #block, var => 1} after'),
);

Assert::match(
	' before [block 1] after',
	$latte->renderToString('{define block-2}[block {$var}]{/} before {include block-2, var => 1} after'),
);

Assert::exception(
	fn() => $latte->renderToString('{define block.2}[block {$var}]{/} before {include block.2, var => 1} after'),
	Latte\RuntimeException::class,
	"Missing template 'block.2'.",
);

Assert::match(
	' before [block 1] after',
	$latte->renderToString('{define block.2}[block {$var}]{/} before {include #block.2, var => 1} after'),
);

Assert::match(
	' before [block 1] after',
	$latte->renderToString('{define block.2}[block {$var}]{/} before {include block block.2, var => 1} after'),
);

Assert::match(
	'  before [block 1] after',
	$latte->renderToString('{define block}[block {$var}]{/define} {var $name = block} before {include block $name, var => 1} after'),
);

Assert::match(
	' before block 1 after',
	$latte->renderToString('{define block}<b>block {$var}</b>{/} before {include block, var => 1|striptags} after'),
);

Assert::match(
	' before block 2 after',
	$latte->renderToString('{define block}block {$var}{/} before {include block true ? "block", var => 2} after'),
);

Assert::exception(
	fn() => $latte->renderToString('{include block (null)}'),
	InvalidArgumentException::class,
	'Block name must be a string, null given.',
);
