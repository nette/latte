<?php

/**
 * Test: Latte\Engine and n:ifcontent.
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(
	'<div>Content</div>',
	$latte->renderToString('<div n:ifcontent>Content</div>')
);


Assert::match(
	'',
	$latte->renderToString('<div n:ifcontent></div>')
);


Assert::match(
	'<div>0</div>',
	$latte->renderToString('<div n:ifcontent>{$content}</div>', ['content' => '0'])
);


Assert::match(
	'',
	$latte->renderToString('<div n:ifcontent>{$empty}</div>', ['empty' => ''])
);


Assert::match(
	'',
	$latte->renderToString("<div n:ifcontent> \r\n </div>")
);


Assert::match(
	'',
	$latte->renderToString('<div n:ifcontent>  {$empty}  </div>', ['empty' => ''])
);


Assert::exception(function() use ($latte) {
	$latte->compile('<html>{ifcontent}');
}, 'Latte\CompileException', 'Unknown macro {ifcontent}, use n:ifcontent attribute.');


Assert::exception(function() use ($latte) {
	$latte->compile('<div n:inner-ifcontent>');
}, 'Latte\CompileException', 'Unknown attribute n:inner-ifcontent, use n:ifcontent attribute.');
