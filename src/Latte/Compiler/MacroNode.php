<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte;


/**
 * Macro element node.
 */
class MacroNode
{
	use Strict;

	const PREFIX_INNER = 'inner',
		PREFIX_TAG = 'tag',
		PREFIX_NONE = 'none';

	/** @var IMacro */
	public $macro;

	/** @var string */
	public $name;

	/** @var bool */
	public $empty = false;

	/** @deprecated */
	public $isEmpty;

	/** @var string  raw arguments */
	public $args;

	/** @var string  raw modifier */
	public $modifiers;

	/** @var bool */
	public $closing = false;

	/** @var bool  has output? */
	public $replaced;

	/** @var MacroTokens */
	public $tokenizer;

	/** @var MacroNode */
	public $parentNode;

	/** @var string */
	public $openingCode;

	/** @var string */
	public $closingCode;

	/** @var string */
	public $attrCode;

	/** @var string */
	public $content;

	/** @var string */
	public $innerContent;

	/** @var \stdClass  user data */
	public $data;

	/** @var HtmlNode  closest HTML node */
	public $htmlNode;

	/** @var array [contentType, context] */
	public $context;

	/** @var string  indicates n:attribute macro and type of prefix (PREFIX_INNER, PREFIX_TAG, PREFIX_NONE) */
	public $prefix;

	/** @var int  position of start tag in source template */
	public $startLine;

	/** @var int  position of end tag in source template */
	public $endLine;

	/** @internal */
	public $saved;


	public function __construct(IMacro $macro, $name, $args = null, $modifiers = null, self $parentNode = null, HtmlNode $htmlNode = null, $prefix = null)
	{
		$this->macro = $macro;
		$this->name = (string) $name;
		$this->modifiers = (string) $modifiers;
		$this->parentNode = $parentNode;
		$this->htmlNode = $htmlNode;
		$this->prefix = $prefix;
		$this->data = new \stdClass;
		$this->isEmpty = &$this->empty;
		$this->setArgs($args);
	}


	public function setArgs($args)
	{
		$this->args = (string) $args;
		$this->tokenizer = new MacroTokens($this->args);
	}


	public function getNotation()
	{
		return $this->prefix
			? Parser::N_PREFIX . ($this->prefix === self::PREFIX_NONE ? '' : $this->prefix . '-') . $this->name
			: '{' . $this->name . '}';
	}
}
