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
interface ISnippetBridge
{

	function isSnippetMode(): bool;


	function setSnippetMode(bool $snippetMode);


	function needsRedraw(string $name): bool;


	/**
	 * @return void
	 */
	function markRedrawn(string $name);


	function getHtmlId(string $name): string;


	/**
	 * @return void
	 */
	function addSnippet(string $name, string $content);


	/**
	 * @return void
	 */
	function renderChildren();

}
