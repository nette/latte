<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Sandbox;

use Latte;


class Policy implements Latte\Policy
{
	use Latte\Strict;

	public const ALL = ['*'];

	/** @var string[] */
	private $macros = [];

	/** @var string[] */
	private $filters = [];

	/** @var string[] */
	private $functions = [];

	/** @var string[][] */
	private $methods = [];

	/** @var string[][] */
	private $properties = [];


	public function allowMacros(array $macros): void
	{
		$this->macros += array_flip(array_map('strtolower', $macros));
	}


	public function allowFilters(array $filters): void
	{
		$this->filters += array_flip(array_map('strtolower', $filters));
	}


	public function allowFunctions(array $functions): void
	{
		$this->functions += array_flip(array_map('strtolower', $functions));
	}


	public function allowMethods(string $class, array $methods): void
	{
		$this->methods[$class] = array_flip(array_map('strtolower', $methods));
	}


	public function allowProperties(string $class, array $properties): void
	{
		$this->properties[$class] = array_flip(array_map('strtolower', $properties));
	}


	public function isMacroAllowed(string $macro): bool
	{
		return isset($this->macros[strtolower($macro)]) || isset($this->macros['*']);
	}


	public function isFilterAllowed(string $filter): bool
	{
		return isset($this->filters[strtolower($filter)]) || isset($this->filters['*']);
	}


	public function isFunctionAllowed(string $function): bool
	{
		return isset($this->functions[strtolower($function)]) || isset($this->functions['*']);
	}


	public function isMethodAllowed(string $class, string $method): bool
	{
		$method = strtolower($method);
		foreach ($this->methods as $c => $methods) {
			if (is_a($class, $c, true) && (isset($methods[$method]) || isset($methods['*']))) {
				return true;
			}
		}
		return false;
	}


	public function isPropertyAllowed(string $class, string $property): bool
	{
		$property = strtolower($property);
		foreach ($this->properties as $c => $properties) {
			if (is_a($class, $c, true) && (isset($properties[$property]) || isset($properties['*']))) {
				return true;
			}
		}
		return false;
	}
}
