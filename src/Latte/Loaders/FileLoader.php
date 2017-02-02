<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

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
	 */
	public function getContent($file): string
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


	public function isExpired($file, $time): bool
	{
		return @filemtime($this->baseDir . $file) > $time; // @ - stat may fail
	}


	/**
	 * Returns referred template name.
	 */
	public function getReferredName($file, $referringFile): string
	{
		if ($this->baseDir || !preg_match('#/|\\\\|[a-z][a-z0-9+.-]*:#iA', $file)) {
			$file = $this->normalizePath($referringFile . '/../' . $file);
		}
		return $file;
	}


	/**
	 * Returns unique identifier for caching.
	 */
	public function getUniqueId($file): string
	{
		return $this->baseDir . strtr($file, '/', DIRECTORY_SEPARATOR);
	}


	private static function normalizePath($path): string
	{
		$res = [];
		foreach (explode('/', strtr($path, '\\', '/')) as $part) {
			if ($part === '..' && $res && end($res) !== '..') {
				array_pop($res);
			} elseif ($part !== '.') {
				$res[] = $part;
			}
		}
		return implode(DIRECTORY_SEPARATOR, $res);
	}

}
