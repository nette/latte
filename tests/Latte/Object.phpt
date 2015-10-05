<?php

/**
 * Test: Latte\Object
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestClass extends Latte\Object
{
	public function abc()
	{
		parent::abc();
	}
}


Assert::exception(function () {
	$obj = new TestClass;
	$obj->undeclared();
}, LogicException::class, 'Call to undefined method TestClass::undeclared().');

Assert::exception(function () {
	$obj = new TestClass;
	$obj->abc();
}, LogicException::class, 'Call to undefined method parent::abc().');

Assert::exception(function () {
	$obj = new TestClass;
	$obj->undeclared = 'value';
}, LogicException::class, 'Cannot write to an undeclared property TestClass::$undeclared.');

Assert::exception(function () {
	$obj = new TestClass;
	$val = $obj->s;
}, LogicException::class, 'Cannot read an undeclared property TestClass::$s.');

Assert::exception(function () {
	$obj = new TestClass;
	unset($obj->s);
}, LogicException::class, 'Cannot unset the property TestClass::$s.');

Assert::false(isset($obj->undeclared));
