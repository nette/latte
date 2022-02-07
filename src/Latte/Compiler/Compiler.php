<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\Engine;
use Latte\Policy;
use Latte\Strict;


/**
 * Latte compiler.
 */
class Compiler
{
	use Strict;

	/** Context-aware escaping content types */
	public const
		CONTENT_HTML = Engine::CONTENT_HTML,
		CONTENT_XML = Engine::CONTENT_XML,
		CONTENT_JS = Engine::CONTENT_JS,
		CONTENT_CSS = Engine::CONTENT_CSS,
		CONTENT_ICAL = Engine::CONTENT_ICAL,
		CONTENT_TEXT = Engine::CONTENT_TEXT;

	/** @internal Context-aware escaping HTML contexts */
	public const
		CONTEXT_HTML_TEXT = null,
		CONTEXT_HTML_TAG = 'Tag',
		CONTEXT_HTML_ATTRIBUTE = 'Attr',
		CONTEXT_HTML_ATTRIBUTE_JS = 'AttrJs',
		CONTEXT_HTML_ATTRIBUTE_CSS = 'AttrCss',
		CONTEXT_HTML_ATTRIBUTE_URL = 'AttrUrl',
		CONTEXT_HTML_ATTRIBUTE_UNQUOTED_URL = 'AttrUnquotedUrl',
		CONTEXT_HTML_COMMENT = 'Comment',
		CONTEXT_HTML_BOGUS_COMMENT = 'Bogus',
		CONTEXT_HTML_CSS = 'Css',
		CONTEXT_HTML_JS = 'Js',

		CONTEXT_XML_TEXT = self::CONTEXT_HTML_TEXT,
		CONTEXT_XML_TAG = self::CONTEXT_HTML_TAG,
		CONTEXT_XML_ATTRIBUTE = self::CONTEXT_HTML_ATTRIBUTE,
		CONTEXT_XML_COMMENT = self::CONTEXT_HTML_COMMENT,
		CONTEXT_XML_BOGUS_COMMENT = self::CONTEXT_HTML_BOGUS_COMMENT;

	private string $contentType = self::CONTENT_HTML;
	private ?string $context = null;
	private ?Policy $policy = null;
	private string $prepare = '';

	/** @var string[] of orig name */
	private array $functions = [];

	/** @var array<string, ?array{body: string, arguments: string, returns: string, comment: ?string}> */
	private array $methods = [];

	/** @var array<string, mixed> */
	private array $properties = [];

	/** @var array<string, mixed> */
	private array $constants = [];


	/**
	 * Registers run-time functions.
	 * @param  string[]  $names
	 */
	public function setFunctions(array $names): static
	{
		$this->functions = array_combine(array_map('strtolower', $names), $names);
		return $this;
	}


	/**
	 * Compiles nodes to PHP file
	 */
	public function compile(
		Node $node,
		string $className,
		?string $comment = null,
		bool $strictMode = false,
		array $extensions = [],
	): string {
		$this->methods = ['main' => null, 'prepare' => null];

		$code = $node->compile($this);

		$extractParams = $this->paramsExtraction ?? 'extract($this->params);';
		$this->addMethod('main', $extractParams . $code . ' return get_defined_vars();', '', 'array');

		foreach ($extensions as $extension) {
			$extension->afterCompile($this);
		}

		if ($this->prepare) {
			$this->addMethod('prepare', $extractParams . $this->prepare, '', 'void');
		}

		if ($this->contentType !== self::CONTENT_HTML) {
			$this->addConstant('CONTENT_TYPE', $this->contentType);
		}

		$members = [];
		foreach ($this->constants as $name => $value) {
			$members[] = "\tprotected const $name = " . PhpHelpers::dump($value, true) . ';';
		}

		foreach ($this->properties as $name => $value) {
			$members[] = "\tpublic $$name = " . PhpHelpers::dump($value, true) . ';';
		}

		foreach (array_filter($this->methods) as $name => $method) {
			$members[] = ($method['comment'] === null ? '' : "\n\t/** " . str_replace('*/', '* /', $method['comment']) . ' */')
				. "\n\tpublic function $name($method[arguments])"
				. ($method['returns'] ? ': ' . $method['returns'] : '')
				. "\n\t{\n"
				. ($method['body'] ? "\t\t$method[body]\n" : '') . "\t}";
		}

		$code = "<?php\n\n"
			. ($strictMode ? "declare(strict_types=1);\n\n" : '')
			. "use Latte\\Runtime as LR;\n\n"
			. ($comment === null ? '' : '/** ' . str_replace('*/', '* /', $comment) . " */\n")
			. "final class $className extends Latte\\Runtime\\Template\n{\n"
			. implode("\n\n", $members)
			. "\n\n}\n";

		$code = PhpHelpers::inlineHtmlToEcho($code);
		$code = PhpHelpers::reformatCode($code);
		return $code;
	}


	public function setPolicy(?Policy $policy): static
	{
		$this->policy = $policy;
		return $this;
	}


	public function getPolicy(): ?Policy
	{
		return $this->policy;
	}


	public function setContentType(string $type): static
	{
		$this->contentType = $type;
		$this->context = null;
		return $this;
	}


	public function getContentType(): string
	{
		return $this->contentType;
	}


	public function setContext(?string $context): static
	{
		$this->context = $context;
		return $this;
	}


	public function getContext(): array
	{
		return [$this->contentType, $this->context];
	}


	/**
	 * @return string[]
	 */
	public function getFunctions(): array
	{
		return $this->functions;
	}


	/**
	 * Adds custom method to template.
	 * @internal
	 */
	public function addMethod(
		string $name,
		string $body,
		string $arguments = '',
		string $returns = '',
		?string $comment = null,
	): void {
		$body = trim($body);
		$this->methods[$name] = compact('body', 'arguments', 'returns', 'comment');
	}


	public function addPrepare(string $body): void
	{
		$this->prepare .= trim($body) . "\n";
	}


	/**
	 * Returns custom methods.
	 * @return array<string, ?array{body: string, arguments: string, returns: string, comment: ?string}>
	 * @internal
	 */
	public function getMethods(): array
	{
		return $this->methods;
	}


	/**
	 * Adds custom property to template.
	 * @internal
	 */
	public function addProperty(string $name, mixed $value): void
	{
		$this->properties[$name] = $value;
	}


	/**
	 * Returns custom properites.
	 * @return array<string, mixed>
	 * @internal
	 */
	public function getProperties(): array
	{
		return $this->properties;
	}


	/**
	 * Adds custom constant to template.
	 * @internal
	 */
	public function addConstant(string $name, mixed $value): void
	{
		$this->constants[$name] = $value;
	}


	public function write(Node $node, string $mask, ...$args): string
	{
		return PhpWriter::using($node, $this)
			->write($mask, ...$args);
	}
}
