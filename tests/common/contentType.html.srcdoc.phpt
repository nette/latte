<?php declare(strict_types=1);

/**
 * Test: HTML document in srcdoc attribute
 */

use Latte\Runtime\Html;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();
$params = [
	'x' => '<script>alert("1")</script> & {',
	'html' => new Html('<b title="a&amp;b">bold</b>'),
];

// text is escaped for HTML and then for the attribute
Assert::same(
	'<iframe srcdoc="<p>&amp;lt;script&amp;gt;alert(&quot;1&quot;)&amp;lt;/script&amp;gt; &amp;amp; &#123;&lt;!-- --&gt;</p>"></iframe>',
	$latte->renderToString('<iframe srcdoc="<p>{$x}</p>"></iframe>', $params),
);

Assert::same(
	'<iframe srcdoc="&amp;lt;script&amp;gt;alert(&quot;1&quot;)&amp;lt;/script&amp;gt; &amp;amp; &#123;&lt;!-- --&gt;"></iframe>',
	$latte->renderToString("<iframe srcdoc='{\$x}'></iframe>", $params),
);

Assert::same(
	'<iframe SRCDOC="a&amp;lt;script&amp;gt;alert(&quot;1&quot;)&amp;lt;/script&amp;gt; &amp;amp; &#123;&lt;!-- --&gt;"></iframe>',
	$latte->renderToString('<iframe SRCDOC=a{$x}></iframe>', $params),
);

// HTML is escaped only for the attribute
Assert::same(
	'<iframe srcdoc="&lt;b title=&quot;a&amp;amp;b&quot;&gt;bold&lt;/b&gt;"></iframe>',
	$latte->renderToString('<iframe srcdoc="{$html}"></iframe>', $params),
);

// |noescape escapes only quotes
Assert::same(
	'<iframe srcdoc="<script>alert(&quot;1&quot;)</script> & {"></iframe>',
	$latte->renderToString('<iframe srcdoc="{$x|noescape}"></iframe>', $params),
);

// dynamic attribute
Assert::same(
	'<iframe srcdoc="&amp;lt;script&amp;gt;alert(&quot;1&quot;)&amp;lt;/script&amp;gt; &amp;amp; &#123;&lt;!-- --&gt;"></iframe>',
	$latte->renderToString('<iframe srcdoc={$x}></iframe>', $params),
);

Assert::same(
	'<iframe srcdoc="&lt;b title=&quot;a&amp;amp;b&quot;&gt;bold&lt;/b&gt;"></iframe>',
	$latte->renderToString('<iframe srcdoc={$html}></iframe>', $params),
);

Assert::same(
	'<iframe></iframe>',
	$latte->renderToString('<iframe srcdoc={$none}></iframe>', ['none' => null]),
);

// n:attr
Assert::same(
	'<iframe srcdoc="&amp;lt;script&amp;gt;alert(&quot;1&quot;)&amp;lt;/script&amp;gt; &amp;amp; &#123;&lt;!-- --&gt;"></iframe>',
	$latte->renderToString('<iframe n:attr="srcdoc => $x"></iframe>', $params),
);

Assert::same(
	'<iframe srcdoc="&lt;b title=&quot;a&amp;amp;b&quot;&gt;bold&lt;/b&gt;"></iframe>',
	$latte->renderToString('<iframe n:attr="srcdoc => $html"></iframe>', $params),
);

// blocks
Assert::same(
	'<iframe srcdoc="&lt;i&gt;&amp;lt;script&amp;gt;alert(&quot;1&quot;)&amp;lt;/script&amp;gt; &amp;amp; &#123;&lt;!-- --&gt;&lt;/i&gt;"></iframe>',
	$latte->renderToString('{define doc}<i>{$x}</i>{/define}<iframe srcdoc="{include doc}"></iframe>', $params),
);

$latte->setLoader(new Latte\Loaders\StringLoader([
	'main' => '<iframe srcdoc="{include html.latte}"></iframe> <iframe srcdoc="{include text.latte}"></iframe> <iframe srcdoc="{include js.latte}"></iframe>',
	'html.latte' => '<i> &amp; </i>',
	'text.latte' => '{contentType text}<i> & "',
	'js.latte' => '{contentType javascript}a < b',
]));

Assert::same(
	'<iframe srcdoc="&lt;i&gt; &amp;amp; &lt;/i&gt;"></iframe> <iframe srcdoc="&amp;lt;i&amp;gt; &amp;amp; &quot;"></iframe> <iframe srcdoc="a &amp;lt; b"></iframe>',
	$latte->renderToString('main'),
);

// XML has no srcdoc context
$latte->setLoader(new Latte\Loaders\StringLoader);
Assert::same(
	'<iframe srcdoc="&lt;p&gt;&lt;script&gt;alert(&quot;1&quot;)&lt;/script&gt; &amp; {&lt;/p&gt;"></iframe>',
	$latte->renderToString('{contentType xml}<iframe srcdoc="{=\'<p>\'}{$x}{=\'</p>\'}"></iframe>', $params),
);
