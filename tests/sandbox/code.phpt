<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/Policy.Logger.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->setPolicy(new PolicyLogger);
$latte->setSandboxMode();


// vars - no checks
$template = <<<'EOD'
	vars

	{=$var['x']}
	{=$var[ '' . change( 10 + inner() ) ]->prop}
	{=$var[ 0 + 1]->method()}
	-
	EOD;

Assert::matchFile(
	__DIR__ . '/expected/code.vars.php',
	$latte->compile($template),
);


// functions
$template = <<<'EOD'
	functions

	{func()}     {* compile-time check *}
	{='func'()}
	{('fu' . 'nc')()}
	{=\func()}   {* compile-time check *}
	{=ns\func()} {* compile-time check *}
	{func()->prop}
	{func()()}
	{func( $a->prop )( func() )}
	{func()['x']()}
	-
	EOD;

Assert::matchFile(
	__DIR__ . '/expected/code.functions.php',
	$latte->compile($template),
);


// callbacks
$template = <<<'EOD'
	callbacks

	{$var()}
	{($var)()}
	{['a', 'b']()}
	{['trim'][0]()}
	-
	EOD;

Assert::matchFile(
	__DIR__ . '/expected/code.callbacks.php',
	$latte->compile($template),
);



// static methods
$template = <<<'EOD'
	static methods

	{=MyClass::method()}
	{=\Name\MyClass :: method()}
	{=\Name\MyClass::{('method')}()}
	{=\Name\MyClass::$method()}
	-
	EOD;

Assert::matchFile(
	__DIR__ . '/expected/code.static.methods.php',
	$latte->compile($template),
);


// static props
$template = <<<'EOD'
	static props

	{=\Name\MyClass::$prop}
	{=\Name\MyClass::$prop->x}
	{=\Name\MyClass::$prop[1]}
	{=\Name\MyClass::$prop[1]->x}
	-
	EOD;

Assert::matchFile(
	__DIR__ . '/expected/code.static.props.php',
	$latte->compile($template),
);


// consts
$template = <<<'EOD'
	consts

	{=\Name\MyClass::CONST}
	{=$obj::CONST}
	{=$obj::CONST}
	{=($obj::CONST)()}
	{=$obj::CONST[0]()}
	{=CONST[0]()}
	-
	EOD;

Assert::matchFile(
	__DIR__ . '/expected/code.consts.php',
	$latte->compile($template),
);


// object methods
$template = <<<'EOD'
	object methods

	{=$obj -> method()}
	{=$obj->{'method'}()}
	{=$obj->$method()}
	{=$obj::method()}
	{=$obj->method()()}
	{=$obj->method()->prop}
	{=$obj::method()->prop}
	-
	EOD;

Assert::matchFile(
	__DIR__ . '/expected/code.object.methods.php',
	$latte->compile($template),
);


// props
$template = <<<'EOD'
	props

	{=$obj -> prop}
	{=$obj->prop->prop}
	{=$obj->prop->$prop}
	{=$obj->prop->{"prop"}}
	{=$obj->prop[$x]}
	{=$obj->prop[($x)]}
	{=$obj->prop[$x]->prop}
	{=$obj->prop['x']->$prop}
	{=$obj->prop['x']->{"prop"}}
	{=$obj->prop['x']['y']->prop}
	{=$obj->prop['x']->method()}
	{=$obj->{"prop"}}
	{=$obj->{"prop"}->prop}
	{=$obj->$prop}
	{=$obj->$prop[$x]}
	-
	EOD;

Assert::matchFile(
	__DIR__ . '/expected/code.props.php',
	$latte->compile($template),
);


// read-write
$template = <<<'EOD'
	read-write

	{=$obj->bar++}
	{=$obj::$static++}
	-
	EOD;

Assert::matchFile(
	__DIR__ . '/expected/code.read-write.php',
	$latte->compile($template),
);


// optional chaining
$template = <<<'EOD'
	optional chaining

	{=$obj?->prop}
	{=$obj??->prop}
	{=$obj?->bar()}
	{=$obj??->bar()}
	-
	EOD;

Assert::matchFile(
	__DIR__ . '/expected/code.optional-chaining.php',
	$latte->compile($template),
);


// firstclass callable
$template = <<<'EOD'
	firstclass callable

	{=trim(...)}
	{=$obj->foo(...)}
	{=$obj::foo(...)}
	-
	EOD;

Assert::matchFile(
	__DIR__ . '/expected/code.callable.php',
	$latte->compile($template),
);
