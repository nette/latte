<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;


/**
 * The exception that indicates error of the last Regexp execution.
 */
class RegexpException extends \Exception
{
	public function __construct()
	{
		parent::__construct(preg_last_error_msg(), preg_last_error());
	}
}


/**
 * Exception thrown when a not allowed construction is used in a template.
 */
class SecurityViolationException extends \Exception
{
}


class RuntimeException extends \RuntimeException
{
}
