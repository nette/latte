<?php declare(strict_types=1);

/**
 * Test: custom attribute contexts registered by Extension::getAttributeContexts()
 */

use Latte\ContentType;
use Latte\Runtime\Html;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class AttributeContextsExtension extends Latte\Extension
{
	public function getAttributeContexts(): array
	{
		return [
			'data-tippy-content' => ContentType::Html,
			'x-on:*' => ContentType::JavaScript,
			'x-style' => ContentType::Css,
		];
	}
}


$latte = createLatte();
$latte->addExtension(new AttributeContextsExtension);
$params = [
	'x' => '<b>"a" & {</b>',
	'html' => new Html('<b>x</b>'),
	'arr' => ['a' => 1],
];

// JavaScript, like onclick
Assert::same(
	'<a onclick="f(&quot;&lt;b&gt;\&quot;a\&quot; &amp; &#123;&lt;\/b&gt;&quot;)" x-on:click="f(&quot;&lt;b&gt;\&quot;a\&quot; &amp; &#123;&lt;\/b&gt;&quot;)" X-ON:Click="f(&quot;&lt;b&gt;\&quot;a\&quot; &amp; &#123;&lt;\/b&gt;&quot;)">',
	$latte->renderToString('<a onclick="f({$x})" x-on:click="f({$x})" X-ON:Click="f({$x})">', $params),
);

// prefix does not match the attribute without it
Assert::same(
	'<a x-on="f(&lt;b&gt;&quot;a&quot; &amp; &#123;&lt;/b&gt;)" data-foo="f(&lt;b&gt;&quot;a&quot; &amp; &#123;&lt;/b&gt;)">',
	$latte->renderToString('<a x-on="f({$x})" data-foo="f({$x})">', $params),
);

// CSS, like style
Assert::same(
	'<a style="a:\&lt;b\&gt;\&quot;a\&quot; \&amp; \&#123;\&lt;\/b\&gt;" x-style="a:\&lt;b\&gt;\&quot;a\&quot; \&amp; \&#123;\&lt;\/b\&gt;">',
	$latte->renderToString('<a style="a:{$x}" x-style="a:{$x}">', $params),
);

// HTML, like srcdoc
Assert::same(
	'<a srcdoc="<p>&amp;lt;b&amp;gt;&quot;a&quot; &amp;amp; &amp;#123;&amp;lt;/b&amp;gt;" data-tippy-content="<p>&amp;lt;b&amp;gt;&quot;a&quot; &amp;amp; &amp;#123;&amp;lt;/b&amp;gt;" data-tippy-content="<p>&lt;b&gt;x&lt;/b&gt;">',
	$latte->renderToString('<a srcdoc="<p>{$x}" data-tippy-content="<p>{$x}" data-tippy-content="<p>{$html}">', $params),
);

// dynamic attribute
Assert::same(
	'<a onclick="&lt;b&gt;&quot;a&quot; &amp; &#123;&lt;/b&gt;" x-on:click="&lt;b&gt;&quot;a&quot; &amp; &#123;&lt;/b&gt;" data-tippy-content="&amp;lt;b&amp;gt;&quot;a&quot; &amp;amp; &amp;#123;&amp;lt;/b&amp;gt;" data-tippy-content="&lt;b&gt;x&lt;/b&gt;" x-style="a: 1" data-foo=\'{"a":1}\'>',
	$latte->renderToString('<a onclick={$x} x-on:click={$x} data-tippy-content={$x} data-tippy-content={$html} x-style={$arr} data-foo={$arr}>', $params),
);

// n:attr
Assert::same(
	'<a x-on:click="&lt;b&gt;&quot;a&quot; &amp; &#123;&lt;/b&gt;" data-tippy-content="&amp;lt;b&amp;gt;&quot;a&quot; &amp;amp; &amp;#123;&amp;lt;/b&amp;gt;" x-style="a: 1" data-foo=\'{"a":1}\'>',
	$latte->renderToString('<a n:attr="\'x-on:click\' => $x, \'data-tippy-content\' => $x, \'x-style\' => $arr, \'data-foo\' => $arr">', $params),
);

Assert::contains(
	"NAttrNode::attrs(\$ʟ_tmp, false, contexts: ['data-tippy-content' => 'html', 'x-on:*' => 'js', 'x-style' => 'css'])",
	$latte->compile('<a n:attr="a => 1">'),
);

// blocks
Assert::same(
	'<a onclick="f(&lt;b&gt;&quot;a&quot; &amp; &#123;&lt;/b&gt;)" x-on:click="f(&lt;b&gt;&quot;a&quot; &amp; &#123;&lt;/b&gt;)" data-tippy-content="<p>&lt;i&gt;&amp;lt;b&amp;gt;&quot;a&quot; &amp;amp; &amp;#123;&amp;lt;/b&amp;gt;&lt;/i&gt;">',
	$latte->renderToString('{define b}<i>{$x}</i>{/define}<a onclick="f({include b})" x-on:click="f({include b})" data-tippy-content="<p>{include b}">', $params),
);

// XML has no custom contexts
Assert::same(
	'<a x-on:click="f(&lt;b&gt;&quot;a&quot; &amp; {&lt;/b&gt;)" data-tippy-content="&lt;p&gt;&lt;b&gt;&quot;a&quot; &amp; {&lt;/b&gt;" x-style="&lt;b&gt;&quot;a&quot; &amp; {&lt;/b&gt;">',
	$latte->renderToString('{contentType xml}<a x-on:click="f({$x})" data-tippy-content="{=\'<p>\'}{$x}" n:attr="\'x-style\' => $x">', $params),
);
