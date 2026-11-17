<?php declare(strict_types=1);

/**
 * Test: whitespace test I.
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();

Assert::match(<<<'EOD'
	qwerty

	EOD, $latte->renderToString(
	<<<'EOD'
		{contentType text}
		qwerty

		EOD,
));


Assert::match(<<<'EOD'
	asdfgh
	EOD, $latte->renderToString(
	<<<'EOD'

		{contentType text}
		asdfgh
		EOD,
));


Assert::match(<<<'EOD'
	qwerty

	EOD, $latte->renderToString(
	<<<'EOD'
		{* comment
		*}
		qwerty

		EOD,
));


Assert::match(<<<'EOD'
	qwerty

	EOD, $latte->renderToString(
	<<<'EOD'
		{* comment
		*}

		qwerty

		EOD,
));


Assert::match(<<<'EOD'

	qwerty

	EOD, $latte->renderToString(
	<<<'EOD'
		{* comment
		*}


		qwerty

		EOD,
));


Assert::match(<<<'EOD'
	qwerty

	EOD, $latte->renderToString(
	<<<'EOD'
		{* comment
		*}

		{contentType text}
		qwerty

		EOD,
));


Assert::match(<<<'EOD'
	qwerty

	EOD, $latte->renderToString(
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
	EOD, $latte->renderToString(
	<<<'EOD'
		line 1 {* comment *}
		line 2
		EOD,
));


Assert::match(<<<'EOD'
	word 1  word 2
	EOD, $latte->renderToString(
	<<<'EOD'
		word 1 {* comment *} word 2
		EOD,
));


Assert::match(<<<'EOD'
		<link>
		<link>
	EOD, $latte->renderToString(
	<<<'EOD'
			<link>
			{* comment *}
			<link>
		EOD,
));


// indented end tag followed by a Latte tag keeps the newline
Assert::match(<<<'EOD'
	<ul>
	  </ul>
	World
	EOD, $latte->renderToString(
	<<<'EOD'
		{if true}
		<ul>
		  </ul>{/if}
		World
		EOD,
));


// indentation of a closing Latte tag followed by an HTML tag stays in the content
Assert::same('<p>hello</p>', $latte->renderToString("<p>{block|strip}\n\t\thello\n\t{/block}</p>"));
Assert::same('<p></p>', $latte->renderToString("<p>{if false}\n\t\thello\n\t{/if}</p>"));
Assert::same("<b>[\t\thello\n\t]</b>", $latte->renderToString("{capture \$c}\n\t\thello\n\t{/capture}<b>[{\$c}]</b>"));


// empty comment
Assert::match('ab', $latte->renderToString('a{**}b'));
Assert::match('ab', $latte->renderToString('a{* *}b'));
Assert::match('ab', $latte->renderToString('a{***}b'));
Assert::match('<div  class="x">ok</div>', $latte->renderToString('<div {**} class="x">ok</div>'));
