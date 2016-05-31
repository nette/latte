<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
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
	private $renderingSnippets = FALSE;

	/** @var Latte\Runtime\ISnippetBridge */
	private $bridge;


	public function __construct(Latte\Runtime\ISnippetBridge $bridge)
	{
		$this->bridge = $bridge;
	}


	public function enter($name, $type)
	{
		if (!$this->bridge->isSnippetMode()) {
			return;
		}
		$obStarted = FALSE;
		if (($this->nestingLevel === 0 && $this->bridge->needsRedraw($name))
			|| ($type === self::TYPE_DYNAMIC && ($previous = end($this->stack)) && $previous[1] === TRUE)) {
			ob_start(function () {});
			$this->nestingLevel = $type === self::TYPE_AREA ? 0 : 1;
			$obStarted = TRUE;
		} elseif ($this->nestingLevel > 0) {
			$this->nestingLevel++;
		}
		$this->stack[] = [$name, $obStarted];
		$this->bridge->markRedrawn($name);
	}


	public function leave()
	{
		if (!$this->bridge->isSnippetMode()) {
			return;
		}
		list($name, $obStarted) = array_pop($this->stack);
		if ($this->nestingLevel > 0 && --$this->nestingLevel === 0) {
			$content = ob_get_clean();
			$this->bridge->addSnippet($name, $content);
		} elseif ($obStarted) { //dynamic snippet wrapper or snippet area
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
			return FALSE;
		}
		$this->renderingSnippets = TRUE;
		foreach ($blocks as $name => $function) {
			if ($name[0] !== '_' || !$this->bridge->needsRedraw(substr($name, 1))) {
				continue;
			}
			$function = reset($function);
			$function($params);
		}
		$this->bridge->renderChildren();

		return TRUE;
	}

}
