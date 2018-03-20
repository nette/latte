<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;


/**
 * Latte parser token.
 */
class Token
{
	use Strict;

	public const
		TEXT = 'text',
		MACRO_TAG = 'macroTag', // latte macro tag
		HTML_TAG_BEGIN = 'htmlTagBegin', // begin of HTML tag or comment
		HTML_TAG_END = 'htmlTagEnd', // end of HTML tag or comment
		HTML_ATTRIBUTE_BEGIN = 'htmlAttributeBegin',
		HTML_ATTRIBUTE_END = 'htmlAttributeEnd',
		COMMENT = 'comment'; // latte comment

	/** @var string  token type [TEXT | MACRO_TAG | HTML_TAG_BEGIN | HTML_TAG_END | HTML_ATTRIBUTE_BEGIN | HTML_ATTRIBUTE_END | COMMENT] */
	public $type;

	/** @var string  original text content of the token */
	public $text;

	/** @var int  line number */
	public $line;

	/** @var string  name of macro tag, HTML tag or attribute; used for types MACRO_TAG, HTML_TAG_BEGIN, HTML_ATTRIBUTE_BEGIN */
	public $name;

	/** @var string  value of macro tag or HTML attribute; used for types MACRO_TAG, HTML_ATTRIBUTE_BEGIN */
	public $value;

	/** @var string  macro modifiers; used for type MACRO_TAG */
	public $modifiers;

	/** @var bool  is closing macro or HTML tag </tag>? used for types MACRO_TAG, HTML_TAG_BEGIN */
	public $closing;

	/** @var bool  is tag empty {name/}? used for type MACRO_TAG */
	public $empty;
}
