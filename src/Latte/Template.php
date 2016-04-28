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
	public $params = [];

	/** @var Filters */
	protected $filters;

	/** @var array */
	protected $blocks = [];

	/** @var Template|NULL */
	private $referrerTemplate;

	/** @var string|NULL */
	private $referenceType;

	/** @var \stdClass local accumulators for intermediate results */
	public $local;

	/** @var \stdClass global accumulators for intermediate results */
	public $global;


	public function __construct(Engine $engine, Filters $filters, $name)
	{
		$this->engine = $engine;
		$this->filters = $filters;
		$this->name = $name;
		$this->local = new \stdClass;
		$this->global = new \stdClass;
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
	 * @return string|NULL
	 */
	public function getParentName()
	{
	}


	/**
	 * @return Template|NULL
	 */
	public function getReferrerTemplate()
	{
		return $this->referrerTemplate;
	}


	/**
	 * @return string|NULL
	 */
	public function getReferenceType()
	{
		return $this->referenceType;
	}


	/**
	 * Initializes block, global & local storage in template.
	 * @return [\stdClass, \stdClass, \stdClass]
	 * @internal
	 */
	protected function initialize($contentType)
	{
		Runtime\Filters::$xhtml = (bool) preg_match('#xml|xhtml#', $contentType);

		// old accumulators
		$this->params['_l'] = $this->local;
		$this->params['_g'] = $this->global;

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

		// extends
		if ($this->local->parentName = $this->getParentName()) {
			ob_start(function () {});
		}

		return [$block, $this->global, $this->local];
	}


	/**
	 * @return bool
	 */
	protected function tryRenderParent($params)
	{
		if ($this->local->parentName) {
			ob_end_clean();
			$this->createTemplate($this->local->parentName, $params, 'extends')->render();
			return TRUE;
		}
	}


	/**
	 * Renders template.
	 * @return Template
	 * @internal
	 */
	public function createTemplate($name, array $params, $referenceType)
	{
		$name = $this->engine->getLoader()->getChildName($name, $this->name);
		$child = $this->engine->createTemplate($name);
		$child->params = $params;
		$child->referrerTemplate = $this;
		$child->referenceType = $referenceType;
		$child->global = $this->global;
		return $child;
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
