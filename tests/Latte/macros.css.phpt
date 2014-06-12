<?php

/**
 * Test: Latte\Engine and CSS.
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(
	'<style>\<\>',
	$latte->renderToString('<style>{="<>"}')
);

Assert::match(
	'<style>\<\/ \]\]\> \<\!',
	$latte->renderToString('<style>{="</"} {="]]>"} {="<!"}')
);

Assert::match(
	'<style></style>&lt;&gt;',
	$latte->renderToString('<style></style>{="<>"}')
);

Assert::match(
	'<style>123',
	$latte->renderToString('<style>{=123}')
);

Assert::match(
	'<style id="&lt;&gt;">',
	$latte->renderToString('<style id="{="<>"}">')
);
