<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 */

namespace Latte\Loaders;

use Latte;


/**
 * Template loader.
 *
 * @author     David Grudl
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
			touch($file);
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
