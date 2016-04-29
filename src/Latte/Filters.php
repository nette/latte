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

	/** @var array */
	private $_static = [
		'bytes' => 'Latte\Runtime\Filters::bytes',
		'capitalize' => 'Latte\Runtime\Filters::capitalize',
		'datastream' => 'Latte\Runtime\Filters::dataStream',
		'date' => 'Latte\Runtime\Filters::date',
		'escapecss' => 'Latte\Runtime\Filters::escapeCss',
		'escapehtml' => 'Latte\Runtime\Filters::escapeHtmlAttr',
		'escapehtmlcomment' => 'Latte\Runtime\Filters::escapeHtmlComment',
		'escapeical' => 'Latte\Runtime\Filters::escapeICal',
		'escapejs' => 'Latte\Runtime\Filters::escapeJs',
		'escapeurl' => 'rawurlencode',
		'escapexml' => 'Latte\Runtime\Filters::escapeXml',
		'firstupper' => 'Latte\Runtime\Filters::firstUpper',
		'checkurl' => 'Latte\Runtime\Filters::safeUrl',
		'implode' => 'implode',
		'indent' => 'Latte\Runtime\Filters::indent',
		'length' => 'Latte\Runtime\Filters::length',
		'lower' => 'Latte\Runtime\Filters::lower',
		'nl2br' => 'Latte\Runtime\Filters::nl2br',
		'number' => 'number_format',
		'repeat' => 'str_repeat',
		'replace' => 'Latte\Runtime\Filters::replace',
		'replacere' => 'Latte\Runtime\Filters::replaceRe',
		'safeurl' => 'Latte\Runtime\Filters::safeUrl',
		'strip' => 'Latte\Runtime\Filters::strip',
		'striptags' => 'strip_tags',
		'substr' => 'Latte\Runtime\Filters::substring',
		'trim' => 'Latte\Runtime\Filters::trim',
		'truncate' => 'Latte\Runtime\Filters::truncate',
		'upper' => 'Latte\Runtime\Filters::upper',
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
			$this->_static[$name] = $callback;
			unset($this->$name);
		}
		return $this;
	}


	/**
	 * Returns all run-time filters.
	 * @return callable[]
	 */
	public function getAll()
	{
		return $this->_static;
	}


	/**
	 * Returns filter.
	 * @return callable
	 */
	public function __get($name)
	{
		$lname = strtolower($name);
		if (isset($this->$lname)) {
			return $this->$lname;

		} elseif (isset($this->_static[$lname])) {
			return $this->$lname = Helpers::checkCallback($this->_static[$lname]);
		}

		return $this->$lname = function ($arg) use ($lname, $name) {
			$args = func_get_args();
			array_unshift($args, $lname);
			foreach ($this->_dynamic as $filter) {
				$res = call_user_func_array(Helpers::checkCallback($filter), $args);
				if ($res !== NULL) {
					return $res;
				} elseif (isset($this->_static[$lname])) {
					$this->$name = Helpers::checkCallback($this->_static[$lname]);
					return call_user_func_array($this->$name, func_get_args());
				}
			}
			$hint = ($t = Helpers::getSuggestion(array_keys($this->_static), $name)) ? ", did you mean '$t'?" : '.';
			throw new \LogicException("Filter '$name' is not defined$hint");
		};
	}

}
