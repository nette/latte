<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Tools;

use Latte;
use Nette;


final class Linter
{
	public function __construct(
		private ?Latte\Engine $engine = null,
		private bool $debug = false,
		private bool $strict = false,
	) {
	}


	public function scanDirectory(string $path): bool
	{
		$this->initialize();

		echo "Scanning $path\n";

		$files = $this->getFiles($path);
		$counter = 0;
		$errors = 0;
		foreach ($files as $file) {
			$file = (string) $file;
			echo preg_replace('~\.?[/\\\]~A', '', $file), "\x0D";
			$errors += $this->lintLatte($file) ? 0 : 1;
			echo str_pad('...', strlen($file)), "\x0D";
			$counter++;
		}

		echo "Done (checked $counter files, found errors in $errors)\n";
		return !$errors;
	}


	private function createEngine(): Latte\Engine
	{
		$engine = new Latte\Engine;
		$engine->enablePhpLinter(PHP_BINARY);
		$engine->setStrictParsing($this->strict);
		$engine->addExtension(new Latte\Essential\TranslatorExtension(null));

		if (class_exists(Nette\Bridges\ApplicationLatte\UIExtension::class)) {
			$engine->addExtension(new Nette\Bridges\ApplicationLatte\UIExtension(null));
		}

		if (class_exists(Nette\Bridges\CacheLatte\CacheExtension::class)) {
			$engine->addExtension(new Nette\Bridges\CacheLatte\CacheExtension(new Nette\Caching\Storages\DevNullStorage));
		}

		if (class_exists(Nette\Bridges\FormsLatte\FormsExtension::class)) {
			$engine->addExtension(new Nette\Bridges\FormsLatte\FormsExtension);
		}

		return $engine;
	}


	public function getEngine(): Latte\Engine
	{
		$this->engine ??= $this->createEngine();
		return $this->engine;
	}


	public function lintLatte(string $file): bool
	{
		set_error_handler(function (int $severity, string $message) use ($file) {
			if (in_array($severity, [E_USER_DEPRECATED, E_USER_WARNING, E_USER_NOTICE], true)) {
				$pos = preg_match('~on line (\d+)~', $message, $m) ? ':' . $m[1] : '';
				fwrite(STDERR, "[DEPRECATED] $file$pos    $message\n");
				return null;
			}
			return false;
		});

		if ($this->debug) {
			echo $file, "\n";
		}
		$s = file_get_contents($file);
		if (substr($s, 0, 3) === "\xEF\xBB\xBF") {
			fwrite(STDERR, "[WARNING]    $file    contains BOM\n");
		}

		try {
			$this->getEngine()
				->setLoader(new Latte\Loaders\StringLoader)
				->compile($s);

		} catch (Latte\CompileException $e) {
			if ($this->debug) {
				echo $e;
			}
			$pos = $e->position?->line ? ':' . $e->position->line : '';
			$pos .= $e->position?->column ? ':' . $e->position->column : '';
			fwrite(STDERR, "[ERROR]      $file$pos    {$e->getMessage()}\n");
			return false;

		} finally {
			restore_error_handler();
		}

		return true;
	}


	private function initialize(): void
	{
		if (function_exists('pcntl_signal')) {
			pcntl_signal(SIGINT, function (): void {
				pcntl_signal(SIGINT, SIG_DFL);
				echo "Terminated\n";
				exit(1);
			});
		} elseif (function_exists('sapi_windows_set_ctrl_handler')) {
			sapi_windows_set_ctrl_handler(function () {
				echo "Terminated\n";
				exit(1);
			});
		}

		set_time_limit(0);
	}


	private function getFiles(string $path): \Iterator
	{
		if (is_file($path)) {
			return new \ArrayIterator([$path]);

		} elseif (preg_match('~[*?]~', $path)) {
			return new \GlobIterator($path);

		} else {
			$it = new \RecursiveDirectoryIterator($path);
			$it = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::LEAVES_ONLY, \RecursiveIteratorIterator::CATCH_GET_CHILD);
			$it = new \RegexIterator($it, '~\.latte$~');
			return $it;
		}
	}
}
