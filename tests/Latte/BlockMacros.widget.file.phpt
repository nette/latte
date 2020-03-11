<?php

/**
 * Test: {widget ...}
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


function testTemplate(array $templates, string $res)
{
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader($templates));
	Assert::match(trim($res), trim($latte->renderToString('main')));
}


// no blocks
testTemplate([
	'main' => '
		outer
		{widget "widget.latte"}{/widget}
		outer
	',
	'widget.latte' => '
		widget
	',
], '
		outer

		widget

		outer
');


// no overwritten blocks
testTemplate([
	'main' => '
		outer
		{widget "widget.latte"}extra text{/widget}
		outer
	',
	'widget.latte' => '
		widget start
			{block a}widget A{/block}
		widget end
	',
], '
		outer

		widget start
			widget A		widget end

		outer
');


// extra overwritten block
testTemplate([
	'main' => '
		outer
		{widget "widget.latte"}
			{block a}main-A{/block}
		{/widget}
		outer
	',
	'widget.latte' => '
		widget
	',
], '
		outer

		widget
			outer
');


// one overwritten block
testTemplate([
	'main' => '
		outer
		{widget "widget.latte"}
			{block a}main-A{/block}
		{/widget}
		outer
	',
	'widget.latte' => '
		widget start
			{block a}widget A{/block}
		widget end
	',
], '
		outer

		widget start
			main-A		widget end
			outer
');


// overwritten block + variables
testTemplate([
	'main' => '
		{var $a = "M"}
		outer
		{widget "widget.latte"}
			{$a}
			{block a}main-A {$a}{/block}
		{/widget}
		outer
	',
	'widget.latte' => '
		{default $a = "W"}
		widget start {$a}
			{block a}widget A{/block}
		widget end
	',
], '
		outer

		widget start W
			main-A W		widget end
			outer
');


// overwritten block + passed variables
testTemplate([
	'main' => '
		{var $a = "M"}
		outer
		{widget "widget.latte", a => "P"}
			{$a}
			{block a}main-A {$a}{/block}
		{/widget}
		outer
	',
	'widget.latte' => '
		{default $a = "W"}
		widget start {$a}
			{block a}widget A{/block}
		widget end
	',
], '
		outer

		widget start P
			main-A P		widget end
			outer
');


// only outer blocks
testTemplate([
	'main' => '
		{block a}outer-A{/block}

		{widget "widget.latte"}extra text{/widget}

		{block d}outer-D{/block}
	',
	'widget.latte' => '
		widget start
			{block c}widget C{/block}

			{block a}widget A{/block}
		widget end
	',
], '
		outer-A

		widget start
			widget C
			widget A		widget end


		outer-D
');


// overwritten & outer blocks
testTemplate([
	'main' => '
		{block a}outer-A{/block}

		{widget "widget.latte"}
			{block a}main-A{/block}
			{block b}main-B{/block}
		{/widget}

		{block d}outer-D{/block}
	',
	'widget.latte' => '
		widget start
			{block c}widget C{/block}

			{block a}widget A{/block}
		widget end
	',
], '
		outer-A

		widget start
			widget C
			main-A		widget end

		outer-D
');


// include instead of block
testTemplate([
	'main' => '
		outer
		{widget "widget.latte"}
			{block a}main-A{/block}
		{/widget}
		outer
	',
	'widget.latte' => '
		widget start
			{include a}
		widget end
	',
], '
		outer

		widget start
main-A		widget end
			outer
');


// outer block include from main
testTemplate([
	'main' => '
		outer
		{widget "widget.latte"}
			{block a}*{include d}*{/block}
		{/widget}

		{block d}outer-D{/block}
	',
	'widget.latte' => '
		widget start
			{block a}widget A{/block}
		widget end
	',
], '
		outer

		widget start
			*outer-D*		widget end

		outer-D
');


// outer block include from widget
Assert::exception(function () {
	testTemplate([
		'main' => '
			{block a}outer-A{/block}

			{widget "widget.latte"}{/widget}
		',
		'widget.latte' => '
			widget start
				{include a}
			widget end
		',
	], '');
}, RuntimeException::class, "Cannot include undefined block 'a'.");


// widget's block include
Assert::exception(function () {
	testTemplate([
		'main' => '
			outer
			{widget "widget.latte"}
				{block b}*{include a}*{/block}
			{/widget}
			outer
		',
		'widget.latte' => '
			widget start
				{block a}widget A{/block}
				{block b}widget B{/block}
			widget end
		',
	], '');
}, RuntimeException::class, "Cannot include undefined block 'a'.");


// widget in series I.
testTemplate([
	'main' => '
		{widget "widget1.latte"}
			{block a}main-A{/block}
		{/widget}
	',
	'widget1.latte' => '
		widget1-start
			{widget "widget2.latte"}{/widget}
			{block a}widget1-A{/block}
		widget1-end
	',
	'widget2.latte' => '
		widget2-start
			{block a}widget2-A{/block}
		widget2-end
	',
], '
		widget1-start

		widget2-start
			widget2-A		widget2-end

			main-A		widget1-end
');


// widget in series II.
testTemplate([
	'main' => '
		{widget "widget1.latte"}
			{block a}main-A{/block}
		{/widget}
	',
	'widget1.latte' => '
		widget1-start
			{widget "widget2.latte"}
				{block a}widget1-A{/block}
			{/widget}
		widget1-end
	',
	'widget2.latte' => '
		widget2-start
			{block a}widget2-A{/block}
		widget2-end
	',
], '
		widget1-start

		widget2-start
			widget1-A		widget2-end
			widget1-end
');


// widget in series III.
testTemplate([
	'main' => '
		{widget "widget1.latte"}
			{block a}main-A{/block}
		{/widget}
	',
	'widget1.latte' => '
		widget1-start
			{block a}
				widget1-A
				{widget "widget2.latte"}{/widget}
			{/block}
		widget1-end
	',
	'widget2.latte' => '
		widget2-start
			{block a}widget2-A{/block}
		widget2-end
	',
], '
		widget1-start
main-A		widget1-end
');


// widget in series IV.
testTemplate([
	'main' => '
		{widget "widget1.latte"}
			{block a}main-A{/block}
		{/widget}
	',
	'widget1.latte' => '
		widget1-start
			{block a}
				widget1-A
				{widget "widget2.latte"}
					{block a}widget nested A{/block}
				{/widget}
			{/block}
		widget1-end
	',
	'widget2.latte' => '
		widget2-start
			{block a}widget2-A{/block}
		widget2-end
	',
], '
		widget1-start
main-A		widget1-end
');


// widgets nested in extra space
testTemplate([
	'main' => '
		{widget "widget1.latte"}
			{widget "widget2.latte"}
				{block a}nested widgets A{/block}
			{/widget}
		{/widget}
	',
	'widget1.latte' => '
		widget1-start
			{block a}widget1-A{/block}
		widget1-end
	',
	'widget2.latte' => '
		widget2-start
			{block a}widget2-A{/block}
		widget2-end
	',
], '
		widget1-start
			widget1-A		widget1-end
');


// nested widgets
testTemplate([
	'main' => '
		{widget "widget1.latte"}
			{block a}
				{widget "widget2.latte"}
					{block a}nested widgets A{/block}
				{/widget}
			{/block}
		{/widget}
	',
	'widget1.latte' => '
		widget1-start
			{block a}widget1-A{/block}
		widget1-end
	',
	'widget2.latte' => '
		widget2-start
			{block a}widget2-A{/block}
		widget2-end
	',
], '
		widget1-start

		widget2-start
			nested widgets A		widget2-end
			widget1-end
');
