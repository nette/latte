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
	$latte->renderToString('<div n:ifcontent>Content</div>'),
);


Assert::match(
	'',
	$latte->renderToString('<div n:ifcontent></div>'),
);


Assert::match(
	'<div>0</div>',
	$latte->renderToString('<div n:ifcontent>{$content}</div>', ['content' => '0']),
);


Assert::match(
	'',
	$latte->renderToString('<div n:ifcontent>{$empty}</div>', ['empty' => '']),
);


Assert::match(
	'',
	$latte->renderToString("<div n:ifcontent> \r\n </div>"),
);


Assert::match(
	'',
	$latte->renderToString('<div n:ifcontent>  {$empty}  </div>', ['empty' => '']),
);


Assert::match(
	'<div>1</div>',
	$latte->renderToString('<div n:foreach="[1,2] as $n" n:ifcontent>{$n}{breakIf true}</div>'),
);


Assert::match(
	'<div>1</div>
<div>2</div>',
	$latte->renderToString('<div n:foreach="[1,2] as $n" n:ifcontent>{$n}{continueIf true}</div>'),
);


Assert::exception(
	fn() => $latte->compile('<div n:ifcontent=x></div>'),
	Latte\CompileException::class,
	'Arguments are not allowed in n:ifcontent',
);


Assert::exception(
	fn() => $latte->compile('<html>{ifcontent}'),
	Latte\CompileException::class,
	'Unknown {ifcontent}, use n:ifcontent attribute.',
);


Assert::exception(
	fn() => $latte->compile('<div n:inner-ifcontent>'),
	Latte\CompileException::class,
	'Unknown n:inner-ifcontent, use n:ifcontent attribute.',
);


Assert::exception(
	fn() => $latte->renderToString('<br n:ifcontent>'),
	Latte\CompileException::class,
	'Unnecessary n:ifcontent on empty element <br>',
);


Assert::exception(
	fn() => $latte->renderToString('<div n:ifcontent />'),
	Latte\CompileException::class,
	'Unnecessary n:ifcontent on empty element <div>',
);


Assert::match(
	<<<'XX'
		%A%
				ob_start(function () {});
				try {
					echo '<div class="bar" ';
					if (isset($id)) /* line 1 */ {
						echo 'id="content"';
					}
					echo '>';
					ob_start();
					try {
					} finally {
						$ʟ_ifc[1] = rtrim(ob_get_flush()) === '';
					}
					echo '</div>';
				} finally {
					if ($ʟ_ifc[1] ?? null) {
						ob_end_clean();
					} else {
						echo ob_get_clean();
					}
				}
		%A%
		XX,
	$latte->compile('<div class="bar" {ifset $id}id="content"{/ifset} n:ifcontent></div>'),
);
