<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;

$latte->addProvider('provider_1', 'value_1');
$latte->addProvider('provider_2', 'value_2');
$latte->addProvider('provider_3', 'value_3');

Assert::same([
	'provider_1' => 'value_1',
	'provider_2' => 'value_2',
	'provider_3' => 'value_3',
], $latte->getProviders());
