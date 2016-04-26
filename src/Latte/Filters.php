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
	use Strict;

	/** @var array */
	private $dynamic = [];

	/** @var array */
	private $static = [
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
			array_unshift($this->dynamic, $callback);
		} else {
			$this->static[strtolower($name)] = $callback;
		}
		return $this;
	}


	/**
	 * Returns all run-time filters.
	 * @return callable[]
	 */
	public function getAll()
	{
		return $this->static;
	}


	/**
	 * Call a run-time filter.
	 * @param  string  filter name
	 * @param  array   arguments
	 * @return mixed
	 */
	public function invoke($name, array $args)
	{
		$lname = strtolower($name);
		if (!isset($this->static[$lname])) {
			$args2 = $args;
			array_unshift($args2, $lname);
			foreach ($this->dynamic as $filter) {
				$res = call_user_func_array(Helpers::checkCallback($filter), $args2);
				if ($res !== NULL) {
					return $res;
				} elseif (isset($this->static[$lname])) {
					return call_user_func_array(Helpers::checkCallback($this->static[$lname]), $args);
				}
			}
			$hint = ($t = Helpers::getSuggestion(array_keys($this->static), $name)) ? ", did you mean '$t'?" : '.';
			throw new \LogicException("Filter '$name' is not defined$hint");
		}
		return call_user_func_array(Helpers::checkCallback($this->static[$lname]), $args);
	}

}
