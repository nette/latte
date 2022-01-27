<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;


/**
 * Latte extension.
 */
interface Extension
{
	public const
		AUTO_CLOSE = 64,
		ALLOWED_IN_HEAD = 128,
		DEFAULT_FLAGS = 0;

	/**
	 * Returns a list of |filters.
	 * @return array<string, callable>
	 */
	function getFilters(): array;

	/**
	 * Returns a list of functions used in templates.
	 * @return array<string, callable>
	 */
	function getFunctions(): array;

	/**
	 * Initializes before template parsing.
	 */
	function beforeParse(): void;

	/**
	 * Finishes template parsing.
	 */
	function afterCompile(Compiler\Compiler $compiler); //: void;
}
