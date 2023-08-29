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
	public ?string $contentType = null;

	/** @var callable[] */
	public array $functions = [];
}
