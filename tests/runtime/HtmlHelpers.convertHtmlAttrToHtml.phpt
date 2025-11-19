<?php

/**
 * Test: Latte\Runtime\HtmlHelpers::convertAttrToHtml
 */

declare(strict_types=1);

use Latte\Runtime\HtmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('', HtmlHelpers::convertAttrToHtml(''));
Assert::same('string', HtmlHelpers::convertAttrToHtml('string'));
Assert::same('&lt; &amp; &apos; &quot; &gt;', HtmlHelpers::convertAttrToHtml('< & \' " >'));
Assert::same('&lt;span&gt;hello&lt;/span&gt;', HtmlHelpers::convertAttrToHtml('<span>hello</span>'));
Assert::same('&quot;', HtmlHelpers::convertAttrToHtml('&quot;'));

// invalid UTF-8
Assert::same("foo \u{FFFD} bar", HtmlHelpers::convertAttrToHtml("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("foo \u{FFFD}&quot; bar", HtmlHelpers::convertAttrToHtml("foo \xE3\x80\x22 bar")); // stripped UTF

// JS
Assert::same('hello &#123; worlds }', HtmlHelpers::convertAttrToHtml('hello { worlds }'));
