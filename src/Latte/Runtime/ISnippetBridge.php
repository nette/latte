<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Runtime;


/**
 * Snippet bridge
 */
interface ISnippetBridge
{

	/**
	 * @return bool
	 */
	public function isSnippetMode();


	/**
	 * @param string
	 * @return bool
	 */
	public function needsRedraw($name);


	/**
	 * @param string
	 * @return void
	 */
	public function markRedrawn($name);


	/**
	 * @param string
	 * @return string
	 */
	public function getHtmlId($name);


	/**
	 * @param string snippet name
	 * @param string html content
	 * @return mixed
	 */
	public function addSnippet($name, $content);


	/**
	 * @return void
	 */
	public function renderChildren();

}
