<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte;


/**
 * Template.
 * @internal
 */
class Template
{
	use Strict;

	/** @var Engine */
	private $engine;

	/** @var string */
	private $name;

	/** @var array */
	protected $params = [];

	/** @var Filters */
	protected $filters;

	/** @var array */
	protected $blocks = [];


	public function __construct(Engine $engine, Filters $filters, $name)
	{
		$this->engine = $engine;
		$this->filters = $filters;
		$this->name = $name;
	}


	/**
	 * @return Engine
	 */
	public function getEngine()
	{
		return $this->engine;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * Initializes block, global & local storage in template.
	 * @return [\stdClass, \stdClass, \stdClass]
	 * @internal
	 */
	public function initialize($contentType)
	{
		Runtime\Filters::$xhtml = (bool) preg_match('#xml|xhtml#', $contentType);

		// local storage
		$this->params['_l'] = new \stdClass;

		// block storage
		if (isset($this->params['_b'])) {
			$block = $this->params['_b'];
			unset($this->params['_b']);
		} else {
			$block = new \stdClass;
		}
		foreach ($this->blocks as $name => $info) {
			$block->blocks[$name][] = [$this, $info[0]];
			Macros\BlockMacrosRuntime::checkType($info[1], $block->types, $name);
		}

		// global storage
		if (!isset($this->params['_g'])) {
			$this->params['_g'] = new \stdClass;
		}

		return [$block, $this->params['_g'], $this->params['_l']];
	}


	/**
	 * Renders template.
	 * @return void
	 * @internal
	 */
	public function renderChildTemplate($name, array $params = [])
	{
		$name = $this->engine->getLoader()->getChildName($name, $this->name);
		$this->engine->render($name, $params);
	}


	/********************* template parameters ****************d*g**/


	/**
	 * Sets all parameters.
	 * @param  array
	 * @return self
	 */
	public function setParameters(array $params)
	{
		$this->params = $params;
		unset($this->params['this']);
		return $this;
	}


	/**
	 * Returns array of all parameters.
	 * @return array
	 */
	public function getParameters()
	{
		return $this->params;
	}

}
