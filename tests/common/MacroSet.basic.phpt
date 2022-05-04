<?php

declare(strict_types=1);

use Latte\MacroNode;
use Latte\Macros\MacroSet;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$set = new MacroSet($latte->getCompiler());


test('', function () use ($set) {
	$set->addMacro('void', 'begin');

	$node = new MacroNode($set, 'void');
	Assert::null($set->nodeOpened($node));
	Assert::same('<?php begin ?>', $node->openingCode);
	Assert::true($node->empty);

	$node = new MacroNode($set, 'void');
	$node->prefix = $node::PREFIX_NONE;
	Assert::false($set->nodeOpened($node));
});


test('', function () use ($set) {
	$set->addMacro('nonvoid', 'begin', 'end');

	$node = new MacroNode($set, 'nonvoid');
	Assert::null($set->nodeOpened($node));
	Assert::same('<?php begin ?>', $node->openingCode);
	Assert::false($node->empty);

	Assert::null($set->nodeClosed($node));
	Assert::same('<?php end ?>', $node->closingCode);

	$node = new MacroNode($set, 'nonvoid');
	$node->prefix = $node::PREFIX_NONE;
	Assert::null($set->nodeOpened($node));
	Assert::null($node->attrCode);

	$node = new MacroNode($set, 'nonvoid');
	$node->prefix = $node::PREFIX_INNER;
	Assert::null($set->nodeOpened($node));
	Assert::null($node->attrCode);
});


test('', function () use ($set) {
	$set->addMacro('attr', 'begin', 'end', 'attr');

	$node = new MacroNode($set, 'attr');
	Assert::null($set->nodeOpened($node));
	Assert::same('<?php begin ?>', $node->openingCode);
	Assert::false($node->empty);

	Assert::null($set->nodeClosed($node));
	Assert::same('<?php end ?>', $node->closingCode);

	$node = new MacroNode($set, 'attr');
	$node->prefix = $node::PREFIX_NONE;
	Assert::null($set->nodeOpened($node));
	Assert::same('<?php attr ?>', $node->attrCode);

	$node = new MacroNode($set, 'attr');
	$node->prefix = $node::PREFIX_INNER;
	Assert::null($set->nodeOpened($node));
	Assert::null($node->attrCode);
});


test('', function () use ($set) {
	$set->addMacro('noattr', function () use (&$called) {
		$called = true;
	}, null, fn() => false);

	$node = new MacroNode($set, 'noattr');
	$node->prefix = $node::PREFIX_NONE;
	Assert::false($set->nodeOpened($node));
	Assert::null($called);
});


test('', function () use ($set) {
	$set->addMacro('onlyattr', null, null, 'attr');

	$node = new MacroNode($set, 'onlyattr');
	Assert::false($set->nodeOpened($node));

	$node = new MacroNode($set, 'onlyattr');
	$node->prefix = $node::PREFIX_NONE;
	Assert::null($set->nodeOpened($node));
	Assert::same('<?php attr ?>', $node->attrCode);

	$node = new MacroNode($set, 'onlyattr');
	$node->prefix = $node::PREFIX_INNER;
	Assert::false($set->nodeOpened($node));
});


test('', function () use ($set) {
	$set->addMacro('dynamic', function (MacroNode $node) use (&$called) {
		$called = true;
		$node->empty = false;
	});

	$node = new MacroNode($set, 'dynamic');
	Assert::null($set->nodeOpened($node));
	Assert::false($node->empty);
	Assert::true($called);

	Assert::null($set->nodeClosed($node));
	Assert::same(null, $node->closingCode);
});


test('', function () use ($set) {
	$set->addMacro('reject', fn(MacroNode $node) => false);

	$node = new MacroNode($set, 'reject');
	Assert::false($set->nodeOpened($node));
});


