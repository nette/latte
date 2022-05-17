<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Block;
use Latte\Compiler\Escaper;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;
use Latte\Runtime\Template;


/**
 * {block [local] [name]}
 */
class BlockNode extends StatementNode
{
	public ?Block $block = null;
	public string $modifier;
	public AreaNode $content;
	public bool $extendsCheck;


	/** @return \Generator<int, ?array, array{AreaNode, ?Tag}, static|AreaNode> */
	public static function create(Tag $tag, TemplateParser $parser): \Generator
	{
		$tag->outputMode = $tag::OutputRemoveIndentation;
		$tag->extractModifier();

		[$name, $local] = $tag->parser->fetchWordWithModifier('local');
		if ($token = $tag->parser->nextValue()) {
			throw new CompileException("Unexpected arguments '$token' in " . $tag->getNotation(), $tag->position);
		}
		$name = ltrim((string) $name, '#');
		$node = new static;

		if ($name !== '') {
			$layer = $local ? Template::LayerLocal : $parser->blockLayer;
			$node->block = new Block($name, $layer, $tag);

			if (!$node->block->isDynamic()) {
				$node->extendsCheck = $parser->blocks[Template::LayerTop] || count($parser->blocks) > 1 || $tag->parent;
				$parser->checkBlockIsUnique($node->block);
				$tag->data->block = $node->block; // for {include}
			}
		}

		$node->modifier = $tag->parser->modifiers;
		if ($node->modifier) {
			$node->modifier .= '|escape';
		}

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
					%node
				} finally {
					$ʟ_fi = new LR\FilterInfo(%dump);
					echo %modifyContent(ob_get_clean());
				}

				XX,
			$this->position,
			$this->content,
			$context->getEscaper()->export(),
			$this->modifier,
		);
	}


	private function printStatic(PrintContext $context): string
	{
		$context->addBlock($this->block, $this->adjustContext($context->getEscaper()));
		$this->block->content = $this->content->print($context); // must be compiled after is added

		return $context->format(
			($this->extendsCheck ? '' : 'if ($this->getParentName()) { return get_defined_vars(); } ')
			. '$this->renderBlock(%dump, get_defined_vars()'
			. ($this->modifier
				? ', function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }'
				: '')
			. ') %2.line;',
			$this->block->name,
			$this->modifier,
			$this->position,
		);
	}


	private function printDynamic(PrintContext $context): string
	{
		$context->addBlock($this->block);
		$this->block->content = $this->content->print($context); // must be compiled after is added
		$escaper = $this->adjustContext($context->getEscaper());

		return $context->format(
			'$this->addBlock($ʟ_nm = %word, %dump, [[$this, %dump]], %dump);
			$this->renderBlock($ʟ_nm, get_defined_vars()'
			. ($this->modifier
				? ', function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }'
				: '')
			. ');',
			$this->block->name,
			$escaper->export(),
			$this->block->method,
			$this->block->layer,
			$this->modifier,
		);
	}


	private function adjustContext(Escaper $escaper): Escaper
	{
		if (in_array($escaper->getState(), [Escaper::HtmlAttribute, Escaper::HtmlAttributeUrl/*...*/], true)) {
			$escaper = new Escaper($escaper->getContentType());
			$this->modifier .= '|escape';
		}
		return $escaper;
	}


	public function &getIterator(): \Generator
	{
		yield $this->content;
	}
}
