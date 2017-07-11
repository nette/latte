<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Runtime;


/**
 * Snippet bridge
 * @internal
 */
interface ISnippetBridge
{

	/**
	 * @return bool
	 */
	function isSnippetMode();

	/**
	 * @param  bool
	 * @return void
	 */
	function setSnippetMode($snippetMode);

	/**
	 * @param  string
	 * @return bool
	 */
	function needsRedraw($name);

	/**
	 * @param  string
	 * @return void
	 */
	function markRedrawn($name);

	/**
	 * @param  string
	 * @return string
	 */
	function getHtmlId($name);

	/**
	 * @param  string
	 * @param  string
	 * @return mixed
	 */
	function addSnippet($name, $content);

	/**
	 * @return void
	 */
	function renderChildren();
}
