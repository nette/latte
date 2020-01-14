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

// invalid UTF-8
Assert::same(" a=\"foo \u{D800} bar\"", Filters::htmlAttributes(['a' => "foo \u{D800} bar"])); // invalid codepoint high surrogates
Assert::same(" a='foo \xE3\x80\x22 bar'", Filters::htmlAttributes(['a' => "foo \xE3\x80\x22 bar"])); // stripped UTF
