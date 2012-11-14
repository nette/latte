<?php

/**
 * Nette Framework
 *
 * Copyright (c) 2004, 2009 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license" that is bundled
 * with this package in the file license.txt.
 *
 * For more information please see http://nettephp.com
 *
 * @copyright  Copyright (c) 2004, 2009 David Grudl
 * @license    http://nettephp.com/license  Nette license
 * @link       http://nettephp.com
 * @category   Nette
 * @package    Nette\Templates
 * @version    $Id$
 */

/*namespace Nette\Templates;*/



/**
 * Standard template helpers shipped with Nette Framework.
 *
 * @author     David Grudl
 * @copyright  Copyright (c) 2004, 2009 David Grudl
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
		$callback = 'Nette\Templates\TemplateHelpers::' . $helper;
		/**/fixCallback($callback);/**/
		if (is_callable($callback)) {
			return $callback;
		}
		$callback = 'Nette\String::' . $helper;
		/**/fixCallback($callback);/**/
		if (is_callable($callback)) {
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
		if (is_object($s) && ($s instanceof Template || $s instanceof /*Nette\Web\*/Html || $s instanceof /*Nette\Forms\*/Form)) {
			return (string) $s;
		}
		return htmlSpecialChars($s, ENT_QUOTES);
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
		return str_replace(']]>', ']]\x3E', json_encode($s));
	}



	/**
	 * Escapes string for use inside HTML JavaScript attribute.
	 * @param  mixed  UTF-8 encoding
	 * @return string
	 */
	public static function escapeHtmlJs($s)
	{
		return htmlSpecialChars(json_encode($s), ENT_QUOTES);
	}



	/**
	 * Replaces all repeated white spaces with a single space.
	 * @param  string UTF-8 encoding or 8-bit
	 * @return string
	 */
	public static function strip($s)
	{
		return trim(preg_replace('#\\s+#', ' ', $s));
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
			$s = strtr($s, "\x1D\x1A", "\r\n");
		}
		return $s;
	}



	/**
	 * Callback for self::indent
	 */
	private static function indentCb($m)
	{
		return strtr($m[0], "\r\n", "\x1D\x1A");
	}



	/**
	 * Date/time formatting.
	 * @param  string|int|DateTime
	 * @param  string
	 * @return string
	 */
	public static function date($value, $format = "%x")
	{
		$value = is_numeric($value) ? (int) $value : ($value instanceof /*\*/DateTime ? $value->format('U') : strtotime($value));
		return strftime($format, $value);
	}



	/**
	 * Converts to human readable file size.
	 * @param  int
	 * @return string
	 */
	public static function bytes($bytes)
	{
		$bytes = round($bytes);
		$units = array('B', 'kB', 'MB', 'GB', 'TB', 'PB');
		foreach ($units as $unit) {
			if (abs($bytes) < 1024) break;
			$bytes = $bytes / 1024;
		}
		return round($bytes, 2) . ' ' . $unit;
	}

}