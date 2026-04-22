<?php declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('simple name is emitted as Source constant and /** source */ comment', function () {
	$latte = createLatte();
	$code = $latte->compile('hello.latte');
	Assert::contains("public const Source = 'hello.latte';", $code);
	Assert::contains('/** source: hello.latte */', $code);
});


test('multi-line name (e.g. string loader content) is not emitted', function () {
	$latte = createLatte();
	$code = $latte->compile("{switch 0}\n{case ''}string\n{/switch}");
	Assert::notContains('public const Source', $code);
	Assert::notContains('/** source:', $code);
});


test('name with question mark is not emitted', function () {
	$latte = createLatte();
	$code = $latte->compile('template?query');
	Assert::notContains('public const Source', $code);
	Assert::notContains('/** source:', $code);
});
