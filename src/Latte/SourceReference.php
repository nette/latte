<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;


/**
 * Reference to template source code with optional position information.
 */
class SourceReference
{
	/**
	 * Attempts to map the compiled template file to the source name and position.
	 */
	public static function fromCompiled(string $file, ?int $line = null): ?self
	{
		if (Cache::isCacheFile($file)
			&& ($content = file_get_contents($file))
			&& preg_match('#^/\*\* source: (\S.+) \*/#m', $content, $source)
		) {
			$line && preg_match('~/\* line (\d+)(?::(\d+))? \*/~', explode("\n", $content)[$line - 1], $pos);
			return new self($source[1], isset($pos[1]) ? (int) $pos[1] : null, isset($pos[2]) ? (int) $pos[2] : null);
		}
		return null;
	}


	/** Tries to guess the position in the template from the backtrace */
	public static function fromCallStack(): ?self
	{
		$trace = debug_backtrace();
		foreach ($trace as $item) {
			if (isset($item['file']) && ($source = self::fromCompiled($item['file'], $item['line']))) {
				return $source;
			}
		}
		return null;
	}


	public function __construct(
		public ?string $name,
		public readonly ?int $line = null,
		public readonly ?int $column = null,
		public readonly ?string $code = null,
	) {
		$this->name = str_contains($name ?? '', "\n") ? null : $name; // caused by StringLoader
	}


	public function isFile(): bool
	{
		return $this->name && @is_file($this->name); // @ - may trigger error
	}


	public function getCode(): ?string
	{
		if ($this->code) {
			return $this->code;
		} elseif ($this->isFile()) {
			file_get_contents($this->name);
		}
		return null;
	}


	public function __toString(): string
	{
		$res = '';
		if ($this->isFile()) {
			$res = "in '" . str_replace(dirname($this->name, 2), '...', $this->name) . "'";
		}

		if ($this->line) {
			$res .= ($res ? ' ' : '') . 'on line ' . $this->line . ($this->column ? ' at column ' . $this->column : '');
		}
		return $res;
	}
}
