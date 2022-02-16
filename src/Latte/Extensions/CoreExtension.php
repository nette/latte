<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Extensions;

use Latte;
use Latte\CompileException;
use Latte\Compiler\Compiler;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\CallableNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\TextNode;
use Latte\Compiler\NodeTraverser;
use Latte\Compiler\Parser;
use Latte\Compiler\TagInfo;
use Latte\RuntimeException;
use Nette;


/**
 * Basic tags and filters for Latte.
 */
final class CoreExtension implements Latte\Extension
{
	public array $overwrittenVars;
	private int $counter;


	public function getTags(): array
	{
		return [
			'if' => [Nodes\IfNode::class, 'parse'],
			'ifset' => [Nodes\IfNode::class, 'parse'],
			'ifchanged' => [Nodes\IfChangedNode::class, 'parse'],
			'n:ifcontent' => [Nodes\IfContentNode::class, 'parse'],

			'switch' => [Nodes\SwitchNode::class, 'parse'],

			'foreach' => [Nodes\ForeachNode::class, 'parse'],
			'iterateWhile' => [Nodes\IterateWhileNode::class, 'parse'],
			'for' => [Nodes\ForNode::class, 'parse'],
			'while' => [Nodes\WhileNode::class, 'parse'],
			'continueIf' => [Nodes\SkipNode::class, 'parse'],
			'breakIf' => [Nodes\SkipNode::class, 'parse'],
			'skipIf' => [Nodes\SkipNode::class, 'parse'],
			'first' => [Nodes\FirstLastSepNode::class, 'parse'],
			'last' => [Nodes\FirstLastSepNode::class, 'parse'],
			'sep' => [Nodes\FirstLastSepNode::class, 'parse'],

			'try' => [Nodes\TryNode::class, 'parse'],
			'rollback' => [Nodes\RollbackNode::class, 'parse'],

			'var' => [Nodes\VarNode::class, 'parse'],
			'default' => [Nodes\VarNode::class, 'parse'],
			'dump' => [Nodes\DumpNode::class, 'parse'],
			'debugbreak' => [Nodes\DebugbreakNode::class, 'parse'],
			'trace' => [Nodes\TraceNode::class, 'parse'],
			'l' => [$this, 'parseLR'],
			'r' => [$this, 'parseLR'],
			'syntax' => [$this, 'parseSyntax'],

			'_' => [Nodes\TranslateNode::class, 'parse'],
			'=' => [Nodes\PrintNode::class, 'parse'],

			'capture' => [Nodes\CaptureNode::class, 'parse'],
			'spaceless' => [Nodes\SpacelessNode::class, 'parse'],
			'include' => [$this, 'parseInclude'],
			'sandbox' => [Nodes\SandboxNode::class, 'parse'],
			'contentType' => [Nodes\ContentTypeNode::class, 'parse'],
			'php' => [Nodes\DoNode::class, 'parse'],
			'do' => [Nodes\DoNode::class, 'parse'],

			'parameters' => [Nodes\ParametersNode::class, 'parse'],
			'varType' => [Nodes\VarTypeNode::class, 'parse'],
			'varPrint' => [Nodes\VarPrintNode::class, 'parse'],
			'templateType' => [Nodes\TemplateTypeNode::class, 'parse'],
			'templatePrint' => [Nodes\TemplatePrintNode::class, 'parse'],

			'n:class' => [$this, 'parseNClass'],
			'n:attr' => [$this, 'parseNAttr'],
			'n:tag' => [$this, 'parseNTag'],

			'import' => [Nodes\ImportNode::class, 'parse'],
			'extends' => [Nodes\ExtendsNode::class, 'parse'],
			'layout' => [Nodes\ExtendsNode::class, 'parse'],
			'snippet' => [Nodes\SnippetNode::class, 'parse'],
			'block' => [Nodes\BlockNode::class, 'parse'],
			'define' => [Nodes\DefineNode::class, 'parse'],
			'embed' => [Nodes\EmbedNode::class, 'parse'],
			'snippetArea' => [Nodes\SnippetAreaNode::class, 'parse'],
		];
	}


