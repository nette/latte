<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential;

use Latte;
use Latte\ContentType;
use Latte\Runtime\FilterInfo;
use Latte\Runtime\Html;
use Stringable;
use function abs, array_combine, array_fill_keys, array_key_last, array_map, array_rand, array_reverse, array_search, array_slice, base64_encode, ceil, count, end, explode, extension_loaded, finfo_buffer, finfo_open, floor, func_num_args, htmlspecialchars, http_build_query, iconv, iconv_strlen, iconv_substr, implode, is_array, is_int, is_numeric, is_string, iterator_count, iterator_to_array, key, ltrim, max, mb_convert_case, mb_strlen, mb_strtolower, mb_strtoupper, mb_substr, min, nl2br, number_format, preg_last_error, preg_last_error_msg, preg_match, preg_quote, preg_replace, preg_replace_callback, preg_split, reset, round, rtrim, str_repeat, str_replace, strip_tags, strlen, strrev, strtr, substr, trim, uasort, uksort, urlencode, utf8_decode;
use const ENT_NOQUOTES, ENT_SUBSTITUTE, FILEINFO_MIME_TYPE, MB_CASE_TITLE, PHP_OUTPUT_HANDLER_FINAL, PHP_OUTPUT_HANDLER_START, PREG_SPLIT_NO_EMPTY;


/**
 * Template filters. Uses UTF-8 only.
 * @internal
 */
final class Filters
{
	public ?string $locale = null;


	/**
	 * Converts HTML to plain text.
	 */
	public static function stripHtml(FilterInfo $info, $s): string
	{
		$info->validate([null, 'html', 'html/attr', 'xml', 'xml/attr'], __FUNCTION__);
		$info->contentType = ContentType::Text;
		return Latte\Runtime\HtmlHelpers::convertHtmlToText((string) $s);
	}


	/**
	 * Removes tags from HTML (but remains HTML entities).
	 */
	public static function stripTags(FilterInfo $info, $s): string
	{
		$info->contentType ??= ContentType::Html;
		$info->validate(['html', 'html/attr', 'xml', 'xml/attr'], __FUNCTION__);
		return strip_tags((string) $s);
	}


	/**
	 * Replaces all repeated white spaces with a single space.
	 */
	public static function strip(FilterInfo $info, string $s): string
	{
		return $info->contentType === ContentType::Html
			? trim(self::spacelessHtml($s))
			: trim(self::spacelessText($s));
	}


	/**
	 * Replaces all repeated white spaces with a single space.
	 */
	public static function spacelessHtml(string $s, bool &$strip = true): string
	{
		return preg_replace_callback(
			'#[ \t\r\n]+|<(/)?(textarea|pre|script)(?=\W)#si',
			function ($m) use (&$strip) {
				if (empty($m[2])) {
					return $strip ? ' ' : $m[0];
				} else {
					$strip = !empty($m[1]);
					return $m[0];
				}
			},
			$s,
		);
	}


	/**
	 * Output buffering handler for spacelessHtml.
	 */
	public static function spacelessHtmlHandler(string $s, ?int $phase = null): string
	{
		static $strip;
		$left = $right = '';

		if ($phase & PHP_OUTPUT_HANDLER_START) {
			$strip = true;
			$tmp = ltrim($s);
			$left = substr($s, 0, strlen($s) - strlen($tmp));
			$s = $tmp;
		}

		if ($phase & PHP_OUTPUT_HANDLER_FINAL) {
			$tmp = rtrim($s);
			$right = substr($s, strlen($tmp));
			$s = $tmp;
		}

		return $left . self::spacelessHtml($s, $strip) . $right;
	}


	/**
	 * Replaces all repeated white spaces with a single space.
	 */
	public static function spacelessText(string $s): string
	{
		return preg_replace('#[ \t\r\n]+#', ' ', $s);
	}


	/**
	 * Indents plain text or HTML the content from the left.
	 */
	public static function indent(FilterInfo $info, string $s, int $level = 1, string $chars = "\t"): string
	{
		if ($level < 1) {
			// do nothing
		} elseif ($info->contentType === ContentType::Html) {
			$s = preg_replace_callback('#<(textarea|pre).*?</\1#si', fn($m) => strtr($m[0], " \t\r\n", "\x1F\x1E\x1D\x1A"), $s);
			if (preg_last_error()) {
				throw new Latte\RuntimeException(preg_last_error_msg());
			}

			$s = preg_replace('#(?:^|[\r\n]+)(?=[^\r\n])#', '$0' . str_repeat($chars, $level), $s);
			$s = strtr($s, "\x1F\x1E\x1D\x1A", " \t\r\n");
		} else {
			$s = preg_replace('#(?:^|[\r\n]+)(?=[^\r\n])#', '$0' . str_repeat($chars, $level), $s);
		}

		return $s;
	}


