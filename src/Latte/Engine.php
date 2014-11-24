<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 */

namespace Latte;


/**
 * Templating engine Latte.
 *
 * @author     David Grudl
 */
class Engine extends Object
{
	/** Content types */
	const CONTENT_HTML = Compiler::CONTENT_HTML,
		CONTENT_XHTML = Compiler::CONTENT_XHTML,
		CONTENT_XML = Compiler::CONTENT_XML,
		CONTENT_JS = Compiler::CONTENT_JS,
		CONTENT_CSS = Compiler::CONTENT_CSS,
		CONTENT_ICAL = Compiler::CONTENT_ICAL,
		CONTENT_TEXT = Compiler::CONTENT_TEXT;

	/** @var array */
	public $onCompile = array();

	/** @var Parser */
	private $parser;

	/** @var Compiler */
	private $compiler;

	/** @var ILoader */
	private $loader;

	/** @var string */
	private $contentType = self::CONTENT_HTML;

	/** @var string */
	private $tempDirectory;

	/** @var bool */
	private $autoRefresh = TRUE;

	/** @var array run-time filters */
	private $filters = array(
		NULL => array(), // dynamic
		'bytes' => 'Latte\Runtime\Filters::bytes',
		'capitalize' => 'Latte\Runtime\Filters::capitalize',
		'datastream' => 'Latte\Runtime\Filters::dataStream',
		'date' => 'Latte\Runtime\Filters::date',
		'escapecss' => 'Latte\Runtime\Filters::escapeCss',
		'escapehtml' => 'Latte\Runtime\Filters::escapeHtml',
		'escapehtmlcomment' => 'Latte\Runtime\Filters::escapeHtmlComment',
		'escapeical' => 'Latte\Runtime\Filters::escapeICal',
		'escapejs' => 'Latte\Runtime\Filters::escapeJs',
		'escapeurl' => 'rawurlencode',
		'escapexml' => 'Latte\Runtime\Filters::escapeXML',
		'firstupper' => 'Latte\Runtime\Filters::firstUpper',
		'implode' => 'implode',
		'indent' => 'Latte\Runtime\Filters::indent',
		'lower' => 'Latte\Runtime\Filters::lower',
		'nl2br' => 'Latte\Runtime\Filters::nl2br',
		'number' => 'Latte\Runtime\Filters::number',
		'repeat' => 'str_repeat',
		'replace' => 'Latte\Runtime\Filters::replace',
		'replacere' => 'Latte\Runtime\Filters::replaceRe',
		'safeurl' => 'Latte\Runtime\Filters::safeUrl',
		'strip' => 'Latte\Runtime\Filters::strip',
		'striptags' => 'strip_tags',
		'substr' => 'Latte\Runtime\Filters::substring',
		'trim' => 'Latte\Runtime\Filters::trim',
		'truncate' => 'Latte\Runtime\Filters::truncate',
		'upper' => 'Latte\Runtime\Filters::upper',
	);

	/** @var string */
	private $baseTemplateClass = 'Latte\Template';


	/**
	 * Renders template to output.
	 * @return void
	 */
	public function render($name, array $params = array())
	{
		$template = new $this->baseTemplateClass($params, $this->filters, $this, $name);
		$this->loadCacheFile($name, $template->getParameters());
	}


	/**
	 * Renders template to string.
	 * @return string
	 */
	public function renderToString($name, array $params = array())
	{
		ob_start();
		try {
			$this->render($name, $params);
		} catch (\Exception $e) {
			ob_end_clean();
			throw $e;
		}
		return ob_get_clean();
	}


	/**
	 * Compiles template to PHP code.
	 * @return string
	 */
	public function compile($name)
	{
		foreach ($this->onCompile ?: array() as $cb) {
			call_user_func(Helpers::checkCallback($cb), $this);
		}
		$this->onCompile = array();

		$source = $this->getLoader()->getContent($name);
		try {
			$tokens = $this->getParser()->setContentType($this->contentType)
				->parse($source);
			$code = $this->getCompiler()->setContentType($this->contentType)
				->compile($tokens);

			if (!preg_match('#\n|\?#', $name)) {
				$code = "<?php\n// source: $name\n?>" . $code;
			}

		} catch (\Exception $e) {
			$e = $e instanceof CompileException ? $e : new CompileException("Thrown exception '{$e->getMessage()}'", NULL, $e);
			throw $e->setSource($source, $this->getCompiler()->getLine(), $name);
		}
		$code = Helpers::optimizePhp($code);
		return $code;
	}


