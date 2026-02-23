<?php declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();

Assert::match(
	'%A%echo LR\HtmlHelpers::escapeText(test(fn() => 1)) /* pos 1:1 */;%A%',
	$latte->compile('{test(function () { return 1;})}'),
);

Assert::match(
	'%A%echo LR\HtmlHelpers::escapeText(test(fn() => 1)) /* pos 1:1 */;%A%',
	$latte->compile('{test(function () use ($a) { return 1;})}'),
);

Assert::match(
	'%A%echo LR\HtmlHelpers::escapeText(test(fn() => 1)) /* pos 1:1 */;%A%',
	$latte->compile('{test(fn () => 1)}'),
);

Assert::match(
	"%A%
		if (1) /* pos 1:1 */ {
			echo 'xxx';
		}
%A%",
	$latte->compile('{if 1}xxx{/}'),
);

Assert::match( // fix #58
	'x',
	$latte->renderToString('{contentType application/xml}{if true}x{/if}'),
);

Assert::match( // fix
	'<input x >',
	$latte->renderToString('<input x {*a*}>{*b*}'),
);

Assert::match( // html is case insensitive
	'<a></a>',
	$latte->renderToString('<a n:if=1></A>'),
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

Assert::match(
	'<span foo empty="" space=" " title disabled="disabled"></span>',
	$latte->renderToString('<span foo empty="" space=" " title disabled="disabled"></span>'),
);


// latte tag in html tag
Assert::match(
	'<a><br {foo}></a>',
	$latte->renderToString('<a n:syntax="double"><br {foo}></a>'),
);

Assert::match(
	'<brx>', // bad, but allowed for compatibility
	$latte->renderToString('<br{if 1}x{/if}>'),
);



// tag name vs content
Assert::contains(
	"HtmlHelpers::escapeText(trim('a'))",
	$latte->compile('{trim("a")}'),
);

Assert::contains(
	"HtmlHelpers::escapeText(\\trim('a'))",
	$latte->compile('{\trim("a")}'),
);

Assert::contains(
	'HtmlHelpers::escapeText(MyClass::foo)',
	$latte->compile('{MyClass::foo}'),
);

Assert::contains(
	'HtmlHelpers::escapeText(My\Class::foo)',
	$latte->compile('{My\Class::foo}'),
);


// n:attributes
Assert::match(
	'<a></a>',
	$latte->renderToString('<a n:if = "1"></a>'),
);

Assert::match(
	'<a></a>',
	$latte->renderToString('<a n:if = {trim("{}")}></a>'),
);
