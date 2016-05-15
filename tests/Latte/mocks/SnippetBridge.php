<?php


class SnippetBridgeMock implements \Latte\ISnippetBridge
{

	public $snippetMode = TRUE;

	public $payload = [];

	public $invalid = [];


	public function isSnippetMode()
	{
		return $this->snippetMode;
	}


	public function isInvalid($name)
	{
		return $this->invalid === TRUE || isset($this->invalid[$name]);
	}


	public function markRedrawn($name)
	{
		if ($this->invalid !== TRUE) {
			unset($this->invalid[$name]);
		}
	}


	public function getHtmlId($name)
	{
		return $name;
	}


	public function addToPayload($name, $content)
	{
		$this->payload[$name] = $content;
	}


	public function renderChildren()
	{

	}


}
