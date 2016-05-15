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
<p><div id="abc">hello</div> world</p>
EOD
, $latte->renderToString(<<<EOD
<p>{snippet abc}hello{/snippet} world</p>
EOD
));
