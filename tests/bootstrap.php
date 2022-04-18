<?php

declare(strict_types=1);

// The Nette Tester command-line runner can be
// invoked through the command: ../vendor/bin/tester .

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/helpers.php';


// configure environment
Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');

// output buffer level check
register_shutdown_function(function ($level): void {
	Tester\Assert::same($level, ob_get_level());
}, ob_get_level());
