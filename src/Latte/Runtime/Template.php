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

	/** @var Block[] */
	private $blocks = [];

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

		foreach (static::BLOCKS as $nm => $info) {
			[$method, $type] = is_array($info) ? $info : [$info, static::CONTENT_TYPE];
			$this->blocks[$nm] = $block = new Block;
			$block->functions[] = [$this, $method];
			$block->contentType = $type;
		}
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


	public function getBlockNames(): array
	{
		return array_keys($this->blocks);
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
				$this->createTemplate($this->parentName, [], 'import')->render();
			}
			return;

		} elseif ($this->parentName) { // extends
			ob_start(function () {});
			$params = $this->main();
			ob_end_clean();
			$this->createTemplate($this->parentName, $params, 'extends')->render($block);
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
			&& $this->global->snippetDriver->renderSnippets($this->blocks, $this->params)
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

		if (in_array($referenceType, ['extends', 'includeblock', 'import'], true)) {
			foreach ($referred->blocks as $nm => $block) {
				$this->addBlock($nm, $block->contentType, $block->functions);
			}
			$referred->blocks = &$this->blocks;
		}
		return $referred;
	}


	/**
	 * @param  string|\Closure  $mod  content-type name or modifier closure
	 * @internal
	 */
	public function renderToContentType($mod): void
	{
		if ($mod instanceof \Closure) {
			echo $mod($this->capture([$this, 'render']), static::CONTENT_TYPE);

		} elseif ($mod && $mod !== static::CONTENT_TYPE) {
			if ($filter = Filters::getConvertor(static::CONTENT_TYPE, $mod)) {
				echo $filter($this->capture([$this, 'render']));
			} else {
				trigger_error(sprintf(
					"Including '{$this->name}' with content type %s into incompatible type %s.",
					strtoupper(static::CONTENT_TYPE),
					strtoupper($mod)
				), E_USER_WARNING);
			}
		} else {
			$this->render();
		}
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
	 * @param  string|\Closure  $mod  content-type name or modifier closure
	 * @internal
	 */
	public function renderBlock(string $name, array $params, $mod = null): void
	{
		$block = $this->blocks[$name] ?? null;
		if (!$block) {
			$hint = ($t = Latte\Helpers::getSuggestion($this->getBlockNames(), $name))
				? ", did you mean '$t'?"
				: '.';
			throw new \RuntimeException("Cannot include undefined block '$name'$hint");
		}

		$function = reset($block->functions);
		if ($mod && $mod !== $block->contentType) {
			if ($filter = (is_string($mod) ? Filters::getConvertor($block->contentType, $mod) : $mod)) {
				echo $filter(
					$this->capture(function () use ($function, $params): void { $function($params); }),
					$block->contentType
				);
				return;
			}
			trigger_error(sprintf(
				"Including block $name with content type %s into incompatible type %s.",
				strtoupper($block->contentType),
				strtoupper($mod)
			), E_USER_WARNING);
		}
		$function($params);
	}


	/**
	 * Renders parent block.
	 * @param  mixed[]  $params
	 * @internal
	 */
	public function renderBlockParent(string $name, array $params): void
	{
		$block = $this->blocks[$name] ?? null;
		if (!$block || ($function = next($block->functions)) === false) {
			throw new \RuntimeException("Cannot include undefined parent block '$name'.");
		}
		$function($params);
		prev($block->functions);
	}


	/**
	 * Creates block if doesn't exist and checks if content type is the same.
	 * @param  callable[]  $functions
	 * @internal
	 */
	protected function addBlock(string $name, string $contentType, array $functions): void
	{
		$block = &$this->blocks[$name];
		$block = $block ?? new Block;
		if ($block->contentType === null) {
			$block->contentType = $contentType;

		} elseif ($block->contentType !== $contentType) {
			trigger_error(sprintf(
				"Overridden block $name with content type %s by incompatible type %s.",
				strtoupper($contentType),
				strtoupper($block->contentType)
			), E_USER_WARNING);
		}

		$block->functions = array_merge($block->functions, $functions);
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


	public function hasBlock(string $name): bool
	{
		return isset($this->blocks[$name]);
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
			$tmp = static::BLOCKS;
			return $tmp;
		}
		throw new \LogicException('Attempt to read undeclared property ' . self::class . '::$' . $name);
	}
}
