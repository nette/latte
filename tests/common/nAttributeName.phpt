<?php

/**
 * Test: names of custom n:attributes containing 'tag-' or 'inner-' inside the name are not mangled.
 */

declare(strict_types=1);

use Latte\Compiler\Nodes\NopNode;
use Latte\Compiler\Tag;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$extension = new class extends Latte\Extension {
	public array $names = [];


	public function getTags(): array
	{
		$parser = function (Tag $tag): Generator {
			$this->names[] = $tag->name;
			[$content] = yield;
			return $content ?? new NopNode;
		};
		return [
			'n:my-tag-attr' => $parser,
			'n:my-inner-x' => $parser,
		];
	}
};

$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->addExtension($extension);

Assert::same('<b>x</b>', $latte->renderToString('<b n:my-tag-attr n:my-inner-x>x</b>'));
Assert::same(['my-tag-attr', 'my-inner-x'], $extension->names);
