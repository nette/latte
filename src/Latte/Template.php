<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte;


/**
 * Template.
 */
class Template
{
	use Strict;

	/** @var Engine */
	private $engine;

	/** @var string */
	private $name;

	/** @var string  @internal */
	protected $contentType = Engine::CONTENT_HTML;

	/** @var array  @internal */
	protected $params = [];

	/** @var Filters */
	protected $filters;

	/** @var array [name => method]  @internal */
	protected $blocks = [];

	/** @var string|NULL|FALSE  @internal */
	protected $parentName;

	/** @var Template|NULL  @internal */
	private $referringTemplate;

	/** @var string|NULL  @internal */
	private $referenceType;

	/** @var \stdClass global accumulators for intermediate results */
	public $global;

	/** @var [name => [callbacks]]  @internal */
	protected $blockQueue = [];

	/** @var [name => type]  @internal */
	protected $blockTypes = [];


	public function __construct(Engine $engine, array $params, Filters $filters, array $providers, $name)
	{
		$this->engine = $engine;
		$this->params = $params;
		$this->filters = $filters;
		$this->name = $name;
		$this->global = (object) $providers;
		foreach ($this->blocks as $nm => $method) {
			$this->blockQueue[$nm][] = [$this, $method];
		}
		$this->params['template'] = $this; // back compatibility
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
	 * Returns parameter.
	 * @return mixed
	 */
	public function getParameter($name)
	{
		if (!array_key_exists($name, $this->params)) {
			trigger_error("The variable '$name' does not exist in template.", E_USER_NOTICE);
		}
		return $this->params[$name];
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
		return $this->parentName ?: NULL;
	}


	/**
	 * @return Template|NULL
	 */
	public function getReferringTemplate()
	{
		return $this->referringTemplate;
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
	protected function initialize(& $params)
	{
		$params = $this->prepare();

		if ($this->parentName === NULL && isset($this->global->coreParentFinder)) {
			$this->parentName = call_user_func($this->global->coreParentFinder, $this);
		}

		if ($this->referenceType === 'import') {
			if ($this->parentName) {
				$this->createTemplate($this->parentName, [], 'import')->render();
			}
			return TRUE;

		} elseif ($this->parentName) { // extends
			$this->createTemplate($this->parentName, $params, 'extends')->render();
			return TRUE;

		} elseif (!empty($this->params['_renderblock'])) { // single block rendering
			$tmp = $this;
			while (in_array($this->referenceType, ['extends', NULL], TRUE) && ($tmp = $tmp->referringTemplate));
			if (!$tmp) {
				$this->renderBlock($this->params['_renderblock'], $this->params);
				return TRUE;
			}
		} elseif (isset($this->global->snippetBridge) && !isset($this->global->snippetDriver)) {
			$this->global->snippetDriver = new Runtime\SnippetDriver($this->global->snippetBridge);
		}

		Runtime\Filters::$xhtml = (bool) preg_match('#xml|xhtml#', $this->contentType);
		// old accumulators for back compatibility
		$this->params['_l'] = $params['_l'] = new \stdClass;
		$this->params['_g'] = $params['_g'] = $this->global;
		$params['_b'] = (object) ['blocks' => & $this->blockQueue, 'types' => & $this->blockTypes];
		if (isset($this->global->snippetDriver) && $this->global->snippetBridge->isSnippetMode()) {
			return $this->global->snippetDriver->renderSnippets($this->blockQueue, $this->params);
		}
	}


	/**
	 * Renders template.
	 * @return Template
	 * @internal
	 */
	protected function createTemplate($name, array $params, $referenceType)
	{
		$name = $this->engine->getLoader()->getReferredName($name, $this->name);
		$child = $this->engine->createTemplate($name, $params);
		$child->referringTemplate = $this;
		$child->referenceType = $referenceType;
		$child->global = $this->global;
		if (in_array($referenceType, ['extends', 'includeblock', 'import'])) {
			$this->blockQueue = array_merge_recursive($this->blockQueue, $child->blockQueue);
			foreach ($child->blockTypes as $nm => $type) {
				$this->checkBlockContentType($type, $nm);
			}
			$child->blockQueue = & $this->blockQueue;
			$child->blockTypes = & $this->blockTypes;
		}
		return $child;
	}


	/**
	 * Renders template to string.
	 * @return string
	 */
	public function renderToString()
	{
		ob_start(function () {});
		try {
			$this->global->coreCaptured = TRUE;
			$this->render();
		} catch (\Throwable $e) {
		} catch (\Exception $e) {
		}
		$this->global->coreCaptured = FALSE;
		if (isset($e)) {
			ob_end_clean();
			throw $e;
		}
		return ob_get_clean();
	}


	/**
	 * @return void
	 * @internal
	 */
	protected function renderToContentType($type)
	{
		if ($type === "html$this->contentType" && in_array($this->contentType, [Engine::CONTENT_JS, Engine::CONTENT_CSS], TRUE)) {
			echo Runtime\Filters::escapeHtmlRawText($this->renderToString());
			return;
		} elseif ($type === 'htmlattr' || ($type === Engine::CONTENT_HTML && $this->contentType !== Engine::CONTENT_HTML)) {
			echo Runtime\Filters::escapeHtml($this->renderToString());
			return;
		} elseif ($type && $type !== $this->contentType) {
			trigger_error("Including '$this->name' with content type " . strtoupper($this->contentType) . ' into incompatible type ' . strtoupper($type) . '.', E_USER_WARNING);
		}
		$this->render();
	}


	/**
	 * @return array
	 * @internal
	 */
	public function prepare()
	{
		return $this->params;
	}


	/********************* blocks ****************d*g**/


	/**
	 * Calls block.
	 * @return void
	 * @internal
	 */
	protected function renderBlock($name, array $params, $type = NULL)
	{
		if (empty($this->blockQueue[$name])) {
			$hint = isset($this->blockQueue) && ($t = Helpers::getSuggestion(array_keys($this->blockQueue), $name)) ? ", did you mean '$t'?" : '.';
			throw new \RuntimeException("Cannot include undefined block '$name'$hint");
		}
		if ($type && isset($this->blockTypes[$name]) && $this->blockTypes[$name] !== $type) {
			trigger_error("Including block $name with content type " . strtoupper($this->blockTypes[$name]) . ' into incompatible type ' . strtoupper($type) . '.', E_USER_WARNING);
		}
		$block = reset($this->blockQueue[$name]);
		$block($params);
	}


	/**
	 * Calls parent block.
	 * @return void
	 * @internal
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
	 * @internal
	 */
	protected function checkBlockContentType($current, $name)
	{
		$expected = & $this->blockTypes[$name];
		if ($expected === NULL) {
			$expected = $current;
		} elseif ($expected !== $current) {
			trigger_error("Overridden block $name with content type " . strtoupper($expected) . ' by incompatible type ' . strtoupper($current) . '.', E_USER_WARNING);
		}
	}


	/** @deprecated */
	public function setParameters(array $params)
	{
		trigger_error(__METHOD__ . ' is deprecated.', E_USER_DEPRECATED);
		$this->params = $params;
		return $this;
	}


	/********************* deprecated ****************d*g**/


	/** @deprecated */
	public function __call($name, $args)
	{
		trigger_error("Invoking filters via \$template->$name(\$vars) is deprecated, use (\$vars|$name)", E_USER_DEPRECATED);
		return call_user_func_array($this->filters->$name, $args);
	}


	/** @deprecated */
	public function __set($name, $value)
	{
		trigger_error("Access to parameters via \$template->$name is deprecated", E_USER_DEPRECATED);
		$this->params[$name] = $value;
	}


	/** @deprecated */
	public function &__get($name)
	{
		trigger_error("Access to parameters via \$template->$name is deprecated, use \$this->getParameter('$name')", E_USER_DEPRECATED);
		if (!array_key_exists($name, $this->params)) {
			trigger_error("The variable '$name' does not exist in template.");
		}
		return $this->params[$name];
	}


	/** @deprecated */
	public function __isset($name)
	{
		trigger_error("Access to parameters via \$template->$name is deprecated, use isset(\$this->getParameters()['$name'])", E_USER_DEPRECATED);
		return isset($this->params[$name]);
	}


	/** @deprecated */
	public function __unset($name)
	{
		trigger_error("Access to parameters via \$template->$name is deprecated.", E_USER_DEPRECATED);
		unset($this->params[$name]);
	}

}
