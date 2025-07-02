<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;

use function array_filter, array_keys, array_search, array_slice, array_unique, count, is_array, is_object, is_string, levenshtein, max, min, strlen, strpos;
use const PHP_VERSION_ID;


/**
 * Latte helpers.
 * @internal
 */
class Helpers
{
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


	/** intentionally without callable typehint, because it generates bad error messages */
	public static function toReflection($callable): \ReflectionFunctionAbstract
	{
		if (is_string($callable) && strpos($callable, '::')) {
			return PHP_VERSION_ID < 80300
				? new \ReflectionMethod($callable)
				: \ReflectionMethod::createFromMethodName($callable);
		} elseif (is_array($callable)) {
			return new \ReflectionMethod($callable[0], $callable[1]);
		} elseif (is_object($callable) && !$callable instanceof \Closure) {
			return new \ReflectionMethod($callable, '__invoke');
		} else {
			return new \ReflectionFunction($callable);
		}
	}


	public static function sortBeforeAfter(array $list): array
	{
		foreach ($list as $name => $info) {
			if (!$info instanceof \stdClass || !($info->before ?? $info->after ?? null)) {
				continue;
			}

			unset($list[$name]);
			$names = array_keys($list);
			$best = null;

			foreach ((array) $info->before as $target) {
				if ($target === '*') {
					$best = 0;
				} elseif (isset($list[$target])) {
					$pos = array_search($target, $names, true);
					$best = min($pos, $best ?? $pos);
				}
			}

			foreach ((array) ($info->after ?? null) as $target) {
				if ($target === '*') {
					$best = count($names);
				} elseif (isset($list[$target])) {
					$pos = array_search($target, $names, true);
					$best = max($pos + 1, $best);
				}
			}

			$list = array_slice($list, 0, $best, true)
				+ [$name => $info]
				+ array_slice($list, $best, null, true);
		}

		return $list;
	}


	public static function removeNulls(array &$items): void
	{
		$items = array_filter($items, fn($item) => $item !== null);
	}
}
