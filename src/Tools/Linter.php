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
	use Latte\Strict;

	/** @var bool */
	private $debug;

	/** @var Latte\Engine|null */
	private $engine;


	public function __construct(?Latte\Engine $engine = null, bool $debug = false)
	{
		$this->engine = $engine;
		$this->debug = $debug;
	}


	public function scanDirectory(string $dir): bool
	{
		echo "Scanning $dir\n";

		$it = new \RecursiveDirectoryIterator($dir);
		$it = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::LEAVES_ONLY);
		$it = new \RegexIterator($it, '~\.latte$~');

		$this->engine ??= $this->createEngine();
		$this->engine->setLoader(new Latte\Loaders\StringLoader);

		$counter = 0;
		$success = true;
		foreach ($it as $file) {
			echo str_pad(str_repeat('.', $counter++ % 40), 40), "\x0D";
			$success = $this->lintLatte((string) $file) && $success;
		}

		echo str_pad('', 40), "\x0D";
		echo "Done.\n";
		return $success;
	}


	private function createEngine(): Latte\Engine
	{
		$engine = new Latte\Engine;

		if (class_exists(Nette\Bridges\CacheLatte\CacheMacro::class)) {
			$engine->getCompiler()->addMacro('cache', new Nette\Bridges\CacheLatte\CacheMacro);
		}

		if (class_exists(Nette\Bridges\ApplicationLatte\UIMacros::class)) {
			Nette\Bridges\ApplicationLatte\UIMacros::install($engine->getCompiler());
		}

		if (class_exists(Nette\Bridges\FormsLatte\FormMacros::class)) {
			Nette\Bridges\FormsLatte\FormMacros::install($engine->getCompiler());
		}

		return $engine;
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
			$code = $this->engine->compile($s);

		} catch (Latte\CompileException $e) {
			if ($this->debug) {
				echo $e;
			}
			$pos = $e->sourceLine ? ':' . $e->sourceLine : '';
			fwrite(STDERR, "[ERROR]      $file$pos    {$e->getMessage()}\n");
			return false;

		} finally {
			restore_error_handler();
		}

		if ($error = $this->lintPHP($code)) {
			fwrite(STDERR, "[ERROR]      $file    $error\n");
			return false;
		}

		return true;
	}


	private function lintPHP(string $code): ?string
	{
		$php = defined('PHP_BINARY') ? PHP_BINARY : 'php';
		$stdin = tmpfile();
		fwrite($stdin, $code);
		fseek($stdin, 0);
		$process = proc_open(
			$php . ' -l -d display_errors=1',
			[$stdin, ['pipe', 'w'], ['pipe', 'w']],
			$pipes,
			null,
			null,
			['bypass_shell' => true],
		);
		if (!is_resource($process)) {
			return 'Unable to lint PHP code';
		}
		$error = stream_get_contents($pipes[1]);
		if (proc_close($process)) {
			return strip_tags(explode("\n", $error)[1]);
		}
		return null;
	}
}
