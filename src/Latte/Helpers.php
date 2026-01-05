<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte;

use function array_filter, array_keys, array_unique, count, in_array, is_array, is_object, is_string, levenshtein, strlen, strpos;
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
	public static function toReflection(mixed $callable): \ReflectionFunctionAbstract
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


	/**
	 * Sorts items using topological sort based on before/after constraints.
	 * @param  array<string, mixed|\stdClass>  $list
	 * @return array<string, mixed|\stdClass>
	 */
	public static function sortBeforeAfter(array $list): array
	{
		$names = array_keys($list);

		// Build adjacency list and in-degree count
		// Edge A → B means "A must come before B"
		$graph = array_fill_keys($names, []);
		$inDegree = array_fill_keys($names, 0);

		foreach ($list as $name => $info) {
			if (!$info instanceof \stdClass) {
				continue;
			}

			// "before: X" means this node → X (this comes before X)
			foreach ((array) ($info->before ?? []) as $target) {
				if ($target === '*') {
					foreach ($names as $other) {
						if ($other !== $name && !in_array($other, $graph[$name], true)) {
							$graph[$name][] = $other;
							$inDegree[$other]++;
						}
					}
				} elseif (isset($list[$target]) && $target !== $name) {
					if (!in_array($target, $graph[$name], true)) {
						$graph[$name][] = $target;
						$inDegree[$target]++;
					}
				}
			}

			// "after: X" means X → this node (X comes before this)
			foreach ((array) ($info->after ?? []) as $target) {
				if ($target === '*') {
					foreach ($names as $other) {
						if ($other !== $name && !in_array($name, $graph[$other], true)) {
							$graph[$other][] = $name;
							$inDegree[$name]++;
						}
					}
				} elseif (isset($list[$target]) && $target !== $name) {
					if (!in_array($name, $graph[$target], true)) {
						$graph[$target][] = $name;
						$inDegree[$name]++;
					}
				}
			}
		}

		// Kahn's algorithm
		$queue = [];
		foreach ($names as $name) {
			if ($inDegree[$name] === 0) {
				$queue[] = $name;
			}
		}

		$result = [];
		while ($queue) {
			$node = array_shift($queue);
			$result[$node] = $list[$node];

			foreach ($graph[$node] as $neighbor) {
				$inDegree[$neighbor]--;
				if ($inDegree[$neighbor] === 0) {
					$queue[] = $neighbor;
				}
			}
		}

		if (count($result) !== count($list)) {
			$cycle = array_diff($names, array_keys($result));
			throw new \LogicException('Circular dependency detected among: ' . implode(', ', $cycle));
		}

		return $result;
	}


	/** @param  mixed[]  $items */
	public static function removeNulls(array &$items): void
	{
		$items = array_values(array_filter($items, fn($item) => $item !== null));
	}


	/**
	 * Attempts to map the compiled template to the source.
	 * @return array{name: ?string, line: ?int, column: ?int}|null
	 */
	public static function mapCompiledToSource(string $compiledFile, ?int $compiledLine = null): ?array
	{
		if (!Runtime\Cache::isCacheFile($compiledFile)) {
			return null;
		}

		$content = file_get_contents($compiledFile);
		if ($content === false) {
			return null;
		}
		$name = preg_match('#^/\*\* source: (\S.+) \*/#m', $content, $m) ? $m[1] : null;
		$compiledLine && preg_match('~/\* pos (\d+)(?::(\d+))? \*/~', explode("\n", $content)[$compiledLine - 1], $pos);
		$line = isset($pos[1]) ? (int) $pos[1] : null;
		$column = isset($pos[2]) ? (int) $pos[2] : null;
		return $name || $line ? compact('name', 'line', 'column') : null;
	}


	/**
	 * Tries to guess the position in the template from the backtrace
	 */
	public static function guessTemplatePosition(): ?string
	{
		$trace = debug_backtrace();
		foreach ($trace as $item) {
			if (isset($item['file']) && ($source = self::mapCompiledToSource($item['file'], $item['line'] ?? null))) {
				$res = [];
				if ($source['name'] && is_file($source['name'])) {
					$res[] = "in '" . str_replace(dirname($source['name'], 2), '...', $source['name']) . "'";
				}
				if ($source['line']) {
					$res[] = 'on line ' . $source['line'] . ($source['column'] ? ' at column ' . $source['column'] : '');
				}
				return implode(' ', $res);
			}
		}
		return null;
	}
}
