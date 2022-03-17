<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;


/**
 * Templating engine Latte.
 */
class Engine
{
	use Strict;

	public const VERSION = '3.0.0-dev';
	public const VERSION_ID = 30000;

	/** Content types */
	public const
		CONTENT_HTML = 'html',
		CONTENT_XML = 'xml',
		CONTENT_JS = 'js',
		CONTENT_CSS = 'css',
		CONTENT_ICAL = 'ical',
		CONTENT_TEXT = 'text';

	/** @var callable[] */
	public $onCompile = [];

	/** @internal */
	public $probe;

	/** @var Parser|null */
	private $parser;

	/** @var Compiler|null */
	private $compiler;

	/** @var Loader|null */
	private $loader;

	/** @var Runtime\FilterExecutor */
	private $filters;

	/** @var \stdClass */
	private $functions;

	/** @var mixed[] */
	private $providers = [];

	/** @var string */
	private $contentType = self::CONTENT_HTML;

	/** @var string|null */
	private $tempDirectory;

	/** @var bool */
	private $autoRefresh = true;

	/** @var bool */
	private $strictTypes = false;

	/** @var Policy|null */
	private $policy;

	/** @var bool */
	private $sandboxed = false;


	public function __construct()
	{
		$this->filters = new Runtime\FilterExecutor;
		$this->functions = new \stdClass;
		$this->probe = function () {};

		$defaults = new Runtime\Defaults;
		foreach ($defaults->getFilters() as $name => $callback) {
			$this->filters->add($name, $callback);
		}

		foreach ($defaults->getFunctions() as $name => $callback) {
			$this->functions->$name = $callback;
		}
	}


	/**
	 * Renders template to output.
	 * @param  object|mixed[]  $params
	 */
	public function render(string $name, $params = [], ?string $block = null): void
	{
		$template = $this->createTemplate($name, $this->processParams($params));
		$template->global->coreCaptured = false;
		($this->probe)($template);
		$template->render($block);
	}


	/**
	 * Renders template to string.
	 * @param  object|mixed[]  $params
	 */
	public function renderToString(string $name, $params = [], ?string $block = null): string
	{
		$template = $this->createTemplate($name, $this->processParams($params));
		$template->global->coreCaptured = true;
		($this->probe)($template);
		return $template->capture(function () use ($template, $block) { $template->render($block); });
	}


	/**
	 * Creates template object.
	 * @param  mixed[]  $params
	 */
	public function createTemplate(string $name, array $params = []): Runtime\Template
	{
		$class = $this->getTemplateClass($name);
		if (!class_exists($class, false)) {
			$this->loadTemplate($name);
		}

		$this->providers['fn'] = $this->functions;
		return new $class($this, $params, $this->filters, $this->providers, $name, $this->sandboxed ? $this->policy : null);
	}


	/**
	 * Compiles template to PHP code.
	 */
	public function compile(string $name): string
	{
		if ($this->sandboxed && !$this->policy) {
			throw new \LogicException('In sandboxed mode you need to set a security policy.');
		}

		foreach ($this->onCompile ?: [] as $cb) {
			(Helpers::checkCallback($cb))($this);
		}

		$this->onCompile = [];

		$source = $this->getLoader()->getContent($name);
		$comment = preg_match('#\n|\?#', $name) ? null : "source: $name";

		try {
			$tokens = $this->getParser()
				->setContentType($this->contentType)
				->parse($source);

			$code = $this->getCompiler()
				->setContentType($this->contentType)
				->setFunctions(array_keys((array) $this->functions))
				->setPolicy($this->sandboxed ? $this->policy : null)
				->compile($tokens, $this->getTemplateClass($name), $comment, $this->strictTypes);

		} catch (\Throwable $e) {
			if (!$e instanceof CompileException) {
				$e = new CompileException($e instanceof SecurityViolationException ? $e->getMessage() : "Thrown exception '{$e->getMessage()}'", 0, $e);
			}

			$line = isset($tokens)
				? $this->getCompiler()->getLine()
				: $this->getParser()->getLine();
			throw $e->setSource($source, $line, $name);
		}

		return $code;
	}


	/**
	 * Compiles template to cache.
	 * @throws \LogicException
	 */
	public function warmupCache(string $name): void
	{
		if (!$this->tempDirectory) {
			throw new \LogicException('Path to temporary directory is not set.');
		}

		$class = $this->getTemplateClass($name);
		if (!class_exists($class, false)) {
			$this->loadTemplate($name);
		}
	}


