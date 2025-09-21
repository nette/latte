<?php

declare(strict_types=1);

use Latte\Runtime\XmlHelpers;
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
	XmlHelpers::formatCommonAttribute('title', 'Hello & Welcome'),
);
Assert::same(
	'title="&quot;Hello&quot; &amp; &apos;Welcome&apos;"',
	XmlHelpers::formatCommonAttribute('title', '"Hello" & \'Welcome\''),
);
Assert::same('title=""', XmlHelpers::formatCommonAttribute('title', ''));
Assert::same('title="1"', XmlHelpers::formatCommonAttribute('title', 1));
Assert::same('title="0"', XmlHelpers::formatCommonAttribute('title', 0));
Assert::same('title="one&amp;"', XmlHelpers::formatCommonAttribute('title', new Latte\Runtime\Html('one&amp;<br>')));
Assert::same('title="one&amp;&lt;br&gt;"', XmlHelpers::formatCommonAttribute('title', new Str));

// special values
Assert::same('title="1"', XmlHelpers::formatCommonAttribute('title', true));
Assert::same('title=""', XmlHelpers::formatCommonAttribute('title', false));
Assert::same('title=""', XmlHelpers::formatCommonAttribute('title', null));

// invalid
Assert::error(
	fn() => Assert::same('title="Array"', XmlHelpers::formatCommonAttribute('title', [])),
	E_WARNING,
);
Assert::exception(
	fn() => XmlHelpers::formatCommonAttribute('title', (object) []),
	Error::class,
);

// invalid UTF-8
Assert::same( // invalid codepoint high surrogates
	"a=\"foo \u{FFFD} bar\"",
	XmlHelpers::formatCommonAttribute('a', "foo \u{D800} bar"),
);
Assert::same( // stripped UTF
	"a=\"foo \u{FFFD}&quot; bar\"",
	XmlHelpers::formatCommonAttribute('a', "foo \xE3\x80\x22 bar"),
);
