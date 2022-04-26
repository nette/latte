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

	public static $publicStatic;

	protected $protected;


	public function publicMethod()
	{
	}


	public static function publicMethodStatic()
	{
	}


	protected function protectedMethod()
	{
	}


	protected static function protectedMethodS()
	{
	}
}

class TestChild extends TestClass
{
	public function callParent()
	{
		parent::callParent();
	}
}


// calling
$obj = new TestClass;
Assert::exception(
	fn() => $obj->undeclared(),
	LogicException::class,
	'Call to undefined method TestClass::undeclared().',
);

Assert::exception(
	fn() => TestClass::undeclared(),
	LogicException::class,
	'Call to undefined static method TestClass::undeclared().',
);

Assert::exception(
	fn() => (new TestChild)->callParent(),
	LogicException::class,
	'Call to undefined method parent::callParent().',
);

Assert::exception(
	fn() => $obj->publicMethodX(),
	LogicException::class,
	'Call to undefined method TestClass::publicMethodX(), did you mean publicMethod()?',
);

Assert::exception(
	fn() => $obj->publicMethodStaticX(),
	LogicException::class,
	'Call to undefined method TestClass::publicMethodStaticX(), did you mean publicMethodStatic()?',
);

Assert::exception(
	fn() => $obj->protectedMethodX(),
	LogicException::class,
	'Call to undefined method TestClass::protectedMethodX().',
);


// writing
Assert::exception(
	fn() => $obj->undeclared = 'value',
	LogicException::class,
	'Attempt to write to undeclared property TestClass::$undeclared.',
);

Assert::exception(
	fn() => $obj->publicX = 'value',
	LogicException::class,
	'Attempt to write to undeclared property TestClass::$publicX, did you mean $public?',
);

Assert::exception(
	fn() => $obj->publicStaticX = 'value',
	LogicException::class,
	'Attempt to write to undeclared property TestClass::$publicStaticX.',
);

Assert::exception(
	fn() => $obj->protectedX = 'value',
	LogicException::class,
	'Attempt to write to undeclared property TestClass::$protectedX.',
);


// reading
Assert::exception(
	fn() => $obj->undeclared,
	LogicException::class,
	'Attempt to read undeclared property TestClass::$undeclared.',
);

Assert::exception(
	fn() => $obj->publicX,
	LogicException::class,
	'Attempt to read undeclared property TestClass::$publicX, did you mean $public?',
);

Assert::exception(
	fn() => $obj->publicStaticX,
	LogicException::class,
	'Attempt to read undeclared property TestClass::$publicStaticX.',
);

Assert::exception(
	fn() => $obj->protectedX,
	LogicException::class,
	'Attempt to read undeclared property TestClass::$protectedX.',
);


// unset/isset
$obj = new TestClass;
Assert::exception(function () use ($obj) {
	unset($obj->undeclared);
}, LogicException::class, 'Attempt to unset undeclared property TestClass::$undeclared.');

Assert::false(isset($obj->undeclared));
