<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;

use Latte;
use Latte\ContentType;
use Latte\Helpers;
use function array_column, array_combine, array_keys, array_unshift, strtoupper;


/**
 * Filter executor.
 * @internal
 */
#[\AllowDynamicProperties]
class FilterExecutor
{
	/** @var callable[] */
	private array $_dynamic = [];

	/** @var array<string, array{callable, ?bool}> */
	private array $_static = [];


	/**
	 * Registers run-time filter.
	 */
	public function add(?string $name, callable $callback): static
	{
		if ($name === null) {
			array_unshift($this->_dynamic, $callback);
		} else {
			$this->_static[$name] = [$callback, null];
			unset($this->$name);
		}

		return $this;
	}


	/**
	 * Returns all run-time filters.
	 * @return callable[]
	 */
	public function getAll(): array
	{
		return array_combine(array_keys($this->_static), array_column($this->_static, 0));
	}


	/**
	 * Returns filter for classic calling.
	 */
	public function __get(string $name): callable
	{
		[$callback, $infoAware] = $this->prepareFilter($name);
		return $this->$name = $infoAware
			? fn(...$args) => $this->callInfoAwareAsClassic($callback, ...$args)
			: $callback;
	}


	/**
	 * Calls filter with FilterInfo.
	 */
	public function filterContent(string $name, FilterInfo $info, mixed ...$args): mixed
	{
		if ($info->contentType === ContentType::Html && $args[0] instanceof HtmlStringable) {
			$args[0] = $args[0]->__toString();
		}

		[$callback, $infoAware] = $this->prepareFilter($name);
		if ($infoAware) {
			array_unshift($args, $info);
			return $callback(...$args);
		}

		// classic filter
		if ($info->contentType !== ContentType::Text) {
			throw new Latte\RuntimeException("Filter |$name is called with incompatible content type " . strtoupper($info->contentType ?? 'NULL')
				. ($info->contentType === ContentType::Html ? ', try to prepend |stripHtml.' : '.'));
		}

		$res = $callback(...$args);
		if ($res instanceof HtmlStringable) {
			trigger_error("Filter |$name should be changed to content-aware filter.");
			$info->contentType = ContentType::Html;
			$res = $res->__toString();
		}

		return $res;
	}


	/**
	 * @return array{callable, bool}
	 */
	private function prepareFilter(string $name): array
	{
		if (isset($this->_static[$name])) {
			$this->_static[$name][1] ??= $this->isInfoAware($this->_static[$name][0]);
			return $this->_static[$name];
		}

		foreach ($this->_dynamic as $loader) {
			if ($callback = $loader($name)) {
				return $this->_static[$name] = [$callback, $this->isInfoAware($callback)];
			}
		}

		$hint = ($t = Helpers::getSuggestion(array_keys($this->_static), $name))
			? ", did you mean '$t'?"
			: '.';
		throw new \LogicException("Filter '$name' is not defined$hint");
	}


	private function isInfoAware(callable $filter): bool
	{
		$params = Helpers::toReflection($filter)->getParameters();
		return $params && (string) $params[0]->getType() === FilterInfo::class;
	}


	private function callInfoAwareAsClassic(callable $filter, mixed ...$args): mixed
	{
		array_unshift($args, $info = new FilterInfo);
		if ($args[1] instanceof HtmlStringable) {
			$args[1] = $args[1]->__toString();
			$info->contentType = ContentType::Html;
		}

		$res = $filter(...$args);
		return $info->contentType === ContentType::Html
			? new Html($res)
			: $res;
	}
}
