<?php declare(strict_types=1);

// Filters

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	($a . $b ?|upper?|truncate),
	($a . $b ?|upper|truncate),
	($a . $b ?|upper|truncate?|trim),
	($a ?|truncate: 10, ($c?|round)|trim),
	($a ?|truncate: 10, (($c?|round)|trim)),
	XX;

$node = parseCode($test);
$code = printNode($node);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	$code,
);

__halt_compiler();
(($ʟ_tmp = (($ʟ_tmp = $a . $b) === null ? null : ($this->filters->upper)($ʟ_tmp))) === null ? null : ($this->filters->truncate)($ʟ_tmp)),
(($ʟ_tmp = $a . $b) === null ? null : ($this->filters->truncate)(($this->filters->upper)($ʟ_tmp))),
(($ʟ_tmp = (($ʟ_tmp = $a . $b) === null ? null : ($this->filters->truncate)(($this->filters->upper)($ʟ_tmp)))) === null ? null : ($this->filters->trim)($ʟ_tmp)),
(($ʟ_tmp = $a) === null ? null : ($this->filters->trim)(($this->filters->truncate)($ʟ_tmp, 10, (($ʟ_tmp = $c) === null ? null : ($this->filters->round)($ʟ_tmp))))),
(($ʟ_tmp = $a) === null ? null : ($this->filters->truncate)($ʟ_tmp, 10, (($ʟ_tmp = $c) === null ? null : ($this->filters->trim)(($this->filters->round)($ʟ_tmp)))))
