<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Runtime;


/**
 * Wraps a pre-escaped HTML string so it is not escaped again when rendered.
 */
class Html implements HtmlStringable
{
	private readonly string $value;


	public function __construct(string|\Stringable|null $value)
	{
		$this->value = (string) $value;
	}


	public function __toString(): string
	{
		return $this->value;
	}
}
