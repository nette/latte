<?php

/**
 * Test: {parameters}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'main1' => '{include inc1.latte, a: 10}',
	'main2' => '{include inc2.latte, a: 10}',
	'main3' => '{include inc3.latte, a: 10}',
	'main4' => '{include inc4.latte, a: 10}',
	'main5' => '{include inc5.latte, a: 10}',

	'inc1.latte' => '{$a ?? "-"} {$b ?? "-"} {$glob ?? "-"}',
	'inc2.latte' => '{parameters $a} {$a ?? "-"} {$b ?? "-"} {$glob ?? "-"}',
	'inc3.latte' => '{parameters int $a = 5} {$a ?? "-"} {$b ?? "-"} {$glob ?? "-"}',
	'inc4.latte' => '{parameters $a, int $b = 5} {$a ?? "-"} {$b ?? "-"} {$glob ?? "-"}',
	'inc5.latte' => '{parameters $glob} {$a ?? "-"} {$b ?? "-"} {$glob ?? "-"}',
]));


Assert::same('10 - 123', $latte->renderToString('main1', ['glob' => 123]));
Assert::same(' 10 - -', $latte->renderToString('main2', ['glob' => 123]));
Assert::same(' 10 - -', $latte->renderToString('main3', ['glob' => 123]));
Assert::same(' 10 5 -', $latte->renderToString('main4', ['glob' => 123]));
Assert::same(' - - 123', $latte->renderToString('main5', ['glob' => 123]));
