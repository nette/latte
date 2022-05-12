<?php

// Filters

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	($a|upper),
	($a . $b |upper|truncate),
	($a |truncate: 10, 20|trim),
	($a |truncate: 10, (20|round)|trim),
	($a |truncate: a: 10, b: true),
	($a |truncate( a: 10, b: true)),

	/* escape */
	($a |escape),
	XX;

$node = parseCode($test);
$code = printNode($node);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	$code,
);

__halt_compiler();
($this->filters->upper)($a),
($this->filters->truncate)(($this->filters->upper)($a . $b)),
($this->filters->trim)(($this->filters->truncate)($a, 10, 20)),
($this->filters->trim)(($this->filters->truncate)($a, 10, ($this->filters->round)(20))),
($this->filters->truncate)($a, a: 10, b: true),
($this->filters->truncate)($a, a: 10, b: true),
($this->filters->escape)($a)
