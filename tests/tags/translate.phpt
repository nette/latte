<?php

/**
 * Test: Latte\Macros\CoreMacros: {translate}
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::contains(
	'echo $this->filters->filterContent("translate", $ʟ_fi, \'abc\') /* line 1 */;',
	$latte->compile('{translate}abc{/translate}')
);
Assert::contains(
	'echo $this->filters->filterContent(\'filter\', $ʟ_fi, $this->filters->filterContent("translate", $ʟ_fi, \'abc\')) /* line 1 */;',
	$latte->compile('{translate|filter}abc{/translate}')
);
