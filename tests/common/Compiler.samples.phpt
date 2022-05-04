<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(
	'%A%echo LR\Filters::escapeHtmlText(test(function () {
			return 1;
		}))%A%',
	$latte->compile('{test(function () { return 1;})}'),
);

Assert::match(
	'%A%echo LR\Filters::escapeHtmlText(test(function () use ($a) {
			return 1;
		}))%A%',
	$latte->compile('{test(function () use ($a) { return 1;})}'),
);

Assert::match(
	'%A%echo LR\Filters::escapeHtmlText(test(fn () => 1))%A%',
	$latte->compile('{test(fn () => 1)}'),
);

Assert::match(
	"%A%('foo')/ **/('bar')%A%",
	$latte->compile('{(foo)//**/**/(bar)}'),
);

Assert::match(
	"%A%
		if (1) /* line 1 */ {
			echo 'xxx';
		}
%A%",
	$latte->compile('{if 1}xxx{/}'),
);

Assert::match( // fix #58
	'x',
	$latte->renderToString('{contentType application/xml}{if true}x{/if}'),
);

Assert::match(
	'<a href=""></a>',
	$latte->renderToString('<a href="{ifset $x}{$x}{/ifset}"></a>'),
);

Assert::match( // </div> is not required here
	'<div class="a">',
	$latte->renderToString('{if true}<div n:class="a">{/if}'),
);

Assert::match(
	'<div> <a> </a></div>', // </a> is accepted
	$latte->renderToString('<div n:if="1"> {if true}<a>{/if} </a></div>'),
);

Assert::match(
	'<span attr1=val></span>',
	$latte->renderToString('<span {if true}attr1=val{else}attr2=val{/if}></span>'),
);


// tag name vs content
Assert::contains(
	'escapeHtmlText(trim("a"))',
	$latte->compile('{trim("a")}'),
);

Assert::contains(
	'escapeHtmlText(\trim("a"))',
	$latte->compile('{\trim("a")}'),
);

Assert::contains(
	'escapeHtmlText(MyClass::foo)',
	$latte->compile('{MyClass::foo}'),
);

Assert::contains(
	'escapeHtmlText(My\Class::foo)',
	$latte->compile('{My\Class::foo}'),
);
