<?php

/**
 * Test: whitespace test I.
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(<<<'EOD'
	qwerty

	EOD

, $latte->renderToString(
	<<<'EOD'
		{contentType text}
		qwerty

		EOD,
));


Assert::match(<<<'EOD'

	asdfgh
	EOD

, $latte->renderToString(
	<<<'EOD'

		{contentType text}
		asdfgh
		EOD,
));


Assert::match(<<<'EOD'
	qwerty

	EOD

, $latte->renderToString(
	<<<'EOD'
		{* comment
		*}
		qwerty

		EOD,
));


Assert::match(<<<'EOD'
	qwerty

	EOD

, $latte->renderToString(
	<<<'EOD'
		{* comment
		*}

		qwerty

		EOD,
));


Assert::match(<<<'EOD'

	qwerty

	EOD

, $latte->renderToString(
	<<<'EOD'
		{* comment
		*}


		qwerty

		EOD,
));


Assert::match(<<<'EOD'
	qwerty

	EOD

, $latte->renderToString(
	<<<'EOD'
		{* comment
		*}

		{contentType text}
		qwerty

		EOD,
));


Assert::match(<<<'EOD'
	qwerty

	EOD

, $latte->renderToString(
	<<<'EOD'
		{* comment
		*}
		{contentType text}
		qwerty

		EOD,
));


Assert::match(<<<'EOD'
	line 1
	line 2
	EOD

, $latte->renderToString(
	<<<'EOD'
		line 1 {* comment *}
		line 2
		EOD,
));


Assert::match(<<<'EOD'
	word 1  word 2
	EOD

, $latte->renderToString(
	<<<'EOD'
		word 1 {* comment *} word 2
		EOD,
));


Assert::match(<<<'EOD'
		<link>
		<link>
	EOD

, $latte->renderToString(
	<<<'EOD'
			<link>
			{* comment *}
			<link>
		EOD,
));
