<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte;


/**
 * Templating engine Latte.
 */
class Engine
{
	use Strict;

	const VERSION = '2.4.6';

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
	private $autoRefresh = true;


	public function __construct()
	{
		$this->filters = new Runtime\FilterExecutor;
	}


	/**
	 * Renders template to output.
	 * @return void
	 */
	public function render($name, array $params = [], $block = null)
	{
		$this->createTemplate($name, $params + ['_renderblock' => $block])
			->render();
	}


	/**
	 * Renders template to string.
	 * @return string
	 */
	public function renderToString($name, array $params = [], $block = null)
	{
		$template = $this->createTemplate($name, $params + ['_renderblock' => $block]);
		return $template->capture([$template, 'render']);
	}


	/**
	 * Creates template object.
	 * @return Runtime\Template
	 */
	public function createTemplate($name, array $params = [])
	{
		$class = $this->getTemplateClass($name);
		if (!class_exists($class, false)) {
			$this->loadTemplate($name);
		}
		return new $class($this, $params, $this->filters, $this->providers, $name);
	}


	/**
	 * Compiles template to PHP code.
	 * @return string
	 */
	public function compile($name)
	{
		foreach ($this->onCompile ?: [] as $cb) {
			call_user_func(Helpers::checkCallback($cb), $this);
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
		$code = PhpHelpers::reformatCode($code);
		return $code;
	}


	/**
	 * Compiles template to cache.
	 * @param  string
	 * @return void
	 * @throws \LogicException
	 */
	public function warmupCache($name)
	{
		if (!$this->tempDirectory) {
			throw new \LogicException('Path to temporary directory is not set.');
		}

		$class = $this->getTemplateClass($name);
		if (!class_exists($class, false)) {
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
			if (@eval('?>' . $code) === false) { // @ is escalated to exception
				throw (new CompileException('Error in template: ' . error_get_last()['message']))
					->setSource($code, error_get_last()['line'], "$name (compiled)");
			}
			return;
		}

		$file = $this->getCacheFile($name);

		if (!$this->isExpired($file, $name) && (@include $file) !== false) { // @ - file may not exist
			return;
		}

		if (!is_dir($this->tempDirectory) && !@mkdir($this->tempDirectory) && !is_dir($this->tempDirectory)) { // @ - dir may already exist
			throw new \RuntimeException("Unable to create directory '$this->tempDirectory'. " . error_get_last()['message']);
		}

		$handle = @fopen("$file.lock", 'c+'); // @ is escalated to exception
		if (!$handle) {
			throw new \RuntimeException("Unable to create file '$file.lock'. " . error_get_last()['message']);
		} elseif (!@flock($handle, LOCK_EX)) { // @ is escalated to exception
			throw new \RuntimeException("Unable to acquire exclusive lock on '$file.lock'. " . error_get_last()['message']);
		}

		if (!is_file($file) || $this->isExpired($file, $name)) {
			$code = $this->compile($name);
			if (file_put_contents("$file.tmp", $code) !== strlen($code) || !rename("$file.tmp", $file)) {
				@unlink("$file.tmp"); // @ - file may not exist
				throw new \RuntimeException("Unable to create '$file'.");
			} elseif (function_exists('opcache_invalidate')) {
				@opcache_invalidate($file, true); // @ can be restricted
			}
		}

		if ((include $file) === false) {
			throw new \RuntimeException("Unable to load '$file'.");
		}

		flock($handle, LOCK_UN);
		fclose($handle);
		@unlink("$file.lock"); // @ file may become locked on Windows
	}


	/**
	 * @param  string
	 * @param  string
	 * @return bool
	 */
	private function isExpired($file, $name)
	{
		return $this->autoRefresh && $this->getLoader()->isExpired($name, (int) @filemtime($file)); // @ - file may not exist
	}


	/**
	 * @return string
	 */
	public function getCacheFile($name)
	{
		$hash = substr($this->getTemplateClass($name), 8);
		$base = preg_match('#([/\\\\][\w@.-]{3,35}){1,3}\z#', $name, $m)
			? preg_replace('#[^\w@.-]+#', '-', substr($m[0], 1)) . '--'
			: '';
		return "$this->tempDirectory/$base$hash.php";
	}


	/**
	 * @return string
	 */
	public function getTemplateClass($name)
	{
		$key = $this->getLoader()->getUniqueId($name) . "\00" . self::VERSION;
		return 'Template' . substr(md5($key), 0, 10);
	}


	/**
	 * Registers run-time filter.
	 * @param  string|null
	 * @param  callable
	 * @return static
	 */
	public function addFilter($name, $callback)
	{
		$this->filters->add($name, $callback);
		return $this;
	}


	/**
	 * Returns all run-time filters.
	 * @return string[]
	 */
	public function getFilters()
	{
		return $this->filters->getAll();
	}


	/**
	 * Call a run-time filter.
	 * @param  string  filter name
	 * @param  array   arguments
	 * @return mixed
	 */
	public function invokeFilter($name, array $args)
	{
		return call_user_func_array($this->filters->$name, $args);
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
	 * @return array
	 */
	public function getProviders()
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
	public function setAutoRefresh($on = true)
	{
		$this->autoRefresh = (bool) $on;
		return $this;
	}


	/**
	 * @return Parser
	 */
	public function getParser()
	{
		if (!$this->parser) {
			$this->parser = new Parser;
		}
		return $this->parser;
	}


	/**
	 * @return Compiler
	 */
	public function getCompiler()
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


	/**
	 * @return ILoader
	 */
	public function getLoader()
	{
		if (!$this->loader) {
			$this->loader = new Loaders\FileLoader;
		}
		return $this->loader;
	}
}
