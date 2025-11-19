<?php

/**
 * Test: Latte\Runtime\HtmlHelpers::escapeRawHtml
 */

declare(strict_types=1);

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

Assert::same('', HtmlHelpers::escapeRawHtml(null));
Assert::same('', HtmlHelpers::escapeRawHtml(''));
Assert::same('1', HtmlHelpers::escapeRawHtml(1));
Assert::same('string', HtmlHelpers::escapeRawHtml('string'));
Assert::same('&lt; &amp; &apos; &quot; &gt;', HtmlHelpers::escapeRawHtml('< & \' " >'));
Assert::same('&amp;quot;', HtmlHelpers::escapeRawHtml('&quot;'));
Assert::same('&lt;/p&gt;', HtmlHelpers::escapeRawHtml('</p>'));
Assert::same('&lt;/script&gt;', HtmlHelpers::escapeRawHtml('</script>'));
Assert::same('<br>', HtmlHelpers::escapeRawHtml(new Test));
Assert::same('<p></p>', HtmlHelpers::escapeRawHtml(new Latte\Runtime\Html('<p></p>')));
Assert::same('<x-script></x-script>', HtmlHelpers::escapeRawHtml(new Latte\Runtime\Html('<script></script>')));

// invalid UTF-8
Assert::same("foo \u{FFFD} bar", HtmlHelpers::escapeRawHtml("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("foo \u{FFFD}&quot; bar", HtmlHelpers::escapeRawHtml("foo \xE3\x80\x22 bar")); // stripped UTF
