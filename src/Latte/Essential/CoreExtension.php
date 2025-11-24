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
use function array_keys, class_exists, extension_loaded, preg_match;


/**
 * Basic tags and filters for Latte.
 */
final class CoreExtension extends Latte\Extension
{
	private Latte\Engine $engine;
	private Filters $filters;


	public function __construct()
	{
		$this->filters = new Filters;
	}


	public function beforeCompile(Latte\Engine $engine): void
	{
		$this->engine = $engine;
	}


	public function beforeRender(Runtime\Template $template): void
	{
		$this->engine = $template->getEngine();
		$this->filters->locale = $this->engine->getLocale();
	}


	public function getTags(): array
	{
		return [
			'embed' => Nodes\EmbedNode::create(...),
			'define' => Nodes\DefineNode::create(...),
			'block' => Nodes\BlockNode::create(...),
			'layout' => Nodes\ExtendsNode::create(...),
			'extends' => Nodes\ExtendsNode::create(...),
			'import' => Nodes\ImportNode::create(...),
			'include' => $this->includeSplitter(...),

			'n:attr' => Nodes\NAttrNode::create(...),
			'n:class' => Nodes\NClassNode::create(...),
			'n:tag' => Nodes\NTagNode::create(...),

			'parameters' => Nodes\ParametersNode::create(...),
			'varType' => Nodes\VarTypeNode::create(...),
			'varPrint' => Nodes\VarPrintNode::create(...),
			'templateType' => Nodes\TemplateTypeNode::create(...),
			'templatePrint' => Nodes\TemplatePrintNode::create(...),

			'=' => Latte\Compiler\Nodes\PrintNode::create(...),
			'do' => Nodes\DoNode::create(...),
			'php' => Nodes\DoNode::create(...), // obsolete
			'contentType' => Nodes\ContentTypeNode::create(...),
			'spaceless' => Nodes\SpacelessNode::create(...),
			'capture' => Nodes\CaptureNode::create(...),
			'l' => fn(Tag $tag) => new TextNode('{', $tag->position),
			'r' => fn(Tag $tag) => new TextNode('}', $tag->position),
			'syntax' => $this->parseSyntax(...),

			'dump' => Nodes\DumpNode::create(...),
			'debugbreak' => Nodes\DebugbreakNode::create(...),
			'trace' => Nodes\TraceNode::create(...),

			'var' => Nodes\VarNode::create(...),
			'default' => Nodes\VarNode::create(...),

			'try' => Nodes\TryNode::create(...),
			'rollback' => Nodes\RollbackNode::create(...),

			'foreach' => Nodes\ForeachNode::create(...),
			'for' => Nodes\ForNode::create(...),
			'while' => Nodes\WhileNode::create(...),
			'iterateWhile' => Nodes\IterateWhileNode::create(...),
			'sep' => Nodes\FirstLastSepNode::create(...),
			'last' => Nodes\FirstLastSepNode::create(...),
			'first' => Nodes\FirstLastSepNode::create(...),
			'skipIf' => Nodes\JumpNode::create(...),
			'breakIf' => Nodes\JumpNode::create(...),
			'exitIf' => Nodes\JumpNode::create(...),
			'continueIf' => Nodes\JumpNode::create(...),

			'if' => Nodes\IfNode::create(...),
			'ifset' => Nodes\IfNode::create(...),
			'ifchanged' => Nodes\IfChangedNode::create(...),
			'n:ifcontent' => Nodes\IfContentNode::create(...),
			'n:else' => Nodes\NElseNode::create(...),
			'n:elseif' => Nodes\NElseNode::create(...),
			'switch' => Nodes\SwitchNode::create(...),
		];
	}


