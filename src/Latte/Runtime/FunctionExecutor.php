<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;

use Latte\Helpers;


/**
 * Functions executor.
 * @internal
 */
#[\AllowDynamicProperties]
class FunctionExecutor
{
	/** @var callable[] */
	private array $_list = [];


	/**
	 * Registers run-time function.
	 */
	public function add(string $name, callable $callback): static
	{
		$this->_list[$name] = $callback;
		unset($this->$name);
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
		}

		return $callback;
	}
}
