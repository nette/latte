<?php declare(strict_types=1);

/**
 * Test: Latte\Essential\WhitespaceMinifier
 */

use Latte\ContentType;
use Latte\Essential\WhitespaceMinifier;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


// checks one-shot output and that any chunking produces byte-identical result
function checkMinify(array $samples, string $contentType = ContentType::Html): void
{
	foreach ($samples as [$input, $expected]) {
		Assert::same($expected, (new WhitespaceMinifier($contentType))->minify($input));

		foreach ([1, 2, 3, 5, 7, 64, 4096] as $size) {
			$minifier = new WhitespaceMinifier($contentType);
			$out = '';
			$chunks = str_split($input, $size) ?: [''];
			foreach ($chunks as $i => $chunk) {
				$out .= $minifier->handle($chunk, $i === array_key_last($chunks) ? PHP_OUTPUT_HANDLER_FINAL : 0);
			}
			Assert::same($expected, $out, "chunk size $size: " . substr($input, 0, 40));
		}
	}
}


test('golden outputs and chunk determinism in HTML mode', function () {
	checkMinify([
		['', ''],
		["\r\n \t", ''],
		["A\r\t\n  B", 'A B'],
		["<p> Hello </p>\r\n ", '<p>Hello</p>'],
		["<p> Hello <i> aa </i> </p>\r\n ", '<p>Hello <i>aa</i></p>'],
		['<i>aa</i> bb', '<i>aa</i> bb'],
		['x  <i> aa </i>  y', 'x <i>aa</i> y'],
		["a \n <div> \n b \n </div> \n c", 'a<div>b</div>c'],
		['a <br> b', 'a<br>b'],
		['a  <img src=x>  b', 'a <img src=x> b'],
		['a  <my-tag>b</my-tag>  c', 'a <my-tag>b</my-tag> c'],
		['a  <script>f()</script>  b', 'a <script>f()</script> b'],
		["<div  class=\"a   b\"\n id=x >x</div>", '<div class="a   b" id=x>x</div>'],
		["<a title=\"a > b\"  href='x  y' >t</a>", "<a title=\"a > b\" href='x  y'>t</a>"],
		['<a href=/foo >x</a>', '<a href=/foo>x</a>'],
		['Ks:  <select> <option> 1 </option> </select>  ks', 'Ks: <select><option>1</option></select> ks'],
		["<pre>  \r\n </pre>\r\n ", "<pre>  \r\n </pre>"],
		["<pre> if (a <\n b) </pre>", "<pre> if (a <\n b) </pre>"],
		["x  <style>\n a { }\n</style>  ", "x <style>\n a { }\n</style>"],
		["<script> if (a < b) { } </script>\n", '<script> if (a < b) { } </script>'],
		["<p> <textarea>  a\n b </textarea> </p>", "<p><textarea>  a\n b </textarea></p>"],
		['<PRE> x </PRE>', '<PRE> x </PRE>'],
		['a  <!-- c  d -->  b', 'a <!-- c  d --> b'],
		['a  <![CDATA[ x > y ]]>  b', 'a <![CDATA[ x > y ]]> b'],
		["<!doctype html>\n<html>", '<!doctype html><html>'],
		['a  <  b', 'a < b'],
		['a<div>b', 'a<div>b'],
		['<div a="x', '<div a="x'],
		['<!-- unterminated ', '<!-- unterminated'],
		['<' . str_repeat('a', 600000), '<' . str_repeat('a', 600000)], // must not hit the PCRE backtrack limit
		["<img alt=it's ok>   <div>   text", "<img alt=it's ok><div>text"],
		["<pre>\n" . str_repeat('x', 10000) . "\n</pre>", "<pre>\n" . str_repeat('x', 10000) . "\n</pre>"],
	]);
});


test('chunk determinism in XML mode', function () {
	checkMinify([
		[" <a>\n <b> x </b>\n</a> ", '<a><b>x</b></a>'],
		["<item  attr=\"a  b\" >\n <![CDATA[ x  y ]]>\n</item>", '<item attr="a  b"><![CDATA[ x  y ]]></item>'],
		["<?xml version=\"1.0\"?>\n<root/>", '<?xml version="1.0"?><root/>'],
	], ContentType::Xml);
});


test('chunk determinism in text mode', function () {
	checkMinify([
		['', ''],
		["\r\n ", ''],
		["Ahoj   světe  \n   \n  další", "Ahoj světe\n\ndalší"],
		["A\r\t\n  B", "A\nB"],
		["a  b\r\nc", "a b\nc"],
		['  x  ', 'x'],
		["<p> Hello </p>\r\n ", '<p> Hello </p>'],
		["<pre>  \r\n </pre>\r\n ", "<pre>\n</pre>"],
	], ContentType::Text);
});


test('attribute subcontexts collapse newlines to a single space', function () {
	checkMinify([["a \n\n b", 'a b']], 'html/attr');
	checkMinify([["a \n\n b", 'a b']], 'html/attr/js');
	checkMinify([["a \n\n b", 'a b']], 'html/attr/css');
	checkMinify([["a \n\n b", 'a b']], 'xml/attr');
});


test('nested start() creates a single buffer', function () {
	ob_start();
	$origLevel = ob_get_level();

	WhitespaceMinifier::start(ContentType::Html);
	$level = ob_get_level();
	Assert::same($origLevel + 1, $level);

	WhitespaceMinifier::start(ContentType::Html);
	Assert::same($level, ob_get_level());

	echo ' <div> x </div> ';
	WhitespaceMinifier::end();
	Assert::same($level, ob_get_level());

	WhitespaceMinifier::end();
	Assert::same($origLevel, ob_get_level());
	Assert::same('<div>x</div>', ob_get_clean());
});


test('end() closes buffers leaked by user code inside the region', function () {
	ob_start();
	$origLevel = ob_get_level();

	WhitespaceMinifier::start(ContentType::Html);
	echo ' <p> a </p> ';
	ob_start(); // user-leaked buffer inside the region
	echo ' <p> b </p> ';
	WhitespaceMinifier::end();

	Assert::same($origLevel, ob_get_level());
	Assert::same('<p>a</p><p>b</p>', ob_get_clean());
});


test('exception inside the region restores the buffer level', function () {
	ob_start();
	$origLevel = ob_get_level();

	try {
		WhitespaceMinifier::start(ContentType::Html);
		throw new Exception('inside');
	} catch (Throwable $e) {
		WhitespaceMinifier::end();
	}

	Assert::same($origLevel, ob_get_level());
	Assert::same('', ob_get_clean());
});
