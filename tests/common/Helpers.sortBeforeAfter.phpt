<?php declare(strict_types=1);

/**
 * Test: Latte\Helpers::sortBeforeAfter()
 */

use Latte\Extension;
use Latte\Helpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


// Helper to create ordered item
$order = fn($before = [], $after = []) => Extension::order(fn() => null, $before, $after);


// Test: No constraints - preserve original order
$list = ['a' => fn() => 1, 'b' => fn() => 2, 'c' => fn() => 3];
Assert::same(['a', 'b', 'c'], array_keys(Helpers::sortBeforeAfter($list)));


// Test: Simple before constraint (c before a)
$list = ['a' => fn() => 1, 'b' => fn() => 2, 'c' => $order(before: 'a')];
$result = array_keys(Helpers::sortBeforeAfter($list));
Assert::true(array_search('c', $result, true) < array_search('a', $result, true), 'c should be before a');


// Test: Simple after constraint (a after c)
$list = ['a' => $order(after: 'c'), 'b' => fn() => 2, 'c' => fn() => 3];
$result = array_keys(Helpers::sortBeforeAfter($list));
Assert::true(array_search('a', $result, true) > array_search('c', $result, true), 'a should be after c');


// Test: before: '*' (move to start)
$list = ['a' => fn() => 1, 'b' => fn() => 2, 'c' => $order(before: '*')];
Assert::same('c', array_keys(Helpers::sortBeforeAfter($list))[0], 'c should be first');


// Test: after: '*' (move to end)
$list = ['a' => $order(after: '*'), 'b' => fn() => 2, 'c' => fn() => 3];
$result = array_keys(Helpers::sortBeforeAfter($list));
Assert::same('a', $result[count($result) - 1], 'a should be last');


// Test: Chained dependencies (c before b, b before a)
$list = ['a' => fn() => 1, 'b' => $order(before: 'a'), 'c' => $order(before: 'b')];
Assert::same(['c', 'b', 'a'], array_keys(Helpers::sortBeforeAfter($list)));


// Test: Missing target ignored
$list = ['a' => fn() => 1, 'b' => $order(before: 'nonexistent')];
Assert::same(['a', 'b'], array_keys(Helpers::sortBeforeAfter($list)));


// Test: Cycle detection (a before b, b before a)
Assert::exception(function () use ($order) {
	$list = ['a' => $order(before: 'b'), 'b' => $order(before: 'a')];
	Helpers::sortBeforeAfter($list);
}, LogicException::class);


// Test: Multiple before targets
$list = ['a' => fn() => 1, 'b' => fn() => 2, 'c' => $order(before: ['a', 'b'])];
Assert::same(['c', 'a', 'b'], array_keys(Helpers::sortBeforeAfter($list)));


// Test: Multiple after targets
$list = ['a' => $order(after: ['b', 'c']), 'b' => fn() => 2, 'c' => fn() => 3];
Assert::same(['b', 'c', 'a'], array_keys(Helpers::sortBeforeAfter($list)));


// Test: Empty list
Assert::same([], Helpers::sortBeforeAfter([]));


// Test: Single item
$list = ['a' => fn() => 1];
Assert::same(['a'], array_keys(Helpers::sortBeforeAfter($list)));


// Test: Combined before and after on same item
$list = ['a' => fn() => 1, 'b' => $order(after: 'a', before: 'c'), 'c' => fn() => 3];
Assert::same(['a', 'b', 'c'], array_keys(Helpers::sortBeforeAfter($list)));


// Test: Two items with before: '*' (both want to be first)
Assert::exception(function () use ($order) {
	$list = ['a' => $order(before: '*'), 'b' => $order(before: '*')];
	Helpers::sortBeforeAfter($list);
}, LogicException::class);


// Test: Two items with after: '*' (both want to be last)
Assert::exception(function () use ($order) {
	$list = ['a' => $order(after: '*'), 'b' => $order(after: '*')];
	Helpers::sortBeforeAfter($list);
}, LogicException::class);
