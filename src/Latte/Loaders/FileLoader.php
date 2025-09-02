<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Loaders;

use Latte;
use function array_pop, end, explode, file_get_contents, implode, is_file, preg_match, str_starts_with, strtr, time, touch;
use const DIRECTORY_SEPARATOR;


/**
 * Template loader.
 */
class FileLoader implements Latte\Loader
{
	protected ?string $baseDir = null;


	public function __construct(?string $baseDir = null)
	{
		$this->baseDir = $baseDir ? $this->normalizePath("$baseDir/") : null;
	}


	public function load(string $file): Latte\LoadedContent
	{
		$path = $this->baseDir . $file;
		if ($this->baseDir && !str_starts_with($this->normalizePath($path), $this->baseDir)) {
			throw new Latte\RuntimeException("Template '$path' is not within the allowed path '{$this->baseDir}'.");

		} elseif (!is_file($path)) {
			throw new Latte\TemplateNotFoundException("Missing template file '$path'.");
		}

		return new Latte\LoadedContent(file_get_contents($path));
	}


	/**
	 * Returns referred template name.
	 */
	public function getReferredName(string $file, string $referringFile): string
	{
		if ($this->baseDir || !preg_match('#/|\\\|[a-z]:|phar:#iA', $file)) {
			$file = $this->normalizePath($referringFile . '/../' . $file);
		}

		return $file;
	}


	/**
	 * Returns unique identifier for caching.
	 */
	public function getUniqueId(string $file): string
	{
		return $this->baseDir . strtr($file, '/', DIRECTORY_SEPARATOR);
	}


	protected static function normalizePath(string $path): string
	{
		preg_match('#^([a-z]:|phar://.+?/)?(.*)#i', $path, $m);
		$res = [];
		foreach (explode('/', strtr($m[2], '\\', '/')) as $part) {
			if ($part === '..' && $res && end($res) !== '..' && end($res) !== '') {
				array_pop($res);
			} elseif ($part !== '.') {
				$res[] = $part;
			}
		}

		return $m[1] . implode(DIRECTORY_SEPARATOR, $res);
	}
}
