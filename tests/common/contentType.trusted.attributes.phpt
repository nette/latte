<?php declare(strict_types=1);

/**
 * Test: trusted JavaScript, CSS and XML in dynamic attributes and n:attr
 */

use Latte\Runtime\{Html, TrustedCss, TrustedJavaScript, TrustedXml};
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();
$params = [
	'js' => new TrustedJavaScript('f("</script>", \'{{x}}\') && a < b'),
	'css' => new TrustedCss('a: "</style>" } b { c: d'),
	'xml' => new TrustedXml('<i a="&quot;">"x" & ]]></i>'),
	'html' => new Html('<b title="&quot;">"x"</b>'),
];
$js = 'f(&quot;&lt;/script&gt;&quot;, &apos;&#123;&#123;x}}&apos;) &amp;&amp; a &lt; b';
$css = 'a: &quot;&lt;/style&gt;&quot; } b &#123; c: d';


// whole value of a code attribute
Assert::same("<p onclick=\"$js\"></p>", $latte->renderToString('<p onclick={$js}></p>', $params));
Assert::same("<p onclick=\"$js\"></p>", $latte->renderToString('<p onclick="{$js}"></p>', $params));
Assert::same("<p style=\"$css\"></p>", $latte->renderToString('<p style={$css}></p>', $params));

// attributes of other types show the code as text
Assert::same("<p title=\"$js\"></p>", $latte->renderToString('<p title={$js}></p>', $params));
Assert::same("<p class=\"$css\"></p>", $latte->renderToString('<p class={$css}></p>', $params));
Assert::same("<p data-x=\"$js\"></p>", $latte->renderToString('<p data-x={$js}></p>', $params));
Assert::same("<p aria-label=\"$js\"></p>", $latte->renderToString('<p aria-label={$js}></p>', $params));
Assert::same("<p hidden=\"$js\"></p>", $latte->renderToString('<p hidden={$js}></p>', $params));
Assert::same(
	'<iframe srcdoc="a: &amp;quot;&amp;lt;/style&amp;gt;&amp;quot; } b &amp;#123; c: d"></iframe>',
	$latte->renderToString('<iframe srcdoc={$css}></iframe>', $params),
);
Assert::noError(fn() => createLatte()
	->setFeature(Latte\Feature::MigrationWarnings)
	->renderToString('<iframe srcdoc={$css}></iframe>', $params)); // trusted content was never rendered as HTML

// mismatches
Assert::exception(
	fn() => $latte->renderToString('<p onclick={$css}></p>', $params),
	Latte\RuntimeException::class,
	'Cannot print Latte\Runtime\TrustedCss in context HTML/ATTR/JS, content type CSS cannot be converted to it.',
);
Assert::exception(
	fn() => $latte->renderToString('<p style={$js}></p>', $params),
	Latte\RuntimeException::class,
	'Cannot print Latte\Runtime\TrustedJavaScript in context HTML/ATTR/CSS, content type JS cannot be converted to it.',
);
Assert::exception(
	fn() => $latte->renderToString('<p style={$xml}></p>', $params),
	Latte\RuntimeException::class,
	'Cannot print Latte\Runtime\TrustedXml in context HTML/ATTR/CSS, content type XML cannot be converted to it.',
);

// XML into HTML goes through the deprecated conversion
Assert::error(
	fn() => Assert::same('<p title="&quot;x&quot; &amp; ]]&gt;"></p>', $latte->renderToString('<p title={$xml}></p>', $params)),
	E_USER_DEPRECATED,
	'Converting content type XML into HTML/ATTR is deprecated, XML and HTML will be incompatible.',
);

// attribute registered by an extension
$latte2 = createLatte();
$latte2->addExtension(new class extends Latte\Extension {
	public function getAttributeContentTypes(): array
	{
		return ['x-on:*' => 'js', 'x-style' => 'css'];
	}
});
Assert::same("<p x-on:click=\"$js\" x-style=\"$css\"></p>", $latte2->renderToString('<p x-on:click={$js} x-style={$css}></p>', $params));
Assert::exception(
	fn() => $latte2->renderToString('<p x-on:click={$css}></p>', $params),
	Latte\RuntimeException::class,
	'Cannot print Latte\Runtime\TrustedCss in context HTML/ATTR/JS, %a%',
);


// n:attr
Assert::same(
	"<p onclick=\"$js\" style=\"$css\" title=\"$js\"></p>",
	$latte->renderToString('<p n:attr="onclick => $js, style => $css, title => $js"></p>', $params),
);
Assert::same('<p onclick></p>', $latte->renderToString('<p n:attr="onclick => true"></p>'));
Assert::exception(
	fn() => $latte->renderToString('<p n:attr="onclick => $css"></p>', $params),
	Latte\RuntimeException::class,
	'Cannot print Latte\Runtime\TrustedCss in context HTML/ATTR/JS, %a%',
);
Assert::exception(
	fn() => $latte->renderToString('<p n:attr="style => $js"></p>', $params),
	Latte\RuntimeException::class,
	'Cannot print Latte\Runtime\TrustedJavaScript in context HTML/ATTR/CSS, %a%',
);


// XML
Assert::same(
	'<a b="&quot;x&quot; &amp; ]]&gt;" c="&quot;x&quot; &amp; ]]&gt;"/>',
	$latte->renderToString('{contentType xml}<a b={$xml} n:attr="c => $xml"/>', $params),
);
Assert::same(
	'<a b="a: &quot;&lt;/style&gt;&quot; } b { c: d"/>',
	$latte->renderToString('{contentType xml}<a b={$css}/>', $params),
);


// |noescape only escapes quotes
Assert::same(
	'<p onclick="a: &quot;</style>&quot; } b { c: d"></p>',
	$latte->renderToString('<p onclick={$css|noescape}></p>', $params),
);


// other objects are escaped as before
Assert::error(
	fn() => Assert::same(
		'<p onclick="&quot;x&quot;" style="&quot;x&quot;" title="&quot;x&quot;"></p>',
		$latte->renderToString('<p onclick={$html} style={$html} title={$html}></p>', $params),
	),
	[
		[
			E_USER_DEPRECATED,
			'Printing Latte\Runtime\TrustedHtml in context HTML/ATTR/JS is deprecated, it will be converted from content type HTML, which changes the output.',
		],
		[
			E_USER_DEPRECATED,
			'Printing Latte\Runtime\TrustedHtml in context HTML/ATTR/CSS is deprecated, it will throw an exception because content type HTML cannot be converted to it.',
		],
	],
);
Assert::error(
	fn() => Assert::same(
		'<p onclick="&quot;x&quot;" title="&quot;x&quot;"></p>',
		$latte->renderToString('<p n:attr="onclick => $html, title => $html"></p>', $params),
	),
	E_USER_DEPRECATED,
	'Printing Latte\Runtime\TrustedHtml in context HTML/ATTR/JS is deprecated, it will be converted from content type HTML, which changes the output.',
);
