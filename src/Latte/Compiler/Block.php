<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;


/** @internal */
final class Block
{
	public ?string $contentType = null;
	public ?string $code = null;
	public bool $hasParameters = false;
	public ?string $comment = null;
}
