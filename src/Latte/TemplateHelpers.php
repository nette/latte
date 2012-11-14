<?php

/**
 * Nette Framework
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @license    http://nettephp.com/license  Nette license
 * @link       http://nettephp.com
 * @category   Nette
 * @package    Nette\Templates
 */

/*namespace Nette\Templates;*/



/**
 * Standard template run-time helpers shipped with Nette Framework.
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @package    Nette\Templates
 */
final class TemplateHelpers
{

	/**
	 * Static class - cannot be instantiated.
	 */
	final public function __construct()
	{
		throw new /*\*/LogicException("Cannot instantiate static class " . get_class($this));
	}



	/**
	 * Try to load the requested helper.
	 * @param  string  helper name
	 * @return callback
	 */
	public static function loader($helper)
	{
		$callback = callback(/*Nette\Templates\*/'TemplateHelpers', $helper);
		if ($callback->isCallable()) {
			return $callback;
		}
		$callback = callback(/*Nette\*/'String', $helper);
		if ($callback->isCallable()) {
			return $callback;
		}
	}



	/**
	 * Escapes string for use inside HTML template.
	 * @param  mixed  UTF-8 encoding or 8-bit
	 * @return string
	 */
	public static function escapeHtml($s)
	{
		if (is_object($s) && ($s instanceof ITemplate || $s instanceof /*Nette\Web\*/Html || $s instanceof /*Nette\Forms\*/Form)) {
			return $s->__toString(TRUE);
		}
		return htmlSpecialChars($s, ENT_QUOTES);
	}



	/**
	 * Escapes string for use inside HTML comments.
	 * @param  mixed  UTF-8 encoding or 8-bit
	 * @return string
	 */
	public static function escapeHtmlComment($s)
	{
		// -- has special meaning in different browsers
		return str_replace('--', '--><!--', $s); // HTML tags have no meaning inside comments
	}



	/**
	 * Escapes string for use inside XML 1.0 template.
	 * @param  string UTF-8 encoding or 8-bit
	 * @return string
	 */
	public static function escapeXML($s)
	{
		// XML 1.0: \x09 \x0A \x0D and C1 allowed directly, C0 forbidden
		// XML 1.1: \x00 forbidden directly and as a character reference, \x09 \x0A \x0D \x85 allowed directly, C0, C1 and \x7F allowed as character references
		return htmlSpecialChars(preg_replace('#[\x00-\x08\x0B\x0C\x0E-\x1F]+#', '', $s), ENT_QUOTES);
	}



	/**
	 * Escapes string for use inside CSS template.
	 * @param  string UTF-8 encoding or 8-bit
	 * @return string
	 */
	public static function escapeCss($s)
	{
		// http://www.w3.org/TR/2006/WD-CSS21-20060411/syndata.html#q6
		return addcslashes($s, "\x00..\x2C./:;<=>?@[\\]^`{|}~");
	}



	/**
	 * Escapes string for use inside HTML style attribute.
	 * @param  string UTF-8 encoding or 8-bit
	 * @return string
	 */
	public static function escapeHtmlCss($s)
	{
		return htmlSpecialChars(self::escapeCss($s), ENT_QUOTES);
	}



	/**
	 * Escapes string for use inside JavaScript template.
	 * @param  mixed  UTF-8 encoding
	 * @return string
	 */
	public static function escapeJs($s)
	{
		if (is_object($s) && ($s instanceof ITemplate || $s instanceof /*Nette\Web\*/Html || $s instanceof /*Nette\Forms\*/Form)) {
			$s = $s->__toString(TRUE);
		}
		return str_replace(']]>', ']]\x3E', json_encode($s));
	}



	/**
	 * Escapes string for use inside HTML JavaScript attribute.
	 * @param  mixed  UTF-8 encoding
	 * @return string
	 */
	public static function escapeHtmlJs($s)
	{
		return htmlSpecialChars(self::escapeJs($s), ENT_QUOTES);
	}



	/**
	 * Replaces all repeated white spaces with a single space.
	 * @param  string UTF-8 encoding or 8-bit
	 * @return string
	 */
	public static function strip($s)
	{
		$s = preg_replace_callback('#<(textarea|pre|script).*?</\\1#si', array(__CLASS__, 'indentCb'), $s);
		$s = trim(preg_replace('#[ \t\r\n]+#', ' ', $s));
		return strtr($s, "\x1F\x1E\x1D\x1A", " \t\r\n");
	}



	/**
	 * Indents the HTML content from the left.
	 * @param  string UTF-8 encoding or 8-bit
	 * @param  int
	 * @param  string
	 * @return string
	 */
	public static function indent($s, $level = 1, $chars = "\t")
	{
		if ($level >= 1) {
			$s = preg_replace_callback('#<(textarea|pre).*?</\\1#si', array(__CLASS__, 'indentCb'), $s);
			$s = /*Nette\*/String::indent($s, $level, $chars);
			$s = strtr($s, "\x1F\x1E\x1D\x1A", " \t\r\n");
		}
		return $s;
	}



	/**
	 * Callback for self::indent
	 */
	private static function indentCb($m)
	{
		return strtr($m[0], " \t\r\n", "\x1F\x1E\x1D\x1A");
	}



	/**
	 * Date/time formatting.
	 * @param  string|int|DateTime
	 * @param  string
	 * @return string
	 */
	public static function date($time, $format = "%x")
	{
		if ($time == NULL) { // intentionally ==
			return NULL;
		}

		$time = /*Nette\*/Tools::createDateTime($time);
		return strpos($format, '%') === FALSE
			? $time->format($format) // formats using date()
			: strftime($format, $time->format('U')); // formats according to locales
	}



	/**
	 * Converts to human readable file size.
	 * @param  int
	 * @param  int
	 * @return string
	 */
	public static function bytes($bytes, $precision = 2)
	{
		$bytes = round($bytes);
		$units = array('B', 'kB', 'MB', 'GB', 'TB', 'PB');
		foreach ($units as $unit) {
			if (abs($bytes) < 1024 || $unit === end($units)) break;
			$bytes = $bytes / 1024;
		}
		return round($bytes, $precision) . ' ' . $unit;
	}



	/**
	 * Returns array of string length.
	 * @param  mixed
	 * @return int
	 */
	public static function length($var)
	{
		return is_string($var) ? iconv_strlen($var, 'UTF-8') : count($var);
	}



	/**
	 * Performs a search and replace.
	 * @param  string
	 * @param  string
	 * @param  string
	 * @return string
	 */
	public static function replace($subject, $search, $replacement = '')
	{
		return str_replace($search, $replacement, $subject);
	}



	/**
	 * Performs a regular expression search and replace.
	 * @param  string
	 * @param  string
	 * @param  string
	 * @return string
	 */
	public static function replaceRe($subject, $pattern, $replacement = '')
	{
		return preg_replace($pattern, $replacement, $subject);
	}



	/**
	 * /dev/null.
	 * @param  mixed
	 * @return string
	 */
	public static function null($value)
	{
		return '';
	}

}