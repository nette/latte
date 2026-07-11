<?php

/**
 * Test: Latte\Bridges\Tracy\LattePanel builds the template tree and measures render times.
 */

declare(strict_types=1);

use Latte\Bridges\Tracy\LattePanel;
use Latte\Extension;
use Latte\Runtime\Template;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

if (!class_exists(Tracy\Debugger::class)) {
	Tester\Environment::skip('Requires Tracy.');
}


$panel = new LattePanel;

$latte = createLatte();
$latte->setLoader(new Latte\Loaders\StringLoader([
	'main' => '{include file inc}{include file inc}{include file slow}',
	'inc' => '{include file sub}i',
	'sub' => 's',
	'slow' => '{do usleep(20000)}s',
]));
$latte->addExtension(new class ($panel) extends Extension {
	public function __construct(
		private LattePanel $panel,
	) {
	}


	public function beforeRender(Template $template): void
	{
		$this->panel->addTemplate($template);
	}


	public function afterRender(Template $template): void
	{
		$this->panel->templateRendered($template);
	}
});

Assert::same('sisis', $latte->renderToString('main'));

$panel->getPanel(); // builds the list
$list = Assert::with($panel, fn() => $this->list);

Assert::count(4, $list); // main, inc (2x), sub (2x, under both inc instances), slow
Assert::same(['main', 'inc', 'sub', 'slow'], array_map(fn($i) => $i->template->getName(), $list));
Assert::same([1, 2, 2, 1], array_map(fn($i) => $i->count, $list));

[$main, $inc, $sub, $slow] = $list;

// children of all instances count into the parent group's time
Assert::true($inc->time >= $sub->time, 'inc total >= sub total (both instances)');

// {do usleep(20000)} means 'slow' takes roughly 20 ms (Windows may undershoot slightly)
Assert::true($slow->selfTime >= 0.015, 'slow template self time');

// main's total includes its children, its own self time is small
Assert::true($main->time >= $slow->time, 'main total >= slow total');
Assert::true($main->selfTime < $main->time, 'main self time excludes children');

// self time never exceeds total time
foreach ($list as $item) {
	Assert::true($item->selfTime <= $item->time + 1e-9, 'self <= total');
}
