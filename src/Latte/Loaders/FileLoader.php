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
class FileLoader extends Latte\Object implements Latte\ILoader
{

	/**
	 * Returns template source code.
	 * @return string
	 */
	public function getContent($file)
	{
		if (!is_file($file)) {
			throw new \RuntimeException("Missing template file '$file'.");

		} elseif ($this->isExpired($file, time())) {
			if (@touch($file) === FALSE) {
				$tmp = error_get_last();
				trigger_error("File's modification time is in the future. Cannot update it: $tmp[message]", E_USER_WARNING);
			}
		}
		return file_get_contents($file);
	}


	/**
	 * @return bool
	 */
	public function isExpired($file, $time)
	{
		return @filemtime($file) > $time; // @ - stat may fail
	}


	/**
	 * Returns fully qualified template name.
	 * @return string
	 */
	public function getChildName($file, $parent = NULL)
	{
		if ($parent && !preg_match('#/|\\\\|[a-z][a-z0-9+.-]*:#iA', $file)) {
			$file = dirname($parent) . '/' . $file;
		}
		return $file;
	}

}