test('', function () use ($set) {
	$set->addMacro('modifyOk1', function () {});
	$set->nodeOpened(new MacroNode($set, 'modifyOk1', '', '|filter'));

	$set->addMacro('modifyOk2', null, function () {});
	$set->nodeOpened(new MacroNode($set, 'modifyOk2', '', '|filter'));

	$set->addMacro('modifyOk3', null, null, function () {});
	$set->nodeOpened(new MacroNode($set, 'modifyOk3', '', '|filter'));

	$set->addMacro('modifyOk4', function () {}, '-');
	$set->nodeOpened(new MacroNode($set, 'modifyOk4', '', '|filter'));

	$set->addMacro('modifyOk5', '-', function () {});
	$set->nodeOpened(new MacroNode($set, 'modifyOk5', '', '|filter'));

	$set->addMacro('modifyOk6', '-', '-', function () {});
	$set->nodeOpened(new MacroNode($set, 'modifyOk6', '', '|filter'));

	$set->addMacro('modifyOk7', '%modify');
	$set->nodeOpened(new MacroNode($set, 'modifyOk7', '', '|filter'));

	$set->addMacro('modifyOk8', null, '%modify');
	$set->nodeOpened(new MacroNode($set, 'modifyOk8', '', '|filter'));

	$set->addMacro('modifyOk9', null, null, '%modify');
	$set->nodeOpened(new MacroNode($set, 'modifyOk9', '', '|filter'));

	$set->addMacro('modifyOk10', '%modify', '-');
	$set->nodeOpened(new MacroNode($set, 'modifyOk10', '', '|filter'));

	$set->addMacro('modifyOk11', '-', '%modify');
	$set->nodeOpened(new MacroNode($set, 'modifyOk11', '', '|filter'));

	$set->addMacro('modifyOk12', '-', '-', '%modify');
	$set->nodeOpened(new MacroNode($set, 'modifyOk12', '', '|filter'));

	$set->addMacro('modifyError1', '-');
	Assert::exception(
		fn() => $set->nodeOpened(new MacroNode($set, 'modifyError1', '', '|filter')),
		Latte\CompileException::class,
		'Filters are not allowed in {modifyError1}',
	);

	$set->addMacro('modifyError2', null, '-');
	Assert::exception(
		fn() => $set->nodeOpened(new MacroNode($set, 'modifyError2', '', '|filter')),
		Latte\CompileException::class,
		'Filters are not allowed in {modifyError2}',
	);

	$set->addMacro('modifyError3', null, null, '-');
	Assert::exception(
		fn() => $set->nodeOpened(new MacroNode($set, 'modifyError3', '', '|filter')),
		Latte\CompileException::class,
		'Filters are not allowed in {modifyError3}',
	);
});


test('', function () use ($set) {
	$set->addMacro('paramsOk1', function () {});
	$set->nodeOpened(new MacroNode($set, 'paramsOk1', 'params'));

	$set->addMacro('paramsOk2', null, function () {});
	$set->nodeOpened(new MacroNode($set, 'paramsOk2', 'params'));

	$set->addMacro('paramsOk3', null, null, function () {});
	$set->nodeOpened(new MacroNode($set, 'paramsOk3', 'params'));

	$set->addMacro('paramsOk4', function () {}, '-');
	$set->nodeOpened(new MacroNode($set, 'paramsOk4', 'params'));

	$set->addMacro('paramsOk5', '-', function () {});
	$set->nodeOpened(new MacroNode($set, 'paramsOk5', 'params'));

	$set->addMacro('paramsOk6', '-', '-', function () {});
	$set->nodeOpened(new MacroNode($set, 'paramsOk6', 'params'));

	$set->addMacro('paramsOk7', '%node');
	$set->nodeOpened(new MacroNode($set, 'paramsOk7', 'params'));

	$set->addMacro('paramsOk8', null, '%node');
	$set->nodeOpened(new MacroNode($set, 'paramsOk8', 'params'));

	$set->addMacro('paramsOk9', null, null, '%node');
	$set->nodeOpened(new MacroNode($set, 'paramsOk9', 'params'));

	$set->addMacro('paramsOk10', '%node', '-');
	$set->nodeOpened(new MacroNode($set, 'paramsOk10', 'params'));

	$set->addMacro('paramsOk11', '-', '%node');
	$set->nodeOpened(new MacroNode($set, 'paramsOk11', 'params'));

	$set->addMacro('paramsOk12', '-', '-', '%node');
	$set->nodeOpened(new MacroNode($set, 'paramsOk12', 'params'));

	$set->addMacro('paramsError1', '-');
	Assert::exception(
		fn() => $set->nodeOpened(new MacroNode($set, 'paramsError1', 'params')),
		Latte\CompileException::class,
		'Arguments are not allowed in {paramsError1}',
	);

	$set->addMacro('paramsError2', null, '-');
	Assert::exception(
		fn() => $set->nodeOpened(new MacroNode($set, 'paramsError2', 'params')),
		Latte\CompileException::class,
		'Arguments are not allowed in {paramsError2}',
	);

	$set->addMacro('paramsError3', null, null, '-');
	Assert::exception(
		fn() => $set->nodeOpened(new MacroNode($set, 'paramsError3', 'params')),
		Latte\CompileException::class,
		'Arguments are not allowed in {paramsError3}',
	);
});
