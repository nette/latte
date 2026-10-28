<?php declare(strict_types=1);

/**
 * Test: {include block}
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();

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

Assert::error(
	fn() => $latte->renderToString('{define block, $name, $value}{/define} {include block, name: 1, 2}'),
	E_USER_DEPRECATED,
	'Positional argument after a named argument is deprecated (on line 1 at column 65)',
);

Assert::match(
	' [1|2]',
	$latte->renderToString('{define block, $name, $value}[{$name}|{$value}]{/define} {include block, 1, value: 2}'),
);
