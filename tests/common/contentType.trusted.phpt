<?php declare(strict_types=1);

/**
 * Test: trusted JavaScript, CSS and XML printed into contexts
 */

use Latte\Runtime\{Html, TrustedCss, TrustedJavaScript, TrustedXml};
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();
$params = [
	'js' => new TrustedJavaScript('if (a < b) f("</script><script>alert(1)</script>", \'{{x}}\')'),
	'css' => new TrustedCss('a { content: "</style><script>alert(1)</script>" }'),
	'xml' => new TrustedXml('<item a="&quot;">]]>--></item>'),
	'html' => new Html('<b title="&quot;">{{x}}</b>'),
	'obj' => new class {
		public function __toString(): string
		{
			return '</script><b title="x">{{x}}</b>';
		}
	},
];


// own context: unchanged, only the end tag of <script> and <style> is neutralized
Assert::same(
	'<script>if (a < b) f("<\/script><script>alert(1)<\/script>", \'{{x}}\')</script>',
	$latte->renderToString('<script>{$js}</script>', $params),
);
Assert::same(
	'<style>a { content: "<\/style><script>alert(1)<\/script>" }</style>',
	$latte->renderToString('<style>{$css}</style>', $params),
);
Assert::same(
	'<p onclick="g(); if (a &lt; b) f(&quot;&lt;/script&gt;&lt;script&gt;alert(1)&lt;/script&gt;&quot;, &apos;&#123;&#123;x}}&apos;)"></p>',
	$latte->renderToString('<p onclick="g(); {$js}"></p>', $params),
);
Assert::same(
	'<p style="color: red; a &#123; content: &quot;&lt;/style&gt;&lt;script&gt;alert(1)&lt;/script&gt;&quot; }"></p>',
	$latte->renderToString('<p style="color: red; {$css}"></p>', $params),
);
Assert::same(
	'if (a < b) f("</script><script>alert(1)</script>", \'{{x}}\')',
	$latte->renderToString('{contentType javascript}{$js}', $params),
);
Assert::same(
	'a { content: "</style><script>alert(1)</script>" }',
	$latte->renderToString('{contentType css}{$css}', $params),
);
Assert::same(
	'<a><item a="&quot;">]]>--></item></a>',
	$latte->renderToString('{contentType xml}<a>{$xml}</a>', $params),
);


// foreign contexts with a convertor: the code is shown as text
Assert::same(
	'<p>if (a &lt; b) f("&lt;/script&gt;&lt;script&gt;alert(1)&lt;/script&gt;", \'{<!-- -->{x}}\')</p>',
	$latte->renderToString('<p>{$js}</p>', $params),
);
Assert::same(
	'<p title="x if (a &lt; b) f(&quot;&lt;/script&gt;&lt;script&gt;alert(1)&lt;/script&gt;&quot;, &apos;&#123;&#123;x}}&apos;)"></p>',
	$latte->renderToString('<p title="x {$js}"></p>', $params),
);
Assert::same(
	'<textarea>a { content: "&lt;/style&gt;&lt;script&gt;alert(1)&lt;/script&gt;" }</textarea>',
	$latte->renderToString('<textarea>{$css}</textarea>', $params),
);
Assert::same(
	'<!--  - - > x -->',
	$latte->renderToString('<!-- {$js} x -->', ['js' => new TrustedJavaScript('-->')]),
);
Assert::same(
	'<iframe srcdoc="x &amp;lt;/style&amp;gt;"></iframe>',
	$latte->renderToString('<iframe srcdoc="x {$css}"></iframe>', ['css' => new TrustedCss('</style>')]),
);
Assert::same(
	'<a>if (a &lt; b) f(&quot;&lt;/script&gt;&lt;script&gt;alert(1)&lt;/script&gt;&quot;, &apos;{{x}}&apos;)</a>',
	$latte->renderToString('{contentType xml}<a>{$js}</a>', $params),
);
Assert::same(
	'<a b="x ]]&gt;--&gt;"/>',
	$latte->renderToString('{contentType xml}<a b="x {$xml}"/>', $params),
);
Assert::same(
	']]>-->',
	$latte->renderToString('{contentType text}{$xml}', $params),
);


// XML into HTML goes through the deprecated conversion
Assert::error(
	fn() => Assert::same(
		'<p><item a="&quot;">]]>--></item></p>',
		$latte->renderToString('<p>{$xml}</p>', $params),
	),
	E_USER_DEPRECATED,
	'Converting content type XML into HTML is deprecated, XML and HTML will be incompatible.',
);


