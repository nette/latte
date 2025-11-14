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

// skipped
Assert::same('', HtmlHelpers::formatAttribute('title', null));
Assert::error(
	fn() => Assert::same('', HtmlHelpers::formatAttribute('title', null, migrationWarnings: true)),
	E_USER_WARNING,
	'Behavior change for attribute \'title\' with value null: previously it rendered as title="", now the attribute is omitted.',
);

// invalid
Assert::error(
	fn() => Assert::same('', HtmlHelpers::formatAttribute('title', true)),
	E_USER_WARNING,
	"Invalid value for attribute 'title': bool is not allowed.",
);
Assert::error(
	fn() => Assert::same('', HtmlHelpers::formatAttribute('title', false)),
	E_USER_WARNING,
	"Invalid value for attribute 'title': bool is not allowed.",
);
Assert::error(
	fn() => Assert::same('', HtmlHelpers::formatAttribute('title', [])),
	E_USER_WARNING,
	"Invalid value for attribute 'title': array is not allowed.",
);
Assert::error(
	fn() => Assert::same('', HtmlHelpers::formatAttribute('title', (object) [])),
	E_USER_WARNING,
	"Invalid value for attribute 'title': stdClass is not allowed.",
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
