<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential;

use Latte;
use Latte\Compiler\Nodes\Php\Scalar;
use Latte\Compiler\Nodes\TextNode;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;
use Latte\Runtime;
use Latte\RuntimeException;
use Nette;


/**
 * Basic tags and filters for Latte.
 */
final class CoreExtension extends Latte\Extension
{
	private Latte\Engine $engine;
	private Runtime\Template $template;


	public function beforeCompile(Latte\Engine $engine): void
	{
		$this->engine = $engine;
	}


	public function beforeRender(Runtime\Template $template): void
	{
		$this->template = $template;
	}


	public function getTags(): array
	{
		return [
			'embed' => [Nodes\EmbedNode::class, 'create'],
			'define' => [Nodes\DefineNode::class, 'create'],
			'block' => [Nodes\BlockNode::class, 'create'],
			'layout' => [Nodes\ExtendsNode::class, 'create'],
			'extends' => [Nodes\ExtendsNode::class, 'create'],
			'import' => [Nodes\ImportNode::class, 'create'],
			'include' => \Closure::fromCallable([$this, 'includeSplitter']),

			'n:attr' => [Nodes\NAttrNode::class, 'create'],
			'n:class' => [Nodes\NClassNode::class, 'create'],
			'n:tag' => [Nodes\NTagNode::class, 'create'],

			'parameters' => [Nodes\ParametersNode::class, 'create'],
			'varType' => [Nodes\VarTypeNode::class, 'create'],
			'varPrint' => [Nodes\VarPrintNode::class, 'create'],
			'templateType' => [Nodes\TemplateTypeNode::class, 'create'],
			'templatePrint' => [Nodes\TemplatePrintNode::class, 'create'],

			'=' => [Nodes\PrintNode::class, 'create'],
			'do' => [Nodes\DoNode::class, 'create'],
			'php' => [Nodes\DoNode::class, 'create'], // obsolete
			'contentType' => [Nodes\ContentTypeNode::class, 'create'],
			'spaceless' => [Nodes\SpacelessNode::class, 'create'],
			'capture' => [Nodes\CaptureNode::class, 'create'],
			'l' => fn(Tag $tag) => new TextNode('{', $tag->position),
			'r' => fn(Tag $tag) => new TextNode('}', $tag->position),
			'syntax' => \Closure::fromCallable([$this, 'parseSyntax']),

			'dump' => [Nodes\DumpNode::class, 'create'],
			'debugbreak' => [Nodes\DebugbreakNode::class, 'create'],
			'trace' => [Nodes\TraceNode::class, 'create'],

			'var' => [Nodes\VarNode::class, 'create'],
			'default' => [Nodes\VarNode::class, 'create'],

			'try' => [Nodes\TryNode::class, 'create'],
			'rollback' => [Nodes\RollbackNode::class, 'create'],

			'foreach' => [Nodes\ForeachNode::class, 'create'],
			'for' => [Nodes\ForNode::class, 'create'],
			'while' => [Nodes\WhileNode::class, 'create'],
			'iterateWhile' => [Nodes\IterateWhileNode::class, 'create'],
			'sep' => [Nodes\FirstLastSepNode::class, 'create'],
			'last' => [Nodes\FirstLastSepNode::class, 'create'],
			'first' => [Nodes\FirstLastSepNode::class, 'create'],
			'skipIf' => [Nodes\JumpNode::class, 'create'],
			'breakIf' => [Nodes\JumpNode::class, 'create'],
			'exitIf' => [Nodes\JumpNode::class, 'create'],
			'continueIf' => [Nodes\JumpNode::class, 'create'],

			'if' => [Nodes\IfNode::class, 'create'],
			'ifset' => [Nodes\IfNode::class, 'create'],
			'ifchanged' => [Nodes\IfChangedNode::class, 'create'],
			'n:ifcontent' => [Nodes\IfContentNode::class, 'create'],
			'n:else' => [Nodes\NElseNode::class, 'create'],
			'switch' => [Nodes\SwitchNode::class, 'create'],
		];
	}


