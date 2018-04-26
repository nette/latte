<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Runtime;

use Latte;


/**
 * Snippet driver
 * @internal
 */
class SnippetDriver
{
	use Latte\Strict;

	const TYPE_STATIC = 'static',
		TYPE_DYNAMIC = 'dynamic',
		TYPE_AREA = 'area';

	/** @var array */
	private $stack = [];

	/** @var int */
	private $nestingLevel = 0;

	/** @var bool */
	private $renderingSnippets = false;

	/** @var ISnippetBridge */
	private $bridge;


	public function __construct(ISnippetBridge $bridge)
	{
		$this->bridge = $bridge;
	}


	public function enter($name, $type)
	{
		if (!$this->renderingSnippets) {
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


	public function leave()
	{
		if (!$this->renderingSnippets) {
			return;
		}
		list($name, $obStarted) = array_pop($this->stack);
		if ($this->nestingLevel > 0 && --$this->nestingLevel === 0) {
			$content = ob_get_clean();
			$this->bridge->addSnippet($name, $content);
		} elseif ($obStarted) { // dynamic snippet wrapper or snippet area
			ob_end_clean();
		}
	}


	public function getHtmlId($name)
	{
		return $this->bridge->getHtmlId($name);
	}


	public function renderSnippets(array $blocks, array $params)
	{
		if ($this->renderingSnippets || !$this->bridge->isSnippetMode()) {
			return false;
		}
		$this->renderingSnippets = true;
		$this->bridge->setSnippetMode(false);
		foreach ($blocks as $name => $function) {
			if ($name[0] !== '_' || !$this->bridge->needsRedraw(substr($name, 1))) {
				continue;
			}
			$function = reset($function);
			$function($params);
		}
		$this->bridge->setSnippetMode(true);
		$this->bridge->renderChildren();
		return true;
	}
}
