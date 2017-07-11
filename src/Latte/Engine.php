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

	const VERSION = '3.0.0-dev';

	/** Content types */
	const CONTENT_HTML = 'html',
		CONTENT_XHTML = 'xhtml',
		CONTENT_XML = 'xml',
		CONTENT_JS = 'js',
		CONTENT_CSS = 'css',
		CONTENT_ICAL = 'ical',
		CONTENT_TEXT = 'text';

	/** @var callable[] */
	public $onCompile = [];

	/** @var Parser */
	private $parser;

	/** @var Compiler */
	private $compiler;

	/** @var ILoader */
	private $loader;

	/** @var Runtime\FilterExecutor */
	private $filters;

	/** @var array */
	private $providers = [];

	/** @var string */
	private $contentType = self::CONTENT_HTML;

	/** @var string */
	private $tempDirectory;

	/** @var bool */
	private $autoRefresh = TRUE;

	/** @var bool */
	private $strictTypes = FALSE;


	public function __construct()
	{
		$this->filters = new Runtime\FilterExecutor;
	}


	/**
	 * Renders template to output.
	 * @return void
	 */
	public function render($name, array $params = [], $block = NULL)
	{
		$this->createTemplate($name, $params + ['_renderblock' => $block])
			->render();
	}


	/**
	 * Renders template to string.
	 */
	public function renderToString($name, array $params = [], $block = NULL): string
	{
		$template = $this->createTemplate($name, $params + ['_renderblock' => $block]);
		return $template->capture([$template, 'render']);
	}


	/**
	 * Creates template object.
	 */
	public function createTemplate($name, array $params = []): Runtime\Template
	{
		$class = $this->getTemplateClass($name);
		if (!class_exists($class, FALSE)) {
			$this->loadTemplate($name);
		}
		return new $class($this, $params, $this->filters, $this->providers, $name);
	}


	/**
	 * Compiles template to PHP code.
	 */
	public function compile($name): string
	{
		foreach ($this->onCompile ?: [] as $cb) {
			(Helpers::checkCallback($cb))($this);
		}
		$this->onCompile = [];

		$source = $this->getLoader()->getContent($name);

		try {
			$tokens = $this->getParser()->setContentType($this->contentType)
				->parse($source);

			$code = $this->getCompiler()->setContentType($this->contentType)
				->compile($tokens, $this->getTemplateClass($name));

		} catch (\Exception $e) {
			if (!$e instanceof CompileException) {
				$e = new CompileException("Thrown exception '{$e->getMessage()}'", 0, $e);
			}
			$line = isset($tokens) ? $this->getCompiler()->getLine() : $this->getParser()->getLine();
			throw $e->setSource($source, $line, $name);
		}

		if (!preg_match('#\n|\?#', $name)) {
			$code = "<?php\n// source: $name\n?>" . $code;
		}
		if ($this->strictTypes) {
			$code = "<?php\ndeclare(strict_types=1);\n?>" . $code;
		}
		$code = PhpHelpers::reformatCode($code);
		return $code;
	}


	/**
	 * Compiles template to cache.
	 * @return void
	 * @throws \LogicException
	 */
	public function warmupCache(string $name)
	{
		if (!$this->tempDirectory) {
			throw new \LogicException('Path to temporary directory is not set.');
		}

		$class = $this->getTemplateClass($name);
		if (!class_exists($class, FALSE)) {
			$this->loadTemplate($name);
		}
	}


	/**
	 * @return void
	 */
	private function loadTemplate($name)
	{
		if (!$this->tempDirectory) {
			$code = $this->compile($name);
			if (@eval('?>' . $code) === FALSE) { // @ is escalated to exception
				throw (new CompileException('Error in template: ' . error_get_last()['message']))
					->setSource($code, error_get_last()['line'], "$name (compiled)");
			}
			return;
		}

		$file = $this->getCacheFile($name);

		if (!$this->isExpired($file, $name) && (@include $file) !== FALSE) { // @ - file may not exist
			return;
		}

		if (!is_dir($this->tempDirectory)) {
			@mkdir($this->tempDirectory); // @ - directory may already exist
		}

		$handle = fopen("$file.lock", 'c+');
		if (!$handle || !flock($handle, LOCK_EX)) {
			throw new \RuntimeException("Unable to acquire exclusive lock '$file.lock'.");
		}

		if (!is_file($file) || $this->isExpired($file, $name)) {
			$code = $this->compile($name);
			if (file_put_contents("$file.tmp", $code) !== strlen($code) || !rename("$file.tmp", $file)) {
				@unlink("$file.tmp"); // @ - file may not exist
				throw new \RuntimeException("Unable to create '$file'.");
			} elseif (function_exists('opcache_invalidate')) {
				@opcache_invalidate($file, TRUE); // @ can be restricted
			}
		}

		if ((include $file) === FALSE) {
			throw new \RuntimeException("Unable to load '$file'.");
		}

		flock($handle, LOCK_UN);
		fclose($handle);
		@unlink("$file.lock"); // @ file may become locked on Windows
	}


	private function isExpired(string $file, string $name): bool
	{
		return $this->autoRefresh && $this->getLoader()->isExpired($name, (int) @filemtime($file)); // @ - file may not exist
	}


	public function getCacheFile($name): string
	{
		$hash = substr($this->getTemplateClass($name), 8);
		$base = preg_match('#([/\\\\][\w@.-]{3,35}){1,3}\z#', $name, $m)
			? preg_replace('#[^\w@.-]+#', '-', substr($m[0], 1)) . '--'
			: '';
		return "$this->tempDirectory/$base$hash.php";
	}


	public function getTemplateClass($name): string
	{
		$key = $this->getLoader()->getUniqueId($name) . "\00" . self::VERSION;
		return 'Template' . substr(md5($key), 0, 10);
	}


	/**
	 * Registers run-time filter.
	 * @param  string|NULL
	 * @return static
	 */
	public function addFilter($name, callable $callback)
	{
		$this->filters->add($name, $callback);
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
	 * @param  string  filter name
	 * @param  array   arguments
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
	public function addMacro($name, IMacro $macro)
	{
		$this->getCompiler()->addMacro($name, $macro);
		return $this;
	}


	/**
	 * Adds new provider.
	 * @return static
	 */
	public function addProvider($name, $value)
	{
		$this->providers[$name] = $value;
		return $this;
	}


	/**
	 * Returns all providers.
	 */
	public function getProviders(): array
	{
		return $this->providers;
	}


	/**
	 * @return static
	 */
	public function setContentType($type)
	{
		$this->contentType = $type;
		return $this;
	}


	/**
	 * Sets path to temporary directory.
	 * @return static
	 */
	public function setTempDirectory($path)
	{
		$this->tempDirectory = $path;
		return $this;
	}


	/**
	 * Sets auto-refresh mode.
	 * @return static
	 */
	public function setAutoRefresh(bool $on = TRUE)
	{
		$this->autoRefresh = $on;
		return $this;
	}


	/**
	 * Enables declare(strict_types=1) in templates.
	 * @return static
	 */
	public function setStrictTypes(bool $on = TRUE)
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


	/**
	 * @return static
	 */
	public function setLoader(ILoader $loader)
	{
		$this->loader = $loader;
		return $this;
	}


	public function getLoader(): ILoader
	{
		if (!$this->loader) {
			$this->loader = new Loaders\FileLoader;
		}
		return $this->loader;
	}
}
