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


testTemplate('one overwritten block', [
	'main' => '
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
	',
], '
		outer
		embed start
			main-A embed A
		embed end

		outer
');


testTemplate('outer variables', [
	'main' => '
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


testTemplate('overwritten block + variables', [
	'main' => '
		{var $a = "M"}
		outer
		{embed embed}
			{$a}
			{block a}main-A {$a}{/block}
		{/embed}
		outer

		{define embed}
		{default $a = "W"}
		embed start {$a}
			{block a}embed A{/block}
		embed end
		{/define}
	',
], '
		outer
		embed start W
			main-A W
		embed end

		outer
');


testTemplate('overwritten block + passed variables', [
	'main' => '
		{var $a = "M"}
		outer
		{embed embed, a => "P"}
			{$a}
			{block a}main-A {$a}{/block}
		{/embed}
		outer

		{define embed}
		{default $a = "W"}
		embed start {$a}
			{block a}embed A{/block}
		embed end
		{/define}
	',
], '
		outer
		embed start P
			main-A P
		embed end

		outer
');


testTemplate('include instead of block', [
	'main' => '
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
		{embed embed}
			{import import.latte}
		{/embed}
		outer

		{define embed}
		embed start
			{include a}
		embed end
		{/define}
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
		{embed embed}
			{block a}*{include outer}*{/block}
		{/embed}

		{block local outer}outer-D{/block}

		{define embed}
		embed start
			{block a}embed A{/block}
		embed end
		{/define}
	',
], '
		outer
		embed start
			*outer-D*
		embed end


		outer-D
');


Assert::exception(function () {
	testTemplate('embed block included from main', [
		'main' => '
			outer
			{embed embed}
				{block a}{/block}
			{/embed}

			{include a}

			{define embed}{/define}
		',
	]);
}, Latte\RuntimeException::class, "Cannot include undefined block 'a'.");


testTemplate('embeds block include', [
	'main' => '
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
	',
], '
		outer
		embed start
			embed A
			*embed A*
		embed end

		outer
');


testTemplate('embed in series II.', [
	'main' => '
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
	',
], '

		embed1-start
		embed2-start
			embed1-A
		embed2-end

		embed1-end
');


testTemplate('nested embedding with different overwritten blocks', [
	'main' => '
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
			import1-B
			main C
');


testTemplate('import on top layer', [
	'main' => '
		{import "import.latte"}

		{block a}outer-A{/block}

		{embed embed1}
			{block a}main-A{/block}
		{/embed}

		{include a}
	',
	'import.latte' => '
		{define embed1}
			{block a}import-A{/block}
			{block b}import-B{/block}
		{/define}
	',
], '

		outer-A

			main-A
			import-B


outer-A
');


// generated code
$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'main' => '
		{embed embed1}
			{block a}
				{embed embed1}
					{block a}nested embeds A{/block}
				{/embed}
			{/block}
		{/embed}

		{define embed1}
		embed1-start
			{block a}embed1-A{/block}
		embed1-end
		{/define}
	',
]));

Assert::matchFile(
	__DIR__ . '/expected/embed.block.phtml',
	$latte->compile('main'),
);
