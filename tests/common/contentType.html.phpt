<?php

/**
 * Test: HTML
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);


// no escape in text
Assert::match(
	'<p></script></p>',
	$latte->renderToString('<p>{="</script>"|noescape}</p>'),
);

// no escape in tag
Assert::match(
	'<p foo a=\'a\' b="b">></p>',
	$latte->renderToString('<p {="foo a=\'a\' b=\"b\">"|noescape}></p>'),
);

// no escape in bogus tag
Assert::match(
	'<!doctype foo a=\'a\' b="b">></p>',
	$latte->renderToString('<!doctype {="foo a=\'a\' b=\"b\">"|noescape}></p>'),
);

// no escape in attribute
Assert::match(
	'<p title="foo a=\'a\' b="b">"></p>',
	$latte->renderToString('<p title={="foo a=\'a\' b=\"b\">"|noescape}></p>'),
);

// no escape in JS attribute
Assert::match(
	'<p onclick="foo a=\'a\' b="b">"></p>',
	$latte->renderToString('<p onclick="{="foo a=\'a\' b=\"b\">"|noescape}"></p>'),
);
