<?php

/**
 * Test: general snippets test.
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'
		<div class="test" n:snippet="outer">
		<p>Outer</p>
		</div>

		<div class="test" n:inner-snippet="inner">
		<p>Inner</p>
		</div>

		<div n:snippet="gallery" class="{=class}"></div>

	EOD;

Assert::matchFile(
	__DIR__ . '/expected/snippet.n.phtml',
	@$latte->compile($template), // deprecated n:inner-snippet
);
