<?php

/**
 * Test: {extends ...} test III.
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();

$template = <<<'EOD'
	{extends none}

	{block content}
		Content
	{/block}
	EOD;

Assert::match(<<<'EOD'

		Content
	EOD, $latte->renderToString($template));
