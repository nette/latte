<?php

/**
 * Test: Latte\Runtime\Filters::escapeHtmlChar
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('', Filters::escapeHtmlChar(null, '"'));
Assert::same('', Filters::escapeHtmlChar('', '"'));
Assert::same('1', Filters::escapeHtmlChar(1, '"'));
Assert::same('string', Filters::escapeHtmlChar('string', '"'));
Assert::same('< & \' &quot; >', Filters::escapeHtmlChar('< & \' " >', '"'));
Assert::same('< & &apos; " >', Filters::escapeHtmlChar('< & \' " >', "'"));
Assert::same('< & \' " &gt;', Filters::escapeHtmlChar('< & \' " >', '>'));
Assert::same('&quot;', Filters::escapeHtmlChar('&quot;', '"'));