	public function getFilters(): array
	{
		return [
			'batch' => [Filters::class, 'batch'],
			'breakLines' => [Filters::class, 'breaklines'],
			'bytes' => [Filters::class, 'bytes'],
			'capitalize' => extension_loaded('mbstring')
				? [Filters::class, 'capitalize']
				: function () { throw new RuntimeException('Filter |capitalize requires mbstring extension.'); },
			'ceil' => [Filters::class, 'ceil'],
			'clamp' => [Filters::class, 'clamp'],
			'dataStream' => [Filters::class, 'dataStream'],
			'date' => [Filters::class, 'date'],
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
				: function () { throw new RuntimeException('Filter |firstUpper requires mbstring extension.'); },
			'floor' => [Filters::class, 'floor'],
			'checkUrl' => [Latte\Runtime\Filters::class, 'safeUrl'],
			'implode' => [Filters::class, 'implode'],
			'indent' => [Filters::class, 'indent'],
			'join' => [Filters::class, 'implode'],
			'last' => [Filters::class, 'last'],
			'length' => [Filters::class, 'length'],
			'lower' => extension_loaded('mbstring')
				? [Filters::class, 'lower']
				: function () { throw new RuntimeException('Filter |lower requires mbstring extension.'); },
			'number' => 'number_format',
			'padLeft' => [Filters::class, 'padLeft'],
			'padRight' => [Filters::class, 'padRight'],
			'query' => [Filters::class, 'query'],
			'random' => [Filters::class, 'random'],
			'repeat' => [Filters::class, 'repeat'],
			'replace' => [Filters::class, 'replace'],
			'replaceRe' => [Filters::class, 'replaceRe'],
			'reverse' => [Filters::class, 'reverse'],
			'round' => [Filters::class, 'round'],
			'slice' => [Filters::class, 'slice'],
			'sort' => [Filters::class, 'sort'],
			'spaceless' => [Filters::class, 'strip'],
			'split' => [Filters::class, 'explode'],
			'strip' => [Filters::class, 'strip'],
			'stripHtml' => [Filters::class, 'stripHtml'],
			'stripTags' => [Filters::class, 'stripTags'],
			'substr' => [Filters::class, 'substring'],
			'trim' => [Filters::class, 'trim'],
			'truncate' => [Filters::class, 'truncate'],
			'upper' => extension_loaded('mbstring')
				? [Filters::class, 'upper']
				: function () { throw new RuntimeException('Filter |upper requires mbstring extension.'); },
			'webalize' => class_exists(Nette\Utils\Strings::class)
				? [Nette\Utils\Strings::class, 'webalize']
				: function () { throw new RuntimeException('Filter |webalize requires nette/utils package.'); },
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
		];
	}


	public function beforeParse(): void
	{
		$this->counter = 0;
	}


	public function afterParse(Node $node): void
	{
		$this->overwrittenVars = [];
		(new NodeTraverser)->traverse($node, function (Node $node) {
			if ($node instanceof Nodes\ForeachNode && $node->checkArgs) {
				preg_match('#.+\s+as\s*\$(\w+)(?:\s*=>\s*\$(\w+))?#i', $node->argsText, $m);
				for ($i = 1; $i < count($m); $i++) {
					$this->overwrittenVars[$m[$i]][] = $node->line;
				}
			}
		});
	}


