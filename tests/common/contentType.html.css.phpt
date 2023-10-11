<?php

/**
 * Test: CSS in HTML
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(
	'<style>\<\></style>',
	$latte->renderToString('<style>{="<>"}</style>'),
);

Assert::match(
	'<style>\<\/ \]\]\> \<\! </style>',
	$latte->renderToString('<style>{="</"} {="]]>"} {="<!"} </style>'),
);

Assert::match(
	'<style></style>&lt;&gt;',
	$latte->renderToString('<style></style>{="<>"}'),
);

Assert::match(
	'<style>123</style>',
	$latte->renderToString('<style>{=123}</style>'),
);

Assert::match(
	'<style id="&lt;&gt;"></style>',
	$latte->renderToString('<style id="{="<>"}"></style>'),
);

Assert::match(
	'<style type="TEXT/CSS">\<\></style>',
	$latte->renderToString('<style type="TEXT/CSS">{="<>"}</style>'),
);

Assert::match(
	'<style type="text/html">&lt;&gt;</style>',
	$latte->renderToString('<style type="text/html">{="<>"}</style>'),
);

Assert::match(
	'<style> a { background: url("\"") } </style>',
	$latte->renderToString('<style> a { background: url("{=\'"\'}") } </style>'),
);

// no escape
Assert::match(
	'<style></style></style>',
	$latte->renderToString('<style>{="</style>"|noescape}</style>'),
);
