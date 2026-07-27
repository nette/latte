<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte;

use Latte\Compiler\Nodes\TemplateNode;
use function array_map, array_merge, class_exists, extension_loaded, get_debug_type, preg_match, serialize, substr;


/**
 * Templating engine Latte.
 */
class Engine
{
	public const Version = '3.1.6';
	public const VersionId = 30106;

	/** @deprecated use Engine::Version */
	public const
		VERSION = self::Version,
		VERSION_ID = self::VersionId;

	#[\Deprecated('use Latte\ContentType::Html')]
	public const CONTENT_HTML = ContentType::Html;

	#[\Deprecated('use Latte\ContentType::Xml')]
	public const CONTENT_XML = ContentType::Xml;

	#[\Deprecated('use Latte\ContentType::JavaScript')]
	public const CONTENT_JS = ContentType::JavaScript;

	#[\Deprecated('use Latte\ContentType::Css')]
	public const CONTENT_CSS = ContentType::Css;

	#[\Deprecated('use Latte\ContentType::ICal')]
	public const CONTENT_ICAL = ContentType::ICal;

	#[\Deprecated('use Latte\ContentType::Text')]
	public const CONTENT_TEXT = ContentType::Text;

	private ?Loader $loader = null;
	private Runtime\FilterExecutor $filters;
	private Runtime\FunctionExecutor $functions;
	private \stdClass $providers;

	/** @var Extension[] */
	private array $extensions = [];
	private string $contentType = ContentType::Html;
	private Runtime\Cache $cache;

	/** @var array<string, bool> */
	private array $features = [
		Feature::StrictTypes->name => true,
	];

	private ?Policy $policy = null;
	private bool $sandboxed = false;
	private ?string $phpBinary = null;
	private ?string $configurationHash = null;
	private ?string $locale = null;
	private ?string $syntax = null;


	public function __construct()
	{
		$this->cache = new Runtime\Cache;
		$this->filters = new Runtime\FilterExecutor;
		$this->functions = new Runtime\FunctionExecutor;
		$this->providers = new \stdClass;
		$this->addDefaultExtensions();
	}


	/**
	 * Renders template to output.
	 * @param  object|mixed[]  $params
	 */
	public function render(string $name, object|array $params = [], ?string $block = null): void
	{
		$template = $this->createTemplate($name, Helpers::resolveParams($this, $params));
		$template->global->coreCaptured = false;
		$template->render($block);
	}


	/**
	 * Renders template to string.
	 * @param  object|mixed[]  $params
	 */
	public function renderToString(string $name, object|array $params = [], ?string $block = null): string
	{
		$template = $this->createTemplate($name, Helpers::resolveParams($this, $params));
		$template->global->coreCaptured = true;
		return $template->capture(fn() => $template->render($block));
	}


	/**
	 * Creates template object.
	 * @param  mixed[]  $params
	 */
	public function createTemplate(string $name, array $params = [], bool $clearCache = true): Runtime\Template
	{
		$this->configurationHash = $clearCache ? null : $this->configurationHash;
		$class = $this->loadTemplate($name);
		$this->providers->fn = $this->functions;
		return new $class(
			$this,
			$params,
			$this->filters,
			$this->providers,
			$name,
		);
	}


	/**
	 * Compiles template to PHP code.
	 */
	public function compile(string $name): string
	{
		if ($this->sandboxed && !$this->policy) {
			throw new \LogicException('In sandboxed mode you need to set a security policy.');
		}

		$template = $this->getLoader()->getContent($name);

		try {
			$node = $this->parse($template);
			$this->applyPasses($node);
			$compiled = $this->generate($node, $name);

		} catch (\Throwable $e) {
			if (!$e instanceof CompileException && !$e instanceof SecurityViolationException) {
				$e = new CompileException("Thrown exception '{$e->getMessage()}'", previous: $e);
			}

			throw $e->setSource($template, $name);
		}

		if ($this->phpBinary) {
			Compiler\PhpHelpers::checkCode($this->phpBinary, $compiled, "(compiled $name)");
		}

		return $compiled;
	}


	/**
	 * Parses template to AST node.
	 */
	public function parse(string $template): TemplateNode
	{
		$parser = new Compiler\TemplateParser;
		$parser->getLexer()->setSyntax($this->syntax);
		$parser->strict = $this->hasFeature(Feature::StrictParsing);
		$parser->dedent = $this->hasFeature(Feature::Dedent);

		foreach ($this->extensions as $extension) {
			$extension->beforeCompile($this);
			$parser->addTags($extension->getTags());
		}

		return $parser
			->setContentType($this->contentType)
			->setPolicy($this->getPolicy(effective: true))
			->parse($template);
	}


