<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;

use Latte;
use Latte\Engine;


/**
 * Template filters. Uses UTF-8 only.
 * @internal
 */
class Filters
{
	/** @deprecated */
	public static $dateFormat = '%x';

	/** @internal @var bool  use XHTML syntax? */
	public static $xhtml = false;


	/**
	 * Escapes string for use everywhere inside HTML (except for comments).
	 * @param  mixed  $s  plain text
	 * @return string HTML
	 */
	public static function escapeHtml($s): string
	{
		return htmlspecialchars((string) $s, ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE, 'UTF-8');
	}


	/**
	 * Escapes string for use inside HTML text.
	 * @param  mixed  $s  plain text or HtmlStringable
	 * @return string HTML
	 */
	public static function escapeHtmlText($s): string
	{
		return $s instanceof HtmlStringable || $s instanceof \Nette\Utils\IHtmlString
			? $s->__toString(true)
			: htmlspecialchars((string) $s, ENT_NOQUOTES | ENT_SUBSTITUTE, 'UTF-8');
	}


	/**
	 * Escapes string for use inside HTML attribute value.
	 * @param  mixed  $s  plain text
	 * @return string HTML
	 */
	public static function escapeHtmlAttr($s, bool $double = true): string
	{
		$double = $double && $s instanceof HtmlStringable ? false : $double;
		$s = (string) $s;
		if (strpos($s, '`') !== false && strpbrk($s, ' <>"\'') === false) {
			$s .= ' '; // protection against innerHTML mXSS vulnerability nette/nette#1496
		}
		return htmlspecialchars($s, ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE, 'UTF-8', $double);
	}


	/**
	 * Escapes HTML for use inside HTML attribute.
	 * @param  mixed  $s  HTML text
	 * @return string HTML
	 */
	public static function escapeHtmlAttrConv($s): string
	{
		return self::escapeHtmlAttr($s, false);
	}


	/**
	 * Escapes string for use inside HTML attribute name.
	 * @param  string  $s  plain text
	 * @return string HTML
	 */
	public static function escapeHtmlAttrUnquoted($s): string
	{
		$s = (string) $s;
		return preg_match('#^[a-z0-9:-]+$#i', $s)
			? $s
			: '"' . self::escapeHtmlAttr($s) . '"';
	}


	/**
	 * Escapes string for use inside HTML/XML comments.
	 * @param  string  $s  plain text
	 * @return string HTML
	 */
	public static function escapeHtmlComment($s): string
	{
		$s = (string) $s;
		if ($s && ($s[0] === '-' || $s[0] === '>' || $s[0] === '!')) {
			$s = ' ' . $s;
		}
		$s = str_replace('--', '- - ', $s);
		if (substr($s, -1) === '-') {
			$s .= ' ';
		}
		return $s;
	}


	/**
	 * Escapes string for use everywhere inside XML (except for comments).
	 * @param  string  $s  plain text
	 * @return string XML
	 */
	public static function escapeXml($s): string
	{
		// XML 1.0: \x09 \x0A \x0D and C1 allowed directly, C0 forbidden
		// XML 1.1: \x00 forbidden directly and as a character reference,
		//   \x09 \x0A \x0D \x85 allowed directly, C0, C1 and \x7F allowed as character references
		$s = preg_replace('#[\x00-\x08\x0B\x0C\x0E-\x1F]#', "\u{FFFD}", (string) $s);
		return htmlspecialchars($s, ENT_QUOTES | ENT_XML1 | ENT_SUBSTITUTE, 'UTF-8');
	}


	/**
	 * Escapes string for use inside XML attribute name.
	 * @param  string  $s  plain text
	 * @return string XML
	 */
	public static function escapeXmlAttrUnquoted($s): string
	{
		$s = (string) $s;
		return preg_match('#^[a-z0-9:-]+$#i', $s)
			? $s
			: '"' . self::escapeXml($s) . '"';
	}


	/**
	 * Escapes string for use inside CSS template.
	 * @param  string  $s  plain text
	 * @return string CSS
	 */
	public static function escapeCss($s): string
	{
		// http://www.w3.org/TR/2006/WD-CSS21-20060411/syndata.html#q6
		return addcslashes((string) $s, "\x00..\x1F!\"#$%&'()*+,./:;<=>?@[\\]^`{|}~");
	}