	public function afterCompile(Compiler $compiler): void
	{
		if ($this->overwrittenVars) {
			$vars = array_map(fn($l) => implode(', ', $l), $this->overwrittenVars);
			$compiler->addPrepare('
				if (!$this->getReferringTemplate() || $this->getReferenceType() === "extends") {
					foreach (array_intersect_key(' . Latte\Compiler\PhpHelpers::dump($vars) . ', $this->params) as $ʟ_v => $ʟ_l) {
						trigger_error("Variable \$$ʟ_v overwritten in foreach on line $ʟ_l");
					}
				}
			');
		}
	}


	/**
	 * {l} {r}
	 */
	public function parseLR(TagInfo $tag): TextNode
	{
		$tag->validate(false);
		return new TextNode($tag->name === 'l' ? '{' : '}');
	}


	/**
	 * {syntax ...}
	 *
	 * @return \Generator<int, ?array, array{FragmentNode, ?TagInfo}, FragmentNode>
	 */
	public function parseSyntax(TagInfo $tag, Parser $parser): \Generator
	{
		$tag->validate(true);
		$parser->getLexer()->setSyntax($tag->args);
		[$inner] = yield;
		$parser->getLexer()->setSyntax(null);
		return $inner;
	}


	/**
	 * {include [file] "file" [with blocks] [,] [params]}
	 * {include [block] name [,] [params]}
	 */
	public function parseInclude(TagInfo $tag, Parser $parser): Nodes\IncludeBlockNode|Nodes\IncludeFileNode
	{
		$tag->extractModifier();
		$tag->validate(true);
		[$name, $mod] = $tag->tokenizer->fetchWordWithModifier(['block', 'file']);
		$tag->tokenizer->reset();
		return $mod === 'file' || (!$mod && $name && !preg_match('~#|[\w-]+$~DA', $name))
			? Nodes\IncludeFileNode::parse($tag)
			: Nodes\IncludeBlockNode::parse($tag, $parser);
	}


	/**
	 * n:class="..."
	 */
	public function parseNClass(TagInfo $tag): void
	{
		if ($tag->htmlElement->startTag->getAttribute('class')) {
			throw new CompileException('It is not possible to combine class with n:class.');
		}

		$tag->validate(true);
		$tag->htmlElement->startTag->attrs->append(new CallableNode(
			fn(Compiler $compiler) => $compiler->write(
				'echo ($ʟ_tmp = array_filter(%array)) ? \' class="\' . LR\Filters::escapeHtmlAttr(implode(" ", array_unique($ʟ_tmp))) . \'"\' : "" %line;',
				$tag->tokenizer,
				$tag->line,
			),
			'n:class',
		));
	}


	/**
	 * n:attr="..."
	 */
	public function parseNAttr(TagInfo $tag): void
	{
		$tag->validate(true);
		$tag->htmlElement->startTag->attrs->append(new CallableNode(
			fn(Compiler $compiler) => $compiler->write(
				'$ʟ_tmp = %array;
				echo Latte\Extensions\Filters::htmlAttributes(isset($ʟ_tmp[0]) && is_array($ʟ_tmp[0]) ? $ʟ_tmp[0] : $ʟ_tmp) %line;',
				$tag->tokenizer,
				$tag->line,
			),
			'n:attr',
		));
	}


	/**
	 * n:tag="..."
	 *
	 * @return \Generator<int, ?array, array{FragmentNode, ?TagInfo}, FragmentNode>
	 */
	public function parseNTag(TagInfo $tag): \Generator
	{
		$htmlEl = $tag->htmlElement;
		if (preg_match('(style$|script$)iA', $htmlEl->startTag->getName())) {
			throw new CompileException('Attribute n:tag is not allowed in <script> or <style>');
		}

		$tag->validate(true);
		$var = '$ʟ_tag[' . $this->counter++ . ']';
		$tagName = var_export($htmlEl->startTag->getName(), true);
		[$inner] = yield;

		$htmlEl->startTag->name = new CallableNode(
			fn(Compiler $compiler) => "$var = ({$tag->tokenizer->compile($compiler)}) ?? $tagName;"
				. "Latte\\Extensions\\Filters::checkTagSwitch($tagName, $var);"
				. "echo $var;",
			'n:tag',
		);

		if ($htmlEl->endTag) {
			$htmlEl->endTag->name = new CallableNode(fn(Compiler $compiler) => "echo $var;", 'n:tag');
		}

		return $inner;
	}
}
