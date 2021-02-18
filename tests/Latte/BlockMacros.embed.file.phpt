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


testTemplate('keyword file', [
	'main' => '{embed file embed}{/embed}',
	'embed' => 'embed',
], 'embed');


testTemplate('expression', [
	'main' => '{embed true ? embed : none}{/embed}',
	'embed' => 'embed',
], 'embed');


testTemplate('no blocks', [
	'main' => '
		outer
		{embed "embed.latte"}{/embed}
		outer
	',
	'embed.latte' => '
		embed
	',
], '
		outer

		embed

		outer
');


testTemplate('no overwritten blocks', [
	'main' => '
		outer
		{embed "embed.latte"}extra text{/embed}
		outer
	',
	'embed.latte' => '
		embed start
			{block a}embed A{/block}
		embed end
	',
], '
		outer

		embed start
			embed A
		embed end

		outer
');


testTemplate('extra overwritten block', [
	'main' => '
		outer
		{embed "embed.latte"}
			{block a}main-A{/block}
		{/embed}
		outer
	',
	'embed.latte' => '
		embed
	',
], '
		outer

		embed

		outer
');


testTemplate('one overwritten block', [
	'main' => '
		outer
		{embed "embed.latte"}
			{block a}main-A {include parent}{/block}
		{/embed}
		outer
	',
	'embed.latte' => '
		embed start
			{block a}embed A{/block}
		embed end
	',
], '
		outer

		embed start
			main-A embed A
		embed end

		outer
');


testTemplate('overwritten block + variables', [
	'main' => '
		{var $a = "M"}
		outer
		{embed "embed.latte"}
			{$a}
			{block a}main-A {$a}{/block}
		{/embed}
		outer
	',
	'embed.latte' => '
		{default $a = "W"}
		embed start {$a}
			{block a}embed A{/block}
		embed end
	',
], '
		outer

		embed start W
			main-A W
		embed end

		outer
');


testTemplate('outer variables', [
	'main' => '
		outer
		{var $var1 = "OUT1"}
		{var $var2 = "OUT2"}
		{embed "embed.latte"}
			{block a}{$var1} {$var2} {$var3} {block b}{$var1} {$var2} {$var3}{/block} {/block}
		{/embed}
		outer
	',
	'embed.latte' => '
		embed start
			{var $var2 = "IN2"}
			{var $var3 = "IN3"}
			{block a}embed A{/block}
			{block b}embed B{/block}
			{block c}embed C {$var1 ?? unset} {$var2 ?? unset} {$var3 ?? unset}{/block}
		embed end
	',
], '
		outer

		embed start
			OUT1 IN2 IN3 OUT1 IN2 IN3
			OUT1 IN2 IN3
			embed C unset IN2 IN3
		embed end

		outer
');


testTemplate('overwritten block + passed variables', [
	'main' => '
		{var $a = "M"}
		outer
		{embed "embed.latte", a => "P"}
			{$a}
			{block a}main-A {$a}{/block}
		{/embed}
		outer
	',
	'embed.latte' => '
		{default $a = "W"}
		embed start {$a}
			{block a}embed A{/block}
		embed end
	',
], '
		outer

		embed start P
			main-A P
		embed end

		outer
');


testTemplate('only outer blocks', [
	'main' => '
		{block a}outer-A{/block}

		{embed "embed.latte"}extra text{/embed}

		{block d}outer-D{/block}
	',
	'embed.latte' => '
		embed start
			{block c}embed C{/block}

			{block a}embed A{/block}
		embed end
	',
], '
		outer-A


		embed start
			embed C

			embed A
		embed end


		outer-D
');


testTemplate('overwritten & outer blocks', [
	'main' => '
		{block a}outer-A{/block}

		{embed "embed.latte"}
			{block a}main-A{/block}
			{block b}main-B{/block}
		{/embed}

		{block d}outer-D{/block}
	',
	'embed.latte' => '
		embed start
			{block c}embed C{/block}

			{block a}embed A{/block}
		embed end
	',
], '
		outer-A


		embed start
			embed C

			main-A
		embed end


		outer-D
');


testTemplate('include instead of block', [
	'main' => '
		outer
		{embed "embed.latte"}
			{block a}main-A{/block}
		{/embed}
		outer
	',
	'embed.latte' => '
		embed start
			{include a}
		embed end
	',
], '
		outer

		embed start
main-A		embed end

		outer
');


testTemplate('import in embed', [
	'main' => '
		outer
		{embed "embed.latte"}
			{import import.latte}
		{/embed}
		outer
	',
	'embed.latte' => '
		embed start
			{include a}
		embed end
	',
	'import.latte' => '{block a}main-A{/block}',
], '
		outer

		embed start
main-A		embed end

		outer
');


testTemplate('local outer block include from main', [
	'main' => '
		outer
		{embed "embed.latte"}
			{block a}*{include outer}*{/block}
		{/embed}

		{block local outer}outer-D{/block}
	',
	'embed.latte' => '
		embed start
			{block a}embed A{/block}
		embed end
	',
], '
		outer

		embed start
			*outer-D*
		embed end


		outer-D
');


Assert::exception(function () {
	testTemplate('outer block include from main', [
		'main' => '
			outer
			{embed "embed.latte"}
				{block a}*{include outer}*{/block}
			{/embed}

			{block outer}outer-D{/block}
		',
		'embed.latte' => '
			embed start
				{block a}embed A{/block}
			embed end
		',
	]);
}, Latte\RuntimeException::class, "Cannot include undefined block 'outer'.");


Assert::exception(function () {
	testTemplate('outer block include from embed', [
		'main' => '
			{block a}outer-A{/block}

			{embed "embed.latte"}{/embed}
		',
		'embed.latte' => '
			embed start
				{include a}
			embed end
		',
	]);
}, Latte\RuntimeException::class, "Cannot include undefined block 'a'.");


testTemplate('embeds block include', [
	'main' => '
		outer
		{embed "embed.latte"}
			{block b}*{include a}*{/block}
		{/embed}
		outer
	',
	'embed.latte' => '
		embed start
			{block a}embed A{/block}
			{block b}embed B{/block}
		embed end
	',
], '
		outer

		embed start
			embed A
			*embed A*
		embed end

		outer
');


testTemplate('embed in series I.', [
	'main' => '
		{embed "embed1.latte"}
			{block a}main-A{/block}
		{/embed}
	',
	'embed1.latte' => '
		embed1-start
			{embed "embed2.latte"}{/embed}
			{block a}embed1-A{/block}
		embed1-end
	',
	'embed2.latte' => '
		embed2-start
			{block a}embed2-A{/block}
		embed2-end
	',
], '

		embed1-start

		embed2-start
			embed2-A
		embed2-end

			main-A
		embed1-end
');


testTemplate('embed in series II.', [
	'main' => '
		{embed "embed1.latte"}
			{block a}main-A{/block}
		{/embed}
	',
	'embed1.latte' => '
		embed1-start
			{embed "embed2.latte"}
				{block a}embed1-A{/block}
			{/embed}
		embed1-end
	',
	'embed2.latte' => '
		embed2-start
			{block a}embed2-A{/block}
		embed2-end
	',
], '

		embed1-start

		embed2-start
			embed1-A
		embed2-end

		embed1-end
');


testTemplate('embed in series III.', [
	'main' => '
		{embed "embed1.latte"}
			{block a}main-A{/block}
		{/embed}
	',
	'embed1.latte' => '
		embed1-start
			{block a}
				embed1-A
				{embed "embed2.latte"}{/embed}
			{/block}
		embed1-end
	',
	'embed2.latte' => '
		embed2-start
			{block a}embed2-A{/block}
		embed2-end
	',
], '

		embed1-start
main-A
		embed1-end
');


testTemplate('embed in series IV.', [
	'main' => '
		{embed "embed1.latte"}
			{block a}main-A{/block}
		{/embed}
	',
	'embed1.latte' => '
		embed1-start
			{block a}
				embed1-A
				{embed "embed2.latte"}
					{block a}embed nested A{/block}
				{/embed}
			{/block}
		embed1-end
	',
	'embed2.latte' => '
		embed2-start
			{block a}embed2-A{/block}
		embed2-end
	',
], '

		embed1-start
main-A
		embed1-end
');


testTemplate('embeds nested in extra space', [
	'main' => '
		{embed "embed1.latte"}
			{embed "embed2.latte"}
				{block a}nested embeds A{/block}
			{/embed}
		{/embed}
	',
	'embed1.latte' => '
		embed1-start
			{block a}embed1-A{/block}
		embed1-end
	',
	'embed2.latte' => '
		embed2-start
			{block a}embed2-A{/block}
		embed2-end
	',
], '

		embed1-start
			embed1-A
		embed1-end
');


testTemplate('nested embeds', [
	'main' => '
		{embed "embed1.latte"}
			{block a}
				{embed "embed2.latte"}
					{block a}nested embeds A{/block}
				{/embed}
			{/block}
		{/embed}
	',
	'embed1.latte' => '
		embed1-start
			{block a}embed1-A{/block}
		embed1-end
	',
	'embed2.latte' => '
		embed2-start
			{block a}embed2-A{/block}
		embed2-end
	',
], '

		embed1-start

		embed2-start
			nested embeds A
		embed2-end


		embed1-end
');


testTemplate('nested embeds', [
	'main' => '
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
	',
	'embed1.latte' => '
		{block a}embed1-A{/block}
	',
	'embed2.latte' => '
		{block a}embed2-A{/block}
	',
], '
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
');


testTemplate('extending embed', [
	'main' => '
		{embed "embed.latte"}
			{block a}main-A{/block}
		{/embed}
	',
	'embed.latte' => '
		{extends embed-ext.latte}
		embed-start
			{block a}embed-A{/block}
		embed-end
	',
	'embed-ext.latte' => '
		embed-ext-start
			{block a}embed-ext-A{/block}
		embed-ext-end
	',
], '

		embed-ext-start
			main-A
		embed-ext-end
');


testTemplate('nested embedding with different overwritten blocks', [
	'main' => '
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
	',
	'embed.latte' => '
		{block a}embed-A{/block}
		{block b}embed-B{/block}
		{block c}embed-C{/block}
	',
	'import1.latte' => '
		{block a}import1-A{/block}
		{block b}import1-B{/block}
		{block c}import1-C{/block}
	',
	'import2.latte' => '
		{block a}import2-A{/block}
	',
], '

		import1-A
		import1-B
		main C

		import2-A
		embed-B
		main C
');


// generated code
$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'main' => '
		{embed "embed1.latte"}
			{block a}
				{embed "embed2.latte"}
					{block a}nested embeds A{/block}
				{/embed}
			{/block}
		{/embed}
	',
	'embed1.latte' => '
		embed1-start
			{block a}embed1-A{/block}
		embed1-end
	',
	'embed2.latte' => '
		embed2-start
			{block a}embed2-A{/block}
		embed2-end
	',
]));

Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.embed.file.phtml',
	$latte->compile('main')
);
