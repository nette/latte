<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Runtime;

use Latte;


/**
 * Filter runtime info
 */
class FilterInfo
{
	use Latte\Strict;

	/** @var string|null */
	public $contentType;


	public function __construct($contentType = null)
	{
		$this->contentType = $contentType;
	}
}
