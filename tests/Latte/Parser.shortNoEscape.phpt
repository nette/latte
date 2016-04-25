<?php

/**
 * Test: Latte\Parser and $shortNoEscape.
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match('&lt;&gt;', $latte->renderToString('{="<>"}'));

Assert::match('<>', @$latte->renderToString('{!="<>"}'));

Assert::match('&lt;&gt;', $latte->renderToString('{="<>"}'));

Assert::error(function () use ($latte) {
	$latte->compile('{!="<>"}');
}, E_USER_DEPRECATED, 'The noescape shortcut {!...} is deprecated, use {...|noescape} modifier on line 1.');