	/**
	 * Join array of text or HTML elements with a string.
	 * @param  string[]  $arr
	 */
	public static function implode(array $arr, string $glue = ''): string
	{
		return implode($glue, $arr);
	}


	/**
	 * Splits a string by a string.
	 */
	public static function explode(string $value, string $separator = ''): array
	{
		return $separator === ''
			? preg_split('//u', $value, -1, PREG_SPLIT_NO_EMPTY)
			: explode($separator, $value);
	}


	/**
	 * Repeats text.
	 */
	public static function repeat(FilterInfo $info, $s, int $count): string
	{
		return str_repeat((string) $s, $count);
	}


	/**
	 * Date/time formatting.
	 */
	public static function date(string|int|\DateTimeInterface|\DateInterval|null $time, ?string $format = null): ?string
	{
		$format ??= Latte\Runtime\Filters::$dateFormat;
		if ($time == null) { // intentionally ==
			return null;
		} elseif ($time instanceof \DateInterval) {
			return $time->format($format);
		} elseif (is_numeric($time)) {
			$time = (new \DateTime)->setTimestamp((int) $time);
		} elseif (!$time instanceof \DateTimeInterface) {
			$time = new \DateTime($time);
		}

		return $time->format($format);
	}


	/**
	 * Date/time formatting according to locale.
	 */
	public function localDate(
		string|int|\DateTimeInterface|null $value,
		?string $format = null,
		?string $date = null,
		?string $time = null,
	): ?string
	{
		if ($this->locale === null) {
			throw new Latte\RuntimeException('Filter |localDate requires the locale to be set using Engine::setLocale()');
		} elseif ($value == null) { // intentionally ==
			return null;
		} elseif (is_numeric($value)) {
			$value = (new \DateTime)->setTimestamp((int) $value);
		} elseif (!$value instanceof \DateTimeInterface) {
			$value = new \DateTime($value);
			$errors = \DateTime::getLastErrors();
			if (!empty($errors['warnings'])) {
				throw new \InvalidArgumentException(reset($errors['warnings']));
			}
		}

		if ($format === null) {
			$xlt = ['' => \IntlDateFormatter::NONE, 'full' => \IntlDateFormatter::FULL, 'long' => \IntlDateFormatter::LONG, 'medium' => \IntlDateFormatter::MEDIUM, 'short' => \IntlDateFormatter::SHORT,
				'relative-full' => \IntlDateFormatter::RELATIVE_FULL, 'relative-long' => \IntlDateFormatter::RELATIVE_LONG, 'relative-medium' => \IntlDateFormatter::RELATIVE_MEDIUM, 'relative-short' => \IntlDateFormatter::RELATIVE_SHORT];
			$date ??= $time === null ? 'long' : null;
			$formatter = new \IntlDateFormatter($this->locale, $xlt[$date], $xlt[$time]);
		} else {
			$formatter = new \IntlDateFormatter($this->locale, pattern: (new \IntlDatePatternGenerator($this->locale))->getBestPattern($format));
		}

		$res = $formatter->format($value);
		$res = preg_replace('~(\d\.) ~', "\$1\u{a0}", $res);
		return $res;
	}


