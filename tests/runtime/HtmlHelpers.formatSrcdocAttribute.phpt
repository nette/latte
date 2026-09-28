<?php declare(strict_types=1);

use Latte\Runtime\Html;
use Latte\Runtime\HtmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('srcdoc', HtmlHelpers::classifyAttributeType('srcdoc'));
Assert::same('srcdoc', HtmlHelpers::classifyAttributeType('SrcDoc'));

// escaped by escapeHtmlAttr
Assert::same('srcdoc="&amp;lt;b&amp;gt;"', HtmlHelpers::formatSrcdocAttribute('srcdoc', '<b>'));
Assert::same(' srcdoc="&lt;b&gt;"', HtmlHelpers::formatSrcdocAttribute(' srcdoc', new Html('<b>')));

// null & invalid
Assert::same('', HtmlHelpers::formatSrcdocAttribute('srcdoc', null));
Assert::error(
	fn() => Assert::same('', HtmlHelpers::formatSrcdocAttribute('srcdoc', [])),
	E_USER_WARNING,
	"Invalid value for attribute 'srcdoc': array is not allowed.",
);
