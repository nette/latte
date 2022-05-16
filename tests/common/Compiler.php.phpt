<?php

/**
 * Test: deprecated <?php ?>
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::contains('a <? b', $latte->compile('a <{syntax off}? b'));

Assert::contains('<?php ?>', $latte->compile('<?php ?>'));

Assert::contains('<? ?>', $latte->compile('<? ?>'));

Assert::contains('<?= $a ?>', $latte->compile('<?= $a ?>'));

Assert::contains('<!-- <? -->', $latte->compile('<!-- <? -->'));

Assert::contains('<div <? >', $latte->compile('<div <? >'));

Assert::contains('<div a="<?">', $latte->compile('<div a="<?">'));

Assert::exception(
	fn() => $latte->compile('{var ?> }'),
	Latte\CompileException::class,
	'Unexpected end in {var ?>} (at column 1)',
);
