<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;


/** @internal */
final class Block
{
	/** content type */
	public ?string $contentType = null;

	/** used by BlockExtension */
	public ?string $code = null;

	/** @var callable[]  used by Template */
	public array $functions = [];

	/** used by BlockExtension */
	public bool $hasParameters = false;

	/** used by BlockExtension */
	public ?string $comment = null;
}
