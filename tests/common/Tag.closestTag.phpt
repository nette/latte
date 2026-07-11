<?php

/**
 * Test: Tag::closestTag() matches subclasses of the given node classes.
 */

declare(strict_types=1);

use Latte\Essential\Nodes\ForeachNode;
use Latte\Essential\Nodes\TryNode;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class MyForeachNode extends ForeachNode
{
}

class MyTryNode extends TryNode
{
}


$latte = createLatte();
$latte->addExtension(new class extends Latte\Extension {
	public function getTags(): array
	{
		return [
			'foreach' => MyForeachNode::create(...),
			'try' => MyTryNode::create(...),
		];
	}
});


test('{iterateWhile} finds a subclassed {foreach}', function () use ($latte) {
	Assert::same(
		'12',
		$latte->renderToString('{foreach [1, 2] as $x}{iterateWhile}{$x}{/iterateWhile false}{/foreach}'),
	);
});


test('{rollback} finds a subclassed {try}', function () use ($latte) {
	Assert::same('', $latte->renderToString('{try}a{rollback}{/try}'));
});
