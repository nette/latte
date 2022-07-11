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
	'main6' => '{include inc6.latte, a: 10}',
	'main7' => '{include inc7.latte, a: 10}',
	'main8' => '{include inc8.latte, a: 10}',

	'inc1.latte' => '{$a ?? "-"} {$b ?? "-"} {$glob ?? "-"}',
	'inc2.latte' => '{parameters $a} {$a ?? "-"} {$b ?? "-"} {$glob ?? "-"}',
	'inc3.latte' => '{parameters int $a = 5} {$a ?? "-"} {$b ?? "-"} {$glob ?? "-"}',
	'inc4.latte' => '{parameters $a, int $b = 5} {$a ?? "-"} {$b ?? "-"} {$glob ?? "-"}',
	'inc5.latte' => '{parameters $glob} {$a ?? "-"} {$b ?? "-"} {$glob ?? "-"}',
	'inc6.latte' => '{parameters ?\Exception $glob} {$a ?? "-"} {$b ?? "-"} {$glob->getMessage() ?? "-"}',
	'inc7.latte' => '{parameters $a, int $b = 5} {block x}{$a ?? "-"} {$b ?? "-"} {$glob ?? "-"}{/block}',
	'inc8.latte' => '{parameters $a, int $b = 5} {define x}{$a ?? "-"} {$b ?? "-"} {$glob ?? "-"}{/define}{include x}',
]));

Assert::matchFile(__DIR__ . '/expected/parameters.inc1.phtml', $latte->compile('inc1.latte'));
Assert::matchFile(__DIR__ . '/expected/parameters.inc2.phtml', $latte->compile('inc2.latte'));
Assert::matchFile(__DIR__ . '/expected/parameters.inc3.phtml', $latte->compile('inc3.latte'));
Assert::matchFile(__DIR__ . '/expected/parameters.inc4.phtml', $latte->compile('inc4.latte'));
Assert::matchFile(__DIR__ . '/expected/parameters.inc5.phtml', $latte->compile('inc5.latte'));
Assert::matchFile(__DIR__ . '/expected/parameters.inc6.phtml', $latte->compile('inc6.latte'));
Assert::matchFile(__DIR__ . '/expected/parameters.inc7.phtml', $latte->compile('inc7.latte'));
Assert::matchFile(__DIR__ . '/expected/parameters.inc8.phtml', $latte->compile('inc8.latte'));

Assert::same('10 - 123', $latte->renderToString('main1', ['glob' => 123]));
Assert::same(' 10 - -', $latte->renderToString('main2', ['glob' => 123]));
Assert::same(' 10 - -', $latte->renderToString('main3', ['glob' => 123]));
Assert::same(' 10 5 -', $latte->renderToString('main4', ['glob' => 123]));
Assert::same(' - - 123', $latte->renderToString('main5', ['glob' => 123]));
Assert::same(' - - 123', $latte->renderToString('main6', ['glob' => new \Exception("123")]));
Assert::same(' 10 5 -', $latte->renderToString('main7', ['glob' => 123]));
Assert::same(' 10 5 -', $latte->renderToString('main8', ['glob' => 123]));
