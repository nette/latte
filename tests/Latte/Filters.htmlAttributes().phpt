<?php

/**
 * Test: Latte\Runtime\Filters::htmlAttributes
 */

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('', Filters::htmlAttributes(NULL));

Assert::same(' style="float:left" class="three" a=\'<>"\' b="\'" title="0" checked', Filters::htmlAttributes([
	'style' => 'float:left',
	'class' => 'three',
	'a' => '<>"',
	'b' => "'",
	'title' => '0',
	'checked' => TRUE,
	'selected' => FALSE,
]));

Assert::same(' a="`test "', Filters::htmlAttributes(['a' => '`test'])); // mXSS

Filters::$xhtml = TRUE;
Assert::same(' style="float:left" class="three" a=\'&lt;>"\' b="\'" title="0" checked="checked"', Filters::htmlAttributes([
	'style' => 'float:left',
	'class' => 'three',
	'a' => '<>"',
	'b' => "'",
	'title' => '0',
	'checked' => TRUE,
	'selected' => FALSE,
]));
