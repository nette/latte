<?php

declare(strict_types=1);

use Latte\Runtime\XmlHelpers;
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
	XmlHelpers::formatAttribute('title', 'Hello & Welcome'),
);
Assert::same(
	'title="&quot;Hello&quot; &amp; &apos;Welcome&apos;"',
	XmlHelpers::formatAttribute('title', '"Hello" & \'Welcome\''),
);
Assert::same('title=""', XmlHelpers::formatAttribute('title', ''));
Assert::same('title="1"', XmlHelpers::formatAttribute('title', 1));
Assert::same('title="0"', XmlHelpers::formatAttribute('title', 0));
Assert::same('title="one&amp;"', XmlHelpers::formatAttribute('title', new Latte\Runtime\Html('one&amp;<br>')));
Assert::same('title="one&amp;&lt;br&gt;"', XmlHelpers::formatAttribute('title', new StringObject));

// skipped
Assert::same('', XmlHelpers::formatAttribute('title', null));

// invalid
Assert::error(
	fn() => Assert::same('', XmlHelpers::formatAttribute('title', true)),
	E_USER_WARNING,
	"Invalid value for attribute 'title': bool is not allowed.",
);
Assert::error(
	fn() => Assert::same('', XmlHelpers::formatAttribute('title', false)),
	E_USER_WARNING,
	"Invalid value for attribute 'title': bool is not allowed.",
);
Assert::error(
	fn() => Assert::same('', XmlHelpers::formatAttribute('title', [])),
	E_USER_WARNING,
	"Invalid value for attribute 'title': array is not allowed.",
);
Assert::error(
	fn() => Assert::same('', XmlHelpers::formatAttribute('title', (object) [])),
	E_USER_WARNING,
	"Invalid value for attribute 'title': stdClass is not allowed.",
);

// invalid UTF-8
Assert::same( // invalid codepoint high surrogates
	"a=\"foo \u{FFFD} bar\"",
	XmlHelpers::formatAttribute('a', "foo \u{D800} bar"),
);
Assert::same( // stripped UTF
	"a=\"foo \u{FFFD}&quot; bar\"",
	XmlHelpers::formatAttribute('a', "foo \xE3\x80\x22 bar"),
);
