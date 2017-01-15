<?php

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);


$template = $latte->createTemplate('');
Assert::same($latte::CONTENT_HTML, $template->getContentType());

$template = $latte->createTemplate('{contentType xml}');
Assert::same($latte::CONTENT_XML, $template->getContentType());

Assert::exception(function () use ($latte) {
	$latte->createTemplate('{block}{contentType xml}{/block}');
}, Latte\CompileException::class, '{contentType} is allowed only in template header.');

Assert::exception(function () use ($latte) {
	$latte->createTemplate('<div>{contentType xml}</div>');
}, Latte\CompileException::class, '{contentType} is allowed only in template header.');

// defined on $latte
$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->setContentType($latte::CONTENT_XML);

$template = $latte->createTemplate('--');
Assert::same($latte::CONTENT_XML, $template->getContentType());
