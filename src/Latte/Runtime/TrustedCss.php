<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Runtime;


/**
 * CSS code that is trusted, so it is not escaped when printed into CSS.
 */
final readonly class TrustedCss implements \Stringable
{
	private string $value;


	public function __construct(string|\Stringable|null $value)
	{
		$this->value = (string) $value;
	}


	public function __toString(): string
	{
		return $this->value;
	}
}
