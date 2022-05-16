<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte;


/**
 * Latte parser token.
 */
class Token
{
	use Latte\Strict;

	public const
		TEXT = 'text',
		MACRO_TAG = 'macroTag', // latte macro tag
		HTML_TAG_BEGIN = 'htmlTagBegin', // begin of HTML tag or comment
		HTML_TAG_END = 'htmlTagEnd', // end of HTML tag or comment
		HTML_ATTRIBUTE_BEGIN = 'htmlAttributeBegin',
		HTML_ATTRIBUTE_END = 'htmlAttributeEnd',
		COMMENT = 'comment', // latte comment
		END = 'end';

	/** name of macro tag, HTML tag or attribute; used for types MACRO_TAG, HTML_TAG_BEGIN, HTML_ATTRIBUTE_BEGIN */
	public string $name = '';

	/** value of macro tag or HTML attribute; used for types MACRO_TAG, HTML_ATTRIBUTE_BEGIN */
	public string $value;

	/** macro modifiers; used for type MACRO_TAG */
	public string $modifiers = '';

	/** is closing macro or HTML tag </tag>? used for types MACRO_TAG, HTML_TAG_BEGIN */
	public bool $closing = false;

	/** is tag empty {name/}? used for type MACRO_TAG */
	public bool $empty = false;

	public ?string $indentation = null;

	public bool $newline = false;


	public function __construct(
		public /*readonly*/ string $type,
		public /*readonly*/ string $text,
		public /*readonly*/ ?Position $position = null,
	) {
	}


	public function is(int|string ...$kind): bool
	{
		return in_array($this->type, $kind, true)
			|| in_array($this->text, $kind, true);
	}


	public function isEnd(): bool
	{
		return $this->type === self::END;
	}
}
