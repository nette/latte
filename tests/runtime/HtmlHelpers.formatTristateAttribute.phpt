<?php declare(strict_types=1);

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


// bool
Assert::same('contenteditable="true"', HtmlHelpers::formatTristateAttribute('contenteditable', true));
Assert::same('contenteditable="false"', HtmlHelpers::formatTristateAttribute('contenteditable', false));

// keywords are passed through, 'false' must not toggle the attribute on
Assert::same('contenteditable="false"', HtmlHelpers::formatTristateAttribute('contenteditable', 'false'));
Assert::same('contenteditable="true"', HtmlHelpers::formatTristateAttribute('contenteditable', 'true'));
Assert::same('contenteditable="plaintext-only"', HtmlHelpers::formatTristateAttribute('contenteditable', 'plaintext-only'));

// text
Assert::same('contenteditable=""', HtmlHelpers::formatTristateAttribute('contenteditable', ''));
Assert::same('contenteditable="1"', HtmlHelpers::formatTristateAttribute('contenteditable', 1));
Assert::same('contenteditable="0"', HtmlHelpers::formatTristateAttribute('contenteditable', 0));
Assert::same('a="Hello &amp; Welcome"', HtmlHelpers::formatTristateAttribute('a', 'Hello & Welcome'));
Assert::same('a="one&amp;"', HtmlHelpers::formatTristateAttribute('a', new Latte\Runtime\Html('one&amp;<br>')));
Assert::same('a="one&amp;&lt;br&gt;"', HtmlHelpers::formatTristateAttribute('a', new StringObject));

// skipped
Assert::same('', HtmlHelpers::formatTristateAttribute('contenteditable', null));

// migration warnings
Assert::error(
	fn() => Assert::same('contenteditable="true"', HtmlHelpers::formatTristateAttribute('contenteditable', true, migrationWarnings: true)),
	E_USER_WARNING,
	'Behavior change for attribute \'contenteditable\' with value true: previously it rendered as contenteditable="1", now it renders as contenteditable="true".',
);
Assert::error(
	fn() => Assert::same('contenteditable="false"', HtmlHelpers::formatTristateAttribute('contenteditable', false, migrationWarnings: true)),
	E_USER_WARNING,
	'Behavior change for attribute \'contenteditable\' with value false: previously it rendered as contenteditable="", now it renders as contenteditable="false".',
);

// invalid
Assert::error(
	fn() => Assert::same('', HtmlHelpers::formatTristateAttribute('contenteditable', [])),
	E_USER_WARNING,
	"Invalid value for attribute 'contenteditable': array is not allowed.",
);
Assert::error(
	fn() => Assert::same('', HtmlHelpers::formatTristateAttribute('contenteditable', (object) [])),
	E_USER_WARNING,
	"Invalid value for attribute 'contenteditable': stdClass is not allowed.",
);
