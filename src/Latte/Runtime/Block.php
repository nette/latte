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
	/** @var ?string  content type */
	public $contentType;

	/** @var ?string  used by BlockMacros */
	public $code;

	/** @var callable[]  used by Template */
	public $functions = [];

	/** @var bool  used by BlockMacros */
	public $hasParameters = false;

	/** @var ?string  used by BlockMacros */
	public $comment;
}
