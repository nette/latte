<?php

/**
 * Test: Latte\Runtime\Filters::escapeHtmlAttrConv
 */

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('', Filters::escapeHtmlAttrConv(null));
Assert::same('', Filters::escapeHtmlAttrConv(''));
Assert::same('1', Filters::escapeHtmlAttrConv(1));
Assert::same('string', Filters::escapeHtmlAttrConv('string'));
Assert::same('&lt; &amp; &#039; &quot; &gt;', Filters::escapeHtmlAttrConv('< & \' " >'));
Assert::same('&quot;', Filters::escapeHtmlAttrConv('&quot;'));
Assert::same('&lt;br&gt;', Filters::escapeHtmlAttrConv(new Latte\Runtime\Html('<br>')));

// mXSS
Assert::same('`hello ', Filters::escapeHtmlAttrConv('`hello'));
Assert::same('`hello&quot;', Filters::escapeHtmlAttrConv('`hello"'));
Assert::same('`hello&#039;', Filters::escapeHtmlAttrConv("`hello'"));
