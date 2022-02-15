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
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\NopNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\Parser;
use Latte\Compiler\TagInfo;
use Latte\Runtime\Template;


/**
 * {block [local] [name]}
 */
class BlockNode extends StatementNode
{
	public ?Block $block = null;
	public Node $content;
	public TagInfo $tag;
	public bool $extendsCheck;


	/** @return \Generator<int, ?array, array{FragmentNode, ?TagInfo}, self> */
	public static function parse(TagInfo $tag, Parser $parser): \Generator
	{
		$tag->extractModifier();
		[$name, $local] = $tag->tokenizer->fetchWordWithModifier('local');
		$tag->checkExtraArgs();
		$name = ltrim((string) $name, '#');
		$layer = $local ? Template::LAYER_LOCAL : null;

		$node = new self;
		$node->tag = $tag;
		if ($name !== '') {
			$node->extendsCheck = $parser->blocks[Template::LAYER_TOP] || count($parser->blocks) > 1 || $tag->parent;
			$node->block = $parser->addBlock($name, $layer, 'block');
			$tag->data->block = $node->block; // TODO: pro include
		}

		[$node->content] = $tag->empty
			? [new NopNode]
			: yield;

		if ($name === '') {
			if ($tag->modifiers === '') {
				return $node->content;
			}

			$tag->modifiers .= '|escape';
			return $node;
		}

		if (!Block::isDynamic($name) && !preg_match('#^[a-z]#iD', $name)) {
			throw new CompileException("Block name must start with letter a-z, '$name' given.");
		}

		return $node;
	}


	public function compile(Compiler $compiler): string
	{
		if (!$this->block) {
			return $this->compileFilter($compiler);

		} elseif (Block::isDynamic($this->block->name)) {
			return $this->compileDynamic($compiler);
		}

		return $this->compileStatic($compiler);
	}


	private function compileFilter(Compiler $compiler): string
	{
		return $compiler->write(
			<<<'XX'
				ob_start(fn() => '') %line;
				try {
					%raw
				} finally {
					$ʟ_fi = new LR\FilterInfo(%var);
					echo %modifyContent(ob_get_clean());
				}

				XX,
			$this->tag->tokenizer->modifier,
			$this->line,
			$this->content->compile($compiler),
			implode('', $compiler->getContext()),
		);
	}


	private function compileStatic(Compiler $compiler): string
	{
		$tag = $this->tag;

		$compiler->addBlock($this->block, $this->content, $tag, $this->getContext($compiler, $tag));

		$res = $compiler->write(
			($this->extendsCheck ? '' : 'if ($this->getParentName()) { return get_defined_vars(); } ')
			. '$this->renderBlock(%var, get_defined_vars()'
			. ($tag->modifiers ? $compiler->write(
				', function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }',
				$tag->tokenizer->modifier,
			) : '')
			. ') %line;',
			$this->block->name,
			$this->line,
		);
		return $res;
	}


	private function compileDynamic(Compiler $compiler): string
	{
		$tag = $this->tag;

		$compiler->addMethod(
			$method = $compiler->generateMethodName($this->block->name),
			'extract($ʟ_args); unset($ʟ_args);' . "\n\n" . $this->content->compile($compiler),
			'array $ʟ_args',
			'void',
			"{{$tag->name} {$tag->args}} on line {$tag->line}",
		);

		$context = $this->getContext($compiler, $tag);

		$res = $compiler->write(
			'$this->addBlock($ʟ_nm = %word, %var, [[$this, %var]], %var);
			$this->renderBlock($ʟ_nm, get_defined_vars()'
			. ($tag->modifiers ? $compiler->write(
				', function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }',
				$tag->tokenizer->modifier,
			) : '')
			. ');',
			$this->block->name,
			implode('', $context),
			$method,
			$this->block->layer,
		);
		return $res;
	}


	public function &getIterator(): \Generator
	{
		yield $this->content;
	}


	private function getContext(Compiler $compiler, TagInfo $tag): array
	{
		$context = $compiler->getContext();
		if (str_starts_with((string) $context[1], Compiler::CONTEXT_HTML_ATTRIBUTE)) {
			$context[1] = null;
			$tag->modifiers .= '|escape';
		} elseif ($tag->modifiers) {
			$tag->modifiers .= '|escape';
		}
		return $context;
	}
}
