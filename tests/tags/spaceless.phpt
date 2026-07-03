<?php declare(strict_types=1);

/**
 * Test: {spaceless}
 */

use Latte\ContentType;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('{spaceless} block', function () {
	$latte = createLatte();
	Assert::same(
		"\t<hr>\n<div id=\"main   space\" class=1><p>Text</p>block</div><!-- /main -->\t<hr>",
		$latte->renderToString(
			<<<'EOD'
					<hr>
					{spaceless}
					<div id="main   space"
					class = 1>
						<p>
							Text
						</p>
						{block sidebar}block{/block}
					</div> <!-- /main -->
					{/spaceless}
					<hr>
				EOD,
		),
	);
});


test('n:spaceless', function () {
	$latte = createLatte();
	Assert::same(
		"\t<hr>\n<div class=a><p>Text</p></div>\t<hr>",
		$latte->renderToString(
			<<<'EOD'
					<hr>
					<div n:spaceless   class =  a>
						<p>
							Text
						</p>
					</div>
					<hr>
				EOD,
		),
	);
});


test('raw-text content survives output buffer chunking', function () {
	$latte = createLatte();
	Assert::same(
		'<p></p><pre>' . "\n\n\n"
		. str_repeat('x', 10000)
		. "\n\n\n" . '</pre><p></p>',
		$latte->renderToString(
			"{spaceless}<p>\n\n\n</p>"
			. "<pre>\n\n\n"
			. str_repeat('x', 5000)
			. '{if true}{/if}'
			. str_repeat('x', 5000)
			. "\n\n\n</pre> <p>\n\n\n</p>{/spaceless}",
		),
	);
});


test('nested {spaceless} is ignored', function () {
	$latte = createLatte();
	Assert::same(
		'a<div>x</div>b',
		$latte->renderToString('{spaceless} a {spaceless} <div> x </div> {/spaceless} b {/spaceless}'),
	);
});


test('quoted attribute value crossing the buffer boundary stays verbatim', function () {
	$latte = createLatte();
	Assert::same(
		'<div title="' . str_repeat('x  y ', 1000) . '">a</div>',
		$latte->renderToString('{spaceless}<div title="{(\'x  y \'|repeat: 1000)}">a</div>{/spaceless}'),
	);
});


test('word separation survives the buffer boundary', function () {
	$latte = createLatte();
	Assert::same(
		'<i>aa</i> bb',
		$latte->renderToString('{spaceless}<i>aa</i>{if true}{/if} bb{/spaceless}'),
	);
});


test('{spaceless} inside {capture}', function () {
	$latte = createLatte();
	Assert::same(
		'<div>x</div>',
		$latte->renderToString('{capture $foo}{spaceless} <div> x </div> {/spaceless}{/capture}{$foo|noescape}'),
	);
});


test('n:spaceless on a raw-text element keeps its content verbatim', function () {
	$latte = createLatte();
	Assert::same(
		'<script> let a = 1;  b = 2; </script>',
		$latte->renderToString('<script n:spaceless> let a = 1;  b = 2; </script>'),
	);
});


test('{spaceless} is forbidden inside raw-text elements and attributes', function () {
	$latte = createLatte();
	Assert::exception(
		fn() => $latte->compile('<script>{spaceless} let a = 1; {/spaceless}</script>'),
		Latte\CompileException::class,
		'{spaceless} cannot be used in this context%A?%',
	);
	Assert::exception(
		fn() => $latte->compile('<div title="{spaceless} a  b {/spaceless}">'),
		Latte\CompileException::class,
		'{spaceless} cannot be used in this context%A?%',
	);
});


test('{spaceless} in text content type keeps newlines', function () {
	$latte = createLatte();
	$latte->setContentType(ContentType::Text);
	Assert::same(
		"A B\n\nC",
		$latte->renderToString("{spaceless}A   B \n   \n  C{/spaceless}"),
	);
});


test('{spaceless} in XML treats all tags as whitespace-insensitive', function () {
	$latte = createLatte();
	$latte->setContentType(ContentType::Xml);
	Assert::same(
		'<a><b>x</b></a>',
		$latte->renderToString("{spaceless} <a>\n <b> x </b>\n</a> {/spaceless}"),
	);
});


test('{spaceless} after inline {contentType xml}', function () {
	$latte = createLatte();
	Assert::same(
		'<a><b>x</b></a>',
		$latte->renderToString("{contentType xml}{spaceless} <a>\n <b> x </b>\n</a> {/spaceless}"),
	);
});
