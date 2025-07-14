<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;

use Latte;
use Latte\ContentType;
use function get_debug_type, in_array, is_string, preg_match, strtolower;


/**
 * Runtime utilities for handling HTML.
 * @internal
 */
final class HtmlHelpers
{
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
}
