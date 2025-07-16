<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;

use function array_filter, implode, is_array, str_contains, str_replace;


/**
 * Runtime utilities for handling XML.
 * @internal
 */
final class XmlHelpers
{
	/**
	 * Formats XML attribute value based on value type.
	 */
	public static function formatAttribute(string $name, mixed $value): ?string
	{
		if ($value === null || $value === false) {
			return null;

		} elseif ($value === true) {
			return $name . '="' . $name . '"';

		} elseif (is_array($value)) {
			$value = array_filter($value); // intentionally ==, skip nulls & empty string
			if (!$value) {
				return null;
			}

			$value = implode(' ', $value);

		} else {
			$value = (string) $value;
		}

		$q = !str_contains($value, '"') ? '"' : "'";
		return $name . '=' . $q
			. str_replace(
				['&', $q, '<'],
				['&amp;', $q === '"' ? '&quot;' : '&#39;', '&lt;'],
				$value,
			)
			. $q;
	}
}
