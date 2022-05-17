<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte;
use Latte\Runtime\Filters;


/**
 * Context-aware escaping.
 */
final class Escaper
{
	use Latte\Strict;

	public const
		Text = 'text',
		JavaScript = 'js',
		Css = 'css',
		ICal = 'ical';

	public const
		Html = 'html',
		HtmlText = '',
		HtmlComment = 'Comment',
		HtmlBogusTag = 'Bogus',
		HtmlCss = 'Css',
		HtmlJavaScript = 'Js',
		HtmlTag = 'Tag',
		HtmlAttribute = 'Attr',
		HtmlAttributeJavaScript = 'AttrJs',
		HtmlAttributeCss = 'AttrCss',
		HtmlAttributeUrl = 'AttrUrl',
		HtmlAttributeUnquotedUrl = 'AttrUnquotedUrl';

	public const
		Xml = 'xml',
		XmlText = '',
		XmlComment = 'Comment',
		XmlBogusTag = 'Bogus',
		XmlTag = 'Tag',
		XmlAttribute = 'Attr';


	public static function getConvertor(string $source, string $dest): ?callable
	{
		$table = [
			self::Text => [
				'html' => 'escapeHtmlText',
				'htmlAttr' => 'escapeHtmlAttr',
				'htmlAttrJs' => 'escapeHtmlAttr',
				'htmlAttrCss' => 'escapeHtmlAttr',
				'htmlAttrUrl' => 'escapeHtmlAttr',
				'htmlComment' => 'escapeHtmlComment',
				'xml' => 'escapeXml',
				'xmlAttr' => 'escapeXml',
			],
			self::JavaScript => [
				'html' => 'escapeHtmlText',
				'htmlAttr' => 'escapeHtmlAttr',
				'htmlAttrJs' => 'escapeHtmlAttr',
				'htmlJs' => 'escapeHtmlRawText',
				'htmlComment' => 'escapeHtmlComment',
			],
			self::Css => [
				'html' => 'escapeHtmlText',
				'htmlAttr' => 'escapeHtmlAttr',
				'htmlAttrCss' => 'escapeHtmlAttr',
				'htmlCss' => 'escapeHtmlRawText',
				'htmlComment' => 'escapeHtmlComment',
			],
			self::Html => [
				'htmlAttr' => 'convertHtmlToHtmlAttr',
				'htmlAttrJs' => 'convertHtmlToHtmlAttr',
				'htmlAttrCss' => 'convertHtmlToHtmlAttr',
				'htmlAttrUrl' => 'convertHtmlToHtmlAttr',
				'htmlComment' => 'escapeHtmlComment',
			],
		];
		return isset($table[$source][$dest])
			? [Filters::class, $table[$source][$dest]]
			: null;
	}
}
