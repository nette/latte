<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte {
	if (false) {
		/** @deprecated use Latte\Loader */
		interface ILoader
		{
		}
	} elseif (!interface_exists(ILoader::class)) {
		class_alias(Loader::class, ILoader::class);
	}

	if (false) {
		/** @deprecated use Latte\Macro */
		interface IMacro
		{
		}
	} elseif (!interface_exists(IMacro::class)) {
		class_alias(Macro::class, IMacro::class);
	}
}

namespace Latte\Runtime {
	if (false) {
		/** @deprecated use Latte\Runtime\HtmlString */
		interface IHtmlString
		{
		}
	} elseif (!interface_exists(IHtmlString::class)) {
		class_alias(HtmlString::class, IHtmlString::class);
	}

	if (false) {
		/** @deprecated use Latte\Runtime\SnippetBridge */
		interface ISnippetBridge
		{
		}
	} elseif (!interface_exists(ISnippetBridge::class)) {
		class_alias(SnippetBridge::class, ISnippetBridge::class);
	}
}
