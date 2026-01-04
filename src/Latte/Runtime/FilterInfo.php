<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Runtime;

use Latte;
use function in_array, strtoupper;


/**
 * Content type context for contextual filters.
 */
class FilterInfo
{
	public function __construct(
		public ?string $contentType = null,
	) {
	}


	/**
	 * Validates content type is allowed for this filter.
	 * @param  list<string|null>  $contentTypes
	 */
	public function validate(array $contentTypes, ?string $name = null): void
	{
		if (!in_array($this->contentType, $contentTypes, strict: true)) {
			$name = $name ? " |$name" : $name;
			$type = $this->contentType ? ' ' . strtoupper($this->contentType) : '';
			throw new Latte\RuntimeException("Filter{$name} used with incompatible type{$type}.");
		}
	}
}
