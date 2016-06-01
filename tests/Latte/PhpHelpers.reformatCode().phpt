<?php

/**
 * Test: Latte\PhpHelpers::reformatCode()
 */

use Latte\PhpHelpers;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$input = file_get_contents(__DIR__ . '/templates/optimize.phtml');
$expected = file_get_contents(__DIR__ . '/expected/PhpHelpers.reformatCode().phtml');
Assert::match($expected, PhpHelpers::reformatCode($input));

Assert::match('<?php echo "<?xml" ?>', PhpHelpers::reformatCode('<?php echo "<?xml" ?>'));
Assert::match('', PhpHelpers::reformatCode('<?php ?>'));
Assert::match(' ', PhpHelpers::reformatCode('<?php ?> <?php '));
Assert::match('<<?php ?>? 123 ?>', PhpHelpers::reformatCode('<<?php ?>? 123 ?>'));