	public function getFilters(): array
	{
		return [
			'batch' => [Filters::class, 'batch'],
			'breakLines' => [Filters::class, 'breaklines'],
			'breaklines' => [Filters::class, 'breaklines'],
			'bytes' => [Filters::class, 'bytes'],
			'capitalize' => extension_loaded('mbstring')
				? [Filters::class, 'capitalize']
				: fn() => throw new RuntimeException('Filter |capitalize requires mbstring extension.'),
			'ceil' => [Filters::class, 'ceil'],
			'checkUrl' => [Latte\Runtime\Filters::class, 'safeUrl'],
			'clamp' => [Filters::class, 'clamp'],
			'dataStream' => [Filters::class, 'dataStream'],
			'datastream' => [Filters::class, 'dataStream'],
			'date' => [Filters::class, 'date'],
			'escape' => [Latte\Runtime\Filters::class, 'nop'],
			'escapeCss' => [Latte\Runtime\Filters::class, 'escapeCss'],
			'escapeHtml' => [Latte\Runtime\Filters::class, 'escapeHtml'],
			'escapeHtmlComment' => [Latte\Runtime\Filters::class, 'escapeHtmlComment'],
			'escapeICal' => [Latte\Runtime\Filters::class, 'escapeICal'],
			'escapeJs' => [Latte\Runtime\Filters::class, 'escapeJs'],
			'escapeUrl' => 'rawurlencode',
			'escapeXml' => [Latte\Runtime\Filters::class, 'escapeXml'],
			'explode' => [Filters::class, 'explode'],
			'first' => [Filters::class, 'first'],
			'firstUpper' => extension_loaded('mbstring')
				? [Filters::class, 'firstUpper']
				: fn() => throw new RuntimeException('Filter |firstUpper requires mbstring extension.'),
			'floor' => [Filters::class, 'floor'],
			'implode' => [Filters::class, 'implode'],
			'indent' => [Filters::class, 'indent'],
			'join' => [Filters::class, 'implode'],
			'last' => [Filters::class, 'last'],
			'length' => [Filters::class, 'length'],
			'lower' => extension_loaded('mbstring')
				? [Filters::class, 'lower']
				: fn() => throw new RuntimeException('Filter |lower requires mbstring extension.'),
			'number' => 'number_format',
			'padLeft' => [Filters::class, 'padLeft'],
			'padRight' => [Filters::class, 'padRight'],
			'query' => [Filters::class, 'query'],
			'random' => [Filters::class, 'random'],
			'repeat' => [Filters::class, 'repeat'],
			'replace' => [Filters::class, 'replace'],
			'replaceRe' => [Filters::class, 'replaceRe'],
			'replaceRE' => [Filters::class, 'replaceRe'],
			'reverse' => [Filters::class, 'reverse'],
			'round' => [Filters::class, 'round'],
			'slice' => [Filters::class, 'slice'],
			'sort' => [Filters::class, 'sort'],
			'spaceless' => [Filters::class, 'strip'],
			'split' => [Filters::class, 'explode'],
			'strip' => [Filters::class, 'strip'], // obsolete
			'stripHtml' => [Filters::class, 'stripHtml'],
			'striphtml' => [Filters::class, 'stripHtml'],
			'stripTags' => [Filters::class, 'stripTags'],
			'striptags' => [Filters::class, 'stripTags'],
			'substr' => [Filters::class, 'substring'],
			'trim' => [Filters::class, 'trim'],
			'truncate' => [Filters::class, 'truncate'],
			'upper' => extension_loaded('mbstring')
				? [Filters::class, 'upper']
				: fn() => throw new RuntimeException('Filter |upper requires mbstring extension.'),
			'webalize' => class_exists(Nette\Utils\Strings::class)
				? [Nette\Utils\Strings::class, 'webalize']
				: fn() => throw new RuntimeException('Filter |webalize requires nette/utils package.'),
		];
	}


	public function getFunctions(): array
	{
		return [
			'clamp' => [Filters::class, 'clamp'],
			'divisibleBy' => [Filters::class, 'divisibleBy'],
			'even' => [Filters::class, 'even'],
			'first' => [Filters::class, 'first'],
			'last' => [Filters::class, 'last'],
			'odd' => [Filters::class, 'odd'],
			'slice' => [Filters::class, 'slice'],
			'hasBlock' => fn(string $name): bool => $this->template->hasBlock($name),
		];
	}


	public function getPasses(): array
	{
		$passes = new Passes($this->engine);
		return [
			'internalVariables' => [$passes, 'forbiddenVariablesPass'],
			'overwrittenVariables' => [Nodes\ForeachNode::class, 'overwrittenVariablesPass'],
			'customFunctions' => [$passes, 'customFunctionsPass'],
			'moveTemplatePrintToHead' => [Nodes\TemplatePrintNode::class, 'moveToHeadPass'],
			'nElse' => [Nodes\NElseNode::class, 'processPass'],
		];
	}


	/**
	 * {include [file] "file" [with blocks] [,] [params]}
	 * {include [block] name [,] [params]}
	 */
	private function includeSplitter(Tag $tag, TemplateParser $parser): Nodes\IncludeBlockNode|Nodes\IncludeFileNode
	{
		$tag->expectArguments();
		$mod = $tag->parser->tryConsumeTokenBeforeUnquotedString('block', 'file');
		if ($mod) {
			$block = $mod->text === 'block';
		} elseif ($tag->parser->stream->tryConsume('#')) {
			$block = true;
		} else {
			$name = $tag->parser->parseUnquotedStringOrExpression();
			$block = $name instanceof Scalar\StringNode && preg_match('~[\w-]+$~DA', $name->value);
		}
		$tag->parser->stream->seek(0);

		return $block
			? Nodes\IncludeBlockNode::create($tag, $parser)
			: Nodes\IncludeFileNode::create($tag);
	}


	/**
	 * {syntax ...}
	 */
	private function parseSyntax(Tag $tag, TemplateParser $parser): \Generator
	{
		$tag->expectArguments();
		$token = $tag->parser->stream->consume();
		$lexer = $parser->getLexer();
		$saved = [$lexer->openDelimiter, $lexer->closeDelimiter];
		$lexer->setSyntax($token->text, $tag->isNAttribute() ? null : $tag->name);
		[$inner] = yield;
		[$lexer->openDelimiter, $lexer->closeDelimiter] = $saved;
		return $inner;
	}
}
