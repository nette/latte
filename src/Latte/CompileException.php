<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;

use Latte\Compiler\Position;


/**
 * The exception occurred during Latte compilation.
 */
class CompileException extends \Exception
{
	public ?string $sourceCode = null;
	public ?string $sourceName = null;
	public ?Position $position = null;
	private string $origMessage;


	public function __construct(string $message, ?Position $position = null, ?\Throwable $previous = null)
	{
		parent::__construct($message, 0, $previous);
		$this->origMessage = $message;
		$this->position = $position;
		$this->generateMessage();
	}


	public function setSource(string $code, ?int $line = null, ?string $name = null): self
	{
		$this->sourceCode = $code;
		$this->position ??= new Position($line, 0);
		$this->sourceName = $name;
		$this->generateMessage();
		return $this;
	}


	private function generateMessage()
	{
		$info = [];
		if ($this->sourceName && @is_file($this->sourceName)) { // @ - may trigger error
			$info[] = "in '" . str_replace(dirname($this->sourceName, 2), '...', $this->sourceName) . "'";
		}
		if ($this->position?->line > 1) {
			$info[] = 'on line ' . $this->position->line;
		}
		if ($this->position?->column) {
			$info[] = 'at column ' . $this->position->column;
		}
		$this->message = $info
			? rtrim($this->origMessage, '.') . ' (' . implode(' ', $info) . ')'
			: $this->origMessage;
	}
}
