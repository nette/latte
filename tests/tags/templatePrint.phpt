<?php

/**
 * Test: {templatePrint}
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(
	<<<'XX'
		%A%
			public function prepare(): void
			{
				extract($this->params);
				(new Latte\Essential\Blueprint)->printClass($this, null);
				exit;
		%A%
		XX,
	$latte->compile('Foo {block}{/block} {templatePrint}'),
);


Assert::match(
	<<<'XX'
		%A%
			public function prepare(): void
			{
				extract($this->params);
				(new Latte\Essential\Blueprint)->printClass($this, 'Foo');
				exit;
		%A%
		XX,
	$latte->compile('{templatePrint Foo}'),
);
