<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Block;
use Latte\Compiler\Nodes\ExpressionNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PhpHelpers;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;
use Latte\Helpers;
use Latte\Runtime\Template;


/**
 * {include [block] name [from file] [, args]}
 */
class IncludeBlockNode extends StatementNode
{
	public string $name;
	public ?ExpressionNode $from = null;
	public ?ExpressionNode $args = null;
	public string $modifier;
	public int|string|null $layer;
	public bool $parent = false;

	/** @var Block[][] */
	public array $blocks;


	public static function create(Tag $tag, TemplateParser $parser): static
	{
		$tag->outputMode = $tag::OutputRemoveIndentation;

		$node = new static;
		[$node->name] = $tag->parser->fetchWordWithModifier(['block', '#']);

		if ($tag->parser->nextValue('from')) {
			$tag->parser->nextValue($tag->parser::T_WHITESPACE);
			$node->from = new ExpressionNode($tag->parser->fetchWord());
		}

		$node->args = $tag->parser->parseExpression();
		$node->modifier = $tag->parser->modifiers;

		$node->parent = $node->name === 'parent';
		if ($node->parent && $tag->parser->modifiers !== '') {
			throw new CompileException('Filters are not allowed in {include parent}', $tag->position);

		} elseif ($node->parent || $node->name === 'this') {
			$item = $tag->closestTag(['block', 'define'], fn($item) => isset($item->data->block) && $item->data->block->name !== '');
			if (!$item) {
				throw new CompileException("Cannot include $node->name block outside of any block.", $tag->position);
			}

			$node->name = $item->data->block->name;
		}

		$node->blocks = &$parser->blocks;
		$node->layer = $parser->blockLayer;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		$modifier = $this->modifier;
		$noEscape = Helpers::removeFilter($modifier, 'noescape');
		if ($modifier && !$noEscape) {
			$modifier .= '|escape';
		}
		$modArg = $modifier
			? $context->format(
				'function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }',
				$modifier,
			)
			: PhpHelpers::dump($noEscape || $this->parent ? null : $context->getEscaper()->export());

		return $this->from
			? $this->printBlockFrom($context, $modArg)
			: $this->printBlock($context, $modArg);
	}


	private function printBlock(PrintContext $context, string $modArg): string
	{
		$block = $this->blocks[$this->layer][$this->name] ?? $this->blocks[Template::LayerLocal][$this->name] ?? null;
		return $context->format(
			'$this->renderBlock' . ($this->parent ? 'Parent' : '')
			. '(' . (Helpers::isNameDynamic($this->name) ? '%word' : '%dump') . ', '
			. '%array? + '
			. ($block && !$block->parameters ? 'get_defined_vars()' : '[]')
			. '%raw) %line;',
			$this->name,
			$this->args,
			$modArg === 'null' ? '' : ", $modArg",
			$this->position,
		);
	}


	private function printBlockFrom(PrintContext $context, string $modArg): string
	{
		return $context->format(
			'$this->createTemplate(%word, %array? + $this->params, "include")->renderToContentType(%raw, %word) %line;',
			$this->from,
			$this->args,
			$modArg,
			$this->name,
			$this->position,
		);
	}


	public function &getIterator(): \Generator
	{
		if ($this->from) {
			yield $this->from;
		}
		if ($this->args) {
			yield $this->args;
		}
	}
}
