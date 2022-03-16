<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;


/**
 * Context-aware escaping contexts.
 */
final class Context
{
	/** Content types */
	public const
		Text = 'text',
		Html = 'html',
		Xml = 'xml',
		JavaScript = 'js',
		Css = 'css',
		ICal = 'ical';

	public const
		HtmlText = null,
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
		XmlText = null,
		XmlComment = 'Comment',
		XmlBogusTag = 'Bogus',
		XmlTag = 'Tag',
		XmlAttribute = 'Attr';
}
