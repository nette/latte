<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;


/**
 * Latte macro.
 */
interface Macro
{
	public const
		AUTO_EMPTY = 4, // deprecated
		AUTO_CLOSE = 64,
		ALLOWED_IN_HEAD = 128,
		DEFAULT_FLAGS = 0;

	/**
	 * Initializes before template parsing.
	 * @return void
	 */
	function initialize();

	/**
	 * Finishes template parsing.
	 * @return array{string, string}|array{string}|null  (prolog, epilog)
	 */
	function finalize();

	/**
	 * New node is found. Returns false to reject.
	 * @return bool|null
	 */
	function nodeOpened(MacroNode $node);

	/**
	 * Node is closed.
	 * @return void
	 */
	function nodeClosed(MacroNode $node);
}
