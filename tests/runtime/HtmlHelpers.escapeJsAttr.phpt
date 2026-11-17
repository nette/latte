<?php declare(strict_types=1);

/**
 * Test: Latte\Runtime\HtmlHelpers::escapeJsAttr
 */

use Latte\Runtime\HtmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('&quot;&lt;b&gt;a &amp;amp; b&lt;\/b&gt;&quot;', HtmlHelpers::escapeJsAttr('<b>a &amp; b</b>'));
Assert::same('&quot;alert(1)&quot;', HtmlHelpers::escapeJsAttr('alert(1)'));
