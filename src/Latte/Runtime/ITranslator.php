<?php
/**
 * This file is part of the the Latte (http://latte.nette.org)
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 */
namespace Latte\Runtime;
/**
 * Translator adapter.
 */
interface ITranslator
{
	/**
	 * Translates the given string.
	 * @param  string   message
	 * @param  int      plural count
	 * @return string
	 */
	function translate($message, $count = NULL);
}
