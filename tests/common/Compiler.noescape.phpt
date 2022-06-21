<?php

/**
 * Test: |noescape
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

// html text
Assert::match(
	'<p></script></p>',
	$latte->renderToString('<p>{="</script>"|noescape}</p>'),
);

// in tag
Assert::match(
	'<p foo a=\'a\' b="b">></p>',
	$latte->renderToString('<p {="foo a=\'a\' b=\"b\">"|noescape}></p>'),
);

// in bogus tag
Assert::match(
	'<!doctype foo a=\'a\' b="b">></p>',
	$latte->renderToString('<!doctype {="foo a=\'a\' b=\"b\">"|noescape}></p>'),
);

// attribute unquoted values
Assert::match(
	'<p title=foo a=\'a\' b="b">></p>',
	$latte->renderToString('<p title={="foo a=\'a\' b=\"b\">"|noescape}></p>'),
);

// attribute quoted values
Assert::match(
	'<p title="foo a=\'a\' b=&quot;b&quot;>"></p>',
	$latte->renderToString('<p title="{="foo a=\'a\' b=\"b\">"|noescape}"></p>'),
);

Assert::match(
	'<p title=\'foo a=&apos;a&apos; b="b">\'></p>',
	$latte->renderToString('<p title=\'{="foo a=\'a\' b=\"b\">"|noescape}\'></p>'),
);

Assert::match(
	'<p style="foo a=\'a\' b=&quot;b&quot;>"></p>',
	$latte->renderToString('<p style="{="foo a=\'a\' b=\"b\">"|noescape}"></p>'),
);

Assert::match(
	'<p onclick="foo a=\'a\' b=&quot;b&quot;>"></p>',
	$latte->renderToString('<p onclick="{="foo a=\'a\' b=\"b\">"|noescape}"></p>'),
);
