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
	 * Returns a list of parsers for Latte tags.
	 * @return array<string, callable(Compiler\TagInfo, Compiler\Parser): Compiler\Node>
	 */
	function getTags(): array;

	/**
	 * Initializes before template parsing.
	 */
	function beforeParse(): void;

	/**
	 * Finishes template parsing.
	 */
	function afterCompile(Compiler\Compiler $compiler); //: void;
}
