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
}, 'LogicException', 'Call to undefined method TestClass::undeclared().');

Assert::exception(function () {
	TestClass::undeclared();
}, 'LogicException', 'Call to undefined static method TestClass::undeclared().');

Assert::exception(function () {
	$obj = new TestClass;
	$obj->callParent();
}, 'LogicException', PHP_VERSION_ID != 50303 ? 'Call to undefined method parent::callParent().' : 'Call to undefined static method TestClass::callParent().'); // PHP bug #52713 (exclusive to PHP 5.3.3)


// writing
Assert::exception(function () {
	$obj = new TestClass;
	$obj->undeclared = 'value';
}, 'LogicException', 'Attempt to write to undeclared property TestClass::$undeclared.');


// reading
Assert::exception(function () {
	$obj = new TestClass;
	$val = $obj->undeclared;
}, 'LogicException', 'Attempt to read undeclared property TestClass::$undeclared.');


// unset/isset
Assert::exception(function () {
	$obj = new TestClass;
	unset($obj->undeclared);
}, 'LogicException', 'Attempt to unset undeclared property TestClass::$undeclared.');

Assert::false(isset($obj->undeclared));
