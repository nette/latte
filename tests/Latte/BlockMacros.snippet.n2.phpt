<?php

/**
 * Test: snippets.
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/mocks/SnippetBridge.php';

$bridge = new SnippetBridgeMock;
$bridge->snippetMode = false;


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->addProvider('snippetBridge', $bridge);

Assert::match(<<<EOD
<p id="abc">hello</p>
EOD
, $latte->renderToString(<<<EOD
<p n:inner-snippet="abc">hello</p>
EOD
));


Assert::match(<<<EOD
<p id="abc">hello</p>
EOD
, $latte->renderToString(<<<EOD
<p n:snippet="abc">hello</p>
EOD
));


Assert::error(function () use ($latte) {
	$latte->compile('<p n:snippet="abc" n:foreach="$items as $item">hello</p>');
}, E_USER_WARNING, 'Combination of n:snippet with n:foreach is invalid, use n:inner-foreach.');
