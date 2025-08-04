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
