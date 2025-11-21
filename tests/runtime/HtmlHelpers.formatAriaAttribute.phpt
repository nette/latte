<?php

declare(strict_types=1);

use Latte\Runtime\HtmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class StringObject
{
	public function __toString()
	{
		return 'one&<br>';
	}
}


// text
Assert::same(
	'aria-foo="Hello &amp; Welcome"',
	HtmlHelpers::formatAriaAttribute('aria-foo', 'Hello & Welcome'),
);
Assert::same(
	'aria-foo="&quot;Hello&quot; &amp; &apos;Welcome&apos;"',
	HtmlHelpers::formatAriaAttribute('aria-foo', '"Hello" & \'Welcome\''),
);
Assert::same('aria-foo=""', HtmlHelpers::formatAriaAttribute('aria-foo', ''));
Assert::same('aria-foo="1"', HtmlHelpers::formatAriaAttribute('aria-foo', 1));
Assert::same('aria-foo="0"', HtmlHelpers::formatAriaAttribute('aria-foo', 0));
Assert::same('aria-foo="one&amp;"', HtmlHelpers::formatAriaAttribute('aria-foo', new Latte\Runtime\Html('one&amp;<br>')));
Assert::same('aria-foo="one&amp;&lt;br&gt;"', HtmlHelpers::formatAriaAttribute('aria-foo', new StringObject));

// bool
Assert::same('aria-foo="true"', HtmlHelpers::formatAriaAttribute('aria-foo', true));
Assert::same('aria-foo="false"', HtmlHelpers::formatAriaAttribute('aria-foo', false));

// array
Assert::same('', HtmlHelpers::formatAriaAttribute('aria-foo', []));
Assert::same('aria-foo="a b"', HtmlHelpers::formatAriaAttribute('aria-foo', ['a', 'b']));
Assert::same('aria-foo="v1 v2"', HtmlHelpers::formatAriaAttribute('aria-foo', ['k1' => 'v1', 'k2' => 'v2']));

// skipped
Assert::same('', HtmlHelpers::formatAriaAttribute('aria-foo', null));

// invalid
Assert::error(
	fn() => Assert::same('', HtmlHelpers::formatAriaAttribute('aria-foo', (object) [])),
	E_USER_WARNING,
	"Invalid value for attribute 'aria-foo': stdClass is not allowed.",
);

// invalid UTF-8
Assert::same( // invalid codepoint high surrogates
	"a=\"foo \u{FFFD} bar\"",
	HtmlHelpers::formatAriaAttribute('a', "foo \u{D800} bar"),
);
Assert::same( // stripped UTF
	"a=\"foo \u{FFFD}&quot; bar\"",
	HtmlHelpers::formatAriaAttribute('a', "foo \xE3\x80\x22 bar"),
);
