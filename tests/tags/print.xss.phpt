<?php

declare(strict_types=1);

use Latte\Runtime\Html;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'
	{$el}
	{$el2}

	<p val = {$xss} > </p>
	<p onclick = {$xss}> </p>
	<p ONCLICK ="{$xss}" {$xss}> </p>

	<STYLE type="text/css">
	<!--
	#{$xss} {
		background: blue;
	}
	-->
	</style>

	<script>
	<!--
	alert('</div>');

	var prop = {$people};

	document.getElementById({$xss}).style.backgroundColor = 'red';

	var html = {$el} || {$el2};
	-->
	</script>

	<SCRIPT>
	/* <![CDATA[ */

	var prop2 = {$people};

	/* ]]> */
	</script>

	<p onclick =
	'alert({$xss});alert("hello");'
	 title='{$xss}'
	 STYLE =
	 "color:{$xss};"
	 rel="{$xss}"
	 onblur="alert({$xss})"
	 alt='{$el} {$el2}'
	 onfocus="alert({$el})"
	>click on me {$xss}</p>
	EOD;

Assert::matchFile(
	__DIR__ . '/expected/print.xss.php',
	$latte->compile($template),
);

$params['people'] = ['John', 'Mary', 'Paul', ']]> <!--'];
$params['el'] = new Html("<div title='1/2\"'></div>");
$params['el2'] = Nette\Utils\Html::el('span', ['title' => '/"'])->setText('foo');
$params['xss'] = 'some&<>"\'/chars';
$params['menu'] = ['about', ['product1', 'product2'], 'contact'];

Assert::matchFile(
	__DIR__ . '/expected/print.xss.html',
	$latte->renderToString($template, $params),
);
