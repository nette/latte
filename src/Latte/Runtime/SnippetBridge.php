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
	function isSnippetMode(): bool;

	function setSnippetMode(bool $snippetMode): void;

	function needsRedraw(string $name): bool;

	function markRedrawn(string $name): void;

	function getHtmlId(string $name): string;

	function addSnippet(string $name, string $content): void;

	function renderChildren(): void;
}
