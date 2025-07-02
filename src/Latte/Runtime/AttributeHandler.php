<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;

use Latte;


/**
 * Handles HTML/XML attributes and tags - formatting, validation and rendering.
 * @internal
 */
final class AttributeHandler
{
	// https://www.w3.org/TR/xml/#NT-Name
	private const
		ReXmlNameStart = ':A-Z_a-z\x{C0}-\x{D6}\x{D8}-\x{F6}\x{F8}-\x{2FF}\x{370}-\x{37D}\x{37F}-\x{1FFF}\x{200C}-\x{200D}\x{2070}-\x{218F}\x{2C00}-\x{2FEF}\x{3001}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFFD}\x{10000}-\x{EFFFF}',
		ReXmlName = '[' . self::ReXmlNameStart . '][-.0-9\x{B7}\x{300}-\x{36F}\x{203F}-\x{2040}' . self::ReXmlNameStart . ']*';


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
}
