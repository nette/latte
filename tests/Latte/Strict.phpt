<?php

/**
 * Test: Latte\Strict
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestClass
{
	use Latte\Strict;

	public $public;

	protected $protected;

	public static $publicStatic;

	public function publicMethod()
	{}

	public static function publicMethodStatic()
	{}

	protected function protectedMethod()
	{}

	protected static function protectedMethodS()
	{}
}

class TestChild extends TestClass
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
	$obj = new TestChild;
	$obj->callParent();
}, 'LogicException', 'Call to undefined method parent::callParent().');

Assert::exception(function () {
	$obj = new TestClass;
	$obj->publicMethodX();
}, 'LogicException', 'Call to undefined method TestClass::publicMethodX(), did you mean publicMethod()?');

Assert::exception(function () { // suggest static method
	$obj = new TestClass;
	$obj->publicMethodStaticX();
}, 'LogicException', 'Call to undefined method TestClass::publicMethodStaticX(), did you mean publicMethodStatic()?');

Assert::exception(function () { // suggest only public method
	$obj = new TestClass;
	$obj->protectedMethodX();
}, 'LogicException', 'Call to undefined method TestClass::protectedMethodX().');


// writing
Assert::exception(function () {
	$obj = new TestClass;
	$obj->undeclared = 'value';
}, 'LogicException', 'Attempt to write to undeclared property TestClass::$undeclared.');

Assert::exception(function () {
	$obj = new TestClass;
	$obj->publicX = 'value';
}, 'LogicException', 'Attempt to write to undeclared property TestClass::$publicX, did you mean $public?');

Assert::exception(function () { // suggest only non-static property
	$obj = new TestClass;
	$obj->publicStaticX = 'value';
}, 'LogicException', 'Attempt to write to undeclared property TestClass::$publicStaticX.');

Assert::exception(function () { // suggest only public property
	$obj = new TestClass;
	$obj->protectedX = 'value';
}, 'LogicException', 'Attempt to write to undeclared property TestClass::$protectedX.');


// reading
Assert::exception(function () {
	$obj = new TestClass;
	$val = $obj->undeclared;
}, 'LogicException', 'Attempt to read undeclared property TestClass::$undeclared.');

Assert::exception(function () {
	$obj = new TestClass;
	$val = $obj->publicX;
}, 'LogicException', 'Attempt to read undeclared property TestClass::$publicX, did you mean $public?');

Assert::exception(function () { // suggest only non-static property
	$obj = new TestClass;
	$val = $obj->publicStaticX;
}, 'LogicException', 'Attempt to read undeclared property TestClass::$publicStaticX.');

Assert::exception(function () { // suggest only public property
	$obj = new TestClass;
	$val = $obj->protectedX;
}, 'LogicException', 'Attempt to read undeclared property TestClass::$protectedX.');


// unset/isset
Assert::exception(function () {
	$obj = new TestClass;
	unset($obj->undeclared);
}, 'LogicException', 'Attempt to unset undeclared property TestClass::$undeclared.');

Assert::false(isset($obj->undeclared));
