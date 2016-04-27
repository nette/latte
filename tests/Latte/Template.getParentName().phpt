<?php

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);


$template = $latte->createTemplate('');
Assert::null($template->getParentName());

$template = $latte->createTemplate('{block}...{/block}');
Assert::null($template->getParentName());

$template = $latte->createTemplate('{block name}...{/block}');
Assert::null($template->getParentName());

$template = $latte->createTemplate('{extends "file.latte"} {block name}...{/block}');
Assert::same('file.latte', $template->getParentName());

$template = $latte->createTemplate('{extends "file.latte"}');
Assert::same('file.latte', $template->getParentName());

$template = $latte->createTemplate('{extends $file} {block name}...{/block}');
$template->params['file'] = 'file.latte';
Assert::same('file.latte', $template->getParentName());

$template = $latte->createTemplate('{extends none}');
Assert::null($template->getParentName());
