<?php

/**
 * Test: snippets.
 */

use Nette\Bridges\ApplicationLatte\UIMacros;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/mocks/SnippetBridge.php';

$bridge = new SnippetBridgeMock();
$bridge->snippetMode = FALSE;


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->addProvider('snippetBridge', $bridge);

Assert::match(<<<EOD
<div>
<div id="abc">	hello
</div></div>
EOD
, $latte->renderToString(<<<EOD
<div>
	{snippet abc}
	hello
	{/snippet}
</div>
EOD
));
//todo indent
