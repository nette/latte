<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 */

namespace Latte\Macros;

use Latte,
	Latte\RuntimeException;


/**
 * Runtime helpers for block macros.
 *
 * @author     David Grudl
 */
class BlockMacrosRuntime extends Latte\Object
{

	/**
	 * Calls block.
	 * @return void
	 */
	public static function callBlock(\stdClass $context, $name, array $params)
	{
		if (empty($context->blocks[$name])) {
			throw new RuntimeException("Cannot include undefined block '$name'.");
		}
		$block = reset($context->blocks[$name]);
		$block($context, $params);
	}


	/**
	 * Calls parent block.
	 * @return void
	 */
	public static function callBlockParent(\stdClass $context, $name, array $params)
	{
		if (empty($context->blocks[$name]) || ($block = next($context->blocks[$name])) === FALSE) {
			throw new RuntimeException("Cannot include undefined parent block '$name'.");
		}
		$block($context, $params);
		prev($context->blocks[$name]);
	}

}
