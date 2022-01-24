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


	public function setSnippetMode(bool $snippetMode): void
	{
		$this->snippetMode = $snippetMode;
	}


	public function needsRedraw(string $name): bool
	{
		return $this->invalid === true || isset($this->invalid[$name]);
	}


	public function markRedrawn(string $name): void
	{
		if ($this->invalid !== true) {
			unset($this->invalid[$name]);
		}
	}


	public function getHtmlId(string $name): string
	{
		return $name;
	}


	public function addSnippet(string $name, string $content): void
	{
		$this->payload[$name] = $content;
	}


	public function renderChildren(): void
	{
	}
}
