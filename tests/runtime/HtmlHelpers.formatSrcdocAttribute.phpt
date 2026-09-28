<?php declare(strict_types=1);

use Latte\Runtime\Html;
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


Assert::same('srcdoc', HtmlHelpers::classifyAttributeType('srcdoc'));
Assert::same('srcdoc', HtmlHelpers::classifyAttributeType('SrcDoc'));

// escaped by escapeHtmlAttr
Assert::same('srcdoc="&amp;lt;b&amp;gt;"', HtmlHelpers::formatSrcdocAttribute('srcdoc', '<b>'));
Assert::same(' srcdoc="&lt;b&gt;"', HtmlHelpers::formatSrcdocAttribute(' srcdoc', new Html('<b>')));

// migration warnings
foreach (['<b>x</b>', 'a &amp; b', new StringObject] as $value) {
	Assert::error(
		fn() => HtmlHelpers::formatSrcdocAttribute('srcdoc', $value, migrationWarnings: true),
		E_USER_WARNING,
		"Behavior change for attribute 'srcdoc' with string value: previously it was rendered as HTML, now it is escaped as text (wrap trusted HTML in Latte\\Runtime\\Html).",
	);
}

foreach (['a & b > " {', 1, new Html('<b>x</b>')] as $value) { // renders the same document as before
	Assert::noError(fn() => HtmlHelpers::formatSrcdocAttribute('srcdoc', $value, migrationWarnings: true));
}

// null & invalid
Assert::same('', HtmlHelpers::formatSrcdocAttribute('srcdoc', null));
Assert::error(
	fn() => Assert::same('', HtmlHelpers::formatSrcdocAttribute('srcdoc', [])),
	E_USER_WARNING,
	"Invalid value for attribute 'srcdoc': array is not allowed.",
);
