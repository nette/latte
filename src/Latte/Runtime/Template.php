<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;

use Latte;
use Latte\Engine;
use Latte\Policy;


/**
 * Template.
 */
class Template
{
	use Latte\Strict;

	public const
		LAYER_TOP = 0,
		LAYER_SNIPPET = 'snippet',
		LAYER_LOCAL = 'local';

	protected const CONTENT_TYPE = Engine::CONTENT_HTML;

	protected const BLOCKS = [];

	/** @var \stdClass global accumulators for intermediate results */
	public $global;

	/** @var mixed[]  @internal */
	protected $params = [];

	/** @var FilterExecutor */
	protected $filters;

	/** @var string|false|null  @internal */
	protected $parentName;

	/** @var Block[][] */
	private $blocks;

	/** @var int  current layer */
	private $index = self::LAYER_TOP;

	/** @var Engine */
	private $engine;

	/** @var string */
	private $name;

	/** @var Policy|null */
	private $policy;

	/** @var Template|null */
	private $referringTemplate;

	/** @var string|null */
	private $referenceType;


	/**
	 * @param  mixed[]  $params
	 * @param  mixed[]  $providers
	 */
	public function __construct(
		Engine $engine,
		array $params,
		FilterExecutor $filters,
		array $providers,
		string $name,
		?Policy $policy
	) {
		$this->engine = $engine;
		$this->params = $params;
		$this->filters = $filters;
		$this->name = $name;
		$this->policy = $policy;
		$this->global = (object) $providers;
		$this->initBlockLayer(self::LAYER_TOP);
		$this->initBlockLayer(self::LAYER_LOCAL);
		$this->initBlockLayer(self::LAYER_SNIPPET);
	}


	public function getEngine(): Engine
	{
		return $this->engine;
	}


	public function getName(): string
	{
		return $this->name;
	}


	/**
	 * Returns array of all parameters.
	 * @return mixed[]
	 */
	public function getParameters(): array
	{
		return $this->params;
	}


	/**
	 * Returns parameter.
	 * @return mixed
	 */
	public function getParameter(string $name)
	{
		if (!array_key_exists($name, $this->params)) {
			trigger_error("The variable '$name' does not exist in template.", E_USER_NOTICE);
		}
		return $this->params[$name];
	}


	/**
	 * @param  int|string  $layer
	 * @return string[]
	 */
	public function getBlockNames($layer = self::LAYER_TOP): array
	{
		return array_keys($this->blocks[$layer] ?? []);
	}


	public function getContentType(): string
	{
		return static::CONTENT_TYPE;
	}


	public function getParentName(): ?string
	{
		return $this->parentName ?: null;
	}


	public function getReferringTemplate(): ?self
	{
		return $this->referringTemplate;
	}


	public function getReferenceType(): ?string
	{
		return $this->referenceType;
	}


	/**
	 * Renders template.
	 * @internal
	 */
	public function render(string $block = null): void
	{
		$this->prepare();

		if ($this->parentName === null && isset($this->global->coreParentFinder)) {
			$this->parentName = ($this->global->coreParentFinder)($this);
		}
		if (isset($this->global->snippetBridge) && !isset($this->global->snippetDriver)) {
			$this->global->snippetDriver = new SnippetDriver($this->global->snippetBridge);
		}
		Filters::$xhtml = (bool) preg_match('#xml|xhtml#', static::CONTENT_TYPE);

		if ($this->referenceType === 'import') {
			if ($this->parentName) {
				throw new Latte\RuntimeException('Imported template cannot use {extends} or {layout}, use {import}');
			}
			return;

		} elseif ($this->parentName) { // extends
			ob_start(function () {});
			$this->params = $this->main();
			ob_end_clean();
			$this->createTemplate($this->parentName, $this->params, 'extends')->render($block);
			return;

		} elseif ($block !== null) { // single block rendering
			$this->renderBlock($block, $this->params);
			return;
		}

		// old accumulators for back compatibility
		$this->params['_l'] = new \stdClass;
		$this->params['_g'] = $this->global;
		if (
			isset($this->global->snippetDriver)
			&& $this->global->snippetBridge->isSnippetMode()
			&& $this->global->snippetDriver->renderSnippets($this->blocks[self::LAYER_SNIPPET], $this->params)
		) {
			return;
		}

		$this->main();
	}


