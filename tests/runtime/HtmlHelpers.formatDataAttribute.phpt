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

class NonSerializable
{
}


// text
Assert::same(
	'data-foo="Hello &amp; Welcome"',
	HtmlHelpers::formatDataAttribute('data-foo', 'Hello & Welcome'),
);
Assert::same(
	'data-foo="&quot;Hello&quot; &amp; &apos;Welcome&apos;"',
	HtmlHelpers::formatDataAttribute('data-foo', '"Hello" & \'Welcome\''),
);
Assert::same('data-foo=""', HtmlHelpers::formatDataAttribute('data-foo', ''));
Assert::same('data-foo="1"', HtmlHelpers::formatDataAttribute('data-foo', 1));
Assert::same('data-foo="0"', HtmlHelpers::formatDataAttribute('data-foo', 0));
Assert::same('data-foo="one&amp;"', HtmlHelpers::formatDataAttribute('data-foo', new Latte\Runtime\Html('one&amp;<br>')));
Assert::same('data-foo="one&amp;&lt;br&gt;"', HtmlHelpers::formatDataAttribute('data-foo', new StringObject));

// bool
Assert::same('data-foo="true"', HtmlHelpers::formatDataAttribute('data-foo', true));
Assert::same('data-foo="false"', HtmlHelpers::formatDataAttribute('data-foo', false));

// array
Assert::same('data-foo="[]"', HtmlHelpers::formatDataAttribute('data-foo', []));
Assert::same('data-foo=\'["a","b"]\'', HtmlHelpers::formatDataAttribute('data-foo', ['a', 'b']));
Assert::same('data-foo=\'{"k1":"v1","k2":"v2"}\'', HtmlHelpers::formatDataAttribute('data-foo', ['k1' => 'v1', 'k2' => 'v2']));

// stdClass
Assert::same('data-foo="{}"', HtmlHelpers::formatDataAttribute('data-foo', (object) []));
Assert::same('data-foo=\'{"a":"b"}\'', HtmlHelpers::formatDataAttribute('data-foo', (object) ['a' => 'b']));

// skipped
Assert::same('', HtmlHelpers::formatDataAttribute('data-foo', null));

// invalid
Assert::error(
	fn() => Assert::same('', HtmlHelpers::formatDataAttribute('data-foo', new NonSerializable)),
	Error::class,
);

// invalid UTF-8
Assert::same( // invalid codepoint high surrogates
	"a=\"foo \u{FFFD} bar\"",
	HtmlHelpers::formatDataAttribute('a', "foo \u{D800} bar"),
);
Assert::same( // stripped UTF
	"a=\"foo \u{FFFD}&quot; bar\"",
	HtmlHelpers::formatDataAttribute('a', "foo \xE3\x80\x22 bar"),
);
