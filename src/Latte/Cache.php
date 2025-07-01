<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;

use function array_map, defined, dirname, file_put_contents, filemtime, flock, fopen, fseek, ftell, ftruncate, function_exists, fwrite, is_dir, is_file, md5, mkdir, preg_match, preg_replace, rename, serialize, stream_get_contents, strlen, substr, unlink;
use const LOCK_EX, LOCK_SH, LOCK_UN;


/**
 * Caching of compiled templates.
 * @internal
 */
final class Cache
{
	public ?string $directory = null;
	public bool $autoRefresh = true;


	public function loadOrCreate(Engine $engine, string $name): void
	{
		// Solving atomicity to work everywhere is really pain in the ass.
		// 1) We want to do as little as possible IO calls on production and also directory and file can be not writable
		// so on Linux we include the file directly without shared lock, therefore, the file must be created atomically by renaming.
		// 2) On Windows file cannot be renamed-to while is open (ie by include), so we have to acquire a lock.
		$file = $engine->getCacheFile($name);
		$signature = $this->autoRefresh
			? md5(serialize($this->generateSignature($engine, $name)))
			: null;
		$lock = defined('PHP_WINDOWS_VERSION_BUILD') || $signature
			? $this->acquireLock("$file.lock", LOCK_SH)
			: null;

		if (
			!($signature && $signature !== stream_get_contents($lock))
			&& (@include $file) !== false // @ - file may not exist
		) {
			return;
		}

		if ($lock) {
			flock($lock, LOCK_UN); // release shared lock so we can get exclusive
			fseek($lock, 0);
		}

		$lock = $this->acquireLock("$file.lock", LOCK_EX);

		// while waiting for exclusive lock, someone might have already created the cache
		if (!is_file($file) || ($signature && $signature !== stream_get_contents($lock))) {
			$compiled = $engine->compile($name);
			if (
				file_put_contents("$file.tmp", $compiled) !== strlen($compiled)
				|| !rename("$file.tmp", $file)
			) {
				@unlink("$file.tmp"); // @ - file may not exist
				throw new RuntimeException("Unable to create '$file'.");
			}

			fseek($lock, 0);
			fwrite($lock, $signature ?? md5(serialize($this->generateSignature($engine, $name))));
			ftruncate($lock, ftell($lock));

			if (function_exists('opcache_invalidate')) {
				@opcache_invalidate($file, true); // @ can be restricted
			}
		}

		if ((include $file) === false) {
			throw new RuntimeException("Unable to load '$file'.");
		}

		flock($lock, LOCK_UN);
	}


	/** @return resource */
	private function acquireLock(string $file, int $mode)
	{
		$dir = dirname($file);
		if (!is_dir($dir) && !@mkdir($dir) && !is_dir($dir)) { // @ - dir may already exist
			throw new RuntimeException("Unable to create directory '$dir'. " . error_get_last()['message']);
		}

		$handle = @fopen($file, 'c+'); // @ is escalated to exception
		if (!$handle) {
			throw new RuntimeException("Unable to create file '$file'. " . error_get_last()['message']);
		} elseif (!@flock($handle, $mode)) { // @ is escalated to exception
			throw new RuntimeException('Unable to acquire ' . ($mode & LOCK_EX ? 'exclusive' : 'shared') . " lock on file '$file'. " . error_get_last()['message']);
		}

		return $handle;
	}


	public function generateFileName(string $name, string $hash): string
	{
		$base = preg_match('#([/\\\][\w@.-]{3,35}){1,3}$#D', '/' . $name, $m)
			? preg_replace('#[^\w@.-]+#', '-', substr($m[0], 1))
			: '';
		if (!str_ends_with($base, 'latte')) {
			$base .= 'latte';
		}
		return $this->directory . '/' . $base . '--' . $hash . '.php';
	}


	/**
	 * Values that check the expiration of the compiled template.
	 */
	protected function generateSignature(Engine $engine, string $name): array
	{
		return [
			Engine::Version,
			$engine->getLoader()->getContent($name),
			array_map(
				fn($extension) => filemtime((new \ReflectionObject($extension))->getFileName()),
				$engine->getExtensions(),
			),
		];
	}
}
