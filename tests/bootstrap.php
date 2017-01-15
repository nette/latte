<?php

declare(strict_types=1);

// The Nette Tester command-line runner can be
// invoked through the command: ../vendor/bin/tester .

if (@!include __DIR__ . '/../vendor/autoload.php') {
	echo 'Install Nette Tester using `composer install`';
	exit(1);
}


// configure environment
Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');


// create temporary directory
define('TEMP_DIR', __DIR__ . '/tmp/' . lcg_value());
@mkdir(dirname(TEMP_DIR));
@mkdir(TEMP_DIR);


// output buffer level check
register_shutdown_function(function ($level) {
	Tester\Assert::same($level, ob_get_level());
}, ob_get_level());


function test(\Closure $function)
{
	$function();
}
