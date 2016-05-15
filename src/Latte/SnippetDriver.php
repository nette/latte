<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 */

namespace Latte;


use Latte;

class SnippetDriver
{
	use Strict;

	const TYPE_STATIC = 'static';
	const TYPE_DYNAMIC = 'dynamic';
	const TYPE_AREA = 'area';

	/** @var array */
	protected $stack = array();

	/** @var int */
	protected $renderingLevel = 0;

	/** @var bool */
	protected $renderingSnippets = FALSE;

	/** @var ISnippetBridge */
	private $bridge;


	public function __construct(ISnippetBridge $bridge)
	{
		$this->bridge = $bridge;
	}


	public function enter($name, $type)
	{
		if (!$this->bridge->isSnippetMode()) {
			return;
		}
		$obStarted = FALSE;
		if (($this->renderingLevel === 0 && $this->bridge->isInvalid($name))
			|| ($type === self::TYPE_DYNAMIC && ($previous = end($this->stack)) && $previous[1] === TRUE)) {
			ob_start(function () {});
			$this->renderingLevel = $type === self::TYPE_AREA ? 0 : 1;
			$obStarted = TRUE;
		} elseif ($this->renderingLevel > 0) {
			$this->renderingLevel++;
		}
		$this->stack[] = array($name, $obStarted);
		if($name !== "") {
			$this->bridge->markRedrawn($name);
		}
	}


	public function leave()
	{
		if (!$this->bridge->isSnippetMode()) {
			return;
		}
		list($name, $obStarted) = array_pop($this->stack);
		if ($this->renderingLevel > 0 && --$this->renderingLevel === 0) {
			$content = ob_get_clean();
			$this->bridge->addToPayload($name, $content);
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
			if ($name[0] !== '_' || !$this->bridge->isInvalid(substr($name, 1))) {
				continue;
			}
			$function = reset($function);
			$function($params);
		}
		$this->bridge->renderChildren();

		return TRUE;
	}


}
