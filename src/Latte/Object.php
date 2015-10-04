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
		throw new LogicException(sprintf('Call to undefined method %s::%s().', $class, $name));
	}


	/**
	 * Call to undefined static method.
	 * @throws LogicException
	 */
	public static function __callStatic($name, $args)
	{
		throw new LogicException(sprintf('Call to undefined static method %s::%s().', get_called_class(), $name));
	}


	/**
	 * Access to undeclared property.
	 * @throws LogicException
	 */
	public function &__get($name)
	{
		throw new LogicException(sprintf('Attempt to read undeclared property %s::$%s.', get_class($this), $name));
	}


	/**
	 * Access to undeclared property.
	 * @throws LogicException
	 */
	public function __set($name, $value)
	{
		throw new LogicException(sprintf('Attempt to write to undeclared property %s::$%s.', get_class($this), $name));
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
		throw new LogicException(sprintf('Attempt to unset undeclared property %s::$%s.', get_class($this), $name));
	}

}
