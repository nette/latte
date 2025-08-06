<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;

use Latte;
use Nette;
use function get_debug_type, html_entity_decode, htmlspecialchars, implode, in_array, is_array, is_string, ord, preg_match, preg_replace, preg_replace_callback, str_contains, str_replace, strip_tags, strtolower, strtr, substr;
use const ENT_HTML5, ENT_NOQUOTES, ENT_QUOTES, ENT_SUBSTITUTE;


/**
 * Runtime utilities for handling HTML.
 * @internal
 */
final class HtmlHelpers
{
	/**
	 * Escapes string for use inside HTML text.
	 */
	public static function escapeText($s): string
	{
		if ($s instanceof HtmlStringable || $s instanceof Nette\HtmlStringable) {
			return $s->__toString();
		}

		$s = htmlspecialchars((string) $s, ENT_NOQUOTES | ENT_SUBSTITUTE, 'UTF-8');
		$s = strtr($s, ['{{' => '{<!-- -->{', '{' => '&#123;']);
		return $s;
	}


	/**
	 * Escapes string for use inside HTML attribute value.
	 */
	public static function escapeAttr($s, bool $double = true): string
	{
		$double = $double && $s instanceof HtmlStringable ? false : $double;
		$s = (string) $s;
		$s = htmlspecialchars($s, ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE, 'UTF-8', $double);
		$s = str_replace('{', '&#123;', $s);
		return $s;
	}


	/**
	 * Escapes string for use inside HTML tag.
	 */
	public static function escapeTag($s): string
	{
		$s = (string) $s;
		$s = htmlspecialchars($s, ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE, 'UTF-8');
		return preg_replace_callback(
			'#[=/\s]#',
			fn($m) => '&#' . ord($m[0]) . ';',
			$s,
		);
	}


	/**
	 * Escapes string for use inside HTML/XML comments.
	 */
	public static function escapeComment($s): string
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
	 * Escapes HTML for usage in <script type=text/html>
	 */
	public static function escapeRawHtml($s): string
	{
		if ($s instanceof HtmlStringable || $s instanceof Nette\HtmlStringable) {
			return self::convertHtmlToRawText($s->__toString());
		}

		return htmlspecialchars((string) $s, ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE, 'UTF-8');
	}


	/**
	 * Escapes only quotes.
	 */
	public static function escapeQuotes($s): string
	{
		return strtr((string) $s, ['"' => '&quot;', "'" => '&apos;']);
	}


	/**
	 * Converts JS and CSS for usage in <script> or <style>
	 */
	public static function convertJSToRawText($s): string
	{
		return preg_replace('#</(script|style)#i', '<\/$1', (string) $s);
	}


	/**
	 * Sanitizes <script> in <script type=text/html>
	 */
	public static function convertHtmlToRawText(string $s): string
	{
		return preg_replace('#(</?)(script)#i', '$1x-$2', $s);
	}


	/**
	 * Converts HTML text to quoted attribute. The quotation marks need to be escaped.
	 */
	public static function convertHtmlToAttr(string $s): string
	{
		return self::escapeAttr($s, false);
	}


	/**
	 * Converts HTML to plain text.
	 */
	public static function convertHtmlToText(string $s): string
	{
		$s = strip_tags($s);
		return html_entity_decode($s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
	}


	/**
	 * Formats HTML attribute value based on attribute type and value type.
	 */
	public static function formatAttribute(string $name, mixed $value): ?string
	{
		if ($value === null || $value === false) {
			return null;

		} elseif ($value === true) {
			return $name;

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
				return null;
			}

			$value = implode($name === 'style' || !strncmp($name, 'on', 2) ? ';' : ' ', $tmp);

		} else {
			$value = (string) $value;
		}

		$q = !str_contains($value, '"') ? '"' : "'";
		return $name . '=' . $q
			. str_replace(
				['&', $q, '<'],
				['&amp;', $q === '"' ? '&quot;' : '&apos;', '<'],
				$value,
			)
			. $q;
	}


	/**
	 * Checks if the given tag name represents a void (empty) HTML element.
	 */
	public static function isVoidElement(string $name): bool
	{
		static $names = [
			'img' => 1, 'hr' => 1, 'br' => 1, 'input' => 1, 'meta' => 1, 'area' => 1, 'embed' => 1, 'keygen' => 1, 'source' => 1, 'base' => 1,
			'col' => 1, 'link' => 1, 'param' => 1, 'basefont' => 1, 'frame' => 1, 'isindex' => 1, 'wbr' => 1, 'command' => 1, 'track' => 1,
		];
		return isset($names[strtolower($name)]);
	}


	/**
	 * Checks that the HTML tag name can be changed.
	 */
	public static function validateTagChange(mixed $name, ?string $origName = null): string
	{
		$name ??= $origName;
		if (!is_string($name)) {
			throw new Latte\RuntimeException('Tag name must be string, ' . get_debug_type($name) . ' given');

		} elseif (!preg_match('~' . Latte\Compiler\TemplateLexer::ReTagName . '$~DAu', $name)) {
			throw new Latte\RuntimeException("Invalid tag name '$name'");

		} elseif (self::isVoidElement($name) !== self::isVoidElement($origName ?? 'div') // non-void is default
			|| in_array(strtolower($name), ['style', 'script'], true)) {
			throw new Latte\RuntimeException("Forbidden: Cannot change element to <$name>");
		}
		return $name;
	}
}