	private function loadTemplate(string $name): void
	{
		if (!$this->tempDirectory) {
			$code = $this->compile($name);
			if (@eval(substr($code, 5)) === false) { // @ is escalated to exception, substr removes <?php
				throw (new CompileException('Error in template: ' . error_get_last()['message']))
					->setSource($code, error_get_last()['line'], "$name (compiled)");
			}

			return;
		}

		// Solving atomicity to work everywhere is really pain in the ass.
		// 1) We want to do as little as possible IO calls on production and also directory and file can be not writable
		// so on Linux we include the file directly without shared lock, therefore, the file must be created atomically by renaming.
		// 2) On Windows file cannot be renamed-to while is open (ie by include), so we have to acquire a lock.
		$file = $this->getCacheFile($name);
		$lock = defined('PHP_WINDOWS_VERSION_BUILD')
			? $this->acquireLock("$file.lock", LOCK_SH)
			: null;

		if (!$this->isExpired($file, $name) && (@include $file) !== false) { // @ - file may not exist
			return;
		}

		if ($lock) {
			flock($lock, LOCK_UN); // release shared lock so we can get exclusive
		}

		$lock = $this->acquireLock("$file.lock", LOCK_EX);

		// while waiting for exclusive lock, someone might have already created the cache
		if (!is_file($file) || $this->isExpired($file, $name)) {
			$code = $this->compile($name);
			if (file_put_contents("$file.tmp", $code) !== strlen($code) || !rename("$file.tmp", $file)) {
				@unlink("$file.tmp"); // @ - file may not exist
				throw new RuntimeException("Unable to create '$file'.");
			}

			if (function_exists('opcache_invalidate')) {
				@opcache_invalidate($file, true); // @ can be restricted
			}
		}

		if ((include $file) === false) {
			throw new RuntimeException("Unable to load '$file'.");
		}
	}


	/**
	 * @return resource
	 */
	private function acquireLock(string $file, int $mode)
	{
		$dir = dirname($file);
		if (!is_dir($dir) && !@mkdir($dir) && !is_dir($dir)) { // @ - dir may already exist
			throw new RuntimeException("Unable to create directory '$dir'. " . error_get_last()['message']);
		}

		$handle = @fopen($file, 'w'); // @ is escalated to exception
		if (!$handle) {
			throw new RuntimeException("Unable to create file '$file'. " . error_get_last()['message']);
		} elseif (!@flock($handle, $mode)) { // @ is escalated to exception
			throw new RuntimeException('Unable to acquire ' . ($mode & LOCK_EX ? 'exclusive' : 'shared') . " lock on file '$file'. " . error_get_last()['message']);
		}

		return $handle;
	}


	private function isExpired(string $file, string $name): bool
	{
		return $this->autoRefresh && $this->getLoader()->isExpired($name, (int) @filemtime($file)); // @ - file may not exist
	}


	public function getCacheFile(string $name): string
	{
		$hash = substr($this->getTemplateClass($name), 8);
		$base = preg_match('#([/\\\\][\w@.-]{3,35}){1,3}$#D', $name, $m)
			? preg_replace('#[^\w@.-]+#', '-', substr($m[0], 1)) . '--'
			: '';
		return "$this->tempDirectory/$base$hash.php";
	}


	public function getTemplateClass(string $name): string
	{
		$key = serialize([
			$this->getLoader()->getUniqueId($name),
			self::VERSION,
			array_keys((array) $this->functions),
			$this->sandboxed,
			$this->contentType,
		]);
		return 'Template' . substr(md5($key), 0, 10);
	}


	/**
	 * Registers run-time filter.
	 * @return static
	 */
	public function addFilter(?string $name, callable $callback)
	{
		if ($name === null) {
			trigger_error('For dynamic filters, use the addFilterLoader() where you pass a callback as a parameter that returns the filter callback.', E_USER_DEPRECATED);
		} elseif (!preg_match('#^[a-z]\w*$#iD', $name)) {
			throw new \LogicException("Invalid filter name '$name'.");
		}

		$this->filters->add($name, $callback);
		return $this;
	}


	/**
	 * Registers filter loader.
	 * @return static
	 */
	public function addFilterLoader(callable $callback)
	{
		$this->filters->add(null, function ($name) use ($callback) {
			if ($filter = $callback($name)) {
				$this->filters->add($name, $filter);
			}
		});
		return $this;
	}


	/**
	 * Returns all run-time filters.
	 * @return string[]
	 */
	public function getFilters(): array
	{
		return $this->filters->getAll();
	}


	/**
	 * Call a run-time filter.
	 * @param  mixed[]  $args
	 * @return mixed
	 */
	public function invokeFilter(string $name, array $args)
	{
		return ($this->filters->$name)(...$args);
	}


	/**
	 * Adds new macro.
	 * @return static
	 */
	public function addMacro(string $name, Macro $macro)
	{
		$this->getCompiler()->addMacro($name, $macro);
		return $this;
	}


