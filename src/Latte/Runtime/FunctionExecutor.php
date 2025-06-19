<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;

use Latte\Helpers;
use function array_keys;


/**
 * Functions executor.
 * @internal
 */
#[\AllowDynamicProperties]
class FunctionExecutor
{
	/** @var callable[] */
	private array $_list = [];

	/** @var bool[] */
	private array $_aware = [];


	/**
	 * Registers run-time function.
	 */
	public function add(string $name, callable $callback): static
	{
		$this->_list[$name] = $callback;
		unset($this->$name, $this->_aware[$name]);
		return $this;
	}


	/**
	 * Returns all run-time functions.
	 * @return callable[]
	 */
	public function getAll(): array
	{
		return $this->_list;
	}


	public function __get(string $name): callable
	{
		$callback = $this->_list[$name] ?? null;
		if (!$callback) {
			$hint = ($t = Helpers::getSuggestion(array_keys($this->_list), $name))
				? ", did you mean '$t'?"
				: '.';
			throw new \LogicException("Function '$name' is not defined$hint");

		} elseif (!isset($this->_aware[$name])) {
			$params = Helpers::toReflection($callback)->getParameters();
			$this->_aware[$name] = $params && (string) $params[0]->getType() === Template::class;
		}

		return $this->$name = $this->_aware[$name]
			? $callback
			: fn($info, ...$args) => $callback(...$args);
	}
}
