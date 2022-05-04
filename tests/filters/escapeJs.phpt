<?php

/**
 * Test: Latte\Runtime\Filters::escapeJs
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class Test implements Latte\Runtime\HtmlStringable
{
	public function __toString(): string
	{
		return '<br>';
	}
}

Assert::same('null', Filters::escapeJs(null));
Assert::same('""', Filters::escapeJs(''));
Assert::same('1', Filters::escapeJs(1));
Assert::same('"string"', Filters::escapeJs('string'));
Assert::same('"<\/tag"', Filters::escapeJs('</tag'));
Assert::same('"\u2028 \u2029 ]]\u003E \u003C!"', Filters::escapeJs("\u{2028} \u{2029} ]]> <!"));
Assert::same('"<br>"', Filters::escapeJs(new Test));
Assert::same('"<br>"', Filters::escapeJs(new Latte\Runtime\Html('<br>')));

// invalid UTF-8
Assert::same("\"foo \u{FFFD} bar\"", Filters::escapeJs("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("\"foo \u{FFFD}\\\" bar\"", Filters::escapeJs("foo \xE3\x80\x22 bar")); // stripped UTF
