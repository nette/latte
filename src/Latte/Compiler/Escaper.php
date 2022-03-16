<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte;


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
}
