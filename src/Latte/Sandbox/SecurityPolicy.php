<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Sandbox;

use Latte;


/**
 * Default-deny policy.
 */
class SecurityPolicy implements Latte\Policy
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

	/** @var array */
	private $methodCache = [];

	/** @var array */
	private $propertyCache = [];


	public static function createSafePolicy(): self
	{
		$policy = new self;

		// does not include: contentType, debugbreak, dump, extends, import, include, includeblock, layout,
		// php (but 'do' is allowed), sandbox, snippet, snippetArea, templatePrint, varPrint
		$policy->allowMacros([
			'_', '=', 'attr', 'block', 'breakIf', 'capture', 'case', 'class', 'continueIf', 'default',
			'define', 'do', 'else', 'elseif', 'elseifset', 'first', 'for', 'foreach', 'if', 'ifcontent',
			'ifset', 'l', 'last', 'r', 'sep', 'spaceless', 'switch', 'templateType', 'var', 'varType', 'while',
		]);

		// does not include: dataStream, noEscape, noCheck
		$policy->allowFilters([
			'batch', 'breakLines', 'bytes', 'capitalize', 'date', 'escapeCss', 'escapeHtml', 'escapeHtmlComment', 'escapeICal',
			'escapeJs', 'escapeUrl', 'escapeXml', 'firstUpper', 'checkUrl', 'implode', 'indent', 'length', 'lower', 'number',
			'padLeft', 'padRight', 'repeat', 'replace', 'replaceRe', 'reverse', 'strip', 'stripHtml', 'stripTags', 'substr',
			'trim', 'truncate', 'upper', 'webalize',
		]);

		$policy->allowMethods(Latte\Runtime\CachingIterator::class, self::ALL);
		$policy->allowProperties(Latte\Runtime\CachingIterator::class, self::ALL);

		return $policy;
	}


	public function allowMacros(array $macros): self
	{
		$this->macros += array_flip(array_map('strtolower', $macros));
		return $this;
	}


	public function allowFilters(array $filters): self
	{
		$this->filters += array_flip(array_map('strtolower', $filters));
		return $this;
	}


	public function allowFunctions(array $functions): self
	{
		$this->functions += array_flip(array_map('strtolower', $functions));
		return $this;
	}


	public function allowMethods(string $class, array $methods): self
	{
		$this->methodCache = [];
		$this->methods[$class] = array_flip(array_map('strtolower', $methods));
		return $this;
	}


	public function allowProperties(string $class, array $properties): self
	{
		$this->propertyCache = [];
		$this->properties[$class] = array_flip(array_map('strtolower', $properties));
		return $this;
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
		$res = &$this->methodCache[$class][$method];
		if (isset($res)) {
			return $res;
		}
		foreach ($this->methods as $c => $methods) {
			if (is_a($class, $c, true) && (isset($methods[$method]) || isset($methods['*']))) {
				return $res = true;
			}
		}
		return $res = false;
	}


	public function isPropertyAllowed(string $class, string $property): bool
	{
		$property = strtolower($property);
		$res = &$this->propertyCache[$class][$property];
		if (isset($res)) {
			return $res;
		}
		foreach ($this->properties as $c => $properties) {
			if (is_a($class, $c, true) && (isset($properties[$property]) || isset($properties['*']))) {
				return $res = true;
			}
		}
		return $res = false;
	}
}
