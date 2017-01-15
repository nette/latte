<?php

/**
 * Test: Latte\Runtime\Filters::escapeJs
 */

declare(strict_types=1);

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

Assert::same('null', Filters::escapeJs(NULL));
Assert::same('""', Filters::escapeJs(''));
Assert::same('1', Filters::escapeJs(1));
Assert::same('"string"', Filters::escapeJs('string'));
Assert::same('"\u2028 \u2029 ]]\x3E \x3C!"', Filters::escapeJs("\u{2028} \u{2029} ]]> <!"));
Assert::same('"<br>"', Filters::escapeJs(new Test));
Assert::same('"<br>"', Filters::escapeJs(new Latte\Runtime\Html('<br>')));