	/**
	 * Runs all registered compiler passes over the AST.
	 */
	public function applyPasses(TemplateNode &$node): void
	{
		$passes = [];
		foreach ($this->extensions as $extension) {
			$passes = array_merge($passes, $extension->getPasses());
		}

		$passes = Helpers::sortBeforeAfter($passes);
		foreach ($passes as $pass) {
			$pass = $pass instanceof \stdClass ? $pass->subject : $pass;
			($pass)($node);
		}
	}


	/**
	 * Generates compiled PHP code.
	 */
	public function generate(TemplateNode $node, string $name): string
	{
		$generator = new Compiler\TemplateGenerator;
		$generator->buildClass($node, $this->features);
		return $generator->generateCode($this->getTemplateClass($name), $name, $this->hasFeature(Feature::StrictTypes));
	}


	/**
	 * Compiles template to cache.
	 * @throws \LogicException
	 */
	public function warmupCache(string $name): void
	{
		if (!$this->cache->directory) {
			throw new \LogicException('Path to temporary directory is not set.');
		}

		$this->loadTemplate($name);
	}


	/** @return class-string<Runtime\Template> */
	private function loadTemplate(string $name): string
	{
		$class = $this->getTemplateClass($name);
		if (class_exists($class, autoload: false)) {
			// nothing
		} elseif ($this->cache->directory) {
			$this->cache->loadOrCreate($this, $name);
		} else {
			$compiled = $this->compile($name);
			try {
				eval(substr($compiled, 5)); // substr removes <?php
			} catch (\ParseError $e) {
				throw (new CompileException('Error in template: ' . $e->getMessage(), previous: $e))
					->setSource($compiled, "$name (compiled)");
			}
		}
		return $class;
	}


	/**
	 * Returns the file path where compiled template will be cached.
	 */
	public function getCacheFile(string $name): string
	{
		return $this->cache->generateFilePath($this, $name);
	}


	/**
	 * Returns the PHP class name for compiled template.
	 */
	public function getTemplateClass(string $name): string
	{
		return 'Template_' . $this->generateTemplateHash($name);
	}


	/**
	 * Generates unique hash for template based on current configuration.
	 * Used to create isolated cache files for different engine configurations.
	 * @internal
	 */
	public function generateTemplateHash(string $name): string
	{
		$hash = $this->configurationHash ??= hash('xxh128', serialize($this->generateConfigurationSignature()));
		$hash .= $this->getLoader()->getUniqueId($name);
		return substr(hash('xxh128', $hash), 0, 16); // 64 bits, a collision would silently render a different template
	}


	/**
	 * Returns values that determine isolation for different configurations.
	 * When any of these values change, a new compiled template is created to avoid conflicts.
	 * @return list<mixed>
	 */
	protected function generateConfigurationSignature(): array
	{
		return [
			$this->contentType,
			$this->features,
			$this->syntax,
			array_map(
				fn($extension) => [get_debug_type($extension), $extension->getCacheKey($this)],
				$this->extensions,
			),
		];
	}


	/**
	 * Registers run-time filter.
	 */
	public function addFilter(string $name, callable $callback): static
	{
		if (!preg_match('#^[a-z]\w*$#iD', $name)) {
			throw new \LogicException("Invalid filter name '$name'.");
		}

		$this->filters->add($name, $callback);
		$this->configurationHash = null;
		return $this;
	}


	#[\Deprecated('Use addFilter() instead.')]
	public function addFilterLoader(callable $loader): static
	{
		trigger_error('Filter loader is deprecated, use addFilter() instead.', E_USER_DEPRECATED);
		$this->filters->add(null, $loader);
		return $this;
	}


	/**
	 * Returns all run-time filters.
	 * @return array<string, callable>
	 */
	public function getFilters(): array
	{
		return $this->filters->getAll();
	}


	/**
	 * Calls a run-time filter.
	 * @param  mixed[]  $args
	 */
	public function invokeFilter(string $name, array $args): mixed
	{
		return ($this->filters->$name)(...$args);
	}


	/**
	 * Adds new extension.
	 */
	public function addExtension(Extension $extension): static
	{
		$this->extensions[] = $extension;
		foreach ($extension->getFilters() as $name => $value) {
			$this->filters->add($name, $value);
		}

		foreach ($extension->getFunctions() as $name => $value) {
			$this->functions->add($name, $value);
		}

		foreach ($extension->getProviders() as $name => $value) {
			$this->providers->$name = $value;
		}

		$this->configurationHash = null;
		return $this;
	}


	/** @return list<Extension> */
	public function getExtensions(): array
	{
		return array_values($this->extensions);
	}


	/**
	 * Registers run-time function.
	 */
	public function addFunction(string $name, callable $callback): static
	{
		if (!preg_match('#^[a-z]\w*$#iD', $name)) {
			throw new \LogicException("Invalid function name '$name'.");
		}

		$this->functions->add($name, $callback);
		$this->configurationHash = null;
		return $this;
	}


