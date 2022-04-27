<?php

declare(strict_types=1);

use Latte\Context;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);


$template = $latte->createTemplate('');
Assert::same(Context::Html, $template::ContentType);

$template = $latte->createTemplate('{contentType xml}');
Assert::same(Context::Xml, $template::ContentType);

Assert::exception(
	fn() => $latte->createTemplate('{block}{contentType xml}{/block}'),
	Latte\CompileException::class,
	'{contentType} is allowed only in template header (at column 8)',
);

Assert::exception(
	fn() => $latte->createTemplate('<div>{contentType xml}</div>'),
	Latte\CompileException::class,
	'{contentType} is allowed only in template header (at column 6)',
);

Assert::same(
	'<script> <p n:if=0 /> </script>',
	$latte->renderToString('{contentType html}<script> <p n:if=0 /> </script>'),
);

Assert::same(
	'<script>  </script>',
	$latte->renderToString('{contentType xml}<script> <p n:if=0 /> </script>'),
);

Assert::same(
	'<p n:if=0 />',
	$latte->renderToString('{contentType text}<p n:if=0 />'),
);

// defined on $latte
$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->setContentType(Context::Xml);

$template = $latte->createTemplate('--');
Assert::same(Context::Xml, $template::ContentType);
