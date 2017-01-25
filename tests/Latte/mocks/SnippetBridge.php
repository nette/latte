<?php

declare(strict_types=1);


class SnippetBridgeMock implements Latte\Runtime\ISnippetBridge
{

	public $snippetMode = TRUE;

	public $payload = [];

	public $invalid = [];


	public function isSnippetMode(): bool
	{
		return $this->snippetMode;
	}


	public function setSnippetMode(bool $snippetMode)
	{
		$this->snippetMode = $snippetMode;
	}


	public function needsRedraw(string $name): bool
	{
		return $this->invalid === TRUE || isset($this->invalid[$name]);
	}


	public function markRedrawn(string $name)
	{
		if ($this->invalid !== TRUE) {
			unset($this->invalid[$name]);
		}
	}


	public function getHtmlId(string $name): string
	{
		return $name;
	}


	public function addSnippet(string $name, string $content)
	{
		$this->payload[$name] = $content;
	}


	public function renderChildren()
	{

	}

}
