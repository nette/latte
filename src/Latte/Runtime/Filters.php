<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;


/**
 * Escaping & sanitization filters.
 * @internal
 */
class Filters
{
	/** @deprecated */
	public static string $dateFormat = "j.\u{a0}n.\u{a0}Y";


	/**
	 * Sanitizes string for use inside href attribute.
	 */
	public static function safeUrl($s): string
	{
		$s = $s instanceof HtmlStringable
			? HtmlHelpers::convertHtmlToText((string) $s)
			: (string) $s;

		return preg_match('~^(?:(?:https?|ftp)://[^@]+(?:/.*)?|(?:mailto|tel|sms):.+|[/?#].*|[^:]+)$~Di', $s) ? $s : '';
	}
}
