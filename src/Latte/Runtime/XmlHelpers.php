<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;

use Latte;
use function get_debug_type, htmlspecialchars, is_float, is_int, is_string, ord, preg_match, preg_replace, preg_replace_callback;
use const ENT_QUOTES, ENT_SUBSTITUTE, ENT_XML1;


/**
 * Runtime utilities for handling XML.
 * @internal
 */
final class XmlHelpers
{
	// https://www.w3.org/TR/xml/#NT-Name
	private const
		ReNameStart = ':A-Z_a-z\x{C0}-\x{D6}\x{D8}-\x{F6}\x{F8}-\x{2FF}\x{370}-\x{37D}\x{37F}-\x{1FFF}\x{200C}-\x{200D}\x{2070}-\x{218F}\x{2C00}-\x{2FEF}\x{3001}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFFD}\x{10000}-\x{EFFFF}',
		ReName = '[' . self::ReNameStart . '][-.0-9\x{B7}\x{300}-\x{36F}\x{203F}-\x{2040}' . self::ReNameStart . ']*';


	/**
	 * Escapes string for use everywhere inside XML (except for comments and tags).
	 */
	public static function escapeText($s): string
	{
		if ($s instanceof HtmlStringable) {
			return $s->__toString();
		}

		// XML 1.0: \x09 \x0A \x0D and C1 allowed directly, C0 forbidden
		// XML 1.1: \x00 forbidden directly and as a character reference,
		//   \x09 \x0A \x0D \x85 allowed directly, C0, C1 and \x7F allowed as character references
		$s = preg_replace('#[\x00-\x08\x0B\x0C\x0E-\x1F]#', "\u{FFFD}", (string) $s);
		return htmlspecialchars($s, ENT_QUOTES | ENT_XML1 | ENT_SUBSTITUTE, 'UTF-8');
	}


	/**
	 * Escapes string for use inside XML attribute value.
	 */
	public static function escapeAttr($s): string
	{
		if ($s instanceof HtmlStringable) {
			$s = HtmlHelpers::convertHtmlToText($s->__toString());
		}
		return self::escapeText($s);
	}


	/**
	 * Escapes string for use inside XML tag.
	 */
	public static function escapeTag($s): string
	{
		$s = self::escapeText((string) $s);
		return preg_replace_callback(
			'#[=/\s]#',
			fn($m) => '&#' . ord($m[0]) . ';',
			$s,
		);
	}


	public static function formatAttribute(string $namePart, mixed $value, bool $migrationWarnings = false): string
	{
		if ($migrationWarnings && $value === null) {
			HtmlHelpers::triggerMigrationWarning(trim($namePart), $value);
		}
		return match (true) {
			is_string($value), is_int($value), is_float($value), $value instanceof \Stringable => $namePart . '="' . self::escapeAttr($value) . '"',
			$value === null => '',
			default => HtmlHelpers::triggerInvalidValue(trim($namePart), $value) ?? '',
		};
	}


	/**
	 * Checks that the HTML tag name can be changed.
	 */
	public static function validateTagChange(mixed $name, ?string $origName = null): string
	{
		$name ??= $origName;
		if (!is_string($name)) {
			throw new Latte\RuntimeException('Tag name must be string, ' . get_debug_type($name) . ' given');

		} elseif (!preg_match('~' . self::ReName . '$~DAu', $name)) {
			throw new Latte\RuntimeException("Invalid tag name '$name'");
		}
		return $name;
	}


	public static function validateAttributeName(mixed $name): void
	{
		if (!is_string($name)) {
			throw new Latte\RuntimeException('Attribute name must be string, ' . get_debug_type($name) . ' given');

		} elseif (!preg_match('~' . self::ReName . '$~DAu', $name)) {
			throw new Latte\RuntimeException("Invalid attribute name '$name'");
		}
	}
}