	/**
	 * Registers run-time function.
	 * @return static
	 */
	public function addFunction(string $name, callable $callback)
	{
		if (!preg_match('#^[a-z]\w*$#iD', $name)) {
			throw new \LogicException("Invalid function name '$name'.");
		}

		$this->functions->$name = $callback;
		return $this;
	}


	/**
	 * Call a run-time function.
	 * @param  mixed[]  $args
	 * @return mixed
	 */
	public function invokeFunction(string $name, array $args)
	{
		if (!isset($this->functions->$name)) {
			$hint = ($t = Helpers::getSuggestion(array_keys((array) $this->functions), $name))
				? ", did you mean '$t'?"
				: '.';
			throw new \LogicException("Function '$name' is not defined$hint");
		}

		return ($this->functions->$name)(...$args);
	}


	/**
	 * Adds new provider.
	 * @param  mixed  $value
	 * @return static
	 */
	public function addProvider(string $name, $value)
	{
		if (!preg_match('#^[a-z]\w*$#iD', $name)) {
			throw new \LogicException("Invalid provider name '$name'.");
		}

		$this->providers[$name] = $value;
		return $this;
	}


	/**
	 * Returns all providers.
	 * @return mixed[]
	 */
	public function getProviders(): array
	{
		return $this->providers;
	}


	/** @return static */
	public function setPolicy(?Policy $policy)
	{
		$this->policy = $policy;
		return $this;
	}


	/** @return static */
	public function setExceptionHandler(callable $callback)
	{
		$this->providers['coreExceptionHandler'] = $callback;
		return $this;
	}


	/** @return static */
	public function setSandboxMode(bool $on = true)
	{
		$this->sandboxed = $on;
		return $this;
	}


	/** @return static */
	public function setContentType(string $type)
	{
		$this->contentType = $type;
		return $this;
	}


	/**
	 * Sets path to temporary directory.
	 * @return static
	 */
	public function setTempDirectory(?string $path)
	{
		$this->tempDirectory = $path;
		return $this;
	}


	/**
	 * Sets auto-refresh mode.
	 * @return static
	 */
	public function setAutoRefresh(bool $on = true)
	{
		$this->autoRefresh = $on;
		return $this;
	}


	/**
	 * Enables declare(strict_types=1) in templates.
	 * @return static
	 */
	public function setStrictTypes(bool $on = true)
	{
		$this->strictTypes = $on;
		return $this;
	}


	public function getParser(): Parser
	{
		if (!$this->parser) {
			$this->parser = new Parser;
		}

		return $this->parser;
	}


	public function getCompiler(): Compiler
	{
		if (!$this->compiler) {
			$this->compiler = new Compiler;
			Macros\CoreMacros::install($this->compiler);
			Macros\BlockMacros::install($this->compiler);
		}

		return $this->compiler;
	}


	/** @return static */
	public function setLoader(Loader $loader)
	{
		$this->loader = $loader;
		return $this;
	}


	public function getLoader(): Loader
	{
		if (!$this->loader) {
			$this->loader = new Loaders\FileLoader;
		}

		return $this->loader;
	}


	/**
	 * @param  object|mixed[]  $params
	 * @return mixed[]
	 */
	private function processParams($params): array
	{
		if (is_array($params)) {
			return $params;
		} elseif (!is_object($params)) {
			throw new \InvalidArgumentException(sprintf('Engine::render() expects array|object, %s given.', gettype($params)));
		}

		$methods = (new \ReflectionClass($params))->getMethods(\ReflectionMethod::IS_PUBLIC);
		foreach ($methods as $method) {
			if ($method->getAttributes(Attributes\TemplateFilter::class)) {
				$this->addFilter($method->name, [$params, $method->name]);
			}

			if ($method->getAttributes(Attributes\TemplateFunction::class)) {
				$this->addFunction($method->name, [$params, $method->name]);
			}

			if (strpos((string) $method->getDocComment(), '@filter')) {
				trigger_error('Annotation @filter is deprecated, use attribute #[Latte\Attributes\TemplateFilter]', E_USER_DEPRECATED);
				$this->addFilter($method->name, [$params, $method->name]);
			}

			if (strpos((string) $method->getDocComment(), '@function')) {
				trigger_error('Annotation @function is deprecated, use attribute #[Latte\Attributes\TemplateFunction]', E_USER_DEPRECATED);
				$this->addFunction($method->name, [$params, $method->name]);
			}
		}

		return array_filter((array) $params, function ($key) { return $key[0] !== "\0"; }, ARRAY_FILTER_USE_KEY);
	}
}
