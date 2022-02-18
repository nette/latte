<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\Context;
use Latte\Policy;
use Latte\SecurityViolationException;
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
	private ?string $subContext = null;


	public function format(string $mask, mixed ...$args): string
	{
		return PhpWriter::using($this)
			->write($mask, ...$args);
	}


	public function checkFilterIsAllowed(string $name): void
	{
		if ($this->policy && !$this->policy->isFilterAllowed($name)) {
			throw new SecurityViolationException("Filter |$name is not allowed.");
		}
	}


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


	public function setEscapingContext(?string $context, ?string $subContext = null): static
	{
		$this->context = $context;
		$this->subContext = $subContext;
		return $this;
	}


	public function getEscapingContext(): array
	{
		return [$this->contentType, $this->context, $this->subContext];
	}


	public function addBlock(Block $block, ?array $context = null): void
	{
		$block->context = implode('', $context ?? $this->getEscapingContext());
		$block->method = 'block' . ucfirst(trim(preg_replace('#\W+#', '_', $block->name), '_'));
		$lower = strtolower($block->method);
		$used = $this->blocks + ['block' => 1];
		$counter = null;
		while (isset($used[$lower . $counter])) {
			$counter++;
		}

		$block->method .= $counter;
		$this->blocks[$lower . $counter] = $block;
	}
}
