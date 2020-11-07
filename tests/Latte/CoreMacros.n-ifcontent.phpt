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
	$latte->compile('<html>{ifcontent}');
}, Latte\CompileException::class, 'Unknown {ifcontent}, use n:ifcontent attribute.');


Assert::exception(function () use ($latte) {
	$latte->compile('<div n:inner-ifcontent>');
}, Latte\CompileException::class, 'Unknown n:inner-ifcontent, use n:ifcontent attribute.');


Assert::match(
	<<<'XX'
%A%
		ob_start(function () {});
		?><div class="bar" <?php
		if (isset($id)) {
			?>id="content"<?php
		}
		?>><?php
		ob_start();
		$__loc[1] = ob_get_flush();
		?></div><?php
		if (rtrim($__loc[1]) === '') {
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