	public function getFilters(): array
	{
		return [
			'batch' => $this->filters->batch(...),
			'breakLines' => $this->filters->breaklines(...),
			'breaklines' => $this->filters->breaklines(...),
			'bytes' => $this->filters->bytes(...),
			'capitalize' => extension_loaded('mbstring')
				? $this->filters->capitalize(...)
				: fn() => throw new RuntimeException('Filter |capitalize requires mbstring extension.'),
			'ceil' => $this->filters->ceil(...),
			'checkUrl' => $this->filters->checkUrl(...),
			'clamp' => $this->filters->clamp(...),
			'dataStream' => $this->filters->dataStream(...),
			'datastream' => $this->filters->dataStream(...),
			'date' => $this->filters->date(...),
			'escape' => Latte\Runtime\Helpers::nop(...),
			'escapeCss' => Latte\Runtime\Helpers::escapeCss(...),
			'escapeHtml' => Latte\Runtime\HtmlHelpers::escapeText(...),
			'escapeHtmlComment' => Latte\Runtime\HtmlHelpers::escapeComment(...),
			'escapeICal' => Latte\Runtime\Helpers::escapeICal(...),
			'escapeJs' => Latte\Runtime\Helpers::escapeJs(...),
			'escapeUrl' => 'rawurlencode',
			'escapeXml' => Latte\Runtime\XmlHelpers::escapeText(...),
			'explode' => $this->filters->explode(...),
			'filter' => $this->filters->filter(...),
			'first' => $this->filters->first(...),
			'firstLower' => extension_loaded('mbstring')
				? $this->filters->firstLower(...)
				: fn() => throw new RuntimeException('Filter |firstLower requires mbstring extension.'),
			'firstUpper' => extension_loaded('mbstring')
				? $this->filters->firstUpper(...)
				: fn() => throw new RuntimeException('Filter |firstUpper requires mbstring extension.'),
			'floor' => $this->filters->floor(...),
			'group' => $this->filters->group(...),
			'implode' => $this->filters->implode(...),
			'indent' => $this->filters->indent(...),
			'join' => $this->filters->implode(...),
			'last' => $this->filters->last(...),
			'length' => $this->filters->length(...),
			'localDate' => $this->filters->localDate(...),
			'lower' => extension_loaded('mbstring')
				? $this->filters->lower(...)
				: fn() => throw new RuntimeException('Filter |lower requires mbstring extension.'),
			'number' => $this->filters->number(...),
			'padLeft' => $this->filters->padLeft(...),
			'padRight' => $this->filters->padRight(...),
			'query' => $this->filters->query(...),
			'random' => $this->filters->random(...),
			'repeat' => $this->filters->repeat(...),
			'replace' => $this->filters->replace(...),
			'replaceRe' => $this->filters->replaceRe(...),
			'replaceRE' => $this->filters->replaceRe(...),
			'reverse' => $this->filters->reverse(...),
			'round' => $this->filters->round(...),
			'slice' => $this->filters->slice(...),
			'sort' => $this->filters->sort(...),
			'spaceless' => $this->filters->strip(...),
			'split' => $this->filters->explode(...),
			'strip' => $this->filters->strip(...), // obsolete
			'stripHtml' => $this->filters->stripHtml(...),
			'striphtml' => $this->filters->stripHtml(...),
			'stripTags' => $this->filters->stripTags(...),
			'striptags' => $this->filters->stripTags(...),
			'substr' => $this->filters->substring(...),
			'trim' => $this->filters->trim(...),
			'truncate' => $this->filters->truncate(...),
			'upper' => extension_loaded('mbstring')
				? $this->filters->upper(...)
				: fn() => throw new RuntimeException('Filter |upper requires mbstring extension.'),
			'webalize' => class_exists(Nette\Utils\Strings::class)
				? Nette\Utils\Strings::webalize(...)
				: fn() => throw new RuntimeException('Filter |webalize requires nette/utils package.'),
		];
	}


	public function getFunctions(): array
	{
		return [
			'clamp' => $this->filters->clamp(...),
			'divisibleBy' => $this->filters->divisibleBy(...),
			'even' => $this->filters->even(...),
			'first' => $this->filters->first(...),
			'group' => $this->filters->group(...),
			'last' => $this->filters->last(...),
			'odd' => $this->filters->odd(...),
			'slice' => $this->filters->slice(...),
			'hasBlock' => fn(Runtime\Template $template, string $name): bool => $template->hasBlock($name),
			'hasTemplate' => fn(Runtime\Template $template, string $name): bool => $this->hasTemplate($name, $template->getName()),
		];
	}


	public function getPasses(): array
	{
		$passes = new Passes($this->engine);
		return [
			'internalVariables' => $passes->forbiddenVariablesPass(...),
			'checkUrls' => $passes->checkUrlsPass(...),
			'overwrittenVariables' => Nodes\ForeachNode::overwrittenVariablesPass(...),
			'customFunctions' => $passes->customFunctionsPass(...),
			'moveTemplatePrintToHead' => Nodes\TemplatePrintNode::moveToHeadPass(...),
			'nElse' => Nodes\NElseNode::processPass(...),
			'scriptTagQuotes' => $passes->scriptTagQuotesPass(...),
		];
	}


	public function getCacheKey(Latte\Engine $engine): mixed
	{
		return array_keys($engine->getFunctions());
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
		if ($tag->isNAttribute() && $tag->prefix !== $tag::PrefixNone) {
			throw new Latte\CompileException("Use n:syntax instead of {$tag->getNotation()}", $tag->position);
		}
		$tag->expectArguments();
		$token = $tag->parser->stream->consume();
		$lexer = $parser->getLexer();
		$lexer->setSyntax($token->text, $tag->isNAttribute() ? null : $tag->name);
		[$inner] = yield;
		if (!$tag->isNAttribute()) {
			$lexer->popSyntax();
		}
		return $inner;
	}


	/**
	 * Checks if template exists.
	 */
	private function hasTemplate(string $name, string $referringName): bool
	{
		try {
			$name = $this->engine->getLoader()->getReferredName($name, $referringName);
			$this->engine->createTemplate($name, [], clearCache: false);
			return true;
		} catch (Latte\TemplateNotFoundException) {
			return false;
		}
	}
}
