<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte;

use LogicException;


/**
 * Object is the ultimate ancestor of all instantiable classes.
 */
abstract class Object
{

	/**
	 * Call to undefined method.
	 * @throws LogicException
	 */
	public function __call($name, $args)
	{
		$class = method_exists($this, $name) ? 'parent' : get_class($this);
		$rc = new \ReflectionClass($this);
		$hint = ($t = Helpers::getSuggestion($rc->getMethods(\ReflectionMethod::IS_PUBLIC), $name)) ? ", did you mean $t()?" : '.';
		throw new LogicException("Call to undefined method $class::$name()$hint");
	}


	/**
	 * Call to undefined static method.
	 * @throws LogicException
	 */
	public static function __callStatic($name, $args)
	{
		$rc = new \ReflectionClass(get_called_class());
		$items = array_intersect($rc->getMethods(\ReflectionMethod::IS_PUBLIC), $rc->getMethods(\ReflectionMethod::IS_STATIC));
		$hint = ($t = Helpers::getSuggestion($items, $name)) ? ", did you mean $t()?" : '.';
		throw new LogicException("Call to undefined static method {$rc->getName()}::$name()$hint");
	}


	/**
	 * Access to undeclared property.
	 * @throws LogicException
	 */
	public function &__get($name)
	{
		$rc = new \ReflectionClass($this);
		$items = array_diff($rc->getProperties(\ReflectionProperty::IS_PUBLIC), $rc->getProperties(\ReflectionProperty::IS_STATIC));
		$hint = ($t = Helpers::getSuggestion($items, $name)) ? ", did you mean $$t?" : '.';
		throw new LogicException("Attempt to read undeclared property {$rc->getName()}::$$name$hint");
	}


	/**
	 * Access to undeclared property.
	 * @throws LogicException
	 */
	public function __set($name, $value)
	{
		$rc = new \ReflectionClass($this);
		$items = array_diff($rc->getProperties(\ReflectionProperty::IS_PUBLIC), $rc->getProperties(\ReflectionProperty::IS_STATIC));
		$hint = ($t = Helpers::getSuggestion($items, $name)) ? ", did you mean $$t?" : '.';
		throw new LogicException("Attempt to write to undeclared property {$rc->getName()}::$$name$hint");
	}


	/**
	 * @return bool
	 */
	public function __isset($name)
	{
		return FALSE;
	}


	/**
	 * Access to undeclared property.
	 * @throws LogicException
	 */
	public function __unset($name)
	{
		$class = get_class($this);
		throw new LogicException("Attempt to unset undeclared property $class::$$name.");
	}

}
