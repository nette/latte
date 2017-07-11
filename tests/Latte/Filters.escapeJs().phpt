<?php

/**
 * Test: Latte\Runtime\Filters::escapeJs
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

Assert::same('null', Filters::escapeJs(null));
Assert::same('""', Filters::escapeJs(''));
Assert::same('1', Filters::escapeJs(1));
Assert::same('"string"', Filters::escapeJs('string'));
Assert::same('"\u2028 \u2029 ]]\x3E \x3C!"', Filters::escapeJs("\xe2\x80\xa8 \xe2\x80\xa9 ]]> <!"));
Assert::same('"<br>"', Filters::escapeJs(new Test));
Assert::same('"<br>"', Filters::escapeJs(new Latte\Runtime\Html('<br>')));
