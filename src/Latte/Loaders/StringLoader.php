<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Loaders;

use Latte;


/**
 * Template loader.
 */
class StringLoader implements Latte\Loader
{
	/** @var string[]|null  [name => content] */
	private ?array $templates = null;


	/**
	 * @param  string[]  $templates
	 */
	public function __construct(?array $templates = null)
	{
		$this->templates = $templates;
	}


	public function load(string $name): Latte\LoadedContent
	{
		if ($this->templates === null) {
			return new Latte\LoadedContent($name);
		} elseif (isset($this->templates[$name])) {
			return new Latte\LoadedContent($this->templates[$name], sourceName: $name);
		} else {
			throw new Latte\TemplateNotFoundException("Missing template '$name'.");
		}
	}


	/**
	 * Returns referred template name.
	 */
	public function getReferredName(string $name, string $referringName): string
	{
		if ($this->templates === null) {
			throw new Latte\TemplateNotFoundException("Missing template '$name'.");
		}

		return $name;
	}


	/**
	 * Returns unique identifier for caching.
	 */
	public function getUniqueId(string $name): string
	{
		return $this->load($name)->content;
	}
}
