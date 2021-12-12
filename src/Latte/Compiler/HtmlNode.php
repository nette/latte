<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

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

	/** @var string[] */
	public $attrs = [];

	/** @var string[] */
	public $macroAttrs = [];

	/** @var bool */
	public $closing = false;

	/** @var HtmlNode|null */
	public $parentNode;

	/** @var string */
	public $attrCode;

	/** @var int  position of start tag in source template */
	public $startLine;

	/** @var int  position of end tag in source template */
	public $endLine;

	/** @var \stdClass  user data */
	public $data;

	/** @var string @internal */
	public $innerMarker;


	public function __construct(string $name, ?self $parentNode = null)
	{
		$this->name = $name;
		$this->parentNode = $parentNode;
		$this->data = new \stdClass;
	}
}
