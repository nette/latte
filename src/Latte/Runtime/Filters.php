<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;

use Latte;
use Latte\Engine;
use Latte\RuntimeException;
use Nette;


/**
 * Escaping & sanitization filters.
 * @internal
 */
class Filters
{
	/**
	 * Escapes string for use everywhere inside HTML (except for comments).
	 * @param  mixed  $s  plain text
	 * @return string HTML
	 */
	public static function escapeHtml(mixed $s): string
	{
		return htmlspecialchars((string) $s, ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE, 'UTF-8');
	}


	/**
	 * Escapes string for use inside HTML text.
	 * @param  mixed  $s  plain text or HtmlStringable
	 * @return string HTML
	 */
	public static function escapeHtmlText(mixed $s): string
	{
		if ($s instanceof HtmlStringable || $s instanceof Nette\Utils\IHtmlString) {
			return $s->__toString(true);
		}

		$s = htmlspecialchars((string) $s, ENT_NOQUOTES | ENT_SUBSTITUTE, 'UTF-8');
		$s = strtr($s, ['{{' => '{<!-- -->{', '{' => '&#123;']);
		return $s;
	}


	/**
	 * Escapes string for use inside HTML attribute value.
	 * @param  mixed  $s  plain text
	 * @return string HTML
	 */
	public static function escapeHtmlAttr(mixed $s, bool $double = true): string
	{
		$double = $double && $s instanceof HtmlStringable ? false : $double;
		$s = (string) $s;
		if (str_contains($s, '`') && strpbrk($s, ' <>"\'') === false) {
			$s .= ' '; // protection against innerHTML mXSS vulnerability nette/nette#1496
		}

		$s = htmlspecialchars($s, ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE, 'UTF-8', $double);
		$s = str_replace('{', '&#123;', $s);
		return $s;
	}


	/**
	 * Escapes HTML for use inside HTML attribute.
	 * @param  mixed  $s  HTML text
	 * @return string HTML
	 */
	public static function escapeHtmlAttrConv(mixed $s): string
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
	public static function escapeJs(mixed $s): string
	{
		if ($s instanceof HtmlStringable || $s instanceof Nette\Utils\IHtmlString) {
			$s = $s->__toString(true);
		}

		$json = json_encode($s, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | (PHP_VERSION_ID >= 70200 ? JSON_INVALID_UTF8_SUBSTITUTE : 0));
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
			throw new RuntimeException('Filters: unable to convert content type ' . strtoupper($source) . ' to ' . strtoupper($dest));
			return $s;
		}
	}


	public static function getConvertor(string $source, string $dest): ?callable
	{
		static $table = [
			Engine::CONTENT_TEXT => [
				'html' => 'escapeHtmlText',
				'htmlAttr' => 'escapeHtmlAttr',
				'htmlAttrJs' => 'escapeHtmlAttr',
				'htmlAttrCss' => 'escapeHtmlAttr',
				'htmlAttrUrl' => 'escapeHtmlAttr',
				'htmlComment' => 'escapeHtmlComment',
				'xml' => 'escapeXml', 'xmlAttr' => 'escapeXml',
			],
			Engine::CONTENT_JS => [
				'html' => 'escapeHtmlText',
				'htmlAttr' => 'escapeHtmlAttr',
				'htmlAttrJs' => 'escapeHtmlAttr',
				'htmlJs' => 'escapeHtmlRawText',
				'htmlComment' => 'escapeHtmlComment',
			],
			Engine::CONTENT_CSS => [
				'html' => 'escapeHtmlText',
				'htmlAttr' => 'escapeHtmlAttr',
				'htmlAttrCss' => 'escapeHtmlAttr',
				'htmlCss' => 'escapeHtmlRawText',
				'htmlComment' => 'escapeHtmlComment',
			],
			Engine::CONTENT_HTML => [
				'htmlAttr' => 'escapeHtmlAttrConv',
				'htmlAttrJs' => 'escapeHtmlAttrConv',
				'htmlAttrCss' => 'escapeHtmlAttrConv',
				'htmlAttrUrl' => 'escapeHtmlAttrConv',
				'htmlComment' => 'escapeHtmlComment',
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
}
