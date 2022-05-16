<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;

use Latte;
use Latte\Compiler\Escaper;
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

	protected const CONTENT_TYPE = Latte\ContentType::Html;

	protected const BLOCKS = [];

	/** global accumulators for intermediate results */
	public \stdClass $global;

	/** @var mixed[]  @internal */
	protected array $params = [];

	protected FilterExecutor $filters;

	/** @internal */
	protected string|false|null $parentName = null;

	/** @var mixed[][] */
	protected array $varStack = [];

	/** @var Block[][] */
	private array $blocks;

	/** @var mixed[][] */
	private array $blockStack = [];

	private Engine $engine;
	private string $name;
	private ?Policy $policy = null;
	private ?Template $referringTemplate = null;
	private ?string $referenceType = null;


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
		?Policy $policy,
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
	 * @return string[]
	 */
	public function getBlockNames(int|string $layer = self::LAYER_TOP): array
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
	public function render(?string $block = null): void
	{
		$level = ob_get_level();
		try {
			$this->prepare();
			if (!$this->doRender($block)) {
				$this->main();
			}

		} catch (\Throwable $e) {
			while (ob_get_level() > $level) {
				ob_end_clean();
			}

			throw $e;
		}
	}


	private function doRender(?string $block = null): bool
	{
		if ($this->parentName === null && isset($this->global->coreParentFinder)) {
			$this->parentName = ($this->global->coreParentFinder)($this);
		}
		if (isset($this->global->snippetBridge) && !isset($this->global->snippetDriver)) {
			$this->global->snippetDriver = new SnippetDriver($this->global->snippetBridge);
		}
		Filters::$xml = static::CONTENT_TYPE === Latte\ContentType::Xml;

		if ($this->referenceType === 'import') {
			if ($this->parentName) {
				throw new Latte\RuntimeException('Imported template cannot use {extends} or {layout}, use {import}');
			}

		} elseif ($this->parentName) { // extends
			ob_start(fn() => '');
			$this->params = $this->main();
			ob_end_clean();
			$this->createTemplate($this->parentName, $this->params, 'extends')->render($block);

		} elseif ($block !== null) { // single block rendering
			$this->renderBlock($block, $this->params);

		} elseif (
			isset($this->global->snippetDriver)
			&& $this->global->snippetDriver->renderSnippets($this->blocks[self::LAYER_SNIPPET], $this->params)
		) {
			// nothing
		} else {
			return false;
		}

		return true;
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

			$referred->blocks[self::LAYER_TOP] = &$this->blocks[self::LAYER_TOP];

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
	public function renderToContentType(string|\Closure|null $mod, ?string $block = null): void
	{
		$this->filter(
			function () use ($block) { $this->render($block); },
			$mod,
			static::CONTENT_TYPE,
			"'$this->name'",
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
	 * @internal
	 */
	public function renderBlock(
		string $name,
		array $params,
		string|\Closure|null $mod = null,
		int|string|null $layer = null,
	): void {
		$block = $layer
			? ($this->blocks[$layer][$name] ?? null)
			: ($this->blocks[self::LAYER_LOCAL][$name] ?? $this->blocks[self::LAYER_TOP][$name] ?? null);

		if (!$block) {
			$hint = $layer && ($t = Latte\Helpers::getSuggestion($this->getBlockNames($layer), $name))
				? ", did you mean '$t'?"
				: '.';
			$name = $layer ? "$layer $name" : $name;
			throw new Latte\RuntimeException("Cannot include undefined block '$name'$hint");
		}

		$this->filter(
			function () use ($block, $params): void { reset($block->functions)($params); },
			$mod,
			$block->contentType,
			"block $name",
		);
	}


	/**
	 * Renders parent block.
	 * @param  mixed[]  $params
	 * @internal
	 */
	public function renderBlockParent(string $name, array $params): void
	{
		$block = $this->blocks[self::LAYER_LOCAL][$name] ?? $this->blocks[self::LAYER_TOP][$name] ?? null;
		if (!$block || ($function = next($block->functions)) === false) {
			throw new Latte\RuntimeException("Cannot include undefined parent block '$name'.");
		}
		$function($params);
		prev($block->functions);
	}


	/**
	 * Creates block if doesn't exist and checks if content type is the same.
	 * @param  callable[]  $functions
	 * @internal
	 */
	protected function addBlock(
		string $name,
		string $contentType,
		array $functions,
		int|string|null $layer = null,
	): void {
		$block = &$this->blocks[$layer ?? self::LAYER_TOP][$name];
		$block ??= new Block;
		if ($block->contentType === null) {
			$block->contentType = $contentType;

		} elseif ($block->contentType !== $contentType) {
			throw new Latte\RuntimeException(sprintf(
				"Overridden block $name with content type %s by incompatible type %s.",
				strtoupper($contentType),
				strtoupper($block->contentType),
			));
		}

		$block->functions = array_merge($block->functions, $functions);
	}


	/**
	 * @param  string|\Closure|null  $mod  content-type name or modifier closure
	 */
	private function filter(callable $function, string|\Closure|null $mod, string $contentType, string $name): void
	{
		if ($mod === null || $mod === $contentType) {
			$function();

		} elseif ($mod instanceof \Closure) {
			echo $mod($this->capture($function), $contentType);

		} elseif ($filter = Escaper::getConvertor($contentType, $mod)) {
			echo $filter($this->capture($function));

		} else {
			throw new Latte\RuntimeException(sprintf(
				"Including $name with content type %s into incompatible type %s.",
				strtoupper($contentType),
				strtoupper($mod),
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
			ob_start(fn() => '');
			$function();
			return ob_get_clean();
		} catch (\Throwable $e) {
			ob_end_clean();
			throw $e;
		}
	}


	private function initBlockLayer(int|string $staticId, ?int $destId = null): void
	{
		$destId ??= $staticId;
		$this->blocks[$destId] = [];
		foreach (static::BLOCKS[$staticId] ?? [] as $nm => $info) {
			[$method, $contentType] = is_array($info) ? $info : [$info, static::CONTENT_TYPE];
			$this->addBlock($nm, $contentType, [[$this, $method]], $destId);
		}
	}


	protected function enterBlockLayer(int $staticId, array $vars): void
	{
		$this->blockStack[] = $this->blocks[self::LAYER_TOP];
		$this->initBlockLayer($staticId, self::LAYER_TOP);
		$this->varStack[] = $vars;
	}


	protected function copyBlockLayer(): void
	{
		foreach (end($this->blockStack) as $nm => $block) {
			$this->addBlock($nm, $block->contentType, $block->functions);
		}
	}


	protected function leaveBlockLayer(): void
	{
		$this->blocks[self::LAYER_TOP] = array_pop($this->blockStack);
		array_pop($this->varStack);
	}


	public function hasBlock(string $name): bool
	{
		return isset($this->blocks[self::LAYER_LOCAL][$name]) || isset($this->blocks[self::LAYER_TOP][$name]);
	}


	/********************* policy ****************d*g**/


	/**
	 * @internal
	 */
	protected function call(mixed $callable): mixed
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


	/**
	 * @internal
	 */
	protected function prop(mixed $obj, mixed $prop): mixed
	{
		$class = is_object($obj) ? $obj::class : $obj;
		if (is_string($class) && !$this->policy->isPropertyAllowed($class, (string) $prop)) {
			throw new Latte\SecurityViolationException("Access to '$prop' property on a $class object is not allowed.");
		}
		return $obj;
	}
}
