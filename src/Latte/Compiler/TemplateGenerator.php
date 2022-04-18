<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\Context;
use Latte\Strict;


/**
 * Template code generator.
 */
final class TemplateGenerator
{
	use Strict;

	/** @var array<string, ?array{body: string, arguments: string, returns: string, comment: ?string}> */
	private array $methods = [];

	/** @var array<string, mixed> */
	private array $properties = [];

	/** @var array<string, mixed> */
	private array $constants = [];


	/**
	 * Compiles nodes to PHP file
	 */
	public function generate(
		PrintContext $context,
		Nodes\TemplateNode $node,
		string $className,
		?string $comment = null,
		bool $strictMode = false,
	): string {
		$code = $node->main->print($context) . ' return get_defined_vars();';
		$code = self::buildParams($code, $context->paramsExtraction, '$this->params');
		$this->addMethod('main', $code, '', 'array');

		$contentType = $context->getContentType();
		if ($contentType !== Context::Html) {
			$this->addConstant('ContentType', $contentType);
		}

		$members = [];
		foreach ($this->constants as $name => $value) {
			$members[] = "\tpublic const $name = " . PhpHelpers::dump($value, true) . ';';
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
			. "\n}\n";

		$code = PhpHelpers::inlineHtmlToEcho($code);
		$code = PhpHelpers::reformatCode($code);
		return $code;
	}


	private function buildParams(string $body, array $params, string $cont): string
	{
		if (!str_contains($body, '$') && !str_contains($body, 'get_defined_vars()')) {
			return $body;
		}

		$extract = $params
			? implode('', $params) . 'unset($ʟ_args);'
			: "extract($cont);" . (str_contains($cont, '$this') ? '' : "unset($cont);");
		return $extract . "\n\n" . $body;
	}


	/**
	 * Adds custom method to template.
	 * @internal
	 */
	public function addMethod(
		string $name,
		string $body,
		string $arguments = '',
		string $returns = 'void',
		?string $comment = null,
	): void {
		$body = trim($body);
		$this->methods[$name] = compact('body', 'arguments', 'returns', 'comment');
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


	private function closeHtmlTag($token): void
	{
		if ($this->htmlNode->macroAttrs) {
			throw new CompileException("Unexpected $token, expecting " . self::printEndTag($this->htmlNode));
		} elseif ($this->contentType === Context::Html
			&& in_array(strtolower($this->htmlNode->name), ['script', 'style'], true)
		) {
			throw new CompileException("Unexpected $token, expecting </{$this->htmlNode->name}>");
		}

		$this->htmlNode = $this->htmlNode->parentNode;
	}
}
