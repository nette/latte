<?php

/**
 * Test: {extends auto}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();

$template = <<<'EOD'
	{extends auto}

	{block content}
		Content
	{/block}
	EOD;

Assert::match(<<<'EOD'

		Content
	EOD, $latte->renderToString($template));
