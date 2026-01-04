<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Runtime;


/**
 * HTML literal.
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
