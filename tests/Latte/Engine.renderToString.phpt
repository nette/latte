<?php

/**
 * Test: Latte\Engine::renderToString()
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';

test(function () {
	$template_text = __DIR__ . '/templates/contentType-text.latte';
	$template_mime = __DIR__ . '/templates/contentType-mime.latte';

	$latte = new Latte\Engine;
	$latte->setTempDirectory(TEMP_DIR);

	Assert::equal("Content\n", $latte->renderToString($template_text));
	Assert::equal([], headers_list());

	Assert::equal("Content\n", $latte->renderToString($template_mime));
	Assert::equal([], headers_list()); // should not affect global state
});
