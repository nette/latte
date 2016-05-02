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

	/** @var string */
	protected $contentType = Engine::CONTENT_HTML;

	/** @var array */
	protected $params = [];

	/** @var Filters */
	protected $filters;

	/** @var array */
	protected $blocks = [];

	/** @var Template|NULL */
	private $referrerTemplate;

	/** @var string|NULL */
	private $referenceType;

	/** @var \stdClass local accumulators for intermediate results */
	protected $local;

	/** @var \stdClass global accumulators for intermediate results */
	protected $global;

	/** @var [name => [methods]] */
	protected $blockQueue = [];

	/** @var [name => type] */
	protected $blockTypes = [];


	public function __construct(Engine $engine, array $params, Filters $filters, $name)
	{
		$this->engine = $engine;
		$this->params = $params;
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
	 * Returns array of all parameters.
	 * @return array
	 */
	public function getParameters()
	{
		return $this->params;
	}


	/**
	 * @return string
	 */
	public function getContentType()
	{
		return $this->contentType;
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
	 * Initializes template.
	 * @return bool
	 * @internal
	 */
	protected function initialize(& $_b, & $_l, & $_g)
	{
		Runtime\Filters::$xhtml = (bool) preg_match('#xml|xhtml#', $this->contentType);

		// old accumulators
		$this->params['_l'] = $_l = $this->local;
		$this->params['_g'] = $_g = $this->global;
		$_b = (object) ['blocks' => & $this->blockQueue, 'types' => & $this->blockTypes];

		foreach ($this->blocks as $name => $info) {
			$this->blockQueue[$name][] = [$this, $info[0]];
			$this->checkBlockContentType($info[1], $name);
		}

		// extends
		if ($this->getParentName()) {
			ob_start(function () {});

		} elseif (!empty($this->params['_renderblock'])) { // single block rendering
			$tmp = $this;
			while (in_array($this->referenceType, ['extends', NULL], TRUE) && ($tmp = $tmp->referrerTemplate));
			if (!$tmp) {
				$this->renderBlock($this->params['_renderblock'], $this->params);
				return TRUE;
			}
		}
	}


	/**
	 * @return bool
	 */
	protected function tryRenderParent($params)
	{
		if ($parent = $this->getParentName()) {
			ob_end_clean();
			$this->createTemplate($parent, $params, 'extends', $this->contentType)->render();
			return TRUE;
		}
	}


	/**
	 * Renders template.
	 * @return Template
	 * @internal
	 */
	protected function createTemplate($name, array $params, $referenceType, $contentType)
	{
		$name = $this->engine->getLoader()->getChildName($name, $this->name);
		$child = $this->engine->createTemplate($name, $params);
		if ($child->contentType !== $contentType) {
			trigger_error("Incompatible context for including $name.", E_USER_WARNING);
		}
		$child->referrerTemplate = $this;
		$child->referenceType = $referenceType;
		$child->global = $this->global;
		if (in_array($referenceType, ['extends', 'includeblock'])) {
			$child->blockTypes = & $this->blockTypes;
			$child->blockQueue = & $this->blockQueue;
		}
		return $child;
	}


	/********************* blocks ****************d*g**/


	/**
	 * Calls block.
	 * @return void
	 */
	protected function renderBlock($name, array $params)
	{
		if (empty($this->blockQueue[$name])) {
			$hint = isset($this->blockQueue) && ($t = Helpers::getSuggestion(array_keys($this->blockQueue), $name)) ? ", did you mean '$t'?" : '.';
			throw new \RuntimeException("Cannot include undefined block '$name'$hint");
		}
		$block = reset($this->blockQueue[$name]);
		$block($params);
	}


	/**
	 * Calls parent block.
	 * @return void
	 */
	protected function renderBlockParent($name, array $params)
	{
		if (empty($this->blockQueue[$name]) || ($block = next($this->blockQueue[$name])) === FALSE) {
			throw new \RuntimeException("Cannot include undefined parent block '$name'.");
		}
		$block($params);
		prev($this->blockQueue[$name]);
	}


	/**
	 * @return void
	 */
	protected function checkBlockContentType($current, $name)
	{
		if (!isset($this->blockTypes[$name])) {
			$this->blockTypes[$name] = $current;
		} elseif ($this->blockTypes[$name] !== $current) {
			trigger_error('Overridden block ' . $name . ' in an incompatible context.', E_USER_WARNING);
		}
	}


	/** @deprecated */
	public function setParameters(array $params)
	{
		trigger_error(__METHOD__ . ' is deprecated.', E_USER_DEPRECATED);
		$this->params = $params;
		return $this;
	}

}
