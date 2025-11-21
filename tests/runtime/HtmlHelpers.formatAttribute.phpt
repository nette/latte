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
	'title="Hello &amp; Welcome"',
	HtmlHelpers::formatAttribute('title', 'Hello & Welcome'),
);
Assert::same(
	'title="&quot;Hello&quot; &amp; &apos;Welcome&apos;"',
	HtmlHelpers::formatAttribute('title', '"Hello" & \'Welcome\''),
);
Assert::same('title=""', HtmlHelpers::formatAttribute('title', ''));
Assert::same('title="1"', HtmlHelpers::formatAttribute('title', 1));
Assert::same('title="0"', HtmlHelpers::formatAttribute('title', 0));
Assert::same('title="one&amp;"', HtmlHelpers::formatAttribute('title', new Latte\Runtime\Html('one&amp;<br>')));
Assert::same('title="one&amp;&lt;br&gt;"', HtmlHelpers::formatAttribute('title', new StringObject));

// special values
Assert::same('title="1"', HtmlHelpers::formatAttribute('title', true));
Assert::same('title=""', HtmlHelpers::formatAttribute('title', false));

// skipped
Assert::same('', HtmlHelpers::formatAttribute('title', null));

// invalid
Assert::error(
	fn() => Assert::same('title="Array"', HtmlHelpers::formatAttribute('title', [])),
	E_WARNING,
);
Assert::exception(
	fn() => HtmlHelpers::formatAttribute('title', (object) []),
	Error::class,
);

// invalid UTF-8
Assert::same( // invalid codepoint high surrogates
	"a=\"foo \u{FFFD} bar\"",
	HtmlHelpers::formatAttribute('a', "foo \u{D800} bar"),
);
Assert::same( // stripped UTF
	"a=\"foo \u{FFFD}&quot; bar\"",
	HtmlHelpers::formatAttribute('a', "foo \xE3\x80\x22 bar"),
);
