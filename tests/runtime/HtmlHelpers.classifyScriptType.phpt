<?php declare(strict_types=1);

use Latte\ContentType;
use Latte\Runtime\HtmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('JavaScript MIME types', function () {
	Assert::same(ContentType::JavaScript, HtmlHelpers::classifyScriptType('')); // JS is default
	Assert::same(ContentType::JavaScript, HtmlHelpers::classifyScriptType('text/javascript'));
	Assert::same(ContentType::JavaScript, HtmlHelpers::classifyScriptType('TEXT/JAVASCRIPT'));
	Assert::same(ContentType::JavaScript, HtmlHelpers::classifyScriptType('application/javascript'));
	Assert::same(ContentType::JavaScript, HtmlHelpers::classifyScriptType('application/x-javascript'));
	Assert::same(ContentType::JavaScript, HtmlHelpers::classifyScriptType('text/ecmascript'));
	Assert::same(ContentType::JavaScript, HtmlHelpers::classifyScriptType('application/ecmascript'));
	Assert::same(ContentType::JavaScript, HtmlHelpers::classifyScriptType('text/jscript'));
	Assert::same(ContentType::JavaScript, HtmlHelpers::classifyScriptType('text/livescript'));
	Assert::same(ContentType::JavaScript, HtmlHelpers::classifyScriptType('application/json'));
	Assert::same(ContentType::JavaScript, HtmlHelpers::classifyScriptType('application/ld+json'));
	Assert::same(ContentType::JavaScript, HtmlHelpers::classifyScriptType('text/plain'));
	Assert::same(ContentType::JavaScript, HtmlHelpers::classifyScriptType('module'));
	Assert::same(ContentType::JavaScript, HtmlHelpers::classifyScriptType('importmap'));
	Assert::same(ContentType::JavaScript, HtmlHelpers::classifyScriptType('speculationrules'));
	Assert::same(ContentType::JavaScript, HtmlHelpers::classifyScriptType('text/javascript1.5'));
});


test('whatever is not plainly a data block is JavaScript', function () {
	Assert::same(ContentType::JavaScript, HtmlHelpers::classifyScriptType('text/java&#115;cript'));
	Assert::same(ContentType::JavaScript, HtmlHelpers::classifyScriptType('text/java&#115cript'));
	Assert::same(ContentType::JavaScript, HtmlHelpers::classifyScriptType('text/javascript; charset=utf-8'));
	Assert::same(ContentType::JavaScript, HtmlHelpers::classifyScriptType('text/x-mustache; foo'));
	Assert::same(ContentType::JavaScript, HtmlHelpers::classifyScriptType('foo'));
});


test('HTML template MIME types', function () {
	Assert::same(ContentType::Html, HtmlHelpers::classifyScriptType('text/template'));
	Assert::same(ContentType::Html, HtmlHelpers::classifyScriptType('text/x-template'));
	Assert::same(ContentType::Html, HtmlHelpers::classifyScriptType('text/html'));
	Assert::same(ContentType::Html, HtmlHelpers::classifyScriptType('TEXT/TEMPLATE'));
});


test('other MIME types default to Text', function () {
	Assert::same(ContentType::Text, HtmlHelpers::classifyScriptType('text/css'));
	Assert::same(ContentType::Text, HtmlHelpers::classifyScriptType('application/xml'));
	Assert::same(ContentType::Text, HtmlHelpers::classifyScriptType('image/png'));
	Assert::same(ContentType::Text, HtmlHelpers::classifyScriptType('unknown/type'));
	Assert::same(ContentType::Text, HtmlHelpers::classifyScriptType('text/x-handlebars-template'));
});
