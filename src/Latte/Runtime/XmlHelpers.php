<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;

use Latte;
use function array_filter, get_debug_type, implode, is_array, is_string, preg_match, str_contains, str_replace;


/**
 * Runtime utilities for handling XML.
 * @internal
 */
final class XmlHelpers
{
	// https://www.w3.org/TR/xml/#NT-Name
	private const
		ReNameStart = ':A-Z_a-z\x{C0}-\x{D6}\x{D8}-\x{F6}\x{F8}-\x{2FF}\x{370}-\x{37D}\x{37F}-\x{1FFF}\x{200C}-\x{200D}\x{2070}-\x{218F}\x{2C00}-\x{2FEF}\x{3001}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFFD}\x{10000}-\x{EFFFF}',
		ReName = '[' . self::ReNameStart . '][-.0-9\x{B7}\x{300}-\x{36F}\x{203F}-\x{2040}' . self::ReNameStart . ']*';


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


	/**
	 * Checks that the HTML tag name can be changed.
	 */
	public static function validateTagChange(mixed $name, ?string $origName = null): string
	{
		$name ??= $origName;
		if (!is_string($name)) {
			throw new Latte\RuntimeException('Tag name must be string, ' . get_debug_type($name) . ' given');

		} elseif (!preg_match('~' . self::ReName . '$~DAu', $name)) {
			throw new Latte\RuntimeException("Invalid tag name '$name'");
		}
		return $name;
	}
}
