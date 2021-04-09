<?php

/**
 * Test: Latte\Engine and n:ifcontent.
 */

declare(strict_types=1);

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


Assert::exception(function () use ($latte) {
	$latte->compile('<div n:ifcontent=x></div>');
}, Latte\CompileException::class, 'Arguments are not allowed in n:ifcontent');


Assert::exception(function () use ($latte) {
	$latte->compile('<html>{ifcontent}');
}, Latte\CompileException::class, 'Unknown {ifcontent}, use n:ifcontent attribute.');


Assert::exception(function () use ($latte) {
	$latte->compile('<div n:inner-ifcontent>');
}, Latte\CompileException::class, 'Unknown n:inner-ifcontent, use n:ifcontent attribute.');


Assert::match(
	<<<'XX'
%A%
		ob_start(function () {});
		echo '<div class="bar" ';
		if (isset($id)) /* line 1 */ {
			echo 'id="content"';
		}
		echo '>';
		ob_start();
		$ʟ_ifc = ob_get_flush();
		echo '</div>';
		if (rtrim($ʟ_ifc) === "") {
			ob_end_clean();
		}
		else {
			echo ob_get_clean();
		}
%A%
XX
,
	$latte->compile('<div class="bar" {ifset $id}id="content"{/ifset} n:ifcontent></div>')
);
