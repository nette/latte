<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;

use Latte\Engine;
use Latte\Helpers;


/**
 * Filter executor.
 * @internal
 */
class FilterExecutor
{
	/** @var array */
	private $_dynamic = [];

	/** @var array [name => [callback, FilterInfo aware] */
	private $_static = [];


	public function __construct(array $list = [])
	{
		foreach ($list as $name => $callback) {
			$this->_static[strtolower($name)] = [$callback, null];
		}
	}


	/**
	 * Registers run-time filter.
	 * @return static
	 */
	public function add(?string $name, callable $callback)
	{
		if ($name == null) { // intentionally ==
			array_unshift($this->_dynamic, $callback);
		} else {
			$name = strtolower($name);
			$this->_static[$name] = [$callback, null];
			unset($this->$name);
		}
		return $this;
	}


	/**
	 * Returns all run-time filters.
	 * @return string[]
	 */
	public function getAll(): array
	{
		return array_combine($tmp = array_keys($this->_static), $tmp);
	}


	/**
	 * Returns filter for classic calling.
	 */
	public function __get($name): callable
	{
		$lname = strtolower($name);
		if (isset($this->$lname)) { // case mismatch
			return $this->$lname;

		} elseif (isset($this->_static[$lname])) {
			[$callback, $aware] = $this->prepareFilter($lname);
			if ($aware) { // FilterInfo aware filter
				return $this->$lname = function (...$args) use ($callback) {
					array_unshift($args, $info = new FilterInfo);
					if ($args[1] instanceof HtmlString) {
						$args[1] = $args[1]->__toString();
						$info->contentType = Engine::CONTENT_HTML;
					}
					$res = $callback(...$args);
					return $info->contentType === Engine::CONTENT_HTML
						? new Html($res)
						: $res;
				};
			} else { // classic filter
				return $this->$lname = $callback;
			}
		}

		return $this->$lname = function (...$args) use ($lname, $name) { // dynamic filter
			array_unshift($args, $lname);
			foreach ($this->_dynamic as $filter) {
				$res = $filter(...$args);
				if ($res !== null) {
					return $res;
				} elseif (isset($this->_static[$lname])) { // dynamic converted to classic
					$this->$name = $this->_static[$lname][0];
					return ($this->$name)(...func_get_args());
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
	public function filterContent(string $name, FilterInfo $info, ...$args)
	{
		$lname = strtolower($name);
		if (!isset($this->_static[$lname])) {
			$hint = ($t = Helpers::getSuggestion(array_keys($this->_static), $name)) ? ", did you mean '$t'?" : '.';
			throw new \LogicException("Filter |$name is not defined$hint");
		}

		[$callback, $aware] = $this->prepareFilter($lname);
		if ($aware) { // FilterInfo aware filter
			array_unshift($args, $info);
			return $callback(...$args);

		} else { // classic filter
			if ($info->contentType !== Engine::CONTENT_TEXT) {
				trigger_error("Filter |$name is called with incompatible content type " . strtoupper($info->contentType)
					. ($info->contentType === Engine::CONTENT_HTML ? ', try to prepend |stripHtml.' : '.'), E_USER_WARNING);
			}
			$res = ($this->$name)(...$args);
			if ($res instanceof HtmlString) {
				trigger_error("Filter |$name should be changed to content-aware filter.");
				$info->contentType = Engine::CONTENT_HTML;
				$res = $res->__toString();
			}
			return $res;
		}
	}


	private function prepareFilter(string $name): array
	{
		if (!isset($this->_static[$name][1])) {
			$params = Helpers::toReflection($this->_static[$name][0])->getParameters();
			$this->_static[$name][1] = $params
				&& $params[0]->getType() instanceof \ReflectionNamedType
				&& $params[0]->getType()->getName() === FilterInfo::class;
		}
		return $this->_static[$name];
	}
}
