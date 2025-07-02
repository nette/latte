<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;

use Latte;
use function in_array, is_array, is_scalar, is_string;
use const JSON_INVALID_UTF8_SUBSTITUTE, JSON_THROW_ON_ERROR, JSON_UNESCAPED_SLASHES, JSON_UNESCAPED_UNICODE;


/**
 * Handles HTML/XML attributes and tags - formatting, validation and rendering.
 * @internal
 */
final class AttributeHandler
{
	private const SpaceSeparatedAttributes = [
		'accesskey' => 1, 'class' => 1, 'headers' => 1, 'itemprop' => 1, 'ping' => 1, 'rel' => 1, 'role' => 1, 'sandbox' => 1,
	];

	private const BooleanAttributes = [
		'allowfullscreen' => 1, 'async' => 1, 'autofocus' => 1, 'autoplay' => 1, 'checked' => 1, 'controls' => 1,
		'contenteditable' => 1, 'default' => 1, 'defer' => 1, 'disabled' => 1, 'draggable' => 1, 'formnovalidate' => 1,
		'hidden' => 1, 'inert' => 1, 'ismap' => 1, 'itemscope' => 1, 'loop' => 1, 'multiple' => 1, 'muted' => 1,
		'nomodule' => 1, 'novalidate' => 1, 'open' => 1, 'playsinline' => 1, 'readonly' => 1, 'required' => 1,
		'reversed' => 1, 'selected' => 1, 'spellcheck' => 1,
	];

	// https://www.w3.org/TR/xml/#NT-Name
	private const
		ReXmlNameStart = ':A-Z_a-z\x{C0}-\x{D6}\x{D8}-\x{F6}\x{F8}-\x{2FF}\x{370}-\x{37D}\x{37F}-\x{1FFF}\x{200C}-\x{200D}\x{2070}-\x{218F}\x{2C00}-\x{2FEF}\x{3001}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFFD}\x{10000}-\x{EFFFF}',
		ReXmlName = '[' . self::ReXmlNameStart . '][-.0-9\x{B7}\x{300}-\x{36F}\x{203F}-\x{2040}' . self::ReXmlNameStart . ']*';


	/**
	 * Formats HTML attribute value based on attribute type and value type.
	 * Parameter $compat enables compatibility mode with n:attr
	 */
	public static function formatHtmlAttribute(string $name, mixed $value, bool $compat = false): ?string
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


	public static function formatXmlAttribute(string $name, mixed $value): ?string
	{
		return match (true) {
			$value === null, $value === false => null,
			$value === true => $name . '="' . $name . '"',
			is_scalar($value) => $name . '="' . Filters::escapeXml($value) . '"',
			default => !trigger_error(ucfirst(get_debug_type($value)) . " value in '$name' attribute is not supported.", E_USER_WARNING) ?: null,
		};
	}


	public static function validateTagName(mixed $name, bool $xml = false): string
	{
		if (!is_string($name)) {
			throw new Latte\RuntimeException('Tag name must be string, ' . get_debug_type($name) . ' given');
		} elseif (!preg_match('~' . ($xml ? self::ReXmlName : Latte\Compiler\TemplateLexer::ReTagName) . '$~DAu', $name)) {
			throw new Latte\RuntimeException("Invalid tag name '$name'");
		} elseif (!$xml && in_array(strtolower($name), ['style', 'script'], true)) {
			throw new Latte\RuntimeException("Forbidden variable tag name <$name>");
		}
		return $name;
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
	 * Checks if the given tag name represents an void (empty) HTML element.
	 */
	public static function isVoidElement(string $name): bool
	{
		static $els = [
			'img' => 1, 'hr' => 1, 'br' => 1, 'input' => 1, 'meta' => 1, 'area' => 1, 'embed' => 1, 'keygen' => 1, 'source' => 1, 'base' => 1,
			'col' => 1, 'link' => 1, 'param' => 1, 'basefont' => 1, 'frame' => 1, 'isindex' => 1, 'wbr' => 1, 'command' => 1, 'track' => 1,
		];
		return isset($els[strtolower($name)]);
	}


	private static function triggerError($type, $name): void
	{
		trigger_error(ucfirst($type) . " value in '$name' attribute is not supported.", E_USER_WARNING);
	}
}
