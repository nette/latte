<?php

/**
 * Test: Latte\Runtime\Filters::indent()
 */

declare(strict_types=1);

use Latte\Engine;
use Latte\Runtime\FilterInfo;
use Latte\Runtime\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('', function () {
	$info = new FilterInfo(Engine::CONTENT_TEXT);
	Assert::same('', Filters::indent($info, ''));
	Assert::same("\n", Filters::indent($info, "\n"));
	Assert::same("\tword", Filters::indent($info, 'word'));
	Assert::same("\n\tword", Filters::indent($info, "\nword"));
	Assert::same("\n\tword", Filters::indent($info, "\nword"));
	Assert::same("\n\tword\n", Filters::indent($info, "\nword\n"));
	Assert::same("\r\n\tword\r\n", Filters::indent($info, "\r\nword\r\n"));
	Assert::same("\r\n\t\tword\r\n", Filters::indent($info, "\r\nword\r\n", 2));
	Assert::same("\r\n      word\r\n", Filters::indent($info, "\r\nword\r\n", 2, '   '));
});


test('', function () {
	$info = new FilterInfo(Engine::CONTENT_HTML);
	Assert::same('', Filters::indent($info, ''));
	Assert::same("\n", Filters::indent($info, "\n"));
	Assert::same("\tword", Filters::indent($info, 'word'));
	Assert::same("\n\tword", Filters::indent($info, "\nword"));
	Assert::same("\n\tword", Filters::indent($info, "\nword"));
	Assert::same("\n\tword\n", Filters::indent($info, "\nword\n"));
	Assert::same("\r\n\tword\r\n", Filters::indent($info, "\r\nword\r\n"));
	Assert::same("\r\n\t\tword\r\n", Filters::indent($info, "\r\nword\r\n", 2));
	Assert::same("\r\n      word\r\n", Filters::indent($info, "\r\nword\r\n", 2, '   '));
});
