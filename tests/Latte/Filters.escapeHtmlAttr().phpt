<?php

/**
 * Test: Latte\Runtime\Filters::escapeHtmlAttr
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('', Filters::escapeHtmlAttr(NULL));
Assert::same('', Filters::escapeHtmlAttr(''));
Assert::same('1', Filters::escapeHtmlAttr(1));
Assert::same('string', Filters::escapeHtmlAttr('string'));
Assert::same('&lt; &amp; &#039; &quot; &gt;', Filters::escapeHtmlAttr('< & \' " >'));
Assert::same('&amp;quot;', Filters::escapeHtmlAttr('&quot;'));
Assert::same('&lt;br&gt; &quot;', Filters::escapeHtmlAttr(new Latte\Runtime\Html('<br> &quot;')));

// mXSS
Assert::same('`hello ', Filters::escapeHtmlAttr('`hello'));
Assert::same('`hello&quot;', Filters::escapeHtmlAttr('`hello"'));
Assert::same('`hello&#039;', Filters::escapeHtmlAttr("`hello'"));
