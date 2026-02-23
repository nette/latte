<?php declare(strict_types=1);

/**
 * Test: Latte\Runtime\HtmlHelpers::escapeText
 */

use Latte\Runtime\HtmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class Test implements Latte\Runtime\HtmlStringable
{
	public function __toString(): string
	{
		return '<br>';
	}
}

Assert::same('', HtmlHelpers::escapeText(null));
Assert::same('', HtmlHelpers::escapeText(''));
Assert::same('1', HtmlHelpers::escapeText(1));
Assert::same('string', HtmlHelpers::escapeText('string'));
Assert::same('&lt;br&gt;', HtmlHelpers::escapeText('<br>'));
Assert::same('&lt; &amp; \' " &gt;', HtmlHelpers::escapeText('< & \' " >'));
Assert::same('&amp;quot;', HtmlHelpers::escapeText('&quot;'));
Assert::same('<br>', HtmlHelpers::escapeText(new Test));
Assert::same('<br>', HtmlHelpers::escapeText(new Latte\Runtime\Html('<br>')));
Assert::same('`hello', HtmlHelpers::escapeText('`hello'));

// invalid UTF-8
Assert::same("foo \u{FFFD} bar", HtmlHelpers::escapeText("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("foo \u{FFFD}\" bar", HtmlHelpers::escapeText("foo \xE3\x80\x22 bar")); // stripped UTF

// JS
Assert::same('hello {<!-- -->{ worlds }}', HtmlHelpers::escapeText('hello {{ worlds }}'));
Assert::same('hello &#123; worlds }', HtmlHelpers::escapeText('hello { worlds }'));
