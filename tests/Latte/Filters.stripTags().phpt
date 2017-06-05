<?php

/**
 * Test: Latte\Runtime\Filters::stripTags()
 */

declare(strict_types=1);

use Latte\Engine;
use Latte\Runtime\Filters;
use Latte\Runtime\FilterInfo;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test(function () {
	$info = new FilterInfo(Engine::CONTENT_HTML);
	Assert::same('',  Filters::stripTags($info, ''));
	Assert::same('abc',  Filters::stripTags($info, 'abc'));
	Assert::same('Test paragraph. Other text',  Filters::stripTags($info, '<p>Test paragraph.</p><!-- Comment --> <a href="#fragment">Other text</a>'));
	Assert::same('<p>Test paragraph.</p> <a href="#fragment">Other text</a>',  Filters::stripTags($info, '<p>Test paragraph.</p><!-- Comment --> <a href="#fragment">Other text</a>', '<p><a>'));
});


test(function () {
	$info = new FilterInfo(Engine::CONTENT_XHTML);
	Assert::same('',  Filters::stripTags($info, ''));
	Assert::same('abc',  Filters::stripTags($info, 'abc'));
	Assert::same('Test paragraph. Other text',  Filters::stripTags($info, '<p>Test paragraph.</p><!-- Comment --> <a href="#fragment">Other text</a>'));
	Assert::same('<p>Test paragraph.</p> <a href="#fragment">Other text</a>',  Filters::stripTags($info, '<p>Test paragraph.</p><!-- Comment --> <a href="#fragment">Other text</a>', '<p><a>'));
});
