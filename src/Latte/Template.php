<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 */

namespace Latte;


/**
 * Template.
 *
 * @author     David Grudl
 * @internal
 */
class Template extends Object
{
	/** @var Engine */
	private $engine;

	/** @var Engine */
	private $name;

	/** @var array */
	protected $params = array();

	/** @var array run-time filters */
	protected $filters = array(
		NULL => array(), // dynamic
	);


	public function __construct(array $params, array & $filters, Engine $engine, $name)
	{
		$params['template'] = $this;
		$this->setParameters($params);
		$this->filters = & $filters;
		$this->engine = $engine;
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
	 */
	public function initialize($templateId, $contentType)
	{
		Runtime\Filters::$xhtml = (bool) preg_match('#xml|xhtml#', $contentType);

		// block storage
		if (isset($this->params['_b'])) {
			$block = $this->params['_b'];
			unset($this->params['_b']);
		} else {
			$block = new \stdClass;
		}
		$block->templates[$templateId] = $this;

		// global storage
		if (!isset($this->params['_g'])) {
			$this->params['_g'] = new \stdClass;
		}

		return array($block, $this->params['_g'], new \stdClass);
	}


	/**
	 * Renders template.
	 * @return void
	 */
	public function renderChildTemplate($name, array $params = array())
	{
		$name = $this->engine->getLoader()->getChildName($name, $this->name);
		$this->engine->render($name, $params);
	}


	/**
	 * Call a template run-time filter. Do not call directly.
	 * @param  string  filter name
	 * @param  array   arguments
	 * @return mixed
	 */
	public function __call($name, $args)
	{
		$lname = strtolower($name);
		if (!isset($this->filters[$lname])) {
			$args2 = $args;
			array_unshift($args2, $lname);
			foreach ($this->filters[NULL] as $filter) {
				$res = call_user_func_array(Helpers::checkCallback($filter), $args2);
				if ($res !== NULL) {
					return $res;
				} elseif (isset($this->filters[$lname])) {
					return call_user_func_array(Helpers::checkCallback($this->filters[$lname]), $args);
				}
			}
			return parent::__call($name, $args);
		}
		return call_user_func_array(Helpers::checkCallback($this->filters[$lname]), $args);
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
		$this->params['template'] = $this;
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


	/**
	 * Sets a template parameter. Do not call directly.
	 * @return void
	 */
	public function __set($name, $value)
	{
		$this->params[$name] = $value;
	}


	/**
	 * Returns a template parameter. Do not call directly.
	 * @return mixed  value
	 */
	public function &__get($name)
	{
		if (!array_key_exists($name, $this->params)) {
			trigger_error("The variable '$name' does not exist in template.", E_USER_NOTICE);
		}
		return $this->params[$name];
	}


	/**
	 * Determines whether parameter is defined. Do not call directly.
	 * @return bool
	 */
	public function __isset($name)
	{
		return isset($this->params[$name]);
	}


	/**
	 * Removes a template parameter. Do not call directly.
	 * @param  string    name
	 * @return void
	 */
	public function __unset($name)
	{
		unset($this->params[$name]);
	}

}
