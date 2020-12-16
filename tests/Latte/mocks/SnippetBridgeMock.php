<?php

declare(strict_types=1);


class SnippetBridgeMock implements Latte\Runtime\SnippetBridge
{
	public $snippetMode = true;

	public $payload = [];

	public $invalid = [];


	public function isSnippetMode(): bool
	{
		return $this->snippetMode;
	}


	public function setSnippetMode($snippetMode)
	{
		$this->snippetMode = $snippetMode;
	}


	public function needsRedraw($name): bool
	{
		return $this->invalid === true || isset($this->invalid[$name]);
	}


	public function markRedrawn($name): void
	{
		if ($this->invalid !== true) {
			unset($this->invalid[$name]);
		}
	}


	public function getHtmlId($name): string
	{
		return $name;
	}


	public function addSnippet($name, $content): void
	{
		$this->payload[$name] = $content;
	}


	public function renderChildren(): void
	{
	}
}
