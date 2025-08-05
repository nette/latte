<?php

// Magic constants

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	__FILE__,
	__DIR__,
	__LINE__,
	XX;

$node = parseCode($test);
$code = printNode($node);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	$code,
);

__halt_compiler();
(is_file($this->getName()) ? $this->getName() : null),
(is_file($this->getName()) ? dirname($this->getName()) : null),
3
