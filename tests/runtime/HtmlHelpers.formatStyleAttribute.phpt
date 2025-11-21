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
	'style="Hello &amp; Welcome"',
	HtmlHelpers::formatStyleAttribute('style', 'Hello & Welcome'),
);
Assert::same(
	'style="&quot;Hello&quot; &amp; &apos;Welcome&apos;"',
	HtmlHelpers::formatStyleAttribute('style', '"Hello" & \'Welcome\''),
);
Assert::same('style=""', HtmlHelpers::formatStyleAttribute('style', ''));
Assert::same('style="one&amp;"', HtmlHelpers::formatStyleAttribute('style', new Latte\Runtime\Html('one&amp;<br>')));
Assert::same('style="one&amp;&lt;br&gt;"', HtmlHelpers::formatStyleAttribute('style', new StringObject));
Assert::same('style="1"', HtmlHelpers::formatStyleAttribute('style', 1));

// array
Assert::same('', HtmlHelpers::formatStyleAttribute('style', []));
Assert::same(
	'style="color: red; font-size: 16px"',
	HtmlHelpers::formatStyleAttribute('style', ['color' => 'red', 'font-size' => '16px']),
);
Assert::same(
	'style="color: red; font-size: 16px"',
	HtmlHelpers::formatStyleAttribute('style', ['color: red', 'font-size: 16px']),
);

// special values
Assert::same('style="1"', HtmlHelpers::formatStyleAttribute('style', true));
Assert::same('style=""', HtmlHelpers::formatStyleAttribute('style', false));

// skipped
Assert::same('', HtmlHelpers::formatStyleAttribute('style', null));

// invalid
Assert::error(
	fn() => Assert::same('', HtmlHelpers::formatStyleAttribute('style', (object) [])),
	Error::class,
);

// invalid UTF-8
Assert::same( // invalid codepoint high surrogates
	"a=\"foo \u{FFFD} bar\"",
	HtmlHelpers::formatStyleAttribute('a', "foo \u{D800} bar"),
);
Assert::same( // stripped UTF
	"a=\"foo \u{FFFD}&quot; bar\"",
	HtmlHelpers::formatStyleAttribute('a', "foo \xE3\x80\x22 bar"),
);
