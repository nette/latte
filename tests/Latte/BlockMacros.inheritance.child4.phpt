<?php

/**
 * Test: {extends ...} test IV.
 */

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
