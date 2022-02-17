<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Extensions\Nodes;

use Latte\CompileException;
use Latte\Compiler\Block;
use Latte\Compiler\Compiler;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\CallableNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\Html\ElementNode;
use Latte\Compiler\Nodes\NopNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\Parser;
use Latte\Compiler\TagInfo;
use Latte\Runtime\SnippetDriver;
use Latte\Runtime\Template;


/**
 * {snippet [name]}
 */
class SnippetNode extends StatementNode
{
	public static string $snippetAttribute = 'id';
	public string $name;
	public ?Block $block = null;
	public Node $content;
	public TagInfo $tag;
	public ?ElementNode $htmlEl;


	/** @return \Generator<int, ?array, array{FragmentNode, ?TagInfo}, self> */
	public static function parse(TagInfo $tag, Parser $parser): \Generator
	{
		$tag->validate(null);
		$name = (string) $tag->tokenizer->fetchWord();
		$tag->checkExtraArgs();
		$node = new self;
		$node->tag = $tag;
		$node->name = $name;
		$node->htmlEl = $htmlEl = $tag->htmlElement;

		if (!Block::isDynamic($name)) {
			if ($name !== '' && !preg_match('#^[a-z]#iD', $name)) {
				throw new CompileException("Snippet name must start with letter a-z, '$name' given.");
			}

			$node->block = $parser->addBlock($name, Template::LAYER_SNIPPET, 'snippet');
		}

		if ($tag->prefix) {
			if ($tag->prefix !== $tag::PREFIX_NONE) {
				throw new CompileException("Use n:snippet instead of {$tag->getNotation()}");

			} elseif ($htmlEl->startTag->getAttribute(self::$snippetAttribute)) {
				throw new CompileException('Cannot combine HTML attribute ' . self::$snippetAttribute . ' with n:snippet.');

			} elseif (isset($htmlEl->nAttrs['ifcontent'])) {
				throw new CompileException('Cannot combine n:ifcontent with n:snippet.');

			} elseif (isset($htmlEl->nAttrs['foreach'])) {
				throw new CompileException('Combination of n:snippet with n:foreach is invalid, use n:inner-foreach.');
			}

			$htmlEl->startTag->attrs->append(new CallableNode(
				fn(Compiler $compiler) => $compiler->write(
					<<<'XX'
						echo ' %raw="', htmlspecialchars($this->global->snippetDriver->getHtmlId($ʟ_nm = %var)), '"';
						XX,
					self::$snippetAttribute,
					$name,
				),
				'n:snippet',
			));
		}

		[$node->content] = $tag->empty
			? [new NopNode]
			: yield;
		return $node;
	}


	public function compile(Compiler $compiler): string
	{
		if ($this->tag->prefix) {
			return $this->block
				? $this->compileStaticAttr($compiler)
				: $this->compileDynamicAttr($compiler);
		} else {
			return $this->block
				? $this->compileStatic($compiler)
				: $this->compileDynamic($compiler);
		}
	}


	private function compileStatic(Compiler $compiler): string
	{
		$name = $compiler->write('%word', $this->name);

		$compiler->addBlock(
			$this->block,
			new CallableNode(fn() => $this->compileSnippet($compiler, $this->content, SnippetDriver::TYPE_STATIC, $name)),
			$this->tag,
		);

		return $this->compileHtmlElement(
			$compiler,
			$compiler->write(
				'$this->renderBlock(%var, [], null, %var) %line;',
				$this->block->name,
				Template::LAYER_SNIPPET,
				$this->line,
			),
			$compiler->write('%var', $this->block->name),
			$this->tag,
		);
	}


	private function compileStaticAttr(Compiler $compiler): string
	{
		$name = $compiler->write('%word', $this->name);

		$compiler->addBlock(
			$this->block,
			new CallableNode(fn() => $this->compileSnippet($compiler, $this->htmlEl->content, SnippetDriver::TYPE_STATIC, $name)),
			$this->tag,
		);

		$snippetContent = $compiler->write(
			'$this->renderBlock(%var, [], null, %var);',
			$this->name,
			Template::LAYER_SNIPPET,
		);
		try {
			$saved = $this->htmlEl->content;
			$this->htmlEl->content = new CallableNode(fn() => $snippetContent);
			return $this->htmlEl->compile($compiler);
		} finally {
			$this->htmlEl->content = $saved;
		}
	}


	private function compileDynamic(Compiler $compiler): string
	{
		return $this->compileHtmlElement(
			$compiler,
			$this->compileSnippet($compiler, $this->content, SnippetDriver::TYPE_DYNAMIC, '$ʟ_nm'),
			$compiler->write('$ʟ_nm = %word', $this->name),
			$this->tag,
		);
	}


	private function compileDynamicAttr(Compiler $compiler): string
	{
		$snippetContent = $this->compileSnippet($compiler, $this->htmlEl->content, SnippetDriver::TYPE_DYNAMIC, '$ʟ_nm');
		try {
			$saved = $this->htmlEl->content;
			$this->htmlEl->content = new CallableNode(fn() => $snippetContent);
			return $this->htmlEl->compile($compiler);
		} finally {
			$this->htmlEl->content = $saved;
		}
	}


	private function compileSnippet(
		Compiler $compiler,
		Node $content,
		string $type,
		string $name,
	): string {
		return $compiler->write(
			<<<'XX'
				$this->global->snippetDriver->enter(%raw, %var) %line;
				try {
					%raw
				} finally {
					$this->global->snippetDriver->leave();
				}

				XX,
			$name,
			$type,
			$this->line,
			$content->compile($compiler),
		);
	}


	private function compileHtmlElement(Compiler $compiler, string $content, string $name, TagInfo $tag): string
	{
		return $compiler->write(
			<<<'XX'
				echo '<div %raw="', htmlspecialchars($this->global->snippetDriver->getHtmlId(%raw)), '">';
				%raw
				echo '</div>';
				XX,
			self::$snippetAttribute,
			$name,
			$content,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->content;
	}
}
