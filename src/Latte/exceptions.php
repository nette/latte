<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte;


/**
 * Common interface for all Latte exceptions.
 */
interface Exception
{
}


/**
 * @internal
 */
trait PositionAwareException
{
	public ?string $sourceCode = null;
	public ?string $sourceName = null;
	public ?Compiler\Position $position = null;
	private string $origMessage;


	public function setSource(string $code, ?string $name = null): self
	{
		$this->sourceCode = $code;
		$this->sourceName = $name;
		$this->generateMessage();
		return $this;
	}


	private function generateMessage(): void
	{
		$this->origMessage ??= $this->message;
		$info = [];
		if ($this->sourceName && @is_file($this->sourceName)) { // @ - may trigger error
			$info[] = "in '" . str_replace(dirname($this->sourceName, 2), '...', $this->sourceName) . "'";
		}
		if ($this->position) {
			$info[] = $this->position;
		}
		$this->message = $info
			? rtrim($this->origMessage, '.') . ' (' . implode(' ', $info) . ')'
			: $this->origMessage;
	}
}


/**
 * Template compilation failed.
 */
class CompileException extends \Exception implements Exception
{
	use PositionAwareException;

	/** @deprecated */
	public ?int $sourceLine;


	public function __construct(string $message, ?Compiler\Position $position = null, ?\Throwable $previous = null)
	{
		parent::__construct($message, 0, $previous);
		$this->position = $position;
		$this->sourceLine = $position?->line;
		$this->generateMessage();
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

	public function __construct(string $message, ?Compiler\Position $position = null)
	{
		parent::__construct($message);
		$this->position = $position;
		$this->generateMessage();
	}
}
