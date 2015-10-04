<?php

/**
 * Test: Latte\Object
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestClass extends Latte\Object
{
	public function callParent()
	{
		parent::callParent();
	}
}


// calling
Assert::exception(function () {
	$obj = new TestClass;
	$obj->undeclared();
}, LogicException::class, 'Call to undefined method TestClass::undeclared().');

Assert::exception(function () {
	TestClass::undeclared();
}, LogicException::class, 'Call to undefined static method TestClass::undeclared().');

Assert::exception(function () {
	$obj = new TestClass;
	$obj->callParent();
}, LogicException::class, 'Call to undefined method parent::callParent().');


// writing
Assert::exception(function () {
	$obj = new TestClass;
	$obj->undeclared = 'value';
}, LogicException::class, 'Attempt to write to undeclared property TestClass::$undeclared.');


// reading
Assert::exception(function () {
	$obj = new TestClass;
	$val = $obj->undeclared;
}, LogicException::class, 'Attempt to read undeclared property TestClass::$undeclared.');


// unset/isset
Assert::exception(function () {
	$obj = new TestClass;
	unset($obj->undeclared);
}, LogicException::class, 'Attempt to unset undeclared property TestClass::$undeclared.');

Assert::false(isset($obj->undeclared));
