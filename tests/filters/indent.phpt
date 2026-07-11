<?php declare(strict_types=1);

/**
 * Test: Latte\Essential\Filters::indent()
 */

use Latte\ContentType;
use Latte\Essential\Filters;
use Latte\Runtime\FilterInfo;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('text indentation', function () {
	$info = new FilterInfo(ContentType::Text);
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


test('HTML indentation', function () {
	$info = new FilterInfo(ContentType::Html);
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


test('indentation characters are not interpreted as regexp backreferences', function () {
	$info = new FilterInfo(ContentType::Text);
	Assert::same('$0word', Filters::indent($info, 'word', 1, '$0'));
	Assert::same('\1word', Filters::indent($info, 'word', 1, '\1'));
});
