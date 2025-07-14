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
	private const BooleanAttributes = [
		'allowfullscreen' => 1, 'async' => 1, 'autofocus' => 1, 'autoplay' => 1, 'checked' => 1, 'controls' => 1,
		'contenteditable' => 1, 'default' => 1, 'defer' => 1, 'disabled' => 1, 'draggable' => 1, 'formnovalidate' => 1,
		'hidden' => 1, 'inert' => 1, 'ismap' => 1, 'itemscope' => 1, 'loop' => 1, 'multiple' => 1, 'muted' => 1,
		'nomodule' => 1, 'novalidate' => 1, 'open' => 1, 'playsinline' => 1, 'readonly' => 1, 'required' => 1,
		'reversed' => 1, 'selected' => 1, 'spellcheck' => 1,
	];

	private const SpaceSeparatedAttributes = [
		'accesskey' => 1, 'class' => 1, 'headers' => 1, 'itemprop' => 1, 'ping' => 1, 'rel' => 1, 'role' => 1, 'sandbox' => 1,
	];


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
	 * Converts HTML text to quoted attribute.
	 */
	public static function convertHtmlToAttr(string $s): string
	{
		return self::escapeAttr(strip_tags($s), false);
	}


	/**
	 * Converts HTML attribute to HTML text. The < > chars need to be escaped.
	 */
	public static function convertAttrToHtml(string $s): string
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
	 * Parameter $compat enables compatibility mode with n:attr
	 */
	public static function formatAttribute(string $name, mixed $value, bool $compat = false): ?string
	{
		$type = get_debug_type($value);
		$lname = strtolower($name);
		$value = match (true) {
			isset(self::BooleanAttributes[$lname]) => (bool) $value,
			isset(self::SpaceSeparatedAttributes[$lname]) => match ($type) {
				'string', 'int', 'float' => (string) $value,
				'bool' => $compat ? $value : self::triggerError($type, $name) ?? (string) $value,
				'null' => $compat ? null : '',
				'array' => self::formatArray($value, fn($v, $k) => $v === true ? $k : $v, ' '),
				default => self::triggerError($type, $name),
			},
			str_starts_with($lname, 'data-') => match ($type) {
				'string', 'int', 'float' => (string) $value,
				'bool' => $compat ? $value : self::triggerError($type, $name) ?? (string) $value,
				'null' => $compat ? null : true,
				'array', 'stdClass' => json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE | JSON_THROW_ON_ERROR),
				default => self::triggerError($type, $name),
			},
			str_starts_with($lname, 'aria-') => match ($type) {
				'string', 'int', 'float' => (string) $value,
				'bool' => $value ? 'true' : 'false',
				'null' => null,
				'array' => self::formatArray($value, fn($v, $k) => $v === true ? $k : $v, ' '),
				default => self::triggerError($type, $name),
			},
			str_starts_with($lname, 'on') => match ($type) {
				'string' => $value,
				'null' => null,
				default => self::triggerError($type, $name),
			},
			$lname === 'style' => match ($type) {
				'string' => $value,
				'null' => null,
				'array' => self::formatArray($value, fn($v, $k) => is_string($k) ? $k . ':' . $v : $v, ';'),
				default => self::triggerError($type, $name),
			},
			default => match ($type) {
				'string', 'int', 'float' => (string) $value,
				'bool' => $compat ? $value : self::triggerError($type, $name) ?? (string) $value,
				'null' => $compat ? null : '',
				default => self::triggerError($type, $name),
			},
		};

		return match ($value) {
			null, false => null,
			true => $name,
			default => $name . '=' . (str_contains($value, '"')
				? "'" . str_replace(['&', "'"], ['&amp;', '&apos;'], $value) . "'"
				: '"' . str_replace(['&', '"'], ['&amp;', '&quot;'], $value) . '"'),
		};
	}


	private static function formatArray(array $items, \Closure $cb, $separator): ?string
	{
		$res = [];
		foreach ($items as $k => $v) {
			if ($v != null) { // intentionally ==, skip nulls & empty string
				$res[] = $cb($v, $k);
			}
		}
		return $res ? implode($separator, $res) : null;
	}


	private static function triggerError($type, $name): void
	{
		$source = Latte\SourceReference::fromCallStack();
		trigger_error(ucfirst($type) . " value in '$name' attribute is not supported" . ($source ? " ($source)" : '.'), E_USER_WARNING);
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


	public static function validateAttributeName(mixed $name): void
	{
		if (!is_string($name)) {
			throw new Latte\RuntimeException('Attribute name must be string, ' . get_debug_type($name) . ' given');

		} elseif (!preg_match('~' . Latte\Compiler\TemplateLexer::ReAttrName . '+$~DAu', $name)) {
			throw new Latte\RuntimeException("Invalid attribute name '$name'");
		}
	}
}
