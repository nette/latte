<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Block;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;
use Latte\Context;
use Latte\Runtime\Template;


/**
 * {block [local] [name]}
 */
class BlockNode extends StatementNode
{
	public ?Block $block = null;
	public string $modifier;
	public AreaNode $content;


	/** @return \Generator<int, ?array, array{AreaNode, ?Tag}, static|AreaNode> */
	public static function create(Tag $tag, TemplateParser $parser): \Generator
	{
		$tag->outputMode = $tag::OutputRemoveIndentation;
		$tag->extractModifier();

		[$name, $local] = $tag->tokenizer->fetchWordWithModifier('local');
		if ($token = $tag->tokenizer->nextValue()) {
			throw new CompileException("Unexpected arguments '$token' in " . $tag->getNotation(), $tag->position);
		}
		$name = ltrim((string) $name, '#');
		$node = new static;

		if ($name !== '') {
			$layer = $local ? Template::LayerLocal : $parser->blockLayer;
			$node->block = new Block($name, $layer, $tag);

			if (!$node->block->isDynamic()) {
				$parser->checkBlockIsUnique($node->block);
				$tag->data->block = $node->block; // for {include}
			}
		}

		$node->modifier = $tag->modifiers;
		[$node->content] = yield;

		if ($name === '' && $node->modifier === '') {
			return $node->content;
		}

		return $node;
	}


	public function print(PrintContext $context): string
	{
		if (!$this->block) {
			return $this->printFilter($context);

		} elseif ($this->block->isDynamic()) {
			return $this->printDynamic($context);
		}

		return $this->printStatic($context);
	}


	private function printFilter(PrintContext $context): string
	{
		return $context->format(
			<<<'XX'
				ob_start(fn() => '') %line;
				try {
					%raw
				} finally {
					$ʟ_fi = new LR\FilterInfo(%dump);
					echo %modifyContent(ob_get_clean());
				}

				XX,
			$this->modifier . '|escape',
			$this->position,
			$this->content,
			implode('', $context->getEscapingContext()),
		);
	}


	private function printStatic(PrintContext $context): string
	{
		$context->addBlock($this->block, $this->adjustContext($context->getEscapingContext()));
		$this->block->content = $this->content->print($context); // must be compiled after is added

		return $context->format(
			'$this->renderBlock(%dump, get_defined_vars()'
			. ($this->modifier
				? $context->format(
					', function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }',
					$this->modifier,
				)
				: '')
			. ') %line;',
			$this->block->name,
			$this->position,
		);
	}


	private function printDynamic(PrintContext $context): string
	{
		$context->addBlock($this->block);
		$this->block->content = $this->content->print($context); // must be compiled after is added
		$escapingContext = $this->adjustContext($context->getEscapingContext());

		return $context->format(
			'$this->addBlock($ʟ_nm = %word, %dump, [[$this, %dump]], %dump);
			$this->renderBlock($ʟ_nm, get_defined_vars()'
			. ($this->modifier
				? $context->format(
					', function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }',
					$this->modifier,
				)
				: '')
			. ');',
			$this->block->name,
			implode('', $escapingContext),
			$this->block->method,
			$this->block->layer,
		);
	}


	private function adjustContext(array $context): array
	{
		if (str_starts_with((string) $context[1], Context::HtmlAttribute)) {
			$context[1] = null;
			$this->modifier .= '|escape';
		} elseif ($this->modifier) {
			$this->modifier .= '|escape';
		}
		return $context;
	}


	public function &getIterator(): \Generator
	{
		yield $this->content;
	}
}
