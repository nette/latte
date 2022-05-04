<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;


interface Exception
{
}


/**
 * The exception occured during Latte compilation.
 */
class CompileException extends \Exception implements Exception
{
	public string $sourceCode;
	public string $sourceName;
	public ?int $sourceLine = null;


	public function setSource(string $code, ?int $line, ?string $name = null): self
	{
		$this->sourceCode = $code;
		$this->sourceLine = $line;
		$this->sourceName = $name;
		if (@is_file($name)) { // @ - may trigger error
			$this->message = rtrim($this->message, '.')
				. ' in ' . str_replace(dirname($name, 2), '...', $name) . ($line ? ":$line" : '');
		} elseif ($line > 1) {
			$this->message = rtrim($this->message, '.') . ' (on line ' . $line . ')';
		}

		return $this;
	}
}


/**
 * The exception that indicates error of the last Regexp execution.
 */
class RegexpException extends \Exception implements Exception
{
	public function __construct()
	{
		parent::__construct(preg_last_error_msg(), preg_last_error());
	}
}


/**
 * Exception thrown when a not allowed construction is used in a template.
 */
class SecurityViolationException extends \Exception implements Exception
{
}


class RuntimeException extends \RuntimeException implements Exception
{
}