	/**
	 * Calls a run-time function.
	 * @param  mixed[]  $args
	 */
	public function invokeFunction(string $name, array $args): mixed
	{
		return ($this->functions->$name)(null, ...$args);
	}


	/**
	 * Returns all run-time functions.
	 * @return array<string, callable>
	 */
	public function getFunctions(): array
	{
		return $this->functions->getAll();
	}


	/**
	 * Adds new provider.
	 */
	public function addProvider(string $name, mixed $provider): static
	{
		if (!preg_match('#^[a-z]\w*$#iD', $name)) {
			throw new \LogicException("Invalid provider name '$name'.");
		}

		$this->providers->$name = $provider;
		return $this;
	}


	/**
	 * Returns all providers.
	 * @return array<string, mixed>
	 */
	public function getProviders(): array
	{
		return (array) $this->providers;
	}


	public function setPolicy(?Policy $policy): static
	{
		$this->policy = $policy;
		$this->configurationHash = null;
		return $this;
	}


	public function getPolicy(bool $effective = false): ?Policy
	{
		return !$effective || $this->sandboxed
			? $this->policy
			: null;
	}


	/**
	 * Sets a handler called when an exception occurs during template rendering.
	 */
	public function setExceptionHandler(callable $handler): static
	{
		$this->providers->coreExceptionHandler = $handler(...);
		return $this;
	}


	public function setSandboxMode(bool $state = true): static
	{
		$this->sandboxed = $state;
		$this->configurationHash = null;
		return $this;
	}


	public function setContentType(string $type): static
	{
		$this->contentType = $type;
		$this->configurationHash = null;
		return $this;
	}


	/**
	 * Sets path to cache directory.
	 */
	public function setCacheDirectory(?string $path): static
	{
		$this->cache->directory = $path;
		return $this;
	}


	/** @deprecated use setCacheDirectory() instead */
	public function setTempDirectory(?string $path): static
	{
		return $this->setCacheDirectory($path);
	}


	/**
	 * Sets auto-refresh mode.
	 */
	public function setAutoRefresh(bool $state = true): static
	{
		$this->cache->autoRefresh = $state;
		return $this;
	}


	/**
	 * Enables or disables an engine feature.
	 */
	public function setFeature(Feature $feature, bool $state = true): static
	{
		$this->features[$feature->name] = $state;
		$this->configurationHash = null;
		return $this;
	}


	/**
	 * Checks if a feature is enabled.
	 */
	public function hasFeature(Feature $feature): bool
	{
		return $this->features[$feature->name] ?? false;
	}


	/**
	 * Enables declare(strict_types=1) in templates.
	 * @deprecated use setFeature(Feature::StrictTypes, ...) instead
	 */
	public function setStrictTypes(bool $state = true): static
	{
		return $this->setFeature(Feature::StrictTypes, $state);
	}


	/** @deprecated use setFeature(Feature::StrictParsing, ...) instead */
	public function setStrictParsing(bool $state = true): static
	{
		return $this->setFeature(Feature::StrictParsing, $state);
	}


	/** @deprecated use hasFeature(Feature::StrictParsing) instead */
	public function isStrictParsing(): bool
	{
		return $this->hasFeature(Feature::StrictParsing);
	}


	/**
	 * Sets the locale. It uses the same identifiers as the PHP intl extension.
	 */
	public function setLocale(?string $locale): static
	{
		if ($locale && !extension_loaded('intl')) {
			throw new RuntimeException("Setting a locale requires the 'intl' extension to be installed.");
		}
		$this->locale = $locale;
		$this->configurationHash = null;
		return $this;
	}


	public function getLocale(): ?string
	{
		return $this->locale;
	}


	public function setLoader(Loader $loader): static
	{
		$this->loader = $loader;
		return $this;
	}


	public function getLoader(): Loader
	{
		return $this->loader ??= new Loaders\FileLoader;
	}


	/**
	 * Validates compiled PHP code using the given PHP binary. Pass null to disable.
	 */
	public function enablePhpLinter(?string $phpBinary): static
	{
		$this->phpBinary = $phpBinary;
		return $this;
	}


	/**
	 * Sets default Latte syntax. Available options: 'single', 'double', 'off'
	 */
	public function setSyntax(string $syntax): static
	{
		$this->syntax = $syntax;
		$this->configurationHash = null;
		return $this;
	}


	/** @deprecated use setFeature(Feature::MigrationWarnings, ...) instead */
	public function setMigrationWarnings(bool $state = true): static
	{
		return $this->setFeature(Feature::MigrationWarnings, $state);
	}


	protected function addDefaultExtensions(): void
	{
		$this->addExtension(new Essential\CoreExtension);
		$this->addExtension(new Sandbox\SandboxExtension);
	}
}
