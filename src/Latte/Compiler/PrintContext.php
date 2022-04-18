<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\Context;
use Latte\Policy;
use Latte\Strict;


/**
 * PHP printing helpers and context.
 */
final class PrintContext
{
	use Strict;

	public ?Policy $policy;
	public array $functions;
	public array $paramsExtraction = [];
	public array $blocks = [];
	private int $counter = 0;
	private string $contentType = Context::Html;
	private ?string $context = null;


	public function generateId(): int
	{
		return $this->counter++;
	}


	public function setContentType(string $type): static
	{
		$this->contentType = $type;
		$this->context = null;
		return $this;
	}


	public function getContentType(): string
	{
		return $this->contentType;
	}


	public function setEscapingContext(?string $context): static
	{
		$this->context = $context;
		return $this;
	}


	public function getEscapingContext(): array
	{
		return [$this->contentType, $this->context];
	}
}
