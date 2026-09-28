<?php declare(strict_types=1);

use Latte\Runtime\HtmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$contexts = [
	'data-tippy-content' => 'html',
	'x-on:*' => 'js',
	'x-on:click.*' => 'css',
	'hidden' => 'js',
];

Assert::same('html', HtmlHelpers::matchAttributeContext('data-tippy-content', $contexts));
Assert::same('html', HtmlHelpers::matchAttributeContext('DATA-Tippy-Content', $contexts));
Assert::same('js', HtmlHelpers::matchAttributeContext('x-on:', $contexts));
Assert::same('js', HtmlHelpers::matchAttributeContext('x-on:keyup', $contexts));
Assert::same('css', HtmlHelpers::matchAttributeContext('x-on:click.prevent', $contexts)); // longest prefix wins
Assert::null(HtmlHelpers::matchAttributeContext('x-on', $contexts));
Assert::null(HtmlHelpers::matchAttributeContext('data-tippy', $contexts));
Assert::null(HtmlHelpers::matchAttributeContext('data-tippy-content-x', $contexts));
Assert::null(HtmlHelpers::matchAttributeContext('x-on:click', []));

// classification
Assert::same('srcdoc', HtmlHelpers::classifyAttributeType('data-tippy-content', $contexts));
Assert::same('', HtmlHelpers::classifyAttributeType('x-on:keyup', $contexts));
Assert::same('style', HtmlHelpers::classifyAttributeType('x-on:click.prevent', $contexts));
Assert::same('', HtmlHelpers::classifyAttributeType('hidden', $contexts)); // overrides built-in type
Assert::same('valuedBool', HtmlHelpers::classifyAttributeType('hidden'));
Assert::same('data', HtmlHelpers::classifyAttributeType('data-tippy-content'));

Assert::exception(
	fn() => HtmlHelpers::classifyAttributeType('data-x', ['data-x' => 'text']),
	LogicException::class,
	"Unknown context 'text' for attribute 'data-x'.",
);