	/**
	 * @return void
	 */
	private function loadCacheFile($name, $params)
	{
		if (!$this->tempDirectory) {
			return call_user_func(function() {
				foreach (func_get_arg(1) as $__k => $__v) {
					$$__k = $__v;
				}
				unset($__k, $__v);
				eval('?>' . func_get_arg(0));
			}, $this->compile($name), $params);
		}

		$file = $this->getCacheFile($name);
		$handle = fopen($file, 'c+');
		if (!$handle) {
			throw new \RuntimeException("Unable to open or create file '$file'.");
		}
		flock($handle, LOCK_SH);
		$stat = fstat($handle);
		if (!$stat['size'] || ($this->autoRefresh && $this->getLoader()->isExpired($name, $stat['mtime']))) {
			ftruncate($handle, 0);
			flock($handle, LOCK_EX);
			$stat = fstat($handle);
			if (!$stat['size']) {
				$code = $this->compile($name);
				if (fwrite($handle, $code, strlen($code)) !== strlen($code)) {
					ftruncate($handle, 0);
					throw new \RuntimeException("Unable to write file '$file'.");
				}
			}
			flock($handle, LOCK_SH); // holds the lock
		}

		call_user_func(function() {
			foreach (func_get_arg(1) as $__k => $__v) {
				$$__k = $__v;
			}
			unset($__k, $__v);
			include func_get_arg(0);
		}, $file, $params);
	}


	/**
	 * @return string
	 */
	public function getCacheFile($name)
	{
		if (!$this->tempDirectory) {
			throw new \RuntimeException('Set path to temporary directory using setTempDirectory().');
		} elseif (!is_dir($this->tempDirectory)) {
			@mkdir($this->tempDirectory); // High concurrency
			if (!is_dir($this->tempDirectory)) {
				throw new \RuntimeException("Temporary directory cannot be created. Check access rights");
			}
		}
		$file = md5($name);
		if (preg_match('#\b\w.{10,50}$#', $name, $m)) {
			$file = trim(preg_replace('#\W+#', '-', $m[0]), '-') . '-' . $file;
		}
		return $this->tempDirectory . '/' . $file . '.php';
	}


	/**
	 * Registers run-time filter.
	 * @param  string|NULL
	 * @param  callable
	 * @return self
	 */
	public function addFilter($name, $callback)
	{
		if ($name == NULL) { // intentionally ==
			array_unshift($this->filters[NULL], $callback);
		} else {
			$this->filters[strtolower($name)] = $callback;
		}
		return $this;
	}


	/**
	 * Returns all run-time filters.
	 * @return callable[]
	 */
	public function getFilters()
	{
		return $this->filters;
	}


	/**
	 * Call a run-time filter.
	 * @param  string  filter name
	 * @param  array   arguments
	 * @return mixed
	 */
	public function invokeFilter($name, array $args)
	{
		$lname = strtolower($name);
		if (!isset($this->filters[$lname])) {
			$args2 = $args;
			array_unshift($args2, $lname);
			foreach ($this->filters[NULL] as $filter) {
				$res = call_user_func_array(Helpers::checkCallback($filter), $args2);
				if ($res !== NULL) {
					return $res;
				} elseif (isset($this->filters[$lname])) {
					return call_user_func_array(Helpers::checkCallback($this->filters[$lname]), $args);
				}
			}
			throw new \LogicException("Filter '$name' is not defined.");
		}
		return call_user_func_array(Helpers::checkCallback($this->filters[$lname]), $args);
	}


	/**
	 * Adds new macro.
	 * @return self
	 */
	public function addMacro($name, IMacro $macro)
	{
		$this->getCompiler()->addMacro($name, $macro);
		return $this;
	}


	/**
	 * @return self
	 */
	public function setContentType($type)
	{
		$this->contentType = $type;
		return $this;
	}


	/**
	 * Sets path to temporary directory.
	 * @return self
	 */
	public function setTempDirectory($path)
	{
		$this->tempDirectory = $path;
		return $this;
	}


	/**
	 * Sets auto-refresh mode.
	 * @return self
	 */
	public function setAutoRefresh($on = TRUE)
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
	 * @return self
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