	/**
	 * Formats a number with grouped thousands and optionally decimal digits according to locale.
	 */
	public function number(
		float $number,
		string|int $patternOrDecimals = 0,
		string $decimalSeparator = '.',
		string $thousandsSeparator = ',',
	): string
	{
		if (is_int($patternOrDecimals) && $patternOrDecimals < 0) {
			throw new Latte\RuntimeException('Filter |number: the number of decimal must not be negative');
		} elseif ($this->locale === null || func_num_args() > 2) {
			if (is_string($patternOrDecimals)) {
				throw new Latte\RuntimeException('Filter |number: using a pattern requires setting a locale via Engine::setLocale().');
			}
			return number_format($number, $patternOrDecimals, $decimalSeparator, $thousandsSeparator);
		}

		$formatter = new \NumberFormatter($this->locale, \NumberFormatter::DECIMAL);
		if (is_string($patternOrDecimals)) {
			$formatter->setPattern($patternOrDecimals);
		} else {
			$formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, $patternOrDecimals);
		}
		return $formatter->format($number);
	}


	/**
	 * Converts to human-readable file size.
	 */
	public function bytes(float $bytes, int $precision = 2): string
	{
		$bytes = round($bytes);
		$units = ['B', 'kB', 'MB', 'GB', 'TB', 'PB'];
		foreach ($units as $unit) {
			if (abs($bytes) < 1024 || $unit === end($units)) {
				break;
			}

			$bytes /= 1024;
		}

		if ($this->locale === null) {
			$bytes = (string) round($bytes, $precision);
		} else {
			$formatter = new \NumberFormatter($this->locale, \NumberFormatter::DECIMAL);
			$formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $precision);
			$bytes = $formatter->format($bytes);
		}

		return $bytes . ' ' . $unit;
	}


	/**
	 * Performs a search and replace.
	 */
	public static function replace(
		FilterInfo $info,
		string|array $subject,
		string|array $search,
		string|array|null $replace = null,
	): string
	{
		$subject = (string) $subject;
		if (is_array($search)) {
			if (is_array($replace)) {
				return strtr($subject, array_combine($search, $replace));
			} elseif ($replace === null && is_string(key($search))) {
				return strtr($subject, $search);
			} else {
				return strtr($subject, array_fill_keys($search, $replace));
			}
		}

		return str_replace($search, $replace ?? '', $subject);
	}


	/**
	 * Perform a regular expression search and replace.
	 */
	public static function replaceRe(string $subject, string $pattern, string $replacement = ''): string
	{
		$res = preg_replace($pattern, $replacement, $subject);
		if (preg_last_error()) {
			throw new Latte\RuntimeException(preg_last_error_msg());
		}

		return $res;
	}


	/**
	 * The data: URI generator.
	 */
	public static function dataStream(string $data, ?string $type = null): string
	{
		$type ??= finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $data);
		return 'data:' . ($type ? "$type;" : '') . 'base64,' . base64_encode($data);
	}


	public static function breaklines(string|Stringable|null $s): Html
	{
		$s = htmlspecialchars((string) $s, ENT_NOQUOTES | ENT_SUBSTITUTE, 'UTF-8');
		return new Html(nl2br($s, false));
	}


	/**
	 * Returns a part of string.
	 */
	public static function substring(string|Stringable|null $s, int $start, ?int $length = null): string
	{
		$s = (string) $s;
		return match (true) {
			extension_loaded('mbstring') => mb_substr($s, $start, $length, 'UTF-8'),
			extension_loaded('iconv') => iconv_substr($s, $start, $length, 'UTF-8'),
			default => throw new Latte\RuntimeException("Filter |substr requires 'mbstring' or 'iconv' extension."),
		};
	}


	/**
	 * Truncates string to maximal length.
	 */
	public static function truncate(string|Stringable|null $s, int $length, string $append = "\u{2026}"): string
	{
		$s = (string) $s;
		if (self::strLength($s) > $length) {
			$length -= self::strLength($append);
			if ($length < 1) {
				return $append;

			} elseif (preg_match('#^.{1,' . $length . '}(?=[\s\x00-/:-@\[-`{-~])#us', $s, $matches)) {
				return $matches[0] . $append;

			} else {
				return self::substring($s, 0, $length) . $append;
			}
		}

		return $s;
	}


	/**
	 * Convert to lower case.
	 */
	public static function lower($s): string
	{
		return mb_strtolower((string) $s, 'UTF-8');
	}


	/**
	 * Convert to upper case.
	 */
	public static function upper($s): string
	{
		return mb_strtoupper((string) $s, 'UTF-8');
	}


	/**
	 * Convert first character to lower case.
	 */
	public static function firstLower($s): string
	{
		$s = (string) $s;
		return self::lower(self::substring($s, 0, 1)) . self::substring($s, 1);
	}


	/**
	 * Convert first character to upper case.
	 */
	public static function firstUpper($s): string
	{
		$s = (string) $s;
		return self::upper(self::substring($s, 0, 1)) . self::substring($s, 1);
	}


	/**
	 * Capitalize string.
	 */
	public static function capitalize($s): string
	{
		return mb_convert_case((string) $s, MB_CASE_TITLE, 'UTF-8');
	}


	/**
	 * Returns length of string or iterable.
	 */
	public static function length(array|\Countable|\Traversable|string $val): int
	{
		if (is_array($val) || $val instanceof \Countable) {
			return count($val);
		} elseif ($val instanceof \Traversable) {
			return iterator_count($val);
		} else {
			return self::strLength($val);
		}
	}


	private static function strLength(string $s): int
	{
		return match (true) {
			extension_loaded('mbstring') => mb_strlen($s, 'UTF-8'),
			extension_loaded('iconv') => iconv_strlen($s, 'UTF-8'),
			default => strlen(@utf8_decode($s)), // deprecated
		};
	}


	/**
	 * Strips whitespace.
	 */
	public static function trim(FilterInfo $info, string $s, string $charlist = " \t\n\r\0\x0B\u{A0}"): string
	{
		$charlist = preg_quote($charlist, '#');
		$s = preg_replace('#^[' . $charlist . ']+|[' . $charlist . ']+$#Du', '', (string) $s);
		if (preg_last_error()) {
			throw new Latte\RuntimeException(preg_last_error_msg());
		}

		return $s;
	}


	/**
	 * Pad a string to a certain length with another string.
	 */
	public static function padLeft($s, int $length, string $append = ' '): string
	{
		$s = (string) $s;
		$length = max(0, $length - self::strLength($s));
		$l = self::strLength($append);
		return str_repeat($append, (int) ($length / $l)) . self::substring($append, 0, $length % $l) . $s;
	}


	/**
	 * Pad a string to a certain length with another string.
	 */
	public static function padRight($s, int $length, string $append = ' '): string
	{
		$s = (string) $s;
		$length = max(0, $length - self::strLength($s));
		$l = self::strLength($append);
		return $s . str_repeat($append, (int) ($length / $l)) . self::substring($append, 0, $length % $l);
	}


	/**
	 * Reverses string or array.
	 */
	public static function reverse(string|iterable $val, bool $preserveKeys = false): string|array
	{
		if (is_array($val)) {
			return array_reverse($val, $preserveKeys);
		} elseif ($val instanceof \Traversable) {
			return array_reverse(iterator_to_array($val), $preserveKeys);
		} else {
			return iconv('UTF-32LE', 'UTF-8', strrev(iconv('UTF-8', 'UTF-32BE', (string) $val)));
		}
	}


	/**
	 * Chunks items by returning an array of arrays with the given number of items.
	 */
	public static function batch(iterable $list, int $length, $rest = null): \Generator
	{
		$batch = [];
		foreach ($list as $key => $value) {
			$batch[$key] = $value;
			if (count($batch) >= $length) {
				yield $batch;
				$batch = [];
			}
		}

		if ($batch) {
			if ($rest !== null) {
				while (count($batch) < $length) {
					$batch[] = $rest;
				}
			}

			yield $batch;
		}
	}


	/**
	 * Sorts elements using the comparison function and preserves the key association.
	 * @template K
	 * @template V
	 * @param  iterable<K, V>  $data
	 * @return iterable<K, V>
	 */
	public function sort(
		iterable $data,
		?\Closure $comparison = null,
		string|int|\Closure|null $by = null,
		string|int|\Closure|bool $byKey = false,
	): iterable
	{
		if ($byKey !== false) {
			if ($by !== null) {
				throw new \InvalidArgumentException('Filter |sort cannot use both $by and $byKey.');
			}
			$by = $byKey === true ? null : $byKey;
		}

		if ($comparison) {
		} elseif ($this->locale === null) {
			$comparison = fn($a, $b) => $a <=> $b;
		} else {
			$collator = new \Collator($this->locale);
			$comparison = fn($a, $b) => is_string($a) && is_string($b)
				? $collator->compare($a, $b)
				: $a <=> $b;
		}

		$comparison = match (true) {
			$by === null => $comparison,
			$by instanceof \Closure => fn($a, $b) => $comparison($by($a), $by($b)),
			default => fn($a, $b) => $comparison(is_array($a) ? $a[$by] : $a->$by, is_array($b) ? $b[$by] : $b->$by),
		};

		if (is_array($data)) {
			$byKey ? uksort($data, $comparison) : uasort($data, $comparison);
			return $data;
		}

		$pairs = [];
		foreach ($data as $key => $value) {
			$pairs[] = [$key, $value];
		}
		uasort($pairs, fn($a, $b) => $byKey ? $comparison($a[0], $b[0]) : $comparison($a[1], $b[1]));

		return new AuxiliaryIterator($pairs);
	}


	/**
	 * Groups elements by the element indices and preserves the key association and order.
	 * @template K
	 * @template V
	 * @param  iterable<K, V>  $data
	 * @return iterable<iterable<K, V>>
	 */
	public static function group(iterable $data, string|int|\Closure $by): iterable
	{
		$fn = $by instanceof \Closure ? $by : fn($a) => is_array($a) ? $a[$by] : $a->$by;
		$keys = $groups = [];

		foreach ($data as $k => $v) {
			$groupKey = $fn($v, $k);
			if (!$groups || $prevKey !== $groupKey) {
				$index = array_search($groupKey, $keys, true);
				if ($index === false) {
					$index = count($keys);
					$keys[$index] = $groupKey;
				}
				$prevKey = $groupKey;
			}
			$groups[$index][] = [$k, $v];
		}

		return new AuxiliaryIterator(array_map(
			fn($key, $group) => [$key, new AuxiliaryIterator($group)],
			$keys,
			$groups,
		));
	}


	/**
	 * Filters elements according to a given $predicate. Maintains original keys.
	 * @template K
	 * @template V
	 * @param  iterable<K, V>  $iterable
	 * @param  callable(V, K, iterable<K, V>): bool  $predicate
	 * @return iterable<K, V>
	 */
	public static function filter(iterable $iterable, callable $predicate): iterable
	{
		foreach ($iterable as $k => $v) {
			if ($predicate($v, $k, $iterable)) {
				yield $k => $v;
			}
		}
	}


	/**
	 * Returns value clamped to the inclusive range of min and max.
	 */
	public static function clamp(int|float $value, int|float $min, int|float $max): int|float
	{
		if ($min > $max) {
			throw new \InvalidArgumentException("Minimum ($min) is not less than maximum ($max).");
		}

		return min(max($value, $min), $max);
	}


	/**
	 * Generates URL-encoded query string
	 */
	public static function query(string|array $data): string
	{
		return is_array($data)
			? http_build_query($data, '', '&')
			: urlencode($data);
	}


	/**
	 * Is divisible by?
	 */
	public static function divisibleBy(int $value, int $by): bool
	{
		return $value % $by === 0;
	}


	/**
	 * Is odd?
	 */
	public static function odd(int $value): bool
	{
		return $value % 2 !== 0;
	}


	/**
	 * Is even?
	 */
	public static function even(int $value): bool
	{
		return $value % 2 === 0;
	}


	/**
	 * Returns the first element in an array or character in a string, or null if none.
	 */
	public static function first(string|iterable $value): mixed
	{
		if (is_string($value)) {
			return self::substring($value, 0, 1);
		}

		foreach ($value as $item) {
			return $item;
		}

		return null;
	}


	/**
	 * Returns the last element in an array or character in a string, or null if none.
	 */
	public static function last(string|array $value): mixed
	{
		return is_array($value)
			? ($value[array_key_last($value)] ?? null)
			: self::substring($value, -1);
	}


	/**
	 * Extracts a slice of an array or string.
	 */
	public static function slice(
		string|array $value,
		int $start,
		?int $length = null,
		bool $preserveKeys = false,
	): string|array
	{
		return is_array($value)
			? array_slice($value, $start, $length, $preserveKeys)
			: self::substring($value, $start, $length);
	}


	public static function round(float $value, int $precision = 0): float
	{
		return round($value, $precision);
	}


	public static function floor(float $value, int $precision = 0): float
	{
		return floor($value * 10 ** $precision) / 10 ** $precision;
	}


	public static function ceil(float $value, int $precision = 0): float
	{
		return ceil($value * 10 ** $precision) / 10 ** $precision;
	}


	/**
	 * Picks random element/char.
	 */
	public static function random(string|array $values): mixed
	{
		if (is_string($values)) {
			$values = preg_split('//u', $values, -1, PREG_SPLIT_NO_EMPTY);
		}

		return $values
			? $values[array_rand($values, 1)]
			: null;
	}
}
