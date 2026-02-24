<?php declare(strict_types=1);

/**
 * Test: Latte\Runtime\Filters::convertHtmlAttrToHtml
 */

use Latte\Runtime\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('', Filters::convertHtmlAttrToHtml(''));
Assert::same('string', Filters::convertHtmlAttrToHtml('string'));
Assert::same('&lt; &amp; &apos; &quot; &gt;', Filters::convertHtmlAttrToHtml('< & \' " >'));
Assert::same('&lt;span&gt;hello&lt;/span&gt;', Filters::convertHtmlAttrToHtml('<span>hello</span>'));
Assert::same('&quot;', Filters::convertHtmlAttrToHtml('&quot;'));

// invalid UTF-8
Assert::same("foo \u{FFFD} bar", Filters::convertHtmlAttrToHtml("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("foo \u{FFFD}&quot; bar", Filters::convertHtmlAttrToHtml("foo \xE3\x80\x22 bar")); // stripped UTF

// JS
Assert::same('hello &#123; worlds }', Filters::convertHtmlAttrToHtml('hello { worlds }'));
