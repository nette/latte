<?php

/**
 * This file is part of the Nette Framework.
 *
 * Copyright (c) 2004, 2010 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license", and/or
 * GPL license. For more information please see http://nette.org
 */

namespace Nette\Latte;

use Nette;



/**
 * HTML element node.
 *
 * @author     David Grudl
 * @internal
 */
class HtmlNode extends Nette\Object
{
	/** @var string */
	public $name;

	/** @var bool */
	public $isEmpty = FALSE;

	/** @var array */
	public $attrs = array();

	/** @var bool */
	public $closing = FALSE;

	/** @var int */
	public $offset;



	public function __construct($name)
	{
		$this->name = $name;
		$this->isEmpty = isset(Nette\Utils\Html::$emptyElements[strtolower($this->name)]);
	}

}
