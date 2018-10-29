<?php

/**
 * Test: templateClass
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


final class CustomTemplate implements Latte\Runtime\ITemplate
{
	public const USE_NAMESPACES = [
		'Latte\Runtime' => 'LR',
	];


	public function render(): void
	{
	}
}


final class InheritedTemplate extends Latte\Runtime\Template
{
}


final class BadTemplate
{
}


$compiler = new Latte\Compiler;
$parser = new Latte\Parser;
$tokens = $parser->parse('Custom Template');

$compiler->setTemplateClass(CustomTemplate::class);
$classBody = $compiler->compile($tokens, 'CustomTemplateChild');

Assert::same('<?php
use Latte\Runtime as LR;

class CustomTemplateChild extends CustomTemplate
{

	function main()
	{
		extract($this->params);?>
Custom Template<?php return get_defined_vars();
	}

}
', $classBody);

Assert::exception(
	function () use ($compiler) {
		$compiler->setTemplateClass(BadTemplate::class);
	},
	Latte\ImplementationException::class,
	"Class 'BadTemplate' must implement '" . Latte\Runtime\ITemplate::class . "' interface"
);

Assert::exception(
	function () use ($compiler) {
		Latte\Macros\CoreMacros::install($compiler);
	},
	Latte\ImplementationException::class,
	'CoreMacros need ' . $compiler->getTemplateCLass() . ' to inherit from ' . Latte\Runtime\Template::class
);

Assert::exception(
	function () use ($compiler) {
		Latte\Macros\BlockMacros::install($compiler);
	},
	Latte\ImplementationException::class,
	'BlockMacros need ' . $compiler->getTemplateCLass() . ' to inherit from ' . Latte\Runtime\Template::class
);

$compiler->setTemplateClass(InheritedTemplate::class);
Latte\Macros\CoreMacros::install($compiler);
Latte\Macros\BlockMacros::install($compiler);
$classBody = $compiler->compile($tokens, 'CustomTemplateChild');
Assert::same('<?php
use Latte\Runtime as LR;

class CustomTemplateChild extends InheritedTemplate
{

	function main()
	{
		extract($this->params);?>
Custom Template<?php return get_defined_vars();
	}

}
', $classBody);
