<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Runtime;

use Latte;
use Latte\{ContentType, RuntimeException};
use Nette;
use function is_string, sprintf;
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
	public static function escapeCss(mixed $s): string
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
	public static function escapeICal(mixed $s): string
	{
		// https://www.ietf.org/rfc/rfc5545.txt
		$s = str_replace("\r", '', (string) $s);
		$s = preg_replace('#[\x00-\x08\x0B-\x1F]#', "\u{FFFD}", (string) $s);
		return addcslashes($s, "\";\\,:\n");
	}


	/**
	 * Converts content between content types.
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


	/**
	 * Converts trusted JavaScript, CSS or XML into the context, or returns null for any other value.
	 * An HTML object is left to the escaper (which prints it as if into $htmlEscapedAs, when given);
	 * printing it is deprecated where that differs from converting it.
	 */
	public static function convertTrusted(object $value, string $context, ?string $htmlEscapedAs = null): ?string
	{
		if ($value instanceof HtmlStringable || $value instanceof Nette\HtmlStringable) {
			if ($context !== 'html') {
				self::deprecateHtmlPrint($value, $context, $htmlEscapedAs);
			}
			return null;
		}

		$type = $value instanceof \Stringable ? self::getTrustedType($value) : null;
		if ($type === null) {
			return null;
		}

		$convertor = Latte\Compiler\Escaper::getConvertor($type, $context) ?? throw new RuntimeException(sprintf(
			'Cannot print %s in context %s, content type %s cannot be converted to it.',
			get_debug_type($value),
			strtoupper($context),
			strtoupper($type),
		));
		return $convertor((string) $value);
	}


	/**
	 * Deprecates printing an HTML object where the escaper prints it otherwise than converting it into the context would.
	 */
	private static function deprecateHtmlPrint(
		HtmlStringable|Nette\HtmlStringable $value,
		string $context,
		?string $escapedAs,
	): void
	{
		$escapedAs ??= $context === 'text' ? 'html' : $context; // the text escaper prints it unchanged
		if (!$value instanceof HtmlStringable && $escapedAs === 'html/attr') {
			return; // escapeAttr() prints Nette\HtmlStringable as a plain string
		}

		$type = str_starts_with($context, 'xml')
			? ContentType::Xml // the XML escapers print it as XML, which {capture} in XML relies on
			: ContentType::Html;
		$outcome = match (true) {
			Latte\Compiler\Escaper::getConvertor($type, $context) === null
				=> 'throw an exception because content type HTML cannot be converted to it',
			$escapedAs !== $context => 'be converted from content type HTML, which changes the output',
			default => null,
		};
		if ($outcome !== null) {
			trigger_error(sprintf(
				'Printing %s in context %s is deprecated, it will %s.',
				get_debug_type($value),
				strtoupper($context),
				$outcome,
			), E_USER_DEPRECATED);
		}
	}


	/**
	 * Returns the content type of trusted content (Latte's HtmlStringable included), or null for any other value.
	 */
	public static function getTrustedType(\Stringable $value): ?string
	{
		return match (true) {
			$value instanceof HtmlStringable => ContentType::Html,
			$value instanceof TrustedJavaScript => ContentType::JavaScript,
			$value instanceof TrustedCss => ContentType::Css,
			$value instanceof TrustedXml => ContentType::Xml,
			default => null,
		};
	}


	/**
	 * Returns the trusted class of the content type, or null if the type has none.
	 * @return ?class-string<TrustedHtml|TrustedJavaScript|TrustedCss|TrustedXml>
	 */
	public static function getTrustedClass(?string $type): ?string
	{
		return match ($type) {
			ContentType::Html => TrustedHtml::class,
			ContentType::JavaScript => TrustedJavaScript::class,
			ContentType::Css => TrustedCss::class,
			ContentType::Xml => TrustedXml::class,
			default => null,
		};
	}


	public static function nop(mixed $s): string
	{
		return (string) $s;
	}
}
