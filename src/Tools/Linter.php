<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Tools;

use Latte;
use Nette;
use function in_array, strlen;
use const PHP_BINARY, STDERR;


final class Linter
{
	/** @var string[] */
	public array $excludedDirs = ['.*', '*.tmp', 'temp', 'vendor', 'node_modules'];


	public function __construct(
		private ?Latte\Engine $engine = null,
		private bool $debug = false,
		private bool $strict = false,
	) {
	}


	public function scanDirectory(string $path): bool
	{
		echo "Scanning $path\n";
		return $this->scanFiles($this->getFiles($path));
	}


	/**
	 * @param  iterable<\Stringable>  $files
	 */
	public function scanFiles(iterable $files): bool
	{
		$this->initialize();

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

		if (class_exists(Nette\Bridges\AssetsLatte\LatteExtension::class)) {
			$engine->addExtension(new Nette\Bridges\AssetsLatte\LatteExtension(new Nette\Assets\Registry));
		}

		$engine->addExtension(new LinterExtension);

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
				$label = $severity === E_USER_DEPRECATED ? 'DEPRECATED' : 'WARNING';
				$this->writeError($label, $file . $pos, $message);
				return null;
			}
			return false;
		});

		if ($this->debug) {
			echo $file, "\n";
		}
		$s = file_get_contents($file);
		if (substr($s, 0, 3) === "\xEF\xBB\xBF") {
			$this->writeError('WARNING', $file, 'contains BOM');
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
			$this->writeError('ERROR', $file . $pos, $e->getMessage());
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
		$it = match (true) {
			is_file($path) => new \ArrayIterator([$path]),
			is_dir($path) => $this->findLatteFiles($path),
			preg_match('~[*?]~', $path) => new \GlobIterator($path),
			default => throw new \InvalidArgumentException("File or directory '$path' not found."),
		};
		$it = new \CallbackFilterIterator($it, fn($file) => is_file((string) $file));
		return $it;
	}


	private function findLatteFiles(string $dir): \Generator
	{
		foreach (scandir($dir) as $name) {
			$path = ($dir === '.' ? '' : $dir . DIRECTORY_SEPARATOR) . $name;
			if ($name !== '.' && $name !== '..' && is_dir($path)) {
				foreach ($this->excludedDirs as $pattern) {
					if (fnmatch($pattern, $name)) {
						continue 2;
					}
				}
				yield from $this->findLatteFiles($path);

			} elseif (str_ends_with($name, '.latte')) {
				yield $path;
			}
		}
	}


	private function writeError(string $label, string $file, string $message): void
	{
		fwrite(STDERR, str_pad("[$label]", 13) . ' ' . $file . '    ' . $message . "\n");
	}
}
