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
			public function prepare(): array
			{
				extract($this->params);

				$ʟ_bp = new Latte\Essential\Blueprint;
				$ʟ_bp->printBegin();
				$ʟ_bp->printClass($ʟ_bp->generateTemplateClass($this->getParameters(), extends: null));
				$ʟ_bp->printEnd();
				exit;
		%A%
		XX,
	$latte->compile('Foo {block}{/block} {templatePrint}'),
);


Assert::match(
	<<<'XX'
		%A%
			public function prepare(): array
			{
				extract($this->params);

				$ʟ_bp = new Latte\Essential\Blueprint;
				$ʟ_bp->printBegin();
				$ʟ_bp->printClass($ʟ_bp->generateTemplateClass($this->getParameters(), extends: 'Foo'));
				$ʟ_bp->printEnd();
				exit;
		%A%
		XX,
	$latte->compile('{templatePrint Foo}'),
);