	/**
	 * Escapes variables for use inside <script>.
	 * @param  mixed  $s  plain text
	 * @return string JSON
	 */
	public static function escapeJs($s): string
	{
		if ($s instanceof HtmlStringable || $s instanceof \Nette\Utils\IHtmlString) {
			$s = $s->__toString(true);
		}

		$json = json_encode($s, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		if ($error = json_last_error()) {
			throw new Latte\RuntimeException(json_last_error_msg(), $error);
		}

		return str_replace([']]>', '<!', '</'], [']]\u003E', '\u003C!', '<\/'], $json);
	}


	/**
	 * Escapes string for use inside iCal template.
	 * @param  string  $s  plain text
	 */
	public static function escapeICal($s): string
	{
		// https://www.ietf.org/rfc/rfc5545.txt
		$s = str_replace("\r", '', (string) $s);
		$s = preg_replace('#[\x00-\x08\x0B-\x1F]#', "\u{FFFD}", (string) $s);
		return addcslashes($s, "\";\\,:\n");
	}


	/**
	 * Escapes CSS/JS for usage in <script> and <style>..
	 * @param  string  $s  CSS/JS
	 * @return string HTML RAWTEXT
	 */
	public static function escapeHtmlRawText($s): string
	{
		return preg_replace('#</(script|style)#i', '<\/$1', (string) $s);
	}


	/**
	 * Converts HTML to plain text.
	 * @param  string  $s  HTML
	 * @return string plain text
	 */
	public static function stripHtml(FilterInfo $info, $s): string
	{
		if (!in_array($info->contentType, [null, 'html', 'xhtml', 'htmlAttr', 'xhtmlAttr', 'xml', 'xmlAttr'], true)) {
			trigger_error('Filter |stripHtml used with incompatible type ' . strtoupper($info->contentType), E_USER_WARNING);
		}
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
		if (!in_array($info->contentType, [null, 'html', 'xhtml', 'htmlAttr', 'xhtmlAttr', 'xml', 'xmlAttr'], true)) {
			trigger_error('Filter |stripTags used with incompatible type ' . strtoupper($info->contentType), E_USER_WARNING);
		}
		return strip_tags((string) $s);
	}


	/**
	 * Converts ... to ...
	 */
	public static function convertTo(FilterInfo $info, string $dest, $s): string
	{
		$source = $info->contentType ?: Engine::CONTENT_TEXT;
		if ($source === $dest) {
			return $s;
		} elseif ($conv = self::getConvertor($source, $dest)) {
			$info->contentType = $dest;
			return $conv($s);
		} else {
			trigger_error('Filters: unable to convert content type ' . strtoupper($source) . ' to ' . strtoupper($dest), E_USER_WARNING);
			return $s;
		}
	}


	public static function getConvertor(string $source, string $dest): ?callable
	{
		static $table = [
			Engine::CONTENT_TEXT => [
				'html' => 'escapeHtmlText', 'xhtml' => 'escapeHtmlText',
				'htmlAttr' => 'escapeHtmlAttr', 'xhtmlAttr' => 'escapeHtmlAttr',
				'htmlAttrJs' => 'escapeHtmlAttr', 'xhtmlAttrJs' => 'escapeHtmlAttr',
				'htmlAttrCss' => 'escapeHtmlAttr', 'xhtmlAttrCss' => 'escapeHtmlAttr',
				'htmlAttrUrl' => 'escapeHtmlAttr', 'xhtmlAttrUrl' => 'escapeHtmlAttr',
				'htmlComment' => 'escapeHtmlComment', 'xhtmlComment' => 'escapeHtmlComment',
				'xml' => 'escapeXml', 'xmlAttr' => 'escapeXml',
			],
			Engine::CONTENT_JS => [
				'html' => 'escapeHtmlText', 'xhtml' => 'escapeHtmlText',
				'htmlAttr' => 'escapeHtmlAttr', 'xhtmlAttr' => 'escapeHtmlAttr',
				'htmlAttrJs' => 'escapeHtmlAttr', 'xhtmlAttrJs' => 'escapeHtmlAttr',
				'htmlJs' => 'escapeHtmlRawText', 'xhtmlJs' => 'escapeHtmlRawText',
				'htmlComment' => 'escapeHtmlComment', 'xhtmlComment' => 'escapeHtmlComment',
			],
			Engine::CONTENT_CSS => [
				'html' => 'escapeHtmlText', 'xhtml' => 'escapeHtmlText',
				'htmlAttr' => 'escapeHtmlAttr', 'xhtmlAttr' => 'escapeHtmlAttr',
				'htmlAttrCss' => 'escapeHtmlAttr', 'xhtmlAttrCss' => 'escapeHtmlAttr',
				'htmlCss' => 'escapeHtmlRawText', 'xhtmlCss' => 'escapeHtmlRawText',
				'htmlComment' => 'escapeHtmlComment', 'xhtmlComment' => 'escapeHtmlComment',
			],
			Engine::CONTENT_HTML => [
				'htmlAttr' => 'escapeHtmlAttrConv',
				'htmlAttrJs' => 'escapeHtmlAttrConv',
				'htmlAttrCss' => 'escapeHtmlAttrConv',
				'htmlAttrUrl' => 'escapeHtmlAttrConv',
				'htmlComment' => 'escapeHtmlComment',
			],
			Engine::CONTENT_XHTML => [
				'xhtmlAttr' => 'escapeHtmlAttrConv',
				'xhtmlAttrJs' => 'escapeHtmlAttrConv',
				'xhtmlAttrCss' => 'escapeHtmlAttrConv',
				'xhtmlAttrUrl' => 'escapeHtmlAttrConv',
				'xhtmlComment' => 'escapeHtmlComment',
			],
		];
		return isset($table[$source][$dest])
			? [self::class, $table[$source][$dest]]
			: null;
	}


	/**
	 * Sanitizes string for use inside href attribute.
	 * @param  string  $s  plain text
	 * @return string plain text
	 */
	public static function safeUrl($s): string
	{
		$s = (string) $s;
		return preg_match('~^(?:(?:https?|ftp)://[^@]+(?:/.*)?|(?:mailto|tel|sms):.+|[/?#].*|[^:]+)$~Di', $s) ? $s : '';
	}


	/**
	 * Replaces all repeated white spaces with a single space.
	 * @param  string  $s  text|HTML
	 * @return string text|HTML
	 */
	public static function strip(FilterInfo $info, string $s): string
	{
		return in_array($info->contentType, [Engine::CONTENT_HTML, Engine::CONTENT_XHTML], true)
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
			$s
		);
	}


