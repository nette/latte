<?php

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setTempDirectory(TEMP_DIR);

$template = $latte->createTemplate(__DIR__ . '/templates/general.latte');
Assert::type('Latte\Template', $template);
