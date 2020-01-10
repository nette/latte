<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;

use Latte;


/**
 * HTML literal.
 */
class Html implements HtmlString
{
	use Latte\Strict;

	/** @var string */
	private $value;


	public function __construct($value)
	{
		$this->value = (string) $value;
	}


	public function __toString(): string
	{
		return $this->value;
	}
}
