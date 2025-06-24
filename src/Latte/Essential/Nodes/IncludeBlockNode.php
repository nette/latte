<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Block;
use Latte\Compiler\Nodes\Php\Expression\ArrayNode;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\ModifierNode;
use Latte\Compiler\Nodes\Php\Scalar;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PhpHelpers;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;
use Latte\Runtime\Template;
use function count;


/**
 * {include [block] name [from file] [, args]}
 */
class IncludeBlockNode extends StatementNode
{
	public ExpressionNode $name;
	public ?ExpressionNode $from = null;
	public ArrayNode $args;
	public ModifierNode $modifier;
	public int|string|null $layer;
	public bool $parent = false;

	/** @var Block[][] */
	public array $blocks;


	public static function create(Tag $tag, TemplateParser $parser): static
	{
		$tag->outputMode = $tag::OutputRemoveIndentation;

		$tag->expectArguments();
		$node = new static;
		$stream = $tag->parser->stream;
		$tag->parser->tryConsumeTokenBeforeUnquotedString('block') ?? $stream->tryConsume('#');
		$node->name = $tag->parser->parseUnquotedStringOrExpression();
		$tokenName = $stream->peek(-1);

		if ($stream->tryConsume('from')) {
			$node->from = $tag->parser->parseUnquotedStringOrExpression();
			$stream->tryConsume(',');
		}

		$stream->tryConsume(',');
		$node->args = $tag->parser->parseArguments();
		$node->modifier = $tag->parser->parseModifier();
		$node->modifier->escape = (bool) $node->modifier->filters;

		$node->parent = $tokenName->is('parent');
		if ($node->parent && $node->modifier->filters) {
			throw new CompileException('Filters are not allowed in {include parent}', $tag->position);

		} elseif ($node->parent || $tokenName->is('this')) {
			$item = $tag->closestTag(
				[BlockNode::class, DefineNode::class],
				fn($item) => $item->node?->block && !$item->node->block->isDynamic() && $item->node->block->name !== '',
			);
			if (!$item) {
				throw new CompileException("Cannot include $tokenName->text block outside of any block.", $tag->position);
			}

			$node->name = $item->node->block->name;
		}

		$node->blocks = &$parser->blocks;
		$node->layer = $parser->blockLayer;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		$noEscape = $this->modifier->hasFilter('noescape');
		$contentFilter = count($this->modifier->filters) > (int) $noEscape
			? $context->format(
				'function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }',
				$this->modifier,
			)
			: ($noEscape || $this->parent ? '' : PhpHelpers::dump($context->getEscaper()->export()));

		return $this->from
			? $this->printBlockFrom($context, $contentFilter)
			: $this->printBlock($context, $contentFilter);
	}


	private function printBlock(PrintContext $context, string $contentFilter): string
	{
		if ($this->name instanceof Scalar\StringNode || $this->name instanceof Scalar\IntegerNode) {
			$staticName = (string) $this->name->value;
			$block = $this->blocks[$this->layer][$staticName] ?? $this->blocks[Template::LayerLocal][$staticName] ?? null;
		}

		return $context->format(
			'$this->render' . ($this->parent ? 'ParentBlock' : 'Block')
			. '(%raw, %node? + '
			. (isset($block) && !$block->parameters ? 'get_defined_vars()' : '[]')
			. '%raw) %line;',
			$context->ensureString($this->name, 'Block name'),
			$this->args,
			$contentFilter ? ", $contentFilter" : '',
			$this->position,
		);
	}


	private function printBlockFrom(PrintContext $context, string $contentFilter): string
	{
		return $context->format(
			'$this->createTemplate(%raw, %node? + $this->params, "include")->renderToContentType(%raw, %raw) %line;',
			$context->ensureString($this->from, 'Template name'),
			$this->args,
			$contentFilter,
			$context->ensureString($this->name, 'Block name'),
			$this->position,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->name;
		if ($this->from) {
			yield $this->from;
		}
		yield $this->args;
		yield $this->modifier;
	}
}
