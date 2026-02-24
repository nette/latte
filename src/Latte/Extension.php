<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte;

use function get_defined_vars;


/**
 * Latte extension.
 */
abstract class Extension
{
	/**
	 * Initializes before template is compiled.
	 */
	public function beforeCompile(Engine $engine): void
	{
	}


	/**
	 * Returns a list of parsers for Latte tags.
	 * @return array<string, callable(Compiler\Tag, Compiler\TemplateParser): (Compiler\Node|\Generator|void)|\stdClass>
	 */
	public function getTags(): array
	{
		return [];
	}


	/**
	 * Returns a list of compiler passes.
	 * @return array<string, callable(Compiler\Nodes\TemplateNode): void|\stdClass>
	 */
	public function getPasses(): array
	{
		return [];
	}


	/**
	 * Returns a list of |filters.
	 * @return array<string, callable>
	 */
	public function getFilters(): array
	{
		return [];
	}


	/**
	 * Returns a list of functions used in templates.
	 * @return array<string, callable>
	 */
	public function getFunctions(): array
	{
		return [];
	}


	/**
	 * Returns a list of providers.
	 * @return array<string, mixed>
	 */
	public function getProviders(): array
	{
		return [];
	}


	/**
	 * Returns a value to distinguish multiple versions of the template.
	 */
	public function getCacheKey(Engine $engine): mixed
	{
		return null;
	}


	/**
	 * Initializes before template is rendered.
	 */
	public function beforeRender(Runtime\Template $template): void
	{
	}


	/**
	 * Wraps callable with ordering metadata for tags and passes.
	 * @param  array<string>|string  $before
	 * @param  array<string>|string  $after
	 */
	public static function order(callable $subject, array|string $before = [], array|string $after = []): \stdClass
	{
		$subject = $subject(...);
		return (object) get_defined_vars();
	}
}
