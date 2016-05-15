<?php

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/Policy.LogPolicy.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->setPolicy(new LogPolicy);
$latte->setSandboxMode();

$template = <<<'EOD'
{global()}
{='global'()}
{$global()}
{($global)()}
{('gl' . 'obal')()}
{=\global()}
{=ns\global()}

{['a', 'b']()}

{=MyClass::method()}
{=\Name\MyClass :: method()}
{=\Name\MyClass::{('method')}()}
{=\Name\MyClass::$method()}
{=\Name\MyClass::$prop}

{=$obj -> method()}
{=$obj->{'method'}()}
{=$obj->$method()}
{=$obj::method()}

{=${'var'}()}
{=$ $var()}
{=$var[ '' . change( 10 + inner() ) ]->method()}
{=$var[ 0 + 1]->method()}

{=$obj -> prop}
{=$obj->{"prop"}}
{=$obj->$prop}
{=$obj->$$prop}
{=$obj->prop->prop}
{=$obj->prop[$x]->prop}
{=$obj->prop[$x]->method()}

{=$obj->bar++}
{=$obj::$static++}

{=$obj?->bar?}
{=$obj?::$static?}
EOD;


Assert::matchFile(
	__DIR__ . '/expected/PhpWriter.sandboxPass.phtml',
	$latte->compile($template)
);
