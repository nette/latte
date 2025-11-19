<?php

/**
 * Test: Latte\Runtime\Helpers::escapeJs
 */

declare(strict_types=1);

use Latte\Runtime\Helpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class Test implements Latte\Runtime\HtmlStringable
{
	public function __toString(): string
	{
		return '<br>';
	}
}

Assert::same('null', Helpers::escapeJs(null));
Assert::same('""', Helpers::escapeJs(''));
Assert::same('1', Helpers::escapeJs(1));
Assert::same('"string"', Helpers::escapeJs('string'));
Assert::same('"<\/tag"', Helpers::escapeJs('</tag'));
Assert::same('"\u2028 \u2029 ]]\u003E \u003C!"', Helpers::escapeJs("\u{2028} \u{2029} ]]> <!"));
Assert::same('"<br>"', Helpers::escapeJs(new Test));
Assert::same('"<br>"', Helpers::escapeJs(new Latte\Runtime\Html('<br>')));

// invalid UTF-8
Assert::same("\"foo \u{FFFD} bar\"", Helpers::escapeJs("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("\"foo \u{FFFD}\\\" bar\"", Helpers::escapeJs("foo \xE3\x80\x22 bar")); // stripped UTF
