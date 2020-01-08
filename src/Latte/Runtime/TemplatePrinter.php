<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;

use Latte;
use Nette\PhpGenerator as Php;


/**
 * Generates blueprint of template class.
 */
class TemplatePrinter
{
	use Latte\Strict;

	public function print(Template $template, string $name = null): Php\PhpNamespace
	{
		if (!class_exists(Php\ClassType::class)) {
			throw new \LogicException('Nette PhpGenerator is required to print template, install package `nette/php-generator`.');
		}

		$name = $name ?: 'Template';
		$namespace = new Php\PhpNamespace(Php\Helpers::extractNamespace($name));
		$class = $namespace->addClass(Php\Helpers::extractShortName($name));

		$this->addProperties($class, $template->getParameters(), true);
		$this->addProperties($class, $template->getParameters(), false);
		$this->addFunctions($class, (array) $template->global->fn);

		return $namespace;
	}


	public function addProperties(Php\ClassType $class, array $props, bool $magic): void
	{
		$printer = new Php\Printer;
		foreach ($props as $name => $value) {
			$type = Php\Type::getType($value);
			$doctype = $printer->printType($type, false, $class->getNamespace()) ?: 'mixed';
			if ($magic) {
				$class->addComment("@property $doctype $$name");
			} else {
				$prop = $class->addProperty($name);
				if (PHP_VERSION_ID >= 70400) {
					$prop->setType($type);
				} else {
					$prop->setComment("@var $doctype");
				}
			}
		}
	}


	public function addFunctions(Php\ClassType $class, array $funcs): void
	{
		$printer = new Php\Printer;
		foreach ($funcs as $name => $func) {
			$method = (new Php\Factory)->fromCallable($func);
			$type = $printer->printType($method->getReturnType(), $method->isReturnNullable(), $class->getNamespace()) ?: 'mixed';
			$class->addComment("@method $type $name" . $printer->printParameters($method, $class->getNamespace()));
		}
	}
}
