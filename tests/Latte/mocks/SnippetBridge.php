<?php


class SnippetBridgeMock implements Latte\Runtime\ISnippetBridge
{
	public $snippetMode = true;

	public $payload = [];

	public $invalid = [];


	public function isSnippetMode()
	{
		return $this->snippetMode;
	}


	public function setSnippetMode($snippetMode)
	{
		$this->snippetMode = $snippetMode;
	}


	public function needsRedraw($name)
	{
		return $this->invalid === true || isset($this->invalid[$name]);
	}


	public function markRedrawn($name)
	{
		if ($this->invalid !== true) {
			unset($this->invalid[$name]);
		}
	}


	public function getHtmlId($name)
	{
		return $name;
	}


	public function addSnippet($name, $content)
	{
		$this->payload[$name] = $content;
	}


	public function renderChildren()
	{
	}
}
