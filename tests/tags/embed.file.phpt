<?php

/**
 * Test: {embed file}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function testTemplate(string $title, array $templates, string $exp = '')
{
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader($templates));
	Assert::match($exp, $latte->renderToString('main'));
}


Assert::exception(function () {
	testTemplate('unexpected content', [
		'main' => '{embed "embed.latte"} {$a} {/embed}',
	]);
}, Latte\CompileException::class, 'Unexpected content inside {embed} tags (on line 1 at column 23)');


testTemplate('keyword file', [
	'main' => '{embed file embed}{/embed}',
	'embed' => 'embed',
], 'embed');


testTemplate('expression', [
	'main' => '{embed true ? embed : none}{/embed}',
	'embed' => 'embed',
], 'embed');


testTemplate(
	'no blocks',
	[
		'main' => <<<'XX'

					outer
					{embed "embed.latte"}{/embed}
					outer

			XX,
		'embed.latte' => <<<'XX'

					embed

			XX,
	],
	<<<'XX'

				outer

				embed
				outer

		XX,
);


testTemplate(
	'no blocks selfclosing',
	[
		'main' => <<<'XX'

					outer
					{embed "embed.latte"/}
					outer

			XX,
		'embed.latte' => <<<'XX'

					embed

			XX,
	],
	<<<'XX'

				outer

				embed
				outer

		XX,
);


testTemplate(
	'no overwritten blocks',
	[
		'main' => <<<'XX'

					outer
					{embed "embed.latte"}extra text{/embed}
					outer

			XX,
		'embed.latte' => <<<'XX'

					embed start
						{block a}embed A{/block}
					embed end

			XX,
	],
	<<<'XX'

				outer

				embed start
					embed A
				embed end

				outer

		XX,
);


testTemplate(
	'extra overwritten block',
	[
		'main' => <<<'XX'

					outer
					{embed "embed.latte"}
						{block a}main-A{/block}
					{/embed}
					outer

			XX,
		'embed.latte' => <<<'XX'

					embed

			XX,
	],
	<<<'XX'

				outer

				embed
				outer

		XX,
);


testTemplate(
	'one overwritten block',
	[
		'main' => <<<'XX'

					outer
					{embed "embed.latte"}
						{block a}main-A {include parent}{/block}
					{/embed}
					outer

			XX,
		'embed.latte' => <<<'XX'

					embed start
						{block a}embed A{/block}
					embed end

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
	'overwritten block + variables',
	[
		'main' => <<<'XX'

					{var $a = "M"}
					outer
					{embed "embed.latte"}
						{block a}main-A {$a}{/block}
					{/embed}
					outer

			XX,
		'embed.latte' => <<<'XX'

					{default $a = "W"}
					embed start {$a}
						{block a}embed A{/block}
					embed end

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
	'outer variables',
	[
		'main' => <<<'XX'

					outer
					{var $var1 = "OUT1"}
					{var $var2 = "OUT2"}
					{embed "embed.latte"}
						{block a}{$var1} {$var2} {$var3} {block b}{$var1} {$var2} {$var3}{/block} {/block}
					{/embed}
					outer

			XX,
		'embed.latte' => <<<'XX'

					embed start
						{var $var2 = "IN2"}
						{var $var3 = "IN3"}
						{block a}embed A{/block}
						{block b}embed B{/block}
						{block c}embed C {$var1 ?? unset} {$var2 ?? unset} {$var3 ?? unset}{/block}
					embed end

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
	'overwritten block + passed variables',
	[
		'main' => <<<'XX'

					{var $a = "M"}
					outer
					{embed "embed.latte", a => "P"}
						{block a}main-A {$a}{/block}
					{/embed}
					outer

			XX,
		'embed.latte' => <<<'XX'

					{default $a = "W"}
					embed start {$a}
						{block a}embed A{/block}
					embed end

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
	'only outer blocks',
	[
		'main' => <<<'XX'

					{block a}outer-A{/block}

					{embed "embed.latte"}extra text{/embed}

					{block d}outer-D{/block}

			XX,
		'embed.latte' => <<<'XX'

					embed start
						{block c}embed C{/block}

						{block a}embed A{/block}
					embed end

			XX,
	],
	<<<'XX'

				outer-A


				embed start
					embed C

					embed A
				embed end


				outer-D

		XX,
);


testTemplate(
	'overwritten & outer blocks',
	[
		'main' => <<<'XX'

					{block a}outer-A{/block}

					{embed "embed.latte"}
						{block a}main-A{/block}
						{block b}main-B{/block}
					{/embed}

					{block d}outer-D{/block}

			XX,
		'embed.latte' => <<<'XX'

					embed start
						{block c}embed C{/block}

						{block a}embed A{/block}
					embed end

			XX,
	],
	<<<'XX'

				outer-A


				embed start
					embed C

					main-A
				embed end

				outer-D

		XX,
);


testTemplate(
	'include instead of block',
	[
		'main' => <<<'XX'

					outer
					{embed "embed.latte"}
						{block a}main-A{/block}
					{/embed}
					outer

			XX,
		'embed.latte' => <<<'XX'

					embed start
						{include a}
					embed end

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
					{embed "embed.latte"}
						{import import.latte}
					{/embed}
					outer

			XX,
		'embed.latte' => <<<'XX'

					embed start
						{include a}
					embed end

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
					{embed "embed.latte"}
						{block a}*{include outer}*{/block}
					{/embed}

					{block local outer}outer-D{/block}

			XX,
		'embed.latte' => <<<'XX'

					embed start
						{block a}embed A{/block}
					embed end

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
	testTemplate('outer block include from main', [
		'main' => <<<'XX'

						outer
						{embed "embed.latte"}
							{block a}*{include outer}*{/block}
						{/embed}

						{block outer}outer-D{/block}

			XX,
		'embed.latte' => <<<'XX'

						embed start
							{block a}embed A{/block}
						embed end

			XX,
	]);
}, Latte\RuntimeException::class, "Cannot include undefined block 'outer'.");


Assert::exception(function () {
	testTemplate('outer block include from embed', [
		'main' => <<<'XX'

						{block a}outer-A{/block}

						{embed "embed.latte"}{/embed}

			XX,
		'embed.latte' => <<<'XX'

						embed start
							{include a}
						embed end

			XX,
	]);
}, Latte\RuntimeException::class, "Cannot include undefined block 'a'.");


testTemplate(
	'embeds block include',
	[
		'main' => <<<'XX'

					outer
					{embed "embed.latte"}
						{block b}*{include a}*{/block}
					{/embed}
					outer

			XX,
		'embed.latte' => <<<'XX'

					embed start
						{block a}embed A{/block}
						{block b}embed B{/block}
					embed end

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
	'embed in series I.',
	[
		'main' => <<<'XX'

					{embed "embed1.latte"}
						{block a}main-A{/block}
					{/embed}

			XX,
		'embed1.latte' => <<<'XX'

					embed1-start
						{embed "embed2.latte"}{/embed}
						{block a}embed1-A{/block}
					embed1-end

			XX,
		'embed2.latte' => <<<'XX'

					embed2-start
						{block a}embed2-A{/block}
					embed2-end

			XX,
	],
	<<<'XX'


				embed1-start

				embed2-start
					embed2-A
				embed2-end
					main-A
				embed1-end

		XX,
);


testTemplate(
	'embed in series II.',
	[
		'main' => <<<'XX'

					{embed "embed1.latte"}
						{block a}main-A{/block}
					{/embed}

			XX,
		'embed1.latte' => <<<'XX'

					embed1-start
						{embed "embed2.latte"}
							{block a}embed1-A{/block}
						{/embed}
					embed1-end

			XX,
		'embed2.latte' => <<<'XX'

					embed2-start
						{block a}embed2-A{/block}
					embed2-end

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
	'embed in series III.',
	[
		'main' => <<<'XX'

					{embed "embed1.latte"}
						{block a}main-A{/block}
					{/embed}

			XX,
		'embed1.latte' => <<<'XX'

					embed1-start
						{block a}
							embed1-A
							{embed "embed2.latte"}{/embed}
						{/block}
					embed1-end

			XX,
		'embed2.latte' => <<<'XX'

					embed2-start
						{block a}embed2-A{/block}
					embed2-end

			XX,
	],
	<<<'XX'


				embed1-start
		main-A		embed1-end

		XX,
);


testTemplate(
	'embed in series IV.',
	[
		'main' => <<<'XX'

					{embed "embed1.latte"}
						{block a}main-A{/block}
					{/embed}

			XX,
		'embed1.latte' => <<<'XX'

					embed1-start
						{block a}
							embed1-A
							{embed "embed2.latte"}
								{block a}embed nested A{/block}
							{/embed}
						{/block}
					embed1-end

			XX,
		'embed2.latte' => <<<'XX'

					embed2-start
						{block a}embed2-A{/block}
					embed2-end

			XX,
	],
	<<<'XX'


				embed1-start
		main-A		embed1-end

		XX,
);


testTemplate(
	'nested embeds',
	[
		'main' => <<<'XX'

					{embed "embed1.latte"}
						{block a}
							{embed "embed2.latte"}
								{block a}nested embeds A{/block}
							{/embed}
						{/block}
					{/embed}

			XX,
		'embed1.latte' => <<<'XX'

					embed1-start
						{block a}embed1-A{/block}
					embed1-end

			XX,
		'embed2.latte' => <<<'XX'

					embed2-start
						{block a}embed2-A{/block}
					embed2-end

			XX,
	],
	<<<'XX'


				embed1-start

				embed2-start
					nested embeds A
				embed2-end

				embed1-end

		XX,
);


testTemplate(
	'nested embeds',
	[
		'main' => <<<'XX'

					{define outer}outer block{/define}
					outer top:
					{ifset outer}{include outer}{/ifset}
					{ifset inembed1}{include inembed1}{/ifset}
					{ifset inembed2}{include inembed2}{/ifset}

					{embed "embed1.latte"}
						{block inembed1}embed1 block{/block}
						{block a}
							embed1 top:
							{ifset outer}{include outer}{/ifset}
							{ifset inembed1}{include inembed1}{/ifset}
							{ifset inembed2}{include inembed2}{/ifset}

							{embed "embed2.latte"}
								{block inembed2}embed2 block{/block}
								{block a}
									embed2:
									{ifset outer}{include outer}{/ifset}
									{ifset inembed1}{include inembed1}{/ifset}
									{ifset inembed2}{include inembed2}{/ifset}
								{/block}
							{/embed}

							embed1 bottom:
							{ifset outer}{include outer}{/ifset}
							{ifset inembed1}{include inembed1}{/ifset}
							{ifset inembed2}{include inembed2}{/ifset}
						{/block}
					{/embed}

					outer bottom:
					{ifset outer}{include outer}{/ifset}
					{ifset inembed1}{include inembed1}{/ifset}
					{ifset inembed2}{include inembed2}{/ifset}

			XX,
		'embed1.latte' => <<<'XX'

					{block a}embed1-A{/block}

			XX,
		'embed2.latte' => <<<'XX'

					{block a}embed2-A{/block}

			XX,
	],
	<<<'XX'


				outer top:
		outer block

								embed1 top:
		embed1 block

										embed2:
		embed2 block

						embed1 bottom:
		embed1 block

				outer bottom:
		outer block
		XX,
);


testTemplate(
	'extending embed',
	[
		'main' => <<<'XX'

					{embed "embed.latte"}
						{block a}main-A{/block}
					{/embed}

			XX,
		'embed.latte' => <<<'XX'

					{extends embed-ext.latte}
					embed-start
						{block a}embed-A{/block}
					embed-end

			XX,
		'embed-ext.latte' => <<<'XX'

					embed-ext-start
						{block a}embed-ext-A{/block}
					embed-ext-end

			XX,
	],
	<<<'XX'


				embed-ext-start
					main-A
				embed-ext-end

		XX,
);


testTemplate(
	'nested embedding with different overwritten blocks',
	[
		'main' => <<<'XX'

					{var $counter = 1}
					{block local outer}
						{if $counter < 3}
							{embed embed.latte}
								{import "import$counter.latte"}

								{block c}main C
									{include block outer, counter: $counter + 1}
								{/block}
							{/embed}
						{/if}
					{/block}

			XX,
		'embed.latte' => <<<'XX'

					{block a}embed-A{/block}
					{block b}embed-B{/block}
					{block c}embed-C{/block}

			XX,
		'import1.latte' => <<<'XX'

					{block a}import1-A{/block}
					{block b}import1-B{/block}
					{block c}import1-C{/block}

			XX,
		'import2.latte' => <<<'XX'

					{block a}import2-A{/block}

			XX,
	],
	<<<'XX'

				import1-A
				import1-B
				main C

				import2-A
				embed-B
				main C

		XX,
);
