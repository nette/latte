<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte;


/**
 * HTML element node.
 */
class HtmlNode
{
	use Strict;

	/** @var string */
	public $name;

	/** @var bool */
	public $empty;

	/** @deprecated */
	public $isEmpty;

	/** @var array */
	public $attrs = [];

	/** @var array */
	public $macroAttrs = [];

	/** @var bool */
	public $closing = false;

	/** @var HtmlNode */
	public $parentNode;

	/** @var string */
	public $attrCode;

	/** @var int  position of start tag in source template */
	public $startLine;

	/** @var int  position of end tag in source template */
	public $endLine;

	/** @var string @internal */
	public $innerMarker;


	public function __construct($name, self $parentNode = null)
	{
		$this->name = $name;
		$this->parentNode = $parentNode;
		$this->isEmpty = &$this->empty;
	}
}
