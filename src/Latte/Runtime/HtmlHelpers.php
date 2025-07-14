<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;

use Latte;
use function get_debug_type, implode, in_array, is_array, is_string, preg_match, str_contains, str_replace, strncmp, strtolower;


/**
 * Runtime utilities for handling HTML.
 * @internal
 */
final class HtmlHelpers
{
	/**
	 * Formats HTML attribute value based on attribute type and value type.
	 */
	public static function formatAttribute(string $name, mixed $value): ?string
	{
		if ($value === null || $value === false) {
			return null;

		} elseif ($value === true) {
			return $name;

		} elseif (is_array($value)) {
			$tmp = null;
			foreach ($value as $k => $v) {
				if ($v != null) { // intentionally ==, skip nulls & empty string
					//  composite 'style' vs. 'others'
					$tmp[] = $v === true
						? $k
						: (is_string($k) ? $k . ':' . $v : $v);
				}
			}

			if ($tmp === null) {
				return null;
			}

			$value = implode($name === 'style' || !strncmp($name, 'on', 2) ? ';' : ' ', $tmp);

		} else {
			$value = (string) $value;
		}

		$q = !str_contains($value, '"') ? '"' : "'";
		return $name . '=' . $q
			. str_replace(
				['&', $q, '<'],
				['&amp;', $q === '"' ? '&quot;' : '&apos;', '<'],
				$value,
			)
			. $q;
	}


	/**
	 * Checks if the given tag name represents a void (empty) HTML element.
	 */
	public static function isVoidElement(string $name): bool
	{
		static $names = [
			'img' => 1, 'hr' => 1, 'br' => 1, 'input' => 1, 'meta' => 1, 'area' => 1, 'embed' => 1, 'keygen' => 1, 'source' => 1, 'base' => 1,
			'col' => 1, 'link' => 1, 'param' => 1, 'basefont' => 1, 'frame' => 1, 'isindex' => 1, 'wbr' => 1, 'command' => 1, 'track' => 1,
		];
		return isset($names[strtolower($name)]);
	}


	/**
	 * Checks that the HTML tag name can be changed.
	 */
	public static function validateTagChange(mixed $name, ?string $origName = null): string
	{
		$name ??= $origName;
		if (!is_string($name)) {
			throw new Latte\RuntimeException('Tag name must be string, ' . get_debug_type($name) . ' given');

		} elseif (!preg_match('~' . Latte\Compiler\TemplateLexer::ReTagName . '$~DAu', $name)) {
			throw new Latte\RuntimeException("Invalid tag name '$name'");

		} elseif (self::isVoidElement($name) !== self::isVoidElement($origName ?? 'div') // non-void is default
			|| in_array(strtolower($name), ['style', 'script'], true)) {
			throw new Latte\RuntimeException("Forbidden: Cannot change element to <$name>");
		}
		return $name;
	}
}
