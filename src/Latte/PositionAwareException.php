<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;

use Latte\Compiler\Position;


/** @internal */
trait PositionAwareException
{
	/** @deprecated */
	public ?string $sourceCode = null;

	/** @deprecated */
	public ?string $sourceName = null;

	/** @deprecated */
	public ?Position $position = null;
	private ?SourceReference $source = null;
	private string $origMessage;


	public function setSource(SourceReference|string $source, ?string $name = null): self
	{
		if (is_string($source)) {
			$source = new SourceReference($name, $this->source?->line, $this->source?->column, $source);
		}
		$this->source = $source;
		$this->sourceCode = $source->code;
		$this->sourceName = $source->name;
		$this->position = $source->line ? new Compiler\Position($source->line, (int) $source->column) : null;
		$this->generateMessage();
		return $this;
	}


	public function getSource(): ?SourceReference
	{
		return $this->source;
	}


	private function generateMessage(): void
	{
		$this->origMessage ??= $this->message;
		$info = (string) $this->source;
		$this->message = $info
			? rtrim($this->origMessage, '.') . ' (' . $info . ')'
			: $this->origMessage;
	}
}
