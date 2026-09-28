<?php declare(strict_types=1);

use Latte\Runtime\Html;
use Latte\Runtime\HtmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('', HtmlHelpers::escapeHtmlAttr(null));
Assert::same('', HtmlHelpers::escapeHtmlAttr(''));
Assert::same('1', HtmlHelpers::escapeHtmlAttr(1));
Assert::same('&amp;lt;b&amp;gt; &amp;amp; &quot; &apos;', HtmlHelpers::escapeHtmlAttr('<b> & " \''));
Assert::same('&amp;#123; &#123;&lt;!-- --&gt;&#123;',HtmlHelpers::escapeHtmlAttr('{ {{'));
Assert::same('&amp;amp;amp;', HtmlHelpers::escapeHtmlAttr('&amp;'));
Assert::same('&lt;b title=&quot;x&quot;&gt;a &amp;amp; b&lt;/b&gt;', HtmlHelpers::escapeHtmlAttr(new Html('<b title="x">a &amp; b</b>')));
Assert::same('&#123;', HtmlHelpers::escapeHtmlAttr(new Html('{')));
