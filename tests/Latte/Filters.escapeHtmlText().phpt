<?php

/**
 * Test: Latte\Runtime\Filters::escapeHtmlText
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

Assert::same('', Filters::escapeHtmlText(NULL));
Assert::same('', Filters::escapeHtmlText(''));
Assert::same('1', Filters::escapeHtmlText(1));
Assert::same('string', Filters::escapeHtmlText('string'));
Assert::same('&lt;br&gt;', Filters::escapeHtmlText('<br>'));
Assert::same('&lt; &amp; \' " &gt;', Filters::escapeHtmlText('< & \' " >'));
Assert::same('&amp;quot;', Filters::escapeHtmlText('&quot;'));
Assert::same('<br>', Filters::escapeHtmlText(new Test));
Assert::same('<br>', Filters::escapeHtmlText(new Latte\Runtime\Html('<br>')));
Assert::same('`hello', Filters::escapeHtmlText('`hello'));
