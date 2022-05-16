<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte;
use Latte\ContentType;
use Latte\Policy;


/**
 * PHP printing helpers and context.
 */
final class PrintContext
{
	use Latte\Strict;

	public ?Policy $policy;
	public array $functions;
	public array $paramsExtraction = [];
	public array $blocks = [];
	private int $counter = 0;
	private Escaper $escaper;

	/** @var Escaper[] */
	private array $escaperStack = [];


	public function __construct(string $contentType = ContentType::Html)
	{
		$this->escaper = new Escaper($contentType);
	}


	public function beginEscape(): Escaper
	{
		$this->escaperStack[] = $this->escaper;
		return $this->escaper = clone $this->escaper;
	}


	public function restoreEscape(): void
	{
		$this->escaper = array_pop($this->escaperStack);
	}


	public function getEscaper(): Escaper
	{
		return clone $this->escaper;
	}


	public function generateId(): int
	{
		return $this->counter++;
	}
}