	/**
	 * Renders template.
	 * @param  mixed[]  $params
	 * @internal
	 */
	public function createTemplate(string $name, array $params, string $referenceType): self
	{
		$name = $this->engine->getLoader()->getReferredName($name, $this->name);
		$referred = $referenceType === 'sandbox'
			? (clone $this->engine)->setSandboxMode()->createTemplate($name, $params)
			: $this->engine->createTemplate($name, $params);

		$referred->referringTemplate = $this;
		$referred->referenceType = $referenceType;
		$referred->global = $this->global;

		if (in_array($referenceType, ['extends', 'includeblock', 'import', 'embed'], true)) {
			foreach ($referred->blocks[self::LAYER_TOP] as $nm => $block) {
				$this->addBlock($nm, $block->contentType, $block->functions);
			}
			$referred->blocks[self::LAYER_TOP] = &$this->blocks[$this->index];

			$this->blocks[self::LAYER_SNIPPET] += $referred->blocks[self::LAYER_SNIPPET];
			$referred->blocks[self::LAYER_SNIPPET] = &$this->blocks[self::LAYER_SNIPPET];
		}

		($this->engine->probe)($referred);
		return $referred;
	}


	/**
	 * @param  string|\Closure|null  $mod  content-type name or modifier closure
	 * @internal
	 */
	public function renderToContentType($mod, string $block = null): void
	{
		$this->filter(
			function () use ($block) { $this->render($block); },
			$mod,
			static::CONTENT_TYPE,
			"'$this->name'"
		);
	}


	/** @internal */
	public function prepare(): void
	{
	}


	/**
	 * @internal
	 * @return mixed[]
	 */
	public function main(): array
	{
		return [];
	}


	/********************* blocks ****************d*g**/


	/**
	 * Renders block.
	 * @param  mixed[]  $params
	 * @param  string|\Closure|null  $mod  content-type name or modifier closure
	 * @param  int|string  $layer
	 * @internal
	 */
	public function renderBlock(string $name, array $params, $mod = null, $layer = null): void
	{
		$block = $layer
			? ($this->blocks[$layer][$name] ?? null)
			: ($this->blocks[self::LAYER_LOCAL][$name] ?? $this->blocks[$this->index][$name] ?? null);

		if (!$block) {
			$hint = ($t = Latte\Helpers::getSuggestion($this->getBlockNames($layer), $name))
				? ", did you mean '$t'?"
				: '.';
			$name = $layer ? "$layer $name" : $name;
			throw new Latte\RuntimeException("Cannot include undefined block '$name'$hint");
		}

		$this->filter(
			function () use ($block, $params): void { reset($block->functions)($params); },
			$mod,
			$block->contentType,
			"block $name"
		);
	}


	/**
	 * Renders parent block.
	 * @param  mixed[]  $params
	 * @internal
	 */
	public function renderBlockParent(string $name, array $params): void
	{
		$block = $this->blocks[self::LAYER_LOCAL][$name] ?? $this->blocks[$this->index][$name] ?? null;
		if (!$block || ($function = next($block->functions)) === false) {
			throw new Latte\RuntimeException("Cannot include undefined parent block '$name'.");
		}
		$function($params);
		prev($block->functions);
	}


	/**
	 * Creates block if doesn't exist and checks if content type is the same.
	 * @param  callable[]  $functions
	 * @param  int|string  $layer
	 * @internal
	 */
	protected function addBlock(string $name, string $contentType, array $functions, $layer = null): void
	{
		$block = &$this->blocks[$layer ?? $this->index][$name];
		$block = $block ?? new Block;
		if ($block->contentType === null) {
			$block->contentType = $contentType;

		} elseif ($block->contentType !== $contentType) {
			throw new Latte\RuntimeException(sprintf(
				"Overridden block $name with content type %s by incompatible type %s.",
				strtoupper($contentType),
				strtoupper($block->contentType)
			));
		}

		$block->functions = array_merge($block->functions, $functions);
	}


