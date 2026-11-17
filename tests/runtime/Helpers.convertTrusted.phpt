<?php declare(strict_types=1);

/**
 * Test: Latte\Runtime\Helpers::convertTrusted()
 */

use Latte\Runtime\{Helpers, Html, TrustedCss, TrustedHtml, TrustedJavaScript, TrustedXml};
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$js = new TrustedJavaScript('if (a < b) alert("</script>")');
$css = new TrustedCss('a { content: "</style>" }');
$xml = new TrustedXml('<a href="x">--></a>');

// own context, unchanged
Assert::same('if (a < b) alert("</script>")', Helpers::convertTrusted($js, 'js'));
Assert::same('a { content: "</style>" }', Helpers::convertTrusted($css, 'css'));
Assert::same('<a href="x">--></a>', Helpers::convertTrusted($xml, 'xml'));

// raw text of <script> and <style>
Assert::same('if (a < b) alert("<\/script>")', Helpers::convertTrusted($js, 'html/raw/js'));
Assert::same('a { content: "<\/style>" }', Helpers::convertTrusted($css, 'html/raw/css'));

// code attributes
Assert::same('if (a &lt; b) alert(&quot;&lt;/script&gt;&quot;)', Helpers::convertTrusted($js, 'html/attr/js'));
Assert::same('a &#123; content: &quot;&lt;/style&gt;&quot; }', Helpers::convertTrusted($css, 'html/attr/css'));

// foreign contexts with a convertor show the code as text
Assert::same('if (a &lt; b) alert("&lt;/script&gt;")', Helpers::convertTrusted($js, 'html'));
Assert::same('if (a &lt; b) alert(&quot;&lt;/script&gt;&quot;)', Helpers::convertTrusted($js, 'html/attr'));
Assert::same('if (a &lt; b) alert("&lt;/script&gt;")', Helpers::convertTrusted($js, 'html/rcdata'));
Assert::same('a &#123; content: &quot;&lt;/style&gt;&quot; }', Helpers::convertTrusted($css, 'html/attr'));
Assert::same('if (a &lt; b) alert(&quot;&lt;/script&gt;&quot;)', Helpers::convertTrusted($js, 'xml'));
Assert::same('&lt;a href=&quot;x&quot;&gt;--&gt;&lt;/a&gt;', Helpers::convertTrusted(new TrustedCss('<a href="x">--></a>'), 'xml/attr'));
Assert::same(' - - >', Helpers::convertTrusted(new TrustedJavaScript('-->'), 'html/comment'));
Assert::same('&amp;lt;b&amp;gt;', Helpers::convertTrusted(new TrustedJavaScript('<b>'), 'html/attr/html'));
Assert::same('--&gt;', Helpers::convertTrusted($xml, 'xml/attr'));
Assert::same('-->', Helpers::convertTrusted($xml, 'text'));

// no convertor
Assert::exception(
	fn() => Helpers::convertTrusted($css, 'html/attr/js'),
	Latte\RuntimeException::class,
	'Cannot print Latte\Runtime\TrustedCss in context HTML/ATTR/JS, content type CSS cannot be converted to it.',
);
Assert::exception(
	fn() => Helpers::convertTrusted($js, 'html/raw/css'),
	Latte\RuntimeException::class,
	'Cannot print Latte\Runtime\TrustedJavaScript in context HTML/RAW/CSS, content type JS cannot be converted to it.',
);
Assert::exception(
	fn() => Helpers::convertTrusted($js, 'html/tag'),
	Latte\RuntimeException::class,
	'Cannot print Latte\Runtime\TrustedJavaScript in context HTML/TAG, %a%',
);
Assert::exception(
	fn() => Helpers::convertTrusted($xml, 'css'),
	Latte\RuntimeException::class,
	'Cannot print Latte\Runtime\TrustedXml in context CSS, content type XML cannot be converted to it.',
);

// XML into HTML goes through the deprecated bridge
Assert::error(
	fn() => Assert::same('<a href="x">--></a>', Helpers::convertTrusted($xml, 'html')),
	E_USER_DEPRECATED,
	'Converting content type XML into HTML is deprecated, XML and HTML will be incompatible.',
);

// other objects are left to the escaper
Assert::null(Helpers::convertTrusted(new TrustedHtml('<b>'), 'html/attr/css'));
Assert::null(Helpers::convertTrusted(new Html('<b>'), 'js'));
Assert::null(Helpers::convertTrusted(new Nette\Utils\Html, 'html'));
Assert::null(Helpers::convertTrusted(new ArrayObject, 'html'));
