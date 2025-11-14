<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;

use Latte;
use Latte\ContentType;
use Nette;
use function get_debug_type, html_entity_decode, htmlspecialchars, in_array, is_array, is_bool, is_float, is_int, is_scalar, is_string, ord, preg_match, preg_replace, preg_replace_callback, str_replace, strip_tags, strtolower, strtr, substr;
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
	public static function escapeAttr($s): string
	{
		if ($s instanceof HtmlStringable) {
			$s = self::convertHtmlToText($s->__toString());
		}
		$s = (string) $s;
		$s = htmlspecialchars($s, ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE, 'UTF-8');
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
		return self::escapeAttr(self::convertHtmlToText($s));
	}


	/**
	 * Converts HTML attribute to HTML text. The < > chars need to be escaped.
	 */
	public static function convertAttrToHtml(string $s): string
	{
		return self::escapeAttr(html_entity_decode($s, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
	}


	/**
	 * Converts HTML to plain text.
	 */
	public static function convertHtmlToText(string $s): string
	{
		$s = strip_tags($s);
		return html_entity_decode($s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
	}


	public static function classifyAttributeType(string $name): string
	{
		$name = strtolower($name);
		return match (true) {
			isset(self::BooleanAttributes[$name]) => 'bool',
			isset(self::SpaceSeparatedAttributes[$name]) => 'list',
			str_starts_with($name, 'data-') => 'data',
			str_starts_with($name, 'aria-') => 'aria',
			$name === 'style' => 'style',
			default => '',
		};
	}


	/**
	 * Formats common HTML attribute.
	 */
	public static function formatAttribute(string $namePart, mixed $value, bool $migrationWarnings = false): string
	{
		if ($migrationWarnings) {
			if ($value === null) {
				self::triggerMigrationWarning(trim($namePart), $value);
			} elseif (is_string($value) && str_starts_with($name = ltrim($namePart), 'on')) {
				self::triggerMigrationWarning($name, 'string value: previously it was JSON-encoded, now it is rendered as a string');
			}
		}
		return match (true) {
			is_string($value), is_int($value), is_float($value), $value instanceof \Stringable => $namePart . '="' . self::escapeAttr($value) . '"',
			$value === null => '',
			default => self::triggerInvalidValue(trim($namePart), $value) ?? '',
		};
	}


	/**
	 * Formats boolean HTML attribute.
	 */
	public static function formatBoolAttribute(string $namePart, mixed $value): string
	{
		return $value ? $namePart : '';
	}


	/**
	 * Formats space separated HTML attribute.
	 */
	public static function formatListAttribute(string $namePart, mixed $value): string
	{
		return match (true) {
			is_array($value) => self::formatArrayAttribute($namePart, $value, fn($v, $k) => $v === true ? $k : $v, ' '),
			default => self::formatAttribute($namePart, $value),
		};
	}


	/**
	 * Formats HTML attribute 'style'.
	 */
	public static function formatStyleAttribute(string $namePart, mixed $value): string
	{
		return match (true) {
			is_array($value) => self::formatArrayAttribute($namePart, $value, fn($v, $k) => is_string($k) ? $k . ': ' . $v : $v, '; '),
			default => self::formatAttribute($namePart, $value),
		};
	}


	/**
	 * Formats data-* HTML attribute.
	 */
	public static function formatDataAttribute(string $namePart, mixed $value, bool $migrationWarnings = false): string
	{
		if ($migrationWarnings && (is_bool($value) || $value === null)) {
			self::triggerMigrationWarning(trim($namePart), $value);
		}
		$escape = fn($value) => str_contains($value, '"')
			? "'" . str_replace(['&', "'"], ['&amp;', '&apos;'], $value) . "'"
			: '"' . str_replace(['&', '"'], ['&amp;', '&quot;'], $value) . '"';
		return match (true) {
			is_bool($value) => $namePart . '="' . ($value ? 'true' : 'false') . '"',
			is_array($value) || $value instanceof \stdClass => $namePart . '=' . $escape(json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE | JSON_THROW_ON_ERROR)),
			default => self::formatAttribute($namePart, $value),
		};
	}


	/**
	 * Formats aria-* HTML attribute.
	 */
	public static function formatAriaAttribute(string $namePart, mixed $value, bool $migrationWarnings = false): string
	{
		if ($migrationWarnings && is_bool($value)) {
			self::triggerMigrationWarning(trim($namePart), $value);
		}
		return match (true) {
			is_bool($value) => $namePart . '="' . ($value ? 'true' : 'false') . '"',
			is_array($value) => self::formatArrayAttribute($namePart, $value, fn($v, $k) => $v === true ? $k : $v, ' '),
			default => self::formatAttribute($namePart, $value),
		};
	}


	private static function formatArrayAttribute(string $namePart, array $items, \Closure $cb, $separator): string
	{
		$res = [];
		foreach ($items as $k => $v) {
			if ($v != null) { // intentionally ==, skip nulls & empty string
				$res[] = $cb($v, $k);
			}
		}
		return $res ? $namePart . '="' . self::escapeAttr(implode($separator, $res)) . '"' : '';
	}


	public static function triggerInvalidValue(string $name, mixed $value): void
	{
		$source = Latte\Helpers::guessTemplatePosition();
		$type = get_debug_type($value);
		trigger_error("Invalid value for attribute '$name': $type is not allowed" . ($source ? " ($source)" : '.'), E_USER_WARNING);
	}


	public static function triggerMigrationWarning(string $name, mixed $value): void
	{
		$source = Latte\Helpers::guessTemplatePosition();
		$message = match ($value) {
			null => "value null: previously it rendered as $name=\"\", now the attribute is omitted",
			true => "value true: previously it rendered as $name=\"1\", now it renders as $name=\"true\"",
			false => "value false: previously it rendered as $name=\"\", now it renders as $name=\"false\"",
			default => $value,
		};
		trigger_error("Behavior change for attribute '$name' with $message" . ($source ? " ($source)" : '.'), E_USER_WARNING);
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
	 * Determines if the given HTML attribute is a URL attribute that requires special handling.
	 */
	public static function isUrlAttribute(string $tag, string $attr): bool
	{
		$attr = strtolower($attr);
		return in_array($attr, ['href', 'src', 'action', 'formaction'], true)
			|| ($attr === 'data' && strtolower($tag) === 'object');
	}


	/**
	 * Classifies script content type based on the MIME type.
	 */
	public static function classifyScriptType(string $type): string
	{
		if (preg_match('#((application|text)/(((x-)?java|ecma|j|live)script|json)|application/.+\+json|text/plain|module|importmap|)$#Ai', $type)) {
			return ContentType::JavaScript;

		} elseif (preg_match('#text/((x-)?template|html)$#Ai', $type)) {
			return ContentType::Html;
		}

		return ContentType::Text;
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
