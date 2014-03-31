<?php

/**
 * Test: Latte\Object
 *
 * @author     David Grudl
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


Assert::exception(function() {
	$obj = new TestClass;
	$obj->undeclared();
}, 'LogicException', 'Call to undefined method TestClass::undeclared().');

Assert::exception(function() {
	$obj = new TestClass;
	$obj->abc();
}, 'LogicException', 'Call to undefined method parent::abc().');

Assert::exception(function() {
	$obj = new TestClass;
	$obj->undeclared = 'value';
}, 'LogicException', 'Cannot write to an undeclared property TestClass::$undeclared.');

Assert::exception(function() {
	$obj = new TestClass;
	$val = $obj->s;
}, 'LogicException', 'Cannot read an undeclared property TestClass::$s.');

Assert::exception(function() {
	$obj = new TestClass;
	unset($obj->s);
}, 'LogicException', 'Cannot unset the property TestClass::$s.');

Assert::false( isset($obj->undeclared) );
