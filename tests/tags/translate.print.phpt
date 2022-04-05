<?php

/**
 * Test: Latte\Macros\CoreMacros: {_}
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::contains(
	'echo LR\Filters::escapeHtmlText(($this->filters->translate)(\'var\')) /* line 1 */;',
	$latte->compile('{_var}')
);
Assert::contains(
	'echo LR\Filters::escapeHtmlText(($this->filters->filter)(($this->filters->translate)(\'var\'))) /* line 1 */;',
	$latte->compile('{_var|filter}')
);
