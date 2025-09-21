<?php

declare(strict_types=1);

use Latte\Runtime\HtmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class Str
{
	public function __toString()
	{
		return 'one&<br>';
	}
}


// text
Assert::same(
	'title="Hello &amp; Welcome"',
	HtmlHelpers::formatCommonAttribute('title', 'Hello & Welcome'),
);
Assert::same(
	'title="&quot;Hello&quot; &amp; &apos;Welcome&apos;"',
	HtmlHelpers::formatCommonAttribute('title', '"Hello" & \'Welcome\''),
);
Assert::same('title=""', HtmlHelpers::formatCommonAttribute('title', ''));
Assert::same('title="1"', HtmlHelpers::formatCommonAttribute('title', 1));
Assert::same('title="0"', HtmlHelpers::formatCommonAttribute('title', 0));
Assert::same('title="one&amp;"', HtmlHelpers::formatCommonAttribute('title', new Latte\Runtime\Html('one&amp;<br>')));
Assert::same('title="one&amp;&lt;br&gt;"', HtmlHelpers::formatCommonAttribute('title', new Str));

// special values
Assert::same('title="1"', HtmlHelpers::formatCommonAttribute('title', true));
Assert::same('title=""', HtmlHelpers::formatCommonAttribute('title', false));
Assert::same('title=""', HtmlHelpers::formatCommonAttribute('title', null));

// invalid
Assert::error(
	fn() => Assert::same('title="Array"', HtmlHelpers::formatCommonAttribute('title', [])),
	E_WARNING,
);
Assert::exception(
	fn() => HtmlHelpers::formatCommonAttribute('title', (object) []),
	Error::class,
);

// invalid UTF-8
Assert::same( // invalid codepoint high surrogates
	"a=\"foo \u{FFFD} bar\"",
	HtmlHelpers::formatCommonAttribute('a', "foo \u{D800} bar"),
);
Assert::same( // stripped UTF
	"a=\"foo \u{FFFD}&quot; bar\"",
	HtmlHelpers::formatCommonAttribute('a', "foo \xE3\x80\x22 bar"),
);
