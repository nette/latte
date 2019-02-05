<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;


/**
 * The exception occured during Latte compilation.
 */
class CompileException extends \Exception
{
	/** @var string */
	public $sourceCode;

	/** @var string */
	public $sourceName;

	/** @var int */
	public $sourceLine;


	public function setSource(string $code, int $line, string $name = null)
	{
		$this->sourceCode = $code;
		$this->sourceLine = $line;
		$this->sourceName = $name;
		if (@is_file($name)) { // @ - may trigger error
			$this->message = rtrim($this->message, '.')
				. ' in ' . str_replace(dirname(dirname($name)), '...', $name) . ($line ? ":$line" : '');
		}
		return $this;
	}
}


/**
 * The exception that indicates error of the last Regexp execution.
 */
class RegexpException extends \Exception
{
	public const MESSAGES = [
		PREG_INTERNAL_ERROR => 'Internal error',
		PREG_BACKTRACK_LIMIT_ERROR => 'Backtrack limit was exhausted',
		PREG_RECURSION_LIMIT_ERROR => 'Recursion limit was exhausted',
		PREG_BAD_UTF8_ERROR => 'Malformed UTF-8 data',
		PREG_BAD_UTF8_OFFSET_ERROR => 'Offset didn\'t correspond to the begin of a valid UTF-8 code point',
		6 => 'Failed due to limited JIT stack space', // PREG_JIT_STACKLIMIT_ERROR
	];


	public function __construct($message, $code = null)
	{
		parent::__construct($message ?: (self::MESSAGES[$code] ?? 'Unknown error'), $code);
	}
}


/**
 * The exception that indicates error during rendering template.
 */
class RuntimeException extends \Exception
{
}
