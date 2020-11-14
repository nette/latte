<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;


/**
 * Snippet bridge
 * @internal
 */
interface SnippetBridge
{
	/**
	 * @return bool
	 */
	function isSnippetMode();

	/**
	 * @param  bool  $snippetMode
	 * @return void
	 */
	function setSnippetMode($snippetMode);

	/**
	 * @param  string  $name
	 * @return bool
	 */
	function needsRedraw($name);

	/**
	 * @param  string  $name
	 * @return void
	 */
	function markRedrawn($name);

	/**
	 * @param  string  $name
	 * @return string
	 */
	function getHtmlId($name);

	/**
	 * @param  string  $name
	 * @param  string  $content
	 * @return void
	 */
	function addSnippet($name, $content);

	/**
	 * @return void
	 */
	function renderChildren();
}


interface_exists(ISnippetBridge::class);
