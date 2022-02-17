<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Extensions;

use Latte;
use Latte\Engine;
use Latte\Runtime\FilterInfo;
use Latte\Runtime\Html;
use function is_array, is_string, count, strlen;


/**
 * Template filters. Uses UTF-8 only.
 * @internal
 */
class Filters
{
	/** @deprecated */
	public static string $dateFormat = "j.\u{a0}n.\u{a0}Y";

	/** @internal use XML syntax? */
	public static bool $xml = false;


	/**
	 * Converts HTML to plain text.
	 * @param  string  $s  HTML
	 * @return string plain text
	 */
	public static function stripHtml(FilterInfo $info, $s): string
	{
		$info->validate([null, 'html', 'htmlAttr', 'xml', 'xmlAttr'], __FUNCTION__);
		$info->contentType = Engine::CONTENT_TEXT;
		return html_entity_decode(strip_tags((string) $s), ENT_QUOTES | ENT_HTML5, 'UTF-8');
	}


	/**
	 * Removes tags from HTML (but remains HTML entites).
	 * @param  string  $s  HTML
	 * @return string HTML
	 */
	public static function stripTags(FilterInfo $info, $s): string
	{
		$info->contentType ??= 'html';
		$info->validate(['html', 'htmlAttr', 'xml', 'xmlAttr'], __FUNCTION__);
		return strip_tags((string) $s);
	}


	/**
	 * Replaces all repeated white spaces with a single space.
	 * @param  string  $s  text|HTML
	 * @return string text|HTML
	 */
	public static function strip(FilterInfo $info, string $s): string
	{
		return $info->contentType === Engine::CONTENT_HTML
			? trim(self::spacelessHtml($s))
			: trim(self::spacelessText($s));
	}


	/**
	 * Replaces all repeated white spaces with a single space.
	 * @param  string  $s  HTML
	 * @param  bool  $strip  stripping mode
	 * @return string HTML
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
	 * @return string text
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
		} elseif ($info->contentType === Engine::CONTENT_HTML) {
			$s = preg_replace_callback('#<(textarea|pre).*?</\1#si', fn($m) => strtr($m[0], " \t\r\n", "\x1F\x1E\x1D\x1A"), $s);
			if (preg_last_error()) {
				throw new Latte\RegexpException(null, preg_last_error());
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
	 * @return string text|HTML
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
	 * @return string plain text
	 */
	public static function repeat(FilterInfo $info, $s, int $count): string
	{
		return str_repeat((string) $s, $count);
	}


	/**
	 * Date/time formatting.
	 * @param  string|int|\DateTimeInterface|\DateInterval  $time
	 */
	public static function date($time, ?string $format = null): ?string
	{
		if ($time == null) { // intentionally ==
			return null;
		}

		if (!isset($format)) {
			$format = self::$dateFormat;
		}

		if ($time instanceof \DateInterval) {
			return $time->format($format);

		} elseif (is_numeric($time)) {
			$time = new \DateTime('@' . $time);
			$time->setTimeZone(new \DateTimeZone(date_default_timezone_get()));

		} elseif (!$time instanceof \DateTimeInterface) {
			$time = new \DateTime($time);
		}

		if (str_contains($format, '%')) {
			if (PHP_VERSION_ID >= 80100) {
				trigger_error("Function strftime() used by filter |date is deprecated since PHP 8.1, use format without % characters like 'Y-m-d'.", E_USER_DEPRECATED);
			}

			return @strftime($format, $time->format('U') + 0);
		}

		return $time->format($format);
	}


	/**
	 * Converts to human readable file size.
	 * @return string plain text
	 */
	public static function bytes(float $bytes, int $precision = 2): string
	{
		$bytes = round($bytes);
		$units = ['B', 'kB', 'MB', 'GB', 'TB', 'PB'];
		foreach ($units as $unit) {
			if (abs($bytes) < 1024 || $unit === end($units)) {
				break;
			}

			$bytes /= 1024;
		}

		return round($bytes, $precision) . ' ' . $unit;
	}


