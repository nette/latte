<?php

/**
 * Test: {embed block}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function testTemplate(string $title, array $templates, string $res = '')
{
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader($templates));
	Assert::match(trim($res), trim($latte->renderToString('main')));
}


testTemplate(
	'one overwritten block',
	[
		'main' => <<<'XX'

					outer
					{embed embed}
						{block a}main-A {include parent}{/block}
					{/embed}
					outer

					{define embed}
					embed start
						{block a}embed A{/block}
					embed end
					{/define}

			XX,
	],
	<<<'XX'

				outer
				embed start
					main-A embed A
				embed end
				outer

		XX,
);


testTemplate(
	'outer variables',
	[
		'main' => <<<'XX'

					outer
					{var $var1 = "OUT1"}
					{var $var2 = "OUT2"}
					{embed embed}
						{block a}{$var1} {$var2} {$var3} {block b}{$var1} {$var2} {$var3}{/block} {/block}
					{/embed}
					outer

					{define embed}
					embed start
						{var $var2 = "IN2"}
						{var $var3 = "IN3"}
						{block a}embed A{/block}
						{block b}embed B{/block}
						{block c}embed C {$var1 ?? unset} {$var2 ?? unset} {$var3 ?? unset}{/block}
					embed end
					{/define}

			XX,
	],
	<<<'XX'

		outer
				embed start
					OUT1 IN2 IN3 OUT1 IN2 IN3
					OUT1 IN2 IN3
					embed C unset IN2 IN3
				embed end
				outer

		XX,
);


testTemplate(
	'overwritten block + variables',
	[
		'main' => <<<'XX'

					{var $a = "M"}
					outer
					{embed embed}
						{block a}main-A {$a}{/block}
					{/embed}
					outer

					{define embed}
					{default $a = "W"}
					embed start {$a}
						{block a}embed A{/block}
					embed end
					{/define}

			XX,
	],
	<<<'XX'

				outer
				embed start W
					main-A W
				embed end
				outer

		XX,
);


testTemplate(
	'overwritten block + passed variables',
	[
		'main' => <<<'XX'

					{var $a = "M"}
					outer
					{embed embed, a => "P"}
						{block a}main-A {$a}{/block}
					{/embed}
					outer

					{define embed}
					{default $a = "W"}
					embed start {$a}
						{block a}embed A{/block}
					embed end
					{/define}

			XX,
	],
	<<<'XX'

				outer
				embed start P
					main-A P
				embed end
				outer

		XX,
);


testTemplate(
	'include instead of block',
	[
		'main' => <<<'XX'

					outer
					{embed embed}
						{block a}main-A{/block}
					{/embed}
					outer

					{define embed}
					embed start
						{include a}
					embed end
					{/define}

			XX,
	],
	<<<'XX'

				outer
				embed start
		main-A		embed end
				outer

		XX,
);


testTemplate(
	'import in embed',
	[
		'main' => <<<'XX'

					outer
					{embed embed}
						{import import.latte}
					{/embed}
					outer

					{define embed}
					embed start
						{include a}
					embed end
					{/define}

			XX,
		'import.latte' => '{block a}main-A{/block}',
	],
	<<<'XX'

				outer
				embed start
		main-A		embed end
				outer

		XX,
);


testTemplate(
	'local outer block include from main',
	[
		'main' => <<<'XX'

					outer
					{embed embed}
						{block a}*{include outer}*{/block}
					{/embed}

					{block local outer}outer-D{/block}

					{define embed}
					embed start
						{block a}embed A{/block}
					embed end
					{/define}

			XX,
	],
	<<<'XX'

				outer
				embed start
					*outer-D*
				embed end

				outer-D

		XX,
);


Assert::exception(function () {
	testTemplate('embed block included from main', [
		'main' => <<<'XX'

						outer
						{embed embed}
							{block a}{/block}
						{/embed}

						{include a}

						{define embed}{/define}

			XX,
	]);
}, Latte\RuntimeException::class, "Cannot include undefined block 'a'.");


testTemplate(
	'embeds block include',
	[
		'main' => <<<'XX'

					outer
					{embed embed}
						{block b}*{include a}*{/block}
					{/embed}
					outer

					{define embed}
					embed start
						{block a}embed A{/block}
						{block b}embed B{/block}
					embed end
					{/define}

			XX,
	],
	<<<'XX'

				outer
				embed start
					embed A
					*embed A*
				embed end
				outer

		XX,
);


testTemplate(
	'embed in series II.',
	[
		'main' => <<<'XX'

					{embed embed1}
						{block a}main-A{/block}
					{/embed}

					{define embed1}
					embed1-start
						{embed embed2}
							{block a}embed1-A{/block}
						{/embed}
					embed1-end
					{/define}

					{define embed2}
					embed2-start
						{block a}embed2-A{/block}
					embed2-end
					{/define}

			XX,
	],
	<<<'XX'


				embed1-start
				embed2-start
					embed1-A
				embed2-end
				embed1-end

		XX,
);


testTemplate(
	'nested embedding with different overwritten blocks',
	[
		'main' => <<<'XX'

					{var $counter = 1}
					{block local outer}
						{if $counter < 3}
							{embed embed}
								{import "import$counter.latte"}

								{block c}main C
									{include block outer, counter: $counter + 1}
								{/block}
							{/embed}
						{/if}
					{/block}

					{define embed}
						{block a}embed-A{/block}
						{block b}embed-B{/block}
						{block c}embed-C{/block}
					{/define}

			XX,
		'import1.latte' => <<<'XX'

					{block a}import1-A{/block}
					{block b}import1-B{/block}
					{block c}import1-C{/block}

			XX,
		'import2.latte' => '
		{block a}import2-A{/block}
	',
	],
	<<<'XX'

					import1-A
					import1-B
					main C
					import2-A
					import1-B
					main C

		XX,
);


testTemplate(
	'import on top layer',
	[
		'main' => <<<'XX'

					{import "import.latte"}

					{block a}outer-A{/block}

					{embed embed1}
						{block a}main-A{/block}
					{/embed}

					{include a}

			XX,
		'import.latte' => <<<'XX'

					{define embed1}
						{block a}import-A{/block}
						{block b}import-B{/block}
					{/define}

			XX,
	],
	<<<'XX'


				outer-A

					main-A
					import-B

		outer-A

		XX,
);
