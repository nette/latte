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

	function setSnippetMode($snippetMode);

	function needsRedraw($name): bool;

	function markRedrawn($name): void;

	function getHtmlId($name): string;

	function addSnippet($name, $content): void;

	function renderChildren(): void;
}
