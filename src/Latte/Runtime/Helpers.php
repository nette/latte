<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;

use Latte;
use Latte\ContentType;
use Latte\RuntimeException;
use Nette;
use function addcslashes, is_string, json_encode, preg_replace, str_replace, strtoupper;
use const JSON_INVALID_UTF8_SUBSTITUTE, JSON_THROW_ON_ERROR, JSON_UNESCAPED_SLASHES, JSON_UNESCAPED_UNICODE;


/**
 * Template runtime helpers.
 * @internal
 */
class Helpers
{
	/**
	 * Ensures the value is a string or returns null.
	 * @return ($value is string ? string : null)
	 */
	public static function stringOrNull(mixed $value): ?string
	{
		return is_string($value) ? $value : null;
	}


	/**
	 * Escapes string for use inside CSS template.
	 */
	public static function escapeCss($s): string
	{
		// http://www.w3.org/TR/2006/WD-CSS21-20060411/syndata.html#q6
		return addcslashes((string) $s, "\x00..\x1F!\"#$%&'()*+,./:;<=>?@[\\]^`{|}~");
	}


	/**
	 * Escapes variables for use inside <script>.
	 */
	public static function escapeJs(mixed $s): string
	{
		if ($s instanceof HtmlStringable || $s instanceof Nette\HtmlStringable) {
			$s = $s->__toString();
		}

		$json = json_encode($s, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE | JSON_THROW_ON_ERROR);
		return str_replace([']]>', '<!', '</'], [']]\u003E', '\u003C!', '<\/'], $json);
	}


	/**
	 * Escapes string for use inside iCal template.
	 */
	public static function escapeICal($s): string
	{
		// https://www.ietf.org/rfc/rfc5545.txt
		$s = str_replace("\r", '', (string) $s);
		$s = preg_replace('#[\x00-\x08\x0B-\x1F]#', "\u{FFFD}", (string) $s);
		return addcslashes($s, "\";\\,:\n");
	}


	/**
	 * Converts ... to ...
	 */
	public static function convertTo(FilterInfo $info, string $dest, string $s): string
	{
		$source = $info->contentType ?: ContentType::Text;
		if ($source === $dest) {
			return $s;
		} elseif ($conv = Latte\Compiler\Escaper::getConvertor($source, $dest)) {
			$info->contentType = $dest;
			return $conv($s);
		} else {
			throw new RuntimeException('Filters: unable to convert content type ' . strtoupper($source) . ' to ' . strtoupper($dest));
		}
	}


	public static function nop($s): string
	{
		return (string) $s;
	}
}
