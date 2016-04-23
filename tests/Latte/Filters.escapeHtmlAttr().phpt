<?php

/**
 * Test: Latte\Runtime\Filters::escapeHtmlAttr
 */

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class Test implements Latte\Runtime\IHtmlString
{
	function __toString()
	{
		return '<br>';
	}
}

Assert::same('', Filters::escapeHtmlAttr(NULL));
Assert::same('', Filters::escapeHtmlAttr(''));
Assert::same('1', Filters::escapeHtmlAttr(1));
Assert::same('string', Filters::escapeHtmlAttr('string'));
Assert::same('&lt; &amp; &#039; &quot; &gt;', Filters::escapeHtmlAttr('< & \' " >'));
Assert::same('<br>', Filters::escapeHtmlAttr(new Test));
Assert::same('<br>', Filters::escapeHtmlAttr(new Latte\Runtime\Html('<br>')));

// mXSS
Assert::same('`hello ', Filters::escapeHtmlAttr('`hello'));
Assert::same('`hello&quot;', Filters::escapeHtmlAttr('`hello"'));
Assert::same('`hello&#039;', Filters::escapeHtmlAttr("`hello'"));
