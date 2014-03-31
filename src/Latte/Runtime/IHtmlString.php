<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 */

namespace Latte\Runtime;

use Latte;


interface IHtmlString
{

	/**
	 * @return string in HTML format
	 */
	function __toString();

}
