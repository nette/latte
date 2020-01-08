<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;


/**
 * Latte helpers.
 * @internal
 */
class Helpers
{
	/** @var array  empty (void) HTML elements */
	public static $emptyElements = [
		'img' => 1, 'hr' => 1, 'br' => 1, 'input' => 1, 'meta' => 1, 'area' => 1, 'embed' => 1, 'keygen' => 1, 'source' => 1, 'base' => 1,
		'col' => 1, 'link' => 1, 'param' => 1, 'basefont' => 1, 'frame' => 1, 'isindex' => 1, 'wbr' => 1, 'command' => 1, 'track' => 1,
	];


	/**
	 * Checks callback.
	 */
	public static function checkCallback($callable): callable
	{
		if (!is_callable($callable, false, $text)) {
			throw new \InvalidArgumentException("Callback '$text' is not callable.");
		}
		return $callable;
	}


	/**
	 * Finds the best suggestion.
	 */
	public static function getSuggestion(array $items, $value): ?string
	{
		$best = null;
		$min = (strlen($value) / 4 + 1) * 10 + .1;
		foreach (array_unique($items, SORT_REGULAR) as $item) {
			$item = is_object($item) ? $item->getName() : $item;
			if (($len = levenshtein($item, $value, 10, 11, 10)) > 0 && $len < $min) {
				$min = $len;
				$best = $item;
			}
		}
		return $best;
	}


	public static function removeFilter(string &$modifier, string $filter): bool
	{
		$modifier = preg_replace('#\|(' . $filter . ')\s?(?=\||$)#Di', '', $modifier, -1, $found);
		return (bool) $found;
	}


	/**
	 * Starts the $haystack string with the prefix $needle?
	 */
	public static function startsWith(string $haystack, string $needle): bool
	{
		return strncmp($haystack, $needle, strlen($needle)) === 0;
	}


	public static function printProperties(array $types, bool $asProps): string
	{
		$keys = array_keys($types);
		if ($asProps) {
			$items = PHP_VERSION_ID >= 70400
				? array_map(function ($key, $type) { return "\tpublic $type $$key;"; }, $keys, $types)
				: array_map(function ($key, $type) { return "\t/** @var $type */\n\tpublic $$key;\n"; }, $keys, $types);
		} else {
			$items = array_map(function ($key, $type) { return " * @property $type $$key"; }, $keys, $types);
		}
		return implode("\n", $items);
	}


	public static function getType($value): string
	{
		if (is_object($value)) {
			return '\\' . get_class($value);
		} elseif (is_int($value)) {
			return 'int';
		} elseif (is_float($value)) {
			return 'float';
		} elseif (is_string($value)) {
			return 'string';
		} elseif (is_bool($value)) {
			return 'bool';
		} elseif (is_array($value)) {
			return 'array';
		} else {
			return '';
		}
	}
}
