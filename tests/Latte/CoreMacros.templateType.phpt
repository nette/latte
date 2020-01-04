<?php

/**
 * Test: Latte\CoreMacros: {templateType ClassWithTypes}
 */

declare(strict_types=1);

use Latte\Macros\CoreMacros;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$compiler = new Latte\Compiler;
CoreMacros::install($compiler);

test(function () use ($compiler) {
	class TemplateTypeClass
	{
		public $publicMixed;
		/** @var string */
		public $publicAnnotated;
		private $privateMixed;
	}

	Assert::same(
		'<?php /** @var mixed $publicMixed *//** @var string $publicAnnotated */ ?>',
		$compiler->expandMacro('templateType', TemplateTypeClass::class)->openingCode
	);
});

test(function () use ($compiler) {
	Assert::exception(function () use ($compiler) {
		$compiler->expandMacro('templateType', 'foo');
	}, Latte\CompileException::class, 'Unknown type foo in {templateType}. Expected name of class or interface.');
});
