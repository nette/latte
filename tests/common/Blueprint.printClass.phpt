<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();
$latte->addFunction('Abc', function (stdClass $a, $b = 132) {});


class ParentTemplate
{
	public $int;
}

$params = ['int' => 123, 'unknown' => null];

$blueprint = new Latte\Essential\Blueprint;
ob_start();
$blueprint->printClass($blueprint->generateTemplateClass($params));
$res = ob_get_clean();

Assert::match(
	<<<'XX'
		%A%class Template
		{
			public int $int;
			public mixed $unknown;
		}
		%A%
		XX,
	$res,
);


ob_start();
$blueprint->printClass($blueprint->generateTemplateClass($params, name: Foo\Template::class, extends: ParentTemplate::class));
$res = ob_get_clean();

Assert::match(
	<<<'XX'
		%A%namespace Foo;

		class Template extends \ParentTemplate
		{
			public mixed $unknown;
		}
		%A%
		XX,
	$res,
);
