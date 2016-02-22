<?php

/**
 * Test: Latte\Helpers::optimizePhp()
 */

use Latte\Helpers;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$input = file_get_contents(__DIR__ . '/templates/optimize.phtml');
$expected = file_get_contents(__DIR__ . '/expected/Helpers.optimizePhp().phtml');
Assert::match($expected, Helpers::optimizePhp($input));

Assert::match('<?xml version="1.0" ;', Helpers::optimizePhp('<?xml version="1.0" ?>'));
Assert::match('<?php echo "<?xml" ;', Helpers::optimizePhp('<?php echo "<?xml" ?>'));
Assert::match('', Helpers::optimizePhp('<?php ?>'));
Assert::match(' <?php', Helpers::optimizePhp('<?php ?> <?php'));
Assert::match('<<?php ?>? 123 ?>', Helpers::optimizePhp('<<?php ?>? 123 ?>'));
Assert::match('<?<?php ?> 123 ?>', Helpers::optimizePhp('<?<?php ?> 123 ?>'));