	/**
	 * @param  string|\Closure|null  $mod  content-type name or modifier closure
	 */
	private function filter(callable $function, $mod, string $contentType, string $name): void
	{
		if ($mod === null || $mod === $contentType) {
			$function();

		} elseif ($mod instanceof \Closure) {
			echo $mod($this->capture($function), $contentType);

		} elseif ($filter = Filters::getConvertor($contentType, $mod)) {
			echo $filter($this->capture($function));

		} else {
			throw new Latte\RuntimeException(sprintf(
				"Including $name with content type %s into incompatible type %s.",
				strtoupper($contentType),
				strtoupper($mod)
			));
		}
	}


	/**
	 * Captures output to string.
	 * @internal
	 */
	public function capture(callable $function): string
	{
		try {
			ob_start(function () {});
			$this->global->coreCaptured = true;
			$function();
			return ob_get_clean();
		} catch (\Throwable $e) {
			ob_end_clean();
			throw $e;
		} finally {
			$this->global->coreCaptured = false;
		}
	}


	/**
	 * @param  int|string  $id
	 */
	protected function initBlockLayer($id, bool $copy = false): void
	{
		$this->blocks[$id] = [];
		foreach (static::BLOCKS[$id] ?? [] as $nm => $info) {
			[$method, $contentType] = is_array($info) ? $info : [$info, static::CONTENT_TYPE];
			$this->addBlock($nm, $contentType, [[$this, $method]], $id);
		}

		if ($copy) {
			foreach ($this->blocks[$this->index] as $nm => $block) {
				$this->addBlock($nm, $block->contentType, $block->functions, $id);
			}
		}
	}


	protected function setBlockLayer(int $id): void
	{
		$this->index = $id;
	}


	public function hasBlock(string $name): bool
	{
		return isset($this->blocks[self::LAYER_LOCAL][$name]) || isset($this->blocks[$this->index][$name]);
	}


	/********************* policy ****************d*g**/


	/**
	 * @param  mixed  $callable
	 * @return mixed
	 * @internal
	 */
	protected function call($callable)
	{
		if (!is_callable($callable)) {
			throw new Latte\SecurityViolationException('Invalid callable.');
		} elseif (is_string($callable)) {
			$parts = explode('::', $callable);
			$allowed = count($parts) === 1
				? $this->policy->isFunctionAllowed($parts[0])
				: $this->policy->isMethodAllowed(...$parts);
		} elseif (is_array($callable)) {
			$allowed = $this->policy->isMethodAllowed(is_object($callable[0]) ? get_class($callable[0]) : $callable[0], $callable[1]);
		} elseif (is_object($callable)) {
			$allowed = $callable instanceof \Closure
				? true
				: $this->policy->isMethodAllowed(get_class($callable), '__invoke');
		} else {
			$allowed = false;
		}

		if (!$allowed) {
			is_callable($callable, false, $text);
			throw new Latte\SecurityViolationException("Calling $text() is not allowed.");
		}
		return $callable;
	}


	/**
	 * @param  mixed  $obj
	 * @param  mixed  $prop
	 * @return mixed
	 * @internal
	 */
	protected function prop($obj, $prop)
	{
		$class = is_object($obj) ? get_class($obj) : $obj;
		if (is_string($class) && !$this->policy->isPropertyAllowed($class, (string) $prop)) {
			throw new Latte\SecurityViolationException("Access to '$prop' property on a $class object is not allowed.");
		}
		return $obj;
	}


	/**
	 * @return mixed
	 */
	public function &__get(string $name)
	{
		if ($name === 'blocks') { // compatibility with nette/application < 3.0.8
			$tmp = static::BLOCKS[self::LAYER_TOP] ?? [];
			return $tmp;
		}
		throw new \LogicException('Attempt to read undeclared property ' . self::class . '::$' . $name);
	}
}