	/**
	 * Output buffering handler for spacelessHtml.
	 */
	public static function spacelessHtmlHandler(string $s, int $phase = null): string
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
		} elseif (in_array($info->contentType, [Engine::CONTENT_HTML, Engine::CONTENT_XHTML], true)) {
			$s = preg_replace_callback('#<(textarea|pre).*?</\1#si', function ($m) {
				return strtr($m[0], " \t\r\n", "\x1F\x1E\x1D\x1A");
			}, $s);
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
	public static function date($time, string $format = null): ?string
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
		return strpos($format, '%') === false
			? $time->format($format) // formats using date()
			: strftime($format, $time->format('U') + 0); // formats according to locales
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
	 * @param  string|string[]  $replacement
	 */
	public static function replace(FilterInfo $info, $subject, $search, $replacement = ''): string
	{
		return str_replace($search, $replacement, (string) $subject);
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
	public static function dataStream(string $data, string $type = null): string
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
		return new Html(nl2br(htmlspecialchars((string) $s, ENT_NOQUOTES | ENT_SUBSTITUTE, 'UTF-8'), self::$xhtml));
	}


	/**
	 * Returns a part of string.
	 */
	public static function substring($s, int $start, int $length = null): string
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
	public static function sort(array $array): array
	{
		sort($array);
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
				if (static::$xhtml) {
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

			$q = strpos($value, '"') === false ? '"' : "'";
			$s .= ' ' . $key . '=' . $q
				. str_replace(
					['&', $q, '<'],
					['&amp;', $q === '"' ? '&quot;' : '&#39;', self::$xhtml ? '&lt;' : '<'],
					$value
				)
				. (strpos($value, '`') !== false && strpbrk($value, ' <>"\'') === false ? ' ' : '')
				. $q;
		}
		return $s;
	}


	public static function checkTagSwitch(string $orig, $new): void
	{
		if (
			!is_string($new)
			|| !preg_match('~' . Latte\Parser::RE_TAG_NAME . '$~DA', $new)
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
