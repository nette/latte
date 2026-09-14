<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Sandbox;

use Latte;
use function count, is_array, is_object, is_string;


/** @internal */
final readonly class RuntimeChecker
{
	public function __construct(
		public Latte\Policy $policy,
	) {
	}


	/**
	 * Calls a function after verifying it is allowed by the policy.
	 * @param  array<mixed>  $args
	 */
	public function call(mixed $callable, array $args): mixed
	{
		self::checkCallable($callable);
		self::args(...$args);
		return $callable(...$args);
	}


	/**
	 * Validates that calling the method is allowed by the policy and returns the object.
	 */
	public function method(mixed $object, mixed $method): mixed
	{
		if (!is_object($object)) {
			return $object;

		} elseif (!is_string($method)) {
			throw new Latte\SecurityViolationException('Invalid callable.');

		} elseif (!$this->policy->isMethodAllowed($class = $object::class, $method)) {
			throw new Latte\SecurityViolationException("Calling $class::$method() is not allowed.");
		}

		return $object;
	}


	/**
	 * Returns the callable wrapped as a Closure after verifying it is allowed by the policy.
	 */
	public function closure(mixed $callable): \Closure
	{
		self::checkCallable($callable);
		return $callable(...);
	}


	/**
	 * Validates that no array argument is a disallowed callable and returns the arguments.
	 * @return mixed[]
	 */
	public function args(mixed ...$args): array
	{
		foreach ($args as $arg) {
			if (
				is_array($arg)
				&& is_callable($arg, true, $text)
				&& !$this->policy->isMethodAllowed(is_object($arg[0]) ? $arg[0]::class : $arg[0], $arg[1])
			) {
				throw new Latte\SecurityViolationException("Calling $text() is not allowed.");
			}
		}

		return $args;
	}


	/**
	 * Validates that property access is allowed by the policy and returns the object.
	 */
	public function prop(mixed $object, mixed $property): mixed
	{
		$class = is_object($object) ? $object::class : $object;
		if (is_string($class) && !$this->policy->isPropertyAllowed($class, (string) $property)) {
			throw new Latte\SecurityViolationException("Access to '$property' property on a $class object is not allowed.");
		}

		return $object;
	}


	/**
	 * Casts the value to array, which is not allowed for an object, as it would reveal its non-public state.
	 * @return mixed[]
	 */
	public function castToArray(mixed $value): array
	{
		if (is_object($value)) {
			throw new Latte\SecurityViolationException('Casting a ' . $value::class . ' object to array is not allowed.');
		}

		return (array) $value;
	}


	/**
	 * Validates that iterating the value is allowed by the policy and returns it. Iterating a plain object reads its properties.
	 */
	public function iterable(mixed $value): mixed
	{
		if (is_object($value) && !$value instanceof \Traversable) {
			foreach (array_keys(get_object_vars($value)) as $property) {
				$this->prop($value, $property);
			}
		}

		return $value;
	}


	/**
	 * Same as iterable() for foreach by reference.
	 */
	public function &iterableByRef(mixed &$value): mixed
	{
		$this->iterable($value);
		return $value;
	}


	private function checkCallable(mixed $callable): void
	{
		if (!is_callable($callable)) {
			throw new Latte\SecurityViolationException('Invalid callable.');

		} elseif (is_string($callable)) {
			$parts = explode('::', $callable);
			$allowed = count($parts) === 1
				? $this->policy->isFunctionAllowed($parts[0])
				: $this->policy->isMethodAllowed(...$parts);

		} elseif (is_array($callable)) {
			$allowed = $this->policy->isMethodAllowed(is_object($callable[0]) ? $callable[0]::class : $callable[0], $callable[1]);

		} elseif (is_object($callable)) {
			$allowed = $callable instanceof \Closure || $this->policy->isMethodAllowed($callable::class, '__invoke');

		} else {
			$allowed = false;
		}

		if (!$allowed) {
			is_callable($callable, false, $text);
			throw new Latte\SecurityViolationException("Calling $text() is not allowed.");
		}
	}
}
