<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte;


/**
 * Filters.
 * @internal
 */
class Filters
{
	/** @var array */
	private $_dynamic = [];

	/** @var array [name => [callback, FilterInfo aware] */
	private $_static = [
		'breaklines' => ['Latte\Runtime\Filters::breaklines', FALSE],
		'bytes' => ['Latte\Runtime\Filters::bytes', FALSE],
		'capitalize' => ['Latte\Runtime\Filters::capitalize', FALSE],
		'datastream' => ['Latte\Runtime\Filters::dataStream', FALSE],
		'date' => ['Latte\Runtime\Filters::date', FALSE],
		'escapecss' => ['Latte\Runtime\Filters::escapeCss', FALSE],
		'escapehtml' => ['Latte\Runtime\Filters::escapeHtmlAttr', FALSE],
		'escapehtmlcomment' => ['Latte\Runtime\Filters::escapeHtmlComment', FALSE],
		'escapeical' => ['Latte\Runtime\Filters::escapeICal', FALSE],
		'escapejs' => ['Latte\Runtime\Filters::escapeJs', FALSE],
		'escapeurl' => ['rawurlencode', FALSE],
		'escapexml' => ['Latte\Runtime\Filters::escapeXml', FALSE],
		'firstupper' => ['Latte\Runtime\Filters::firstUpper', FALSE],
		'checkurl' => ['Latte\Runtime\Filters::safeUrl', FALSE],
		'implode' => ['implode', FALSE],
		'indent' => ['Latte\Runtime\Filters::indent', FALSE],
		'length' => ['Latte\Runtime\Filters::length', FALSE],
		'lower' => ['Latte\Runtime\Filters::lower', FALSE],
		'nl2br' => ['Latte\Runtime\Filters::nl2br', FALSE],
		'number' => ['number_format', FALSE],
		'repeat' => ['str_repeat', FALSE],
		'replace' => ['Latte\Runtime\Filters::replace', FALSE],
		'replacere' => ['Latte\Runtime\Filters::replaceRe', FALSE],
		'safeurl' => ['Latte\Runtime\Filters::safeUrl', FALSE],
		'strip' => ['Latte\Runtime\Filters::strip', FALSE],
		'striptags' => ['strip_tags', FALSE],
		'substr' => ['Latte\Runtime\Filters::substring', FALSE],
		'trim' => ['Latte\Runtime\Filters::trim', FALSE],
		'truncate' => ['Latte\Runtime\Filters::truncate', FALSE],
		'upper' => ['Latte\Runtime\Filters::upper', FALSE],
	];


	/**
	 * Registers run-time filter.
	 * @param  string|NULL
	 * @param  callable
	 * @return self
	 */
	public function add($name, $callback)
	{
		if ($name == NULL) { // intentionally ==
			array_unshift($this->_dynamic, $callback);
		} else {
			$name = strtolower($name);
			$this->_static[$name] = [$callback, NULL];
			unset($this->$name);
		}
		return $this;
	}


	/**
	 * Returns all run-time filters.
	 * @return string[]
	 */
	public function getAll()
	{
		return array_combine($tmp = array_keys($this->_static), $tmp);
	}


	/**
	 * Returns filter for classic calling.
	 * @return callable
	 */
	public function __get($name)
	{
		$lname = strtolower($name);
		if (isset($this->$lname)) { // case mismatch
			return $this->$lname;

		} elseif (isset($this->_static[$lname])) {
			list($callback, $aware) = $this->prepareFilter($lname);
			if ($aware) { // FilterInfo aware filter
				return $this->$lname = function ($arg) use ($callback) {
					$args = func_get_args();
					array_unshift($args, new Runtime\FilterInfo);
					return call_user_func_array($callback, $args);
				};
			} else { // classic filter
				return $this->$lname = $callback;
			}
		}

		return $this->$lname = function ($arg) use ($lname, $name) { // dynamic filter
			$args = func_get_args();
			array_unshift($args, $lname);
			foreach ($this->_dynamic as $filter) {
				$res = call_user_func_array(Helpers::checkCallback($filter), $args);
				if ($res !== NULL) {
					return $res;
				} elseif (isset($this->_static[$lname])) { // dynamic converted to classic
					$this->$name = Helpers::checkCallback($this->_static[$lname][0]);
					return call_user_func_array($this->$name, func_get_args());
				}
			}
			$hint = ($t = Helpers::getSuggestion(array_keys($this->_static), $name)) ? ", did you mean '$t'?" : '.';
			throw new \LogicException("Filter '$name' is not defined$hint");
		};
	}


	/**
	 * Calls filter with FilterInfo.
	 * @return mixed
	 */
	public function filterContent($name, Runtime\FilterInfo $info, $arg)
	{
		$lname = strtolower($name);
		$args = func_get_args();
		array_shift($args);

		if (!isset($this->_static[$lname])) {
			$hint = ($t = Helpers::getSuggestion(array_keys($this->_static), $name)) ? ", did you mean '$t'?" : '.';
			throw new \LogicException("Filter |$name is not defined$hint");
		}

		list($callback, $aware) = $this->prepareFilter($lname);
		if ($aware) { // FilterInfo aware filter
			return call_user_func_array($callback, $args);

		} else { // classic filter
			array_shift($args);
			if ($info->contentType !== Engine::CONTENT_TEXT && $info->contentType !== Engine::CONTENT_HTML) {
				trigger_error("Filter |$name is called with incompatible content type " . strtoupper($info->contentType), E_USER_WARNING);
			}
			return call_user_func_array($this->$name, $args);
		}
	}


	private function prepareFilter($name)
	{
		if (!isset($this->_static[$name][1])) {
			$callback = Helpers::checkCallback($this->_static[$name][0]);
			if (is_string($callback) && strpos($callback, '::')) {
				$callback = explode('::', $callback);
			}
			$ref = is_array($callback)
				? new \ReflectionMethod($callback[0], $callback[1])
				: new \ReflectionFunction($callback);
			$this->_static[$name][1] = $ref->getParameters() && $ref->getParameters()[0]->getClass();
		}
		return $this->_static[$name];
	}

}
