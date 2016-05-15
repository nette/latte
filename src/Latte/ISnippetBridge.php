<?php
namespace Latte;

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
	public function isInvalid($name);


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
	public function addToPayload($name, $content);


	/**
	 * @return void
	 */
	public function renderChildren();


}