	/**
	 * Performs a search and replace.
	 * @param  string|string[]  $search
	 * @param  string|string[]  $replace
	 */
	public static function replace(FilterInfo $info, $subject, $search, $replace = null): string
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
			throw new Latte\RegexpException(null, preg_last_error());
		}

		return $res;
	}


	/**
	 * The data: URI generator.
	 * @return string plain text
	 */
	public static function dataStream(string $data, ?string $type = null): string
	{
		if ($type === null) {
			$type = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $data);
		}

		return 'data:' . ($type ? "$type;" : '') . 'base64,' . base64_encode($data);
	}


	/**
	 * @param  string  $s  plain text
	 */
	public static function breaklines($s): Html
	{
		return new Html(nl2br(htmlspecialchars((string) $s, ENT_NOQUOTES | ENT_SUBSTITUTE, 'UTF-8'), self::$xml));
	}


	/**
	 * Returns a part of string.
	 */
	public static function substring($s, int $start, ?int $length = null): string
	{
		$s = (string) $s;
		if ($length === null) {
			$length = self::strLength($s);
		}

		if (function_exists('mb_substr')) {
			return mb_substr($s, $start, $length, 'UTF-8'); // MB is much faster
		}

		return iconv_substr($s, $start, $length, 'UTF-8');
	}


	/**
	 * Truncates string to maximal length.
	 * @return string plain text
	 */
	public static function truncate($s, $length, $append = "\u{2026}"): string
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
	 * @param  string  $s  plain text
	 * @return string plain text
	 */
	public static function lower($s): string
	{
		return mb_strtolower((string) $s, 'UTF-8');
	}


	/**
	 * Convert to upper case.
	 * @param  string  $s  plain text
	 * @return string plain text
	 */
	public static function upper($s): string
	{
		return mb_strtoupper((string) $s, 'UTF-8');
	}


	/**
	 * Convert first character to upper case.
	 * @param  string  $s  plain text
	 * @return string plain text
	 */
	public static function firstUpper($s): string
	{
		$s = (string) $s;
		return self::upper(self::substring($s, 0, 1)) . self::substring($s, 1);
	}


	/**
	 * Capitalize string.
	 * @param  string  $s  plain text
	 * @return string plain text
	 */
	public static function capitalize($s): string
	{
		return mb_convert_case((string) $s, MB_CASE_TITLE, 'UTF-8');
	}


	/**
	 * Returns length of string or iterable.
	 * @param  array|\Countable|\Traversable|string  $val
	 */
	public static function length($val): int
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
		return function_exists('mb_strlen')
			? mb_strlen($s, 'UTF-8')
			: strlen(utf8_decode($s));
	}


	/**
	 * Strips whitespace.
	 */
	public static function trim(FilterInfo $info, $s, string $charlist = " \t\n\r\0\x0B\u{A0}"): string
	{
		$charlist = preg_quote($charlist, '#');
		$s = preg_replace('#^[' . $charlist . ']+|[' . $charlist . ']+$#Du', '', (string) $s);
		if (preg_last_error()) {
			throw new Latte\RegexpException(null, preg_last_error());
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
	 * @param  string|array|\Traversable  $val
	 */
	public static function reverse($val, bool $preserveKeys = false)
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
	 * @param  array|\Traversable  $list
	 */
	public static function batch($list, int $length, $rest = null): \Generator
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
	 * Sorts an array.
	 * @param  mixed[]  $array
	 * @return mixed[]
	 */
	public static function sort(array $array, ?\Closure $callback = null): array
	{
		$callback ? uasort($array, $callback) : asort($array);
		return $array;
	}


	/**
	 * Returns value clamped to the inclusive range of min and max.
	 * @param  int|float  $value
	 * @param  int|float  $min
	 * @param  int|float  $max
	 * @return int|float
	 */
	public static function clamp($value, $min, $max)
	{
		if ($min > $max) {
			throw new \InvalidArgumentException("Minimum ($min) is not less than maximum ($max).");
		}

		return min(max($value, $min), $max);
	}


	/**
	 * Generates URL-encoded query string
	 * @param  string|array  $data
	 * @return string
	 */
	public static function query($data): string
	{
		return is_array($data)
			? http_build_query($data, '', '&')
			: urlencode((string) $data);
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
	 * Returns the first item from the array or null if array is empty.
	 * @param  string|array  $value
	 * @return mixed
	 */
	public static function first($value)
	{
		return is_array($value)
			? (count($value) ? reset($value) : null)
			: self::substring($value, 0, 1);
	}


	/**
	 * Returns the last item from the array or null if array is empty.
	 * @param  string|array  $value
	 * @return mixed
	 */
	public static function last($value)
	{
		return is_array($value)
			? (count($value) ? end($value) : null)
			: self::substring($value, -1);
	}


	/**
	 * Extracts a slice of an array or string.
	 * @param  string|array  $value
	 * @return string|array
	 */
	public static function slice($value, int $start, ?int $length = null, bool $preserveKeys = false)
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
	 * @param  string|array  $value
	 */
	public static function random($values): mixed
	{
		if (is_string($values)) {
			$values = preg_split('//u', $values, -1, PREG_SPLIT_NO_EMPTY);
		}

		return $values
			? $values[array_rand($values, 1)]
			: null;
	}


	/**
	 * Returns element's attributes.
	 */
	public static function htmlAttributes($attrs): string
	{
		if (!is_array($attrs)) {
			return '';
		}

		$s = '';
		foreach ($attrs as $key => $value) {
			if ($value === null || $value === false) {
				continue;

			} elseif ($value === true) {
				if (static::$xml) {
					$s .= ' ' . $key . '="' . $key . '"';
				} else {
					$s .= ' ' . $key;
				}

				continue;

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
					continue;
				}

				$value = implode($key === 'style' || !strncmp($key, 'on', 2) ? ';' : ' ', $tmp);

			} else {
				$value = (string) $value;
			}

			$q = !str_contains($value, '"') ? '"' : "'";
			$s .= ' ' . $key . '=' . $q
				. str_replace(
					['&', $q, '<'],
					['&amp;', $q === '"' ? '&quot;' : '&#39;', self::$xml ? '&lt;' : '<'],
					$value,
				)
				. (str_contains($value, '`') && strpbrk($value, ' <>"\'') === false ? ' ' : '')
				. $q;
		}

		return $s;
	}


	public static function checkTagSwitch(string $orig, $new): void
	{
		if (
			!is_string($new)
			|| !preg_match('~' . Latte\Compiler\Lexer::RE_TAG_NAME . '$~DA', $new)
		) {
			throw new Latte\RuntimeException('Invalid tag name ' . var_export($new, true));
		}

		$new = strtolower($new);
		if (
			$new === 'style' || $new === 'script'
			|| isset(Latte\Helpers::$emptyElements[strtolower($orig)]) !== isset(Latte\Helpers::$emptyElements[$new])
		) {
			throw new Latte\RuntimeException("Forbidden tag <$orig> change to <$new>.");
		}
	}
}
