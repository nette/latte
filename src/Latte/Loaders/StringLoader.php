<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Loaders;

use Latte;


/**
 * Template loader.
 */
class StringLoader implements Latte\ILoader
{
	use Latte\Strict;

	/** @var array|NULL [name => content] */
	private $templates;


	public function __construct(array $templates = NULL)
	{
		$this->templates = $templates;
	}


	/**
	 * Returns template source code.
	 * @return string
	 */
	public function getContent($name)
	{
		if ($this->templates === NULL) {
			return $name;
		} elseif (isset($this->templates[$name])) {
			return $this->templates[$name];
		} else {
			throw new \RuntimeException("Missing template '$name'.");
		}
	}


	/**
	 * @return bool
	 */
	public function isExpired($name, $time)
	{
		return FALSE;
	}


	/**
	 * Returns referred template name.
	 * @return string
	 */
	public function getReferredName($name, $referringName)
	{
		if ($this->templates === NULL) {
			throw new \LogicException("Missing template '$name'.");
		}
		return $name;
	}


	/**
	 * Returns unique identifier for caching.
	 * @return string
	 */
	public function getUniqueId($name)
	{
		return $this->getContent($name);
	}

}
