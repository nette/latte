<?php

/**
 * n:class
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'

	{foreach [1,2,3] as $foo}
		<b n:class="$iterator->even ? even">item</b>
	{/foreach}

	<p n:foreach="[1, 2, 3] as $foo" n:class="$iterator->even ? even">{$foo}</p>

	<p n:class="foo, (false ? first), odd, (true ? foo : bar)">n:class</p>

	<p n:class="false ? first">n:class empty</p>

	<p n:class="true ? bem--modifier">n:class with BEM</p>

	EOD;

Assert::matchFile(
	__DIR__ . '/expected/n-class.phtml',
	$latte->compile($template),
);

Assert::match(
	<<<'XX'

			<b>item</b>
			<b class="even">item</b>
			<b>item</b>

		<p>1</p>
		<p class="even">2</p>
		<p>3</p>

		<p class="foo odd">n:class</p>

		<p>n:class empty</p>

		<p class="bem--modifier">n:class with BEM</p>
		XX
,
	$latte->renderToString($template),
);


Assert::exception(
	fn() => $latte->compile('<div n:class/>'),
	Latte\CompileException::class,
	'Missing arguments in n:class',
);


Assert::exception(
	fn() => $latte->compile('<div n:inner-class/>'),
	Latte\CompileException::class,
	'Unknown attribute n:inner-class',
);
