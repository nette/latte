<?php

/**
 * Test: Latte\Runtime\Filters::htmlAttributes
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('', Filters::htmlAttributes(null));

Assert::same(' style="float:left" class="three" a=\'<>"\' b="\'" title="0" checked', Filters::htmlAttributes([
	'style' => 'float:left',
	'class' => 'three',
	'a' => '<>"',
	'b' => "'",
	'title' => '0',
	'checked' => true,
	'selected' => false,
]));

Assert::same(' a="`test "', Filters::htmlAttributes(['a' => '`test'])); // mXSS

Filters::$xhtml = true;
Assert::same(' style="float:left" class="three" a=\'&lt;>"\' b="\'" title="0" checked="checked"', Filters::htmlAttributes([
	'style' => 'float:left',
	'class' => 'three',
	'a' => '<>"',
	'b' => "'",
	'title' => '0',
	'checked' => true,
	'selected' => false,
]));
