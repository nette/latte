<?php

/**
 * Test: Latte\PhpHelpers::reformatCode()
 */

declare(strict_types=1);

use Latte\Compiler\PhpHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$input = file_get_contents(__DIR__ . '/templates/PhpHelpers.reformatCode().phtml');
$expected = file_get_contents(__DIR__ . '/expected/PhpHelpers.reformatCode().phtml');
Assert::match($expected, PhpHelpers::reformatCode($input));

Assert::match('<?php
echo "<?xml";', PhpHelpers::reformatCode('<?php echo "<?xml";'));

Assert::match('<?php ', PhpHelpers::reformatCode('<?php '));

Assert::match('<?php
echo $ {"a"};
echo 1
', PhpHelpers::reformatCode('<?php echo $ {"a"}; echo 1'));

Assert::match('<?php
echo $a -> {"a"};
echo 1
', PhpHelpers::reformatCode('<?php echo $a -> {"a"}; echo 1'));
