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
	/** @var array<string, int>  empty (void) HTML elements */
	public static array $emptyElements = [
		'img' => 1, 'hr' => 1, 'br' => 1, 'input' => 1, 'meta' => 1, 'area' => 1, 'embed' => 1, 'keygen' => 1, 'source' => 1, 'base' => 1,
		'col' => 1, 'link' => 1, 'param' => 1, 'basefont' => 1, 'frame' => 1, 'isindex' => 1, 'wbr' => 1, 'command' => 1, 'track' => 1,
	];


	/**
	 * Checks callback.
	 */
	public static function checkCallback(mixed $callable): callable
	{
		if (!is_callable($callable, false, $text)) {
			throw new \InvalidArgumentException("Callback '$text' is not callable.");
		}

		return $callable;
	}


	/**
	 * Finds the best suggestion.
	 * @param  string[]  $items
	 */
	public static function getSuggestion(array $items, string $value): ?string
	{
		$best = null;
		$min = (strlen($value) / 4 + 1) * 10 + .1;
		foreach (array_unique($items) as $item) {
			if (($len = levenshtein($item, $value, 10, 11, 10)) > 0 && $len < $min) {
				$min = $len;
				$best = $item;
			}
		}

		return $best;
	}


	public static function removeFilter(string &$modifier, string $filter): bool
	{
		$tmp = str_replace('|checkUrl', '', $modifier);
		if ($filter === 'noescape' && preg_match('#\|noescape\s*\S#Di', $tmp)) {
			trigger_error("Filter |noescape should be placed at the very end in '$tmp'", E_USER_DEPRECATED);
		}
		$modifier = preg_replace('#\|(' . $filter . ')\s?(?=\||$)#Di', '', $modifier, -1, $found);
		return (bool) $found;
	}


	/** intentionally without callable typehint, because it generates bad error messages */
	public static function toReflection($callable): \ReflectionFunctionAbstract
	{
		if (is_string($callable) && strpos($callable, '::')) {
			return new \ReflectionMethod($callable);
		} elseif (is_array($callable)) {
			return new \ReflectionMethod($callable[0], $callable[1]);
		} elseif (is_object($callable) && !$callable instanceof \Closure) {
			return new \ReflectionMethod($callable, '__invoke');
		} else {
			return new \ReflectionFunction($callable);
		}
	}
}
