<?php

/**
 * Test: {onCompile}
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class Notes
{
	public static $notes = [];


	public static function add($message)
	{
		self::$notes[] = $message;
	}


	public static function fetch()
	{
		$res = self::$notes;
		self::$notes = [];
		return $res;
	}
}


test('', function () {
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader);

	$latte->onCompile[] = function (Latte\Engine $engine) {
		Notes::add('adding macros 1');
	};

	$latte->onCompile[] = function (Latte\Engine $engine) {
		Notes::add('adding macros 2');
	};

	Assert::match('%A%$var%A%', $latte->compile('{$var}'));
	Assert::match('%A%$var%A%', $latte->compile('{$var}'));

	Assert::same([
		'adding macros 1',
		'adding macros 2',
	], Notes::fetch());
});


test('', function () {
	$callbacks = [];
	$callbacks[] = function (Latte\Engine $engine) {
		Notes::add('adding macros 3');
	};

	$callbacks[] = function (Latte\Engine $engine) {
		Notes::add('adding macros 4');
	};

	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader);
	$latte->onCompile = new ArrayIterator($callbacks);

	Assert::match('%A%$var%A%', $latte->compile('{$var}'));
	Assert::match('%A%$var%A%', $latte->compile('{$var}'));

	Assert::same([
		'adding macros 3',
		'adding macros 4',
	], Notes::fetch());
});


test('', function () {
	class EventObj implements IteratorAggregate
	{
		public $events;


		public function __construct($events)
		{
			$this->events = $events;
		}


		public function getIterator()
		{
			return new ArrayIterator($this->events);
		}
	}

	$callbacks = [];
	$callbacks[] = function (Latte\Engine $engine) {
		Notes::add('adding macros 5');
	};

	$callbacks[] = function (Latte\Engine $engine) {
		Notes::add('adding macros 6');
	};


	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader);
	$latte->onCompile = new EventObj($callbacks);

	Assert::match('%A%$var%A%', $latte->compile('{$var}'));
	Assert::match('%A%$var%A%', $latte->compile('{$var}'));

	Assert::same([
		'adding macros 5',
		'adding macros 6',
	], Notes::fetch());
});
