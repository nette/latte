<?php

/**
 * Test: FileLoader & contentType
 */

declare(strict_types=1);

use Latte\ContentType;
use Latte\Loaders\FileLoader;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$loader = new FileLoader;

// not latte
Assert::same([ContentType::Html, true], $loader->detectContentType('template.html'));
Assert::same([null, true], $loader->detectContentType('template'));
Assert::same([null, true], $loader->detectContentType('template.LATTE'));
Assert::same([null, true], $loader->detectContentType('template.latte.x'));

// latte without content-type
Assert::same([null, false], $loader->detectContentType('template.latte'));
Assert::same([null, false], $loader->detectContentType('template.foo.latte'));

// content type extensions
Assert::same([ContentType::Text, false], $loader->detectContentType('template.txt.latte'));
Assert::same([ContentType::Html, false], $loader->detectContentType('template.html.latte'));
Assert::same([ContentType::Xml, false], $loader->detectContentType('template.xml.latte'));
Assert::same([ContentType::JavaScript, false], $loader->detectContentType('template.js.latte'));
Assert::same([ContentType::Css, false], $loader->detectContentType('template.css.latte'));
Assert::same([ContentType::ICal, false], $loader->detectContentType('template.ical.latte'));
