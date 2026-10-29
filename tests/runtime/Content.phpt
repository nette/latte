<?php declare(strict_types=1);

/**
 * Test: Latte\Runtime\Content and Html
 */

use Latte\ContentType;
use Latte\Runtime\{Content, Helpers, Html, HtmlHelpers, HtmlStringable};
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$content = new Content('alert(1)', ContentType::JavaScript);
Assert::same('alert(1)', (string) $content);
Assert::same(ContentType::JavaScript, $content->contentType);
Assert::false($content instanceof HtmlStringable);
Assert::same('alert(1)&lt;', HtmlHelpers::escapeText(new Content('alert(1)<', ContentType::JavaScript)));
Assert::same('"a {}"', json_encode(new Content('a {}', ContentType::Css)));
Assert::same('"a {}"', Helpers::escapeJs(new Content('a {}', ContentType::Css)));


$html = new Html('<br>');
Assert::type(Content::class, $html);
Assert::type(HtmlStringable::class, $html);
Assert::same('<br>', (string) $html);
Assert::same(ContentType::Html, $html->contentType);
Assert::same('<br>', HtmlHelpers::escapeText($html));
Assert::same('"<br>"', json_encode($html));

Assert::same('', (string) new Html(null));
Assert::same('<br>', (string) new Html(new Html('<br>')));
