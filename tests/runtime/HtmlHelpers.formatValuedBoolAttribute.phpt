<?php declare(strict_types=1);

use Latte\Runtime\HtmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class StringObject
{
	public function __toString()
	{
		return 'until-found';
	}
}


// bool
Assert::same('hidden', HtmlHelpers::formatValuedBoolAttribute('hidden', true));
Assert::same('', HtmlHelpers::formatValuedBoolAttribute('hidden', false));

// keyword is preserved
Assert::same('hidden="until-found"', HtmlHelpers::formatValuedBoolAttribute('hidden', 'until-found'));
Assert::same('popover="manual"', HtmlHelpers::formatValuedBoolAttribute('popover', 'manual'));
Assert::same('hidden="until-found"', HtmlHelpers::formatValuedBoolAttribute('hidden', new StringObject));
Assert::same('hidden="one&amp;two"', HtmlHelpers::formatValuedBoolAttribute('hidden', 'one&two'));

// falsey values keep the attribute off, they are not keywords
Assert::same('', HtmlHelpers::formatValuedBoolAttribute('hidden', ''));
Assert::same('', HtmlHelpers::formatValuedBoolAttribute('hidden', '0'));
Assert::same('', HtmlHelpers::formatValuedBoolAttribute('hidden', 0));
Assert::same('', HtmlHelpers::formatValuedBoolAttribute('hidden', []));
Assert::same('hidden="1"', HtmlHelpers::formatValuedBoolAttribute('hidden', 1));

// skipped
Assert::same('', HtmlHelpers::formatValuedBoolAttribute('hidden', null));

// invalid
Assert::error(
	fn() => Assert::same('', HtmlHelpers::formatValuedBoolAttribute('hidden', [1])),
	E_USER_WARNING,
	"Invalid value for attribute 'hidden': array is not allowed.",
);
