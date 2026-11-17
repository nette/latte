<?php declare(strict_types=1);

use Latte\Runtime\HtmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$contentTypes = [
	'data-tippy-content' => 'html',
	'x-on:*' => 'js',
	'x-on:click.*' => 'css',
	'hidden' => 'js',
];

Assert::same('html', HtmlHelpers::matchAttributeContentType('data-tippy-content', $contentTypes));
Assert::same('html', HtmlHelpers::matchAttributeContentType('DATA-Tippy-Content', $contentTypes));
Assert::same('js', HtmlHelpers::matchAttributeContentType('x-on:', $contentTypes));
Assert::same('js', HtmlHelpers::matchAttributeContentType('x-on:keyup', $contentTypes));
Assert::same('css', HtmlHelpers::matchAttributeContentType('x-on:click.prevent', $contentTypes)); // longest prefix wins
Assert::null(HtmlHelpers::matchAttributeContentType('x-on', $contentTypes));
Assert::null(HtmlHelpers::matchAttributeContentType('data-tippy', $contentTypes));
Assert::null(HtmlHelpers::matchAttributeContentType('data-tippy-content-x', $contentTypes));
Assert::null(HtmlHelpers::matchAttributeContentType('x-on:click', []));

// built-in content types are the default table
Assert::same('js', HtmlHelpers::matchAttributeContentType('on'));
Assert::same('js', HtmlHelpers::matchAttributeContentType('OnClick'));
Assert::same('css', HtmlHelpers::matchAttributeContentType('style'));
Assert::same('html', HtmlHelpers::matchAttributeContentType('SrcDoc'));
Assert::null(HtmlHelpers::matchAttributeContentType('styles'));
Assert::null(HtmlHelpers::matchAttributeContentType('x-on:click'));
Assert::same('js', HtmlHelpers::matchAttributeContentType('onclick', ['x-on:*' => 'js'] + HtmlHelpers::AttributeContentTypes));

// classification
Assert::same('srcdoc', HtmlHelpers::classifyAttributeType('data-tippy-content', $contentTypes));
Assert::same('js', HtmlHelpers::classifyAttributeType('x-on:keyup', $contentTypes));
Assert::same('style', HtmlHelpers::classifyAttributeType('x-on:click.prevent', $contentTypes));
Assert::same('js', HtmlHelpers::classifyAttributeType('hidden', $contentTypes)); // overrides built-in type
Assert::same('valuedBool', HtmlHelpers::classifyAttributeType('hidden'));
Assert::same('data', HtmlHelpers::classifyAttributeType('data-tippy-content'));
Assert::same('js', HtmlHelpers::classifyAttributeType('onclick'));
Assert::same('', HtmlHelpers::classifyAttributeType('title'));
Assert::same('style', HtmlHelpers::classifyAttributeType('Style'));
Assert::same('', HtmlHelpers::classifyAttributeType('style', [])); // the table decides, no hard-coded names

// cached result belongs to the name and the table
for ($i = 0; $i < 3; $i++) {
	Assert::same('js', HtmlHelpers::classifyAttributeType('hidden', $contentTypes));
	Assert::same('valuedBool', HtmlHelpers::classifyAttributeType('hidden'));
	Assert::same('valuedBool', HtmlHelpers::classifyAttributeType('HIDDEN'));
}

for ($i = 0; $i < 3000; $i++) { // more names than the cache holds
	Assert::same('data', HtmlHelpers::classifyAttributeType("data-$i"));
}
Assert::same('valuedBool', HtmlHelpers::classifyAttributeType('hidden'));

Assert::exception(
	fn() => HtmlHelpers::classifyAttributeType('data-x', ['data-x' => 'text']),
	LogicException::class,
	"Unknown content type 'text' of attribute 'data-x'.",
);
