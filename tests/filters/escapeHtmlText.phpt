<?php

/**
 * Test: Latte\Runtime\Filters::escapeHtmlText
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

Assert::same('', Filters::escapeHtmlText(null));
Assert::same('', Filters::escapeHtmlText(''));
Assert::same('1', Filters::escapeHtmlText(1));
Assert::same('string', Filters::escapeHtmlText('string'));
Assert::same('&lt;br&gt;', Filters::escapeHtmlText('<br>'));
Assert::same('&lt; &amp; \' " &gt;', Filters::escapeHtmlText('< & \' " >'));
Assert::same('&amp;quot;', Filters::escapeHtmlText('&quot;'));
Assert::same('<br>', Filters::escapeHtmlText(new Test));
Assert::same('<br>', Filters::escapeHtmlText(new Latte\Runtime\Html('<br>')));
Assert::same('`hello', Filters::escapeHtmlText('`hello'));

// invalid UTF-8
Assert::same("foo \u{FFFD} bar", Filters::escapeHtmlText("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("foo \u{FFFD}\" bar", Filters::escapeHtmlText("foo \xE3\x80\x22 bar")); // stripped UTF

// JS
Assert::same('hello {<!-- -->{ worlds }}', Filters::escapeHtmlText('hello {{ worlds }}'));
Assert::same('hello &#123; worlds }', Filters::escapeHtmlText('hello { worlds }'));
