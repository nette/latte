<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);


$template = $latte->createTemplate('');
$template->prepare();
Assert::null($template->getParentName());
Assert::null($template->getParentName());

$template = $latte->createTemplate('{block}...{/block}');
$template->prepare();
Assert::null($template->getParentName());

$template = $latte->createTemplate('{block name}...{/block}');
$template->prepare();
Assert::null($template->getParentName());

$template = $latte->createTemplate('{extends "file.latte"} {block name}...{/block}');
$template->prepare();
Assert::same('file.latte', $template->getParentName());
Assert::same('file.latte', $template->getParentName());

$template = $latte->createTemplate('{extends "file.latte"}');
$template->prepare();
Assert::same('file.latte', $template->getParentName());

$template = $latte->createTemplate('{extends $file} {block name}...{/block}', ['file' => 'file.latte']);
$template->prepare();
Assert::same('file.latte', $template->getParentName());

$template = $latte->createTemplate('{extends none}');
$template->prepare();
Assert::null($template->getParentName());


$latte->setLoader(new Latte\Loaders\StringLoader([
	'empty' => '',
	'extends' => '{extends "parent"}',
	'extendsNone' => '{extends none}',
	'parent' => '',
]));
$latte->addProvider('coreParentFinder', function ($template) {
	if (!$template->getReferenceType()) {
		return 'parent';
	}
});

$template = $latte->createTemplate('empty');
$template->render();
Assert::same('parent', $template->getParentName());

$template = $latte->createTemplate('extends');
$template->render();
Assert::same('parent', $template->getParentName());

$template = $latte->createTemplate('extendsNone');
$template->render();
Assert::null($template->getParentName());
