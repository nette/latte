<?php

/**
 * TestAuxiliaryIterator usage.
 */

declare(strict_types=1);

use Latte\Essential\AuxiliaryIterator;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function exportIterator(iterable $iterator): array
{
	$res = [];
	foreach ($iterator as $key => $value) {
		$res[] = [$key, $value];
	}
	return $res;
}


test('empty array', function () {
	$iterator = new AuxiliaryIterator([]);
	Assert::same(
		[],
		exportIterator($iterator),
	);
	Assert::same(
		0,
		count($iterator),
	);
});


test('special keys', function () {
	$pairs = [[new stdClass, new stdClass], [null, null]];
	$iterator = new AuxiliaryIterator($pairs);
	Assert::same(
		$pairs,
		exportIterator($iterator),
	);
	Assert::same(
		2,
		count($iterator),
	);
});


test('re-iteration', function () {
	$pairs = [[0, 0], [1, 1], [2, 2]];
	$iterator = new AuxiliaryIterator($pairs);
	Assert::same(
		$pairs,
		exportIterator($iterator),
	);
	Assert::same(
		$pairs,
		exportIterator($iterator),
	);
});


test('nested re-iteration', function () {
	$pairs = [[0, 0], [1, 1], [2, 2]];
	$keys = [];
	$iterator = new AuxiliaryIterator($pairs);
	foreach ($iterator as $key => $value) {
		$keys[] = $key;
		foreach ($iterator as $value);
	}

	Assert::same(
		[0, 1, 2],
		$keys,
	);
});
