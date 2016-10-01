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
class FileLoader implements Latte\ILoader
{
	use Latte\Strict;

	/** @var string|NULL */
	private $baseDir;


	public function __construct($baseDir = NULL)
	{
		$this->baseDir = $baseDir ? $this->normalizePath("$baseDir/") : NULL;
	}


	/**
	 * Returns template source code.
	 * @return string
	 */
	public function getContent($file)
	{
		$file = $this->baseDir . $file;
		if ($this->baseDir && !Latte\Helpers::startsWith($this->normalizePath($file), $this->baseDir)) {
			throw new \RuntimeException("Template '$file' is not within the allowed path '$this->baseDir'.");

		} elseif (!is_file($file)) {
			throw new \RuntimeException("Missing template file '$file'.");

		} elseif ($this->isExpired($file, time())) {
			if (@touch($file) === FALSE) {
				trigger_error("File's modification time is in the future. Cannot update it: " . error_get_last()['message'], E_USER_WARNING);
			}
		}
		return file_get_contents($file);
	}


	/**
	 * @return bool
	 */
	public function isExpired($file, $time)
	{
		return @filemtime($this->baseDir . $file) > $time; // @ - stat may fail
	}


	/**
	 * Returns referred template name.
	 * @return string
	 */
	public function getReferredName($file, $referringFile)
	{
		if ($this->baseDir || !preg_match('#/|\\\\|[a-z][a-z0-9+.-]*:#iA', $file)) {
			$file = $this->normalizePath($referringFile . '/../' . $file);
		}
		return $file;
	}


	/**
	 * Returns unique identifier for caching.
	 * @return string
	 */
	public function getUniqueId($file)
	{
		return $this->baseDir . strtr($file, '/', DIRECTORY_SEPARATOR);
	}


	/**
	 * @return string
	 */
	private static function normalizePath($path)
	{
		$res = $prefixes = [];
		foreach (explode('/', strtr($path, '\\', '/')) as $part) {
			if ($part === '..') {
				if ($res) {
					array_pop($res);
				} else {
					$prefixes[] = $part;
				}
			} elseif ($part !== '.') {
				$res[] = $part;
			}
		}
		return implode(DIRECTORY_SEPARATOR, array_merge($prefixes, $res));

	}

}
