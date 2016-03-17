<?php

/**
 * Test: deprecated <?php ?>
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::contains('a <<?php ?>? b', $latte->compile('a <{syntax off}? b'));


Assert::error(function () use ($latte) {
	$latte->compile('<?php ?>');
}, E_USER_DEPRECATED, 'Inline <?php ... ?> is deprecated, use {php ... } on line 1');


Assert::error(function () use ($latte) {
	$latte->compile('<? ?>');
}, E_USER_DEPRECATED, 'Inline <?php ... ?> is deprecated, use {php ... } on line 1');


Assert::error(function () use ($latte) {
	$latte->compile('<?= $a ?>');
}, E_USER_DEPRECATED, 'Inline <?php ... ?> is deprecated, use {php ... } on line 1');


Assert::error(function () use ($latte) {
	$latte->compile('<!-- <? -->');
}, E_USER_DEPRECATED, 'Inline <?php ... ?> is deprecated, use {php ... } on line 1');


Assert::error(function () use ($latte) {
	$latte->compile('<div <? >');
}, E_USER_DEPRECATED, 'Inline <?php ... ?> is deprecated, use {php ... } on line 1');


Assert::error(function () use ($latte) {
	$latte->compile('<div a="<?">');
}, E_USER_DEPRECATED, 'Inline <?php ... ?> is deprecated, use {php ... } on line 1');


Assert::exception(function () use ($latte) {
	echo $latte->compile('{var ?> }');
}, 'Latte\CompileException', 'Forbidden ?> inside macro');
