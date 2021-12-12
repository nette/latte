<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;

use Latte;


/**
 * Snippet driver
 * @internal
 */
class SnippetDriver
{
	use Latte\Strict;

	public const
		TYPE_STATIC = 'static',
		TYPE_DYNAMIC = 'dynamic',
		TYPE_AREA = 'area';

	/** @var array<array{string, bool}> */
	private $stack = [];

	/** @var int */
	private $nestingLevel = 0;

	/** @var bool */
	private $renderingSnippets = false;

	/** @var SnippetBridge */
	private $bridge;


	public function __construct(SnippetBridge $bridge)
	{
		$this->bridge = $bridge;
	}


	public function enter(string $name, string $type): void
	{
		if (!$this->renderingSnippets) {
			if ($type === self::TYPE_DYNAMIC && $this->nestingLevel === 0) {
				trigger_error('Dynamic snippets are allowed only inside static snippet/snippetArea.', E_USER_WARNING);
			}

			$this->nestingLevel++;
			return;
		}

		$obStarted = false;
		if (
			($this->nestingLevel === 0 && $this->bridge->needsRedraw($name))
			|| ($type === self::TYPE_DYNAMIC && ($previous = end($this->stack)) && $previous[1] === true)
		) {
			ob_start(function () {});
			$this->nestingLevel = $type === self::TYPE_AREA ? 0 : 1;
			$obStarted = true;
		} elseif ($this->nestingLevel > 0) {
			$this->nestingLevel++;
		}

		$this->stack[] = [$name, $obStarted];
		$this->bridge->markRedrawn($name);
	}


	public function leave(): void
	{
		if (!$this->renderingSnippets) {
			$this->nestingLevel--;
			return;
		}

		[$name, $obStarted] = array_pop($this->stack);
		if ($this->nestingLevel > 0 && --$this->nestingLevel === 0) {
			$content = ob_get_clean();
			$this->bridge->addSnippet($name, $content);
		} elseif ($obStarted) { // dynamic snippet wrapper or snippet area
			ob_end_clean();
		}
	}


	public function getHtmlId(string $name): string
	{
		return $this->bridge->getHtmlId($name);
	}


	/**
	 * @param  Block[]  $blocks
	 * @param  mixed[]  $params
	 */
	public function renderSnippets(array $blocks, array $params): bool
	{
		if ($this->renderingSnippets || !$this->bridge->isSnippetMode()) {
			return false;
		}

		$this->renderingSnippets = true;
		$this->bridge->setSnippetMode(false);
		foreach ($blocks as $name => $block) {
			if (!$this->bridge->needsRedraw($name)) {
				continue;
			}

			$function = reset($block->functions);
			$function($params);
		}

		$this->bridge->setSnippetMode(true);
		$this->bridge->renderChildren();
		return true;
	}
}
