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
	public $isEmpty;

	/** @var array */
	public $attrs = [];

	/** @var array */
	public $macroAttrs = [];

	/** @var bool */
	public $closing = FALSE;

	/** @var HtmlNode */
	public $parentNode;

	/** @var string */
	public $attrCode;

	/** @var int */
	public $offset;


	public function __construct($name, self $parentNode = NULL)
	{
		$this->name = $name;
		$this->parentNode = $parentNode;
	}

}
