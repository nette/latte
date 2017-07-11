<?php

/**
 * Test: {extends ...} test IV.
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'
{block content}
	Content
{/block}
EOD;

Assert::match(<<<'EOD'
	Content
EOD
, $latte->renderToString($template));