// no convertor
$noConvertor = [
	['<script>{$css}</script>', 'TrustedCss', 'HTML/RAW/JS', 'CSS'],
	['<style>{$js}</style>', 'TrustedJavaScript', 'HTML/RAW/CSS', 'JS'],
	['<p onclick="f({$css})"></p>', 'TrustedCss', 'HTML/ATTR/JS', 'CSS'],
	['<p style="color: {$js}"></p>', 'TrustedJavaScript', 'HTML/ATTR/CSS', 'JS'],
	['<p {$js}></p>', 'TrustedJavaScript', 'HTML/TAG', 'JS'],
	['<script type="text/html">{$js}</script>', 'TrustedJavaScript', 'HTML/RAW/HTML', 'JS'],
	['{contentType javascript}{$css}', 'TrustedCss', 'JS', 'CSS'],
	['{contentType css}{$js}', 'TrustedJavaScript', 'CSS', 'JS'],
	['{contentType css}{$xml}', 'TrustedXml', 'CSS', 'XML'],
	['{contentType text}{$js}', 'TrustedJavaScript', 'TEXT', 'JS'],
	['{contentType calendar}{$css}', 'TrustedCss', 'ICAL', 'CSS'],
	['{contentType xml}<a {$js}/>', 'TrustedJavaScript', 'XML/TAG', 'JS'],
];
foreach ($noConvertor as [$template, $class, $context, $type]) {
	Assert::exception(
		fn() => $latte->renderToString($template, $params),
		Latte\RuntimeException::class,
		"Cannot print Latte\\Runtime\\$class in context $context, content type $type cannot be converted to it.",
	);
}


// |noescape keeps the value as it is
Assert::same(
	'<p>if (a < b) f("</script><script>alert(1)</script>", \'{{x}}\')</p>',
	$latte->renderToString('<p>{$js|noescape}</p>', $params),
);
Assert::same(
	'<script>a { content: "<\/style><script>alert(1)<\/script>" }</script>',
	$latte->renderToString('<script>{$css|noescape}</script>', $params),
);


// the expression is evaluated once
$count = 0;
$fn = function () use (&$count) {
	$count++;
	return new TrustedJavaScript('a</script>');
};
Assert::same('<script>a<\/script></script>', $latte->renderToString('<script>{$fn()}</script>', ['fn' => $fn]));
Assert::same('<p>a&lt;/script&gt;</p>', $latte->renderToString('<p>{$fn() ?: 0}</p>', ['fn' => $fn]));
Assert::same(2, $count);
Assert::same('<script>1</script><p>2</p>', $latte->renderToString('{var $i = 1}<script>{$i++}</script><p>{$i}</p>'));


// nested contexts
Assert::same(
	'<script>a<\/script></script><p>a&lt;/script&gt;</p>',
	$latte->renderToString(
		'{define x}<script>{$js}</script>{/define}{if true}{include x}{/if}{foreach [1] as $i}<p>{$js}</p>{/foreach}',
		['js' => new TrustedJavaScript('a</script>')],
	),
);
Assert::same(
	'<script>a<\/script></script>',
	$latte->renderToString(
		'{capture $c}<script>{$js}</script>{/capture}{$c}',
		['js' => new TrustedJavaScript('a</script>')],
	),
);
Assert::same(
	'<style>b<\/style></style>',
	$latte->renderToString(
		'{block b}<style>{$css}</style>{/block}',
		['css' => new TrustedCss('b</style>')],
	),
);


// other objects are escaped as before
Assert::same(
	'<script>{}</script><p>&lt;/script&gt;&lt;b title="x"&gt;{<!-- -->{x}}&lt;/b&gt;</p>',
	$latte->renderToString('<script>{$obj}</script><p>{$obj}</p>', $params),
);
Assert::error(
	fn() => Assert::same(
		'<p><b title="&quot;">{{x}}</b></p>'
		. '<script>"<b title=\"&quot;\">{{x}}<\/b>"</script>'
		. '<style>\<b title\=\"\&quot\;\"\>\{\{x\}\}\<\/b\></style>'
		. '<p title="a &#123;&#123;x}}" style="a: \&lt;b title\=\&quot;\&amp;quot\;\&quot;\&gt;\&#123;\&#123;x\}\}\&lt;\/b\&gt;"'
		. ' onclick="f(&quot;&lt;b title=\&quot;&amp;quot;\&quot;&gt;&#123;&#123;x}}&lt;\/b&gt;&quot;)"></p>',
		$latte->renderToString(
			'<p>{$html}</p><script>{$html}</script><style>{$html}</style>'
			. '<p title="a {$html}" style="a: {$html}" onclick="f({$html})"></p>',
			$params,
		),
	),
	[
		[
			E_USER_DEPRECATED,
			'Printing Latte\Runtime\TrustedHtml in context HTML/RAW/CSS is deprecated, it will throw an exception because content type HTML cannot be converted to it.',
		],
		[
			E_USER_DEPRECATED,
			'Printing Latte\Runtime\TrustedHtml in context HTML/ATTR/CSS is deprecated, it will throw an exception because content type HTML cannot be converted to it.',
		],
	],
);
