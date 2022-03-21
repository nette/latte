<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Sandbox;

use Latte;


/** @internal */
final class RuntimeChecker
{
	use Latte\Strict;

	public function __construct(
		public Latte\Policy $policy,
	) {
	}


	public function call(mixed $callable): mixed
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
			$allowed = $callable instanceof \Closure
				? true
				: $this->policy->isMethodAllowed($callable::class, '__invoke');
		} else {
			$allowed = false;
		}

		if (!$allowed) {
			is_callable($callable, false, $text);
			throw new Latte\SecurityViolationException("Calling $text() is not allowed.");
		}
		return $callable;
	}


	public function callMethod(mixed $object, mixed $method): ?\Closure
	{
		if ($object === null) {
			return null; // to support ?->
		} elseif (!is_object($object) || !is_string($method)) {
			throw new Latte\SecurityViolationException('Invalid callable.');
		} elseif (!$this->policy->isMethodAllowed($class = $object::class, $method)) {
			throw new Latte\SecurityViolationException("Calling $class::$method() is not allowed.");
		}
		return \Closure::fromCallable([$object, $method]); // to allow __invoke()
	}


	public function prop(mixed $object, mixed $property): mixed
	{
		$class = is_object($object) ? $object::class : $object;
		if (is_string($class) && !$this->policy->isPropertyAllowed($class, (string) $property)) {
			throw new Latte\SecurityViolationException("Access to '$property' property on a $class object is not allowed.");
		}
		return $object;
	}
}
