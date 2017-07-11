<?php

/**
 * Test: Latte\Runtime\Filters::escapeHtml
 */

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class Test implements Latte\Runtime\IHtmlString
{
	public function __toString()
	{
		return '<br>';
	}
}

Assert::same('', Filters::escapeHtml(null));
Assert::same('', Filters::escapeHtml(''));
Assert::same('1', Filters::escapeHtml(1));
Assert::same('string', Filters::escapeHtml('string'));
Assert::same('&lt;br&gt;', Filters::escapeHtml('<br>'));
Assert::same('&lt; &amp; &#039; &quot; &gt;', Filters::escapeHtml('< & \' " >'));
Assert::same('&amp;quot;', Filters::escapeHtml('&quot;'));
Assert::same('&lt;br&gt;', Filters::escapeHtml(new Test));
Assert::same('&lt;br&gt;', Filters::escapeHtml(new Latte\Runtime\Html('<br>')));
Assert::same('`hello', Filters::escapeHtml('`hello'));
