<?php

/**
 * Test: invocation resolver
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$list = [];
$latte->getCompiler()->setInvocationResolver(function ($expr) use (&$list) {
	$list[] = $expr;
	if ($expr === 'error') {
		return;
	} elseif ($expr === 'change') {
		return 'A::b';
	} else {
		return $expr;
	}
});

// changed invocation
Assert::match(
	'%A%echo LR\\Filters::escapeHtmlText(A::b(123))%A%',
	$latte->compile('{change(123)}')
);
Assert::same(['change'], $list);

// forbidden invocation
Assert::exception(function () use ($latte) {
	$latte->compile('{error(123)}');
}, 'Latte\CompileException', "Calling 'error()' is not allowed.");


$list = [];
$template = <<<'EOD'
{global1()}

{=\global2()}

{=MyClass::method()}

{=\Name\MyClass :: method()}

{=$obj -> method()}

{=$obj::method()}

{=${'var'}()}

{=$ $var()}

{=$var[ '' . change( 10 + inner() ) ]->method()}

{=$var[ 0 + 1]->method()}
EOD;
$latte->compile($template);
Assert::same([
	'global1',
	'global2',
	'MyClass::method',
	'Name\MyClass::method',
	'$obj->method',
	'$obj::method',
	'${\'var\'}',
	'$$var',
	'change',
	'inner',
	'$var[\'\'.A::b(10+inner())]->method',
	'$var[0+1]->method',
], $list);
