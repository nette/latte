<?php declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;

$template = $latte->createTemplate(__DIR__ . '/templates/block.latte');
Assert::type(Latte\Runtime\Template::class, $template);
Assert::null($template->getReferringTemplate());
Assert::null($template->getReferenceType());
Assert::same(['menu'], $template->getBlockNames());


$template = $latte->createTemplate(__DIR__ . '/templates/block.latte');
Assert::type(Latte\Runtime\Template::class, $template);
Assert::null($template->getReferringTemplate());
Assert::null($template->getReferenceType());
Assert::same(['menu'], $template->getBlockNames());


test('clearCache: false works as the very first call', function () {
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader(['main' => 'ok']));
	$template = $latte->createTemplate('main', [], clearCache: false);
	Assert::type(Latte\Runtime\Template::class, $template);
});


test('configuration changes invalidate the memoized hash', function () {
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader(['main' => 'ok']));
	$class = $latte->getTemplateClass('main');
	Assert::same($class, $latte->getTemplateClass('main')); // memoized & stable

	$latte->addFunction('newFunc', fn() => 1);
	Assert::notSame($class, $latte->getTemplateClass('main')); // no stale hash

	$latte->setContentType(Latte\ContentType::Text);
	$latte->setPolicy(Latte\Sandbox\SecurityPolicy::createSafePolicy());
	$latte->setSandboxMode();
	$latte->setFeature(Latte\Feature::StrictParsing);
	// each mutator produced a fresh hash without an intervening render
	Assert::notSame($class, $latte->getTemplateClass('main'));
});
