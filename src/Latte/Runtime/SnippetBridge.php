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
	function isSnippetMode();

	function setSnippetMode($snippetMode);

	function needsRedraw($name);

	function markRedrawn($name);

	function getHtmlId($name);

	function addSnippet($name, $content);

	function renderChildren();
}


interface_exists(ISnippetBridge::class);
