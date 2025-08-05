<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;


/**
 * Common interface for all Latte exceptions.
 */
interface Exception
{
}


/**
 * Template compilation failed.
 */
class CompileException extends \Exception implements Exception
{
	use PositionAwareException;

	/** @deprecated */
	public ?int $sourceLine;


	public function __construct(
		string $message,
		Compiler\Position|SourceReference|null $source = null,
		?\Throwable $previous = null,
	) {
		parent::__construct($message, 0, $previous);
		if ($source) {
			$this->setSource($source instanceof Compiler\Position
				? new SourceReference(null, $source->line, $source->column)
				: $source);
			$this->sourceLine = $this->source?->line;
		}
	}
}


/**
 * Template rendering failed.
 */
class RuntimeException extends \RuntimeException implements Exception
{
}


/**
 * Template file not found or could not be loaded.
 */
class TemplateNotFoundException extends RuntimeException
{
}


/**
 * Template uses forbidden function, filter or variable in sandbox mode.
 */
class SecurityViolationException extends \Exception implements Exception
{
	use PositionAwareException;

	public function __construct(string $message, Compiler\Position|SourceReference|null $source = null)
	{
		parent::__construct($message);
		if ($source) {
			$this->setSource($source instanceof Compiler\Position
				? new SourceReference(null, $source->line, $source->column)
				: $source);
		}
	}
}
