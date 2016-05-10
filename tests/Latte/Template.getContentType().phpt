<?php

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);


$template = $latte->createTemplate('');
Assert::same($latte::CONTENT_HTML, $template->getContentType());

$template = $latte->createTemplate('{contentType xml}');
Assert::same($latte::CONTENT_XML, $template->getContentType());

// ignored
$template = $latte->createTemplate('{block}{contentType xml}{/block}');
Assert::same($latte::CONTENT_XML, $template->getContentType());

// ignored
$template = $latte->createTemplate('<div>{contentType xml}</div>');
Assert::same($latte::CONTENT_XML, $template->getContentType());

// defined on $latte
$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->setContentType($latte::CONTENT_XML);

$template = $latte->createTemplate('--');
Assert::same($latte::CONTENT_XML, $template->getContentType());
