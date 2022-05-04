<?php

/**
 * n:tag
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(
	'<span>Content</span>',
	$latte->renderToString('<div n:tag="span">Content</div>'),
);


Assert::match(
	'<span></span>',
	$latte->renderToString('<DIV n:tag="span"></DIV>'),
);


Assert::match(
	'
	<h1
class="a" title="b">
		<h2></h2
		>
	</h1>
',
	$latte->renderToString('
	<div
class="a" n:tag="h1" title="b">
		<div n:tag="h2"></div
		>
	</div>
'),
);


Assert::match(
	'<br class="a" title="b">',
	$latte->renderToString('<img class="a" n:tag="br" title="b">'),
);


Assert::match(
	'<br/>',
	$latte->renderToString('<img n:tag="br"/>'),
);


// no change
Assert::match(
	'<img/>',
	$latte->renderToString('<img n:tag="null"/>'),
);


Assert::match(
	<<<'XX'
		%A%
				$ʟ_tag[0] = ('h' . 1) ?? 'div';
				Latte\Runtime\Filters::checkTagSwitch('div', $ʟ_tag[0]);
				echo '<';
				echo $ʟ_tag[0];
				echo ' class="bar" ';
				if (isset($id)) /* line 1 */ {
					echo 'id="content"';
				}
				echo '></';
				echo $ʟ_tag[0];
				echo '>';
		%A%
		XX
,
	$latte->compile('<div class="bar" {ifset $id}id="content"{/ifset} n:tag="h . 1"></div>'),
);


Assert::exception(function () use ($latte) {
	$latte->compile('<div n:tag>');
}, Latte\CompileException::class, 'Missing arguments in n:tag');


Assert::exception(function () use ($latte) {
	$latte->compile('<div n:inner-tag>');
}, Latte\CompileException::class, 'Unknown n:inner-tag, use n:tag attribute.');


Assert::exception(function () use ($latte) {
	$latte->renderToString('<div n:tag="1"></div>');
}, Latte\RuntimeException::class, 'Invalid tag name 1');


Assert::exception(function () use ($latte) {
	$latte->compile('<script n:tag="foo"></script>');
}, Latte\CompileException::class, 'Attribute n:tag is not allowed in <script> or <style>');


Assert::exception(function () use ($latte) {
	$latte->compile('<STYLE n:tag="foo"></STYLE>');
}, Latte\CompileException::class, 'Attribute n:tag is not allowed in <script> or <style>');


Assert::exception(function () use ($latte) {
	$latte->renderToString('<div n:tag="\'SCRIPT\'"></div>');
}, Latte\RuntimeException::class, 'Forbidden tag <div> change to <script>.');


Assert::exception(function () use ($latte) {
	$latte->renderToString('<div n:tag="style"></div>');
}, Latte\RuntimeException::class, 'Forbidden tag <div> change to <style>.');


Assert::exception(function () use ($latte) {
	$latte->renderToString('<span n:tag="br"></span>');
}, Latte\RuntimeException::class, 'Forbidden tag <span> change to <br>.');
