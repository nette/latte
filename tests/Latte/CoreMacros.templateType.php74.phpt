<?php

/**
 * Test: Latte\CoreMacros: {templateType ClassWithTypes}
 */

declare(strict_types=1);

use Latte\Macros\CoreMacros;
use Tester\Assert;
use Tester\Environment;
use Tester\FileMock;

require __DIR__ . '/../bootstrap.php';

if (PHP_VERSION_ID < 70400) {
	Environment::skip('Typed properties cannot be tested with PHP < 7.4');
}

$compiler = new Latte\Compiler;
CoreMacros::install($compiler);

test(function () use ($compiler) {
	require FileMock::create('
	class TemplateTypeClass
	{
		public string $publicTyped;
		private int $privateTyped;
	}
	', 'php');

	Assert::same(
		'<?php /** @var string $publicTyped */ ?>',
		$compiler->expandMacro('templateType', TemplateTypeClass::class)->openingCode
	);
});
