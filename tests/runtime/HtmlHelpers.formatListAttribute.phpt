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
	'class="Hello &amp; Welcome"',
	HtmlHelpers::formatListAttribute('class', 'Hello & Welcome'),
);
Assert::same(
	'class="&quot;Hello&quot; &amp; &apos;Welcome&apos;"',
	HtmlHelpers::formatListAttribute('class', '"Hello" & \'Welcome\''),
);
Assert::same('class=""', HtmlHelpers::formatListAttribute('class', ''));
Assert::same('class="1"', HtmlHelpers::formatListAttribute('class', 1));
Assert::same('class="0"', HtmlHelpers::formatListAttribute('class', 0));
Assert::same('class="one&amp;"', HtmlHelpers::formatListAttribute('class', new Latte\Runtime\Html('one&amp;<br>')));
Assert::same('class="one&amp;&lt;br&gt;"', HtmlHelpers::formatListAttribute('class', new StringObject));

// array
Assert::same('', HtmlHelpers::formatListAttribute('class', []));
Assert::same('class="a b"', HtmlHelpers::formatListAttribute('class', ['a', 'b']));
Assert::same('class="k1"', HtmlHelpers::formatListAttribute('class', ['k1' => true, 'k2' => false]));

// skipped
Assert::same('', HtmlHelpers::formatListAttribute('class', null));

// invalid
Assert::error(
	fn() => Assert::same('', HtmlHelpers::formatListAttribute('class', true)),
	E_USER_WARNING,
	"Invalid value for attribute 'class': bool is not allowed.",
);
Assert::error(
	fn() => Assert::same('', HtmlHelpers::formatListAttribute('class', false)),
	E_USER_WARNING,
	"Invalid value for attribute 'class': bool is not allowed.",
);
Assert::error(
	fn() => Assert::same('', HtmlHelpers::formatListAttribute('class', (object) [])),
	E_USER_WARNING,
	"Invalid value for attribute 'class': stdClass is not allowed.",
);

// invalid UTF-8
Assert::same( // invalid codepoint high surrogates
	"a=\"foo \u{FFFD} bar\"",
	HtmlHelpers::formatListAttribute('a', "foo \u{D800} bar"),
);
Assert::same( // stripped UTF
	"a=\"foo \u{FFFD}&quot; bar\"",
	HtmlHelpers::formatListAttribute('a', "foo \xE3\x80\x22 bar"),
);
