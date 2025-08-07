<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;


/**
 * Loaded content from loader.
 * @internal
 */
class LoadedContent
{
	public function __construct(
		public readonly string $content,
		public readonly ?string $sourceName = null,
	) {
	}
}
