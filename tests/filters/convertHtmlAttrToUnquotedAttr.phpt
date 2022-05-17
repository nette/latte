<?php

/**
 * Test: Latte\Runtime\Filters::convertHtmlAttrToUnquotedAttr
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('""', Filters::convertHtmlAttrToUnquotedAttr(''));
Assert::same('"string"', Filters::convertHtmlAttrToUnquotedAttr('string'));
Assert::same('"< & \' >"', Filters::convertHtmlAttrToUnquotedAttr('< & \' >'));

Assert::same('"""', Filters::convertHtmlAttrToUnquotedAttr('"')); // should not occur
