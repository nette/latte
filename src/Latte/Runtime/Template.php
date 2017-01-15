<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;

use Latte;
use Latte\Engine;


/**
 * Template.
 */
class Template
{
	use Latte\Strict;

	/** @var Engine */
	private $engine;

	/** @var string */
	private $name;

	/** @var string  @internal */
	protected $contentType = Engine::CONTENT_HTML;

	/** @var array  @internal */
	protected $params = [];

	/** @var FilterExecutor */
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


	public function __construct(Engine $engine, array $params, FilterExecutor $filters, array $providers, $name)
	{
		$this->engine = $engine;
		$this->params = $params;
		$this->filters = $filters;
		$this->name = $name;
		$this->global = (object) $providers;
		foreach ($this->blocks as $nm => $method) {
			$this->blockQueue[$nm][] = [$this, $method];
		}
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
	 * Renders template.
	 * @return void
	 * @internal
	 */
	public function render()
	{
		$this->prepare();

		if ($this->parentName === NULL && isset($this->global->coreParentFinder)) {
			$this->parentName = ($this->global->coreParentFinder)($this);
		}
		if (isset($this->global->snippetBridge) && !isset($this->global->snippetDriver)) {
			$this->global->snippetDriver = new SnippetDriver($this->global->snippetBridge);
		}
		Filters::$xhtml = (bool) preg_match('#xml|xhtml#', $this->contentType);

		if ($this->referenceType === 'import') {
			if ($this->parentName) {
				$this->createTemplate($this->parentName, [], 'import')->render();
			}
			return;

		} elseif ($this->parentName) { // extends
			ob_start(function () {});
			$params = $this->main();
			ob_end_clean();
			$this->createTemplate($this->parentName, $params, 'extends')->render();
			return;

		} elseif (!empty($this->params['_renderblock'])) { // single block rendering
			$tmp = $this;
			while (in_array($this->referenceType, ['extends', NULL], TRUE) && ($tmp = $tmp->referringTemplate));
			if (!$tmp) {
				$this->renderBlock($this->params['_renderblock'], $this->params);
				return;
			}
		}

		// old accumulators for back compatibility
		$this->params['_l'] = new \stdClass;
		$this->params['_g'] = $this->global;
		$this->params['_b'] = (object) ['blocks' => &$this->blockQueue, 'types' => &$this->blockTypes];
		if (isset($this->global->snippetDriver) && $this->global->snippetBridge->isSnippetMode()) {
			if ($this->global->snippetDriver->renderSnippets($this->blockQueue, $this->params)) {
				return;
			}
		}

		$this->main();
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
			$child->blockQueue = &$this->blockQueue;
			$child->blockTypes = &$this->blockTypes;
		}
		return $child;
	}


	/**
	 * @param  string|\Closure content-type name or modifier closure
	 * @return void
	 * @internal
	 */
	protected function renderToContentType($mod)
	{
		if ($mod instanceof \Closure) {
			echo $mod($this->capture([$this, 'render']), $this->contentType);
		} elseif ($mod && $mod !== $this->contentType) {
			if ($filter = Filters::getConvertor($this->contentType, $mod)) {
				echo $filter($this->capture([$this, 'render']));
			} else {
				trigger_error("Including '$this->name' with content type " . strtoupper($this->contentType) . ' into incompatible type ' . strtoupper($mod) . '.', E_USER_WARNING);
			}
		} else {
			$this->render();
		}
	}


	/**
	 * @return void
	 * @internal
	 */
	public function prepare()
	{
	}


	/********************* blocks ****************d*g**/


	/**
	 * Renders block.
	 * @param  string
	 * @param  array
	 * @param  string|\Closure content-type name or modifier closure
	 * @return void
	 * @internal
	 */
	protected function renderBlock($name, array $params, $mod = NULL)
	{
		if (empty($this->blockQueue[$name])) {
			$hint = isset($this->blockQueue) && ($t = Latte\Helpers::getSuggestion(array_keys($this->blockQueue), $name)) ? ", did you mean '$t'?" : '.';
			throw new \RuntimeException("Cannot include undefined block '$name'$hint");
		}

		$block = reset($this->blockQueue[$name]);
		if ($mod && $mod !== ($blockType = $this->blockTypes[$name])) {
			if ($filter = (is_string($mod) ? Filters::getConvertor($blockType, $mod) : $mod)) {
				echo $filter($this->capture(function () use ($block, $params) { $block($params); }), $blockType);
				return;
			}
			trigger_error("Including block $name with content type " . strtoupper($blockType) . ' into incompatible type ' . strtoupper($mod) . '.', E_USER_WARNING);
		}
		$block($params);
	}


	/**
	 * Renders parent block.
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
		$expected = &$this->blockTypes[$name];
		if ($expected === NULL) {
			$expected = $current;
		} elseif ($expected !== $current) {
			trigger_error("Overridden block $name with content type " . strtoupper($current) . ' by incompatible type ' . strtoupper($expected) . '.', E_USER_WARNING);
		}
	}


	/**
	 * Captures output to string.
	 * @return string
	 * @internal
	 */
	public function capture(callable $function)
	{
		try {
			ob_start(function () {});
			$this->global->coreCaptured = TRUE;
			$function();
			return ob_get_clean();
		} catch (\Throwable $e) {
			ob_end_clean();
			throw $e;
		} finally {
			$this->global->coreCaptured = FALSE;
		}
	}

}
