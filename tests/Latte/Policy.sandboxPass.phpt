<?php

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class Policy implements Latte\Policy
{
	public function isMacroAllowed(string $macro): bool
	{
		return true;
	}


	public function isFilterAllowed(string $filter): bool
	{
		return true;
	}


	public function isFunctionAllowed(string $function): bool
	{
		return true;
	}


	public function isMethodAllowed(string $class, string $method): bool
	{
		return true;
	}


	public function isPropertyAllowed(string $class, string $property): bool
	{
		return true;
	}
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->setPolicy(new Policy);
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

EOD;


Assert::matchFile(
	__DIR__ . '/expected/PhpWriter.sandboxPass.phtml',
	$latte->compile($template)
);
