<?php declare(strict_types=1);

/**
 * Test: HTML
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();


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
	'<p title="foo a=&apos;a&apos; b=&quot;b&quot;>"></p>',
	$latte->renderToString('<p title={="foo a=\'a\' b=\"b\">"|noescape}></p>'),
);

// no escape in JS attribute
Assert::match(
	'<p onclick="foo a=&apos;a&apos; b=&quot;b&quot;>"></p>',
	$latte->renderToString('<p onclick="{="foo a=\'a\' b=\"b\">"|noescape}"></p>'),
);
