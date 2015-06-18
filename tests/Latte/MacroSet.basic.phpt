<?php

use Latte\Macros\MacroSet;
use Latte\MacroNode;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$set = new MacroSet($latte->getCompiler());


test(function () use ($set) {
	$set->addMacro('void', 'begin');

	$node = new MacroNode($set, 'void');
	Assert::null($set->nodeOpened($node));
	Assert::same('<?php begin ?>', $node->openingCode);
	Assert::true($node->isEmpty);

	$node = new MacroNode($set, 'void');
	$node->prefix = $node::PREFIX_NONE;
	Assert::false($set->nodeOpened($node));
});


test(function () use ($set) {
	$set->addMacro('nonvoid', 'begin', 'end');

	$node = new MacroNode($set, 'nonvoid');
	Assert::null($set->nodeOpened($node));
	Assert::same('<?php begin ?>', $node->openingCode);
	Assert::false($node->isEmpty);

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


test(function () use ($set) {
	$set->addMacro('attr', 'begin', 'end', 'attr');

	$node = new MacroNode($set, 'attr');
	Assert::null($set->nodeOpened($node));
	Assert::same('<?php begin ?>', $node->openingCode);
	Assert::false($node->isEmpty);

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


test(function () use ($set) {
	$set->addMacro('noattr', function () use (& $called) {
		$called = TRUE;
	}, NULL, function () { return FALSE; });

	$node = new MacroNode($set, 'noattr');
	$node->prefix = $node::PREFIX_NONE;
	Assert::false($set->nodeOpened($node));
	Assert::null($called);
});


test(function () use ($set) {
	$set->addMacro('onlyattr', NULL, NULL, 'attr');

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


test(function () use ($set) {
	$set->addMacro('dynamic', function (MacroNode $node) use (& $called) {
		$called = TRUE;
		$node->isEmpty = FALSE;
	});

	$node = new MacroNode($set, 'dynamic');
	Assert::null($set->nodeOpened($node));
	Assert::false($node->isEmpty);
	Assert::true($called);

	Assert::null($set->nodeClosed($node));
	Assert::same(NULL, $node->closingCode);
});


test(function () use ($set) {
	$set->addMacro('reject', function (MacroNode $node) {
		return FALSE;
	});

	$node = new MacroNode($set, 'reject');
	Assert::false($set->nodeOpened($node));
});
