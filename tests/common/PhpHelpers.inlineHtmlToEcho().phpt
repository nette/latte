<?php

/**
 * Test: Latte\PhpHelpers::inlineHtmlToEcho()
 */

declare(strict_types=1);

use Latte\PhpHelpers;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::match("<?php echo 'hello' ?>", PhpHelpers::inlineHtmlToEcho('hello'));
Assert::match("<?php echo 'hellohello' ?>", PhpHelpers::inlineHtmlToEcho('hello<?php   ?>hello'));
Assert::match("<?php echo 'hellohellohello' ?>", PhpHelpers::inlineHtmlToEcho('hello<?php   ?>hello<?php   ?>hello'));

Assert::match('<?php echo "\n" ?>', PhpHelpers::inlineHtmlToEcho("\n"));
