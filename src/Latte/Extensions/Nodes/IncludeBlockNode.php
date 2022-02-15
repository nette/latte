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
use Latte\Compiler\Nodes\ExpressionNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\Parser;
use Latte\Compiler\PhpHelpers;
use Latte\Compiler\TagInfo;
use Latte\Helpers;
use Latte\Runtime\Template;


/**
 * {include [block] name [,] [params]}
 */
class IncludeBlockNode extends StatementNode
{
	public ExpressionNode $name;
	public string $nameString;
	public int|string|null $layer;
	public ?ExpressionNode $from = null;
	public bool $parent = false;
	public bool $noEscape = false;
	public array $blocks;


	public static function parse(TagInfo $tag, Parser $parser): self
	{
		[$name] = $tag->tokenizer->fetchWordWithModifier(['block']);
		$name = ltrim($name, '#');

		if ($name === 'parent' && $tag->modifiers !== '') {
			throw new CompileException('Filters are not allowed in {include parent}');
		}

		$node = new self;
		$node->nameString = $name;
		$node->name = $tag->tokenizer;

		$node->noEscape = Helpers::removeFilter($tag->modifiers, 'noescape');
		if ($tag->modifiers && !$node->noEscape) {
			$tag->modifiers .= '|escape';
		}

		if ($tag->tokenizer->nextValue('from')) {
			$tag->tokenizer->nextValue($tag->tokenizer::T_WHITESPACE);
			$node->from = $tag->tokenizer;
			return $node;
		}

		$node->parent = $name === 'parent';
		if ($name === 'parent' || $name === 'this') {
			$item = $tag->closest(['block', 'define'], fn($item) => isset($item->data->block) && $item->data->block->name !== '');
			if (!$item) {
				throw new CompileException("Cannot include $name block outside of any block.");
			}

			$node->nameString = $item->data->block->name;
		}

		$node->blocks = &$parser->blocks;
		$node->layer = $parser->layer;
		return $node;
	}


	public function compile(Compiler $compiler): string
	{
		return $this->from
			? $this->compileFrom($compiler)
			: $this->compileCommon($compiler);
	}


	private function compileCommon(Compiler $compiler): string
	{
		// TODO '$iterator'; // to fool CoreMacros::macroEndForeach
		$block = $this->blocks[$this->layer][$this->nameString] ?? $this->blocks[Template::LAYER_LOCAL][$this->nameString] ?? null;
		return $compiler->write(
			'$this->renderBlock' . ($this->parent ? 'Parent' : '')
			. '(' . (Block::isDynamic($this->nameString) ? '%word' : '%var') . ', ' // isDynamic
			. '%array? + '
			. ($block && !$block->parameters ? 'get_defined_vars()' : '[]')
			. '%raw) %line;',
			$this->nameString,
			$this->name,
			$this->name->modifier
				? $compiler->write(
					', function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }',
					$this->name->modifier,
				)
				: ($this->noEscape || $this->parent ? '' : ', ' . PhpHelpers::dump(implode('', $compiler->getContext()))),
			$this->line,
		);
	}


	private function compileFrom(Compiler $compiler): string
	{
		return $compiler->write(
			'$this->createTemplate(%word, %array? + $this->params, "include")->renderToContentType(%raw, %word) %line;',
			$this->name->fetchWord(),
			$this->name,
			$this->name->modifier
				? $compiler->write(
					'function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }',
					$this->name->modifier,
				)
				: PhpHelpers::dump($this->noEscape ? null : implode('', $compiler->getContext())),
			$this->nameString,
			$this->line,
		);
	}
}
