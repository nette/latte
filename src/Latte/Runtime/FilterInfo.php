<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;

use Latte;


/**
 * Filter runtime info
 */
class FilterInfo
{
	use Latte\Strict;

	/** @var string */
	public $contentType;


	public function __construct(string $contentType = Latte\Engine::CONTENT_TEXT)
	{
		$this->contentType = $contentType;
	}


	public function validate(array $contentTypes, string $name = null): void
	{
		if (!in_array($this->contentType, $contentTypes, true)) {
			$name = $name ? " |$name" : $name;
			throw new Latte\CompileException("Filter$name used with incompatible type " . strtoupper($this->contentType));
		}
	}
}
