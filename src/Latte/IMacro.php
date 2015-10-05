<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte;


/**
 * Latte macro.
 */
interface IMacro
{

	/**
	 * Initializes before template parsing.
	 * @return void
	 */
	function initialize();

	/**
	 * Finishes template parsing.
	 * @return array(prolog, epilog)
	 */
	function finalize();

	/**
	 * New node is found. Returns FALSE to reject.
	 * @return bool
	 */
	function nodeOpened(MacroNode $node);

	/**
	 * Node is closed.
	 * @return void
	 */
	function nodeClosed(MacroNode $node);

}
