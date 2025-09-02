<?php

/**
 * Test: Latte\Runtime\HtmlHelpers::escapeQuotes
 */

declare(strict_types=1);

use Latte\Runtime\HtmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('', HtmlHelpers::escapeQuotes(null));
Assert::same('', HtmlHelpers::escapeQuotes(''));
Assert::same('1', HtmlHelpers::escapeQuotes(1));
Assert::same('string', HtmlHelpers::escapeQuotes('string'));
Assert::same('< & &apos; &quot; >', HtmlHelpers::escapeQuotes('< & \' " >'));
Assert::same('&quot;', HtmlHelpers::escapeQuotes('&quot;'));
