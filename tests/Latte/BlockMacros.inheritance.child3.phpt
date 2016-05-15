<?php

/**
 * Test: {extends ...} test III.
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'
{extends none}

{block content}
	Content
{/block}
EOD;

Assert::match(<<<EOD

	Content
EOD
, $latte->renderToString($template));
