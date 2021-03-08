<?php

/**
 * Rules for Nette Coding Standard
 * https://github.com/nette/coding-standard
 */

declare(strict_types=1);


return function (Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator): void {
	$containerConfigurator->import(PRESET_DIR . '/php71.php');

	$parameters = $containerConfigurator->parameters();

	$parameters->set('skip', [
		// use function
		PhpCsFixer\Fixer\Import\SingleImportPerStatementFixer::class => [
			'src/Latte/Runtime/Filters.php',
		],

		// use function
		PhpCsFixer\Fixer\Import\OrderedImportsFixer::class => [
			'src/Latte/Runtime/Filters.php',
		],

		// #Attribute
		Nette\CodingStandard\Sniffs\WhiteSpace\FunctionSpacingSniff::class => [
			'tests/Latte/Engine.paramsObject.phpt',
		],
	]);
};
