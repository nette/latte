<?php

declare(strict_types=1);

use Latte\Essential\Nodes\NAttrNode;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('', NAttrNode::attrs(null, false));

Assert::same(' style="float:left" class="three" a=\'<>"\' b="\'" title="0" checked', NAttrNode::attrs([
	'style' => 'float:left',
	'class' => 'three',
	'a' => '<>"',
	'b' => "'",
	'title' => '0',
	'checked' => true,
	'selected' => false,
], false));

Assert::same(' a="`test "', NAttrNode::attrs(['a' => '`test'], false)); // mXSS

Assert::same(' style="float:left" class="three" a=\'&lt;>"\' b="\'" title="0" checked="checked"', NAttrNode::attrs([
	'style' => 'float:left',
	'class' => 'three',
	'a' => '<>"',
	'b' => "'",
	'title' => '0',
	'checked' => true,
	'selected' => false,
], true));

// invalid UTF-8
Assert::same(" a=\"foo \u{D800} bar\"", NAttrNode::attrs(['a' => "foo \u{D800} bar"], false)); // invalid codepoint high surrogates
Assert::same(" a='foo \xE3\x80\x22 bar'", NAttrNode::attrs(['a' => "foo \xE3\x80\x22 bar"], false)); // stripped UTF
