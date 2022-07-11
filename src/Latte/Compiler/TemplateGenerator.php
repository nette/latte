<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte;
use Latte\ContentType;
use Latte\Essential\Blueprint;
use Nette\PhpGenerator as Php;


/**
 * Template code generator.
 */
final class TemplateGenerator
{
	use Latte\Strict;

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
		Nodes\TemplateNode $node,
		string $className,
		?string $comment = null,
		bool $strictMode = false,
		array $filters = [],
	): string {
		$context = new PrintContext($node->contentType);
		$scope = $context->getVariableScope();
		$this->addMethod('main', '');

		$head = (new NodeTraverser)->traverse($node->head, fn(Node $node) => $node instanceof Nodes\TextNode ? new Nodes\NopNode : $node);
		$code = $head->print($context);
		if ($code || $context->paramsExtraction) {
			$code .= 'return get_defined_vars();';
			$code = self::buildParams($code, $context->paramsExtraction, '$this->params', $context, $scope);
			$this->addMethod('prepare', $code, '', 'array');
		}

		$code = $node->main->print($context);
		$code = self::buildParams($code, [], '$ʟ_args', $context, $context->getVariableScope());
		$this->addMethod('main', $code, 'array $ʟ_args');

		if ($node->contentType !== ContentType::Html) {
			$this->addConstant('ContentType', $node->contentType);
		}

		$this->generateBlocks($context->blocks, $context);

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

		$comment .= "\n@property Filters$className \$filters";
		$comment = str_replace('*/', '* /', $comment);
		$comment = str_replace("\n", "\n * ", "/**\n" . trim($comment)) . "\n */\n";

		$code = "<?php\n\n"
			. ($strictMode ? "declare(strict_types=1);\n\n" : '')
			. "use Latte\\Runtime as LR;\n\n"
			. $comment
			. "final class $className extends Latte\\Runtime\\Template\n{\n"
			. implode("\n\n", $members)
			. "\n}\n\n\n"
			. $this->generateStub($node, 'Filters' . $className, $filters);

		$code = PhpHelpers::optimizeEcho($code);
		$code = PhpHelpers::reformatCode($code);
		return $code;
	}


	/** @param  Block[]  $blocks */
	private function generateBlocks(array $blocks, PrintContext $context): void
	{
		$contentType = $context->getEscaper()->getContentType();
		foreach ($blocks as $block) {
			if (!$block->isDynamic()) {
				$meta[$block->layer][$block->name->value] = $contentType === $block->escaping
					? $block->method
					: [$block->method, $block->escaping];
			}

			$body = self::buildParams($block->content, $block->parameters, '$ʟ_args', $context, $block->variables);
			if (!$block->isDynamic() && str_contains($body, '$')) {
				$embedded = $block->tag->name === 'block' && is_int($block->layer) && $block->layer;
				if($context->paramsExtraction) {
					$paramTypes = '';
					foreach ($context->paramsExtraction as $param) {
						$paramTypes .= $param->type ?
							VariableScope::printComment($param->var->name, $param->type->type) . "\n" :
							'';
					}
					$body = 'extract($this->prepare());' . "\n" . $paramTypes . $body;
				} else {
					$body = 'extract(' . ($embedded ? 'end($this->varStack)' : '$this->params') . ');' . $body;
				}

			}

			$this->addMethod(
				$block->method,
				$body,
				'array $ʟ_args',
				'void',
				$block->tag->getNotation(true) . ' on line ' . $block->tag->position->line,
			);
		}

		if (isset($meta)) {
			$this->addConstant('Blocks', $meta);
		}
	}


	private function generateStub(Node $node, string $className, $filters): string
	{
		if (!class_exists(Php\ClassType::class)) {
			return '';
		}

		$used = [];
		(new NodeTraverser)->traverse($node, function (Node $node) use (&$used) {
			if ($node instanceof Nodes\Php\FilterNode) {
				$used[$node->name->name] = true;
			}
		});

		$class = new Php\ClassType($className);
		$filters = array_intersect_key($filters, $used);
		foreach ($filters as $name => $callback) {
			$func = (new Php\Factory)->fromCallable($callback);
			$type = Blueprint::printType($func->getReturnType(), $func->isReturnNullable(), null) ?: 'mixed';
			$params = [];
			$list = $func->getParameters();
			foreach ($list as $param) {
				$variadic = $func->isVariadic() && $param === end($list);
				$params[] = (Blueprint::printType($param->getType(), $param->isNullable(), null) ?: 'mixed')
					. ($variadic ? '...' : '');
			}

			$class->addComment('@property callable(' . implode(', ', $params) . "): $type \$$name");
		}

		return (string) $class;
	}


	/**
	 * @param Nodes\Php\ParameterNode[] $params
	 */
	private static function buildParams(
		string $body,
		array $params,
		string $cont,
		PrintContext $context,
		VariableScope $scope,
	): string {
		if (!str_contains($body, '$') && !str_contains($body, 'get_defined_vars()')) {
			return $body;
		}

		$res = [];
		foreach ($params as $i => $param) {
			$res[] = $context->format(
				'%raw%node = %raw[%dump] ?? %raw[%dump] ?? %node;',
				$param->type ? VariableScope::printComment($param->var->name, $param->type->type) . ' ' : '',
				$param->var,
				$cont,
				$i,
				$cont,
				$param->var->name,
				$param->default,
			);
		}

		$extract = $params ? implode('', $res) : "extract($cont);";
		$extract .= (str_contains($cont, '$this') ? '' : "unset($cont);");

		return $extract
			. $scope->extractTypes() . "\n\n"
			. $body;
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
}
