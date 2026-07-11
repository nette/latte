<?php

/**
 * Test: ParseError in compiled template is converted to CompileException.
 */

declare(strict_types=1);

use Latte\Compiler\Nodes\AuxiliaryNode;
use Latte\Compiler\Nodes\TemplateNode;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('ParseError thrown by eval is converted to CompileException with source', function () {
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader(['main' => 'ok']));
	$latte->addExtension(new class extends Latte\Extension {
		public function getPasses(): array
		{
			return [
				'injectInvalidCode' => function (TemplateNode $node) {
					$node->main->append(new AuxiliaryNode(fn() => 'this is not valid PHP;'));
				},
			];
		}
	});

	$e = Assert::exception(
		fn() => $latte->renderToString('main'),
		Latte\CompileException::class,
		'Error in template: %a%',
	);
	Assert::type(ParseError::class, $e->getPrevious());
});
