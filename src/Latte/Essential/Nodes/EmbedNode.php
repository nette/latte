<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\ExpressionNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\Nodes\TextNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;
use Latte\Helpers;


/**
 * {embed [block|file] name [,] [params]}
 */
class EmbedNode extends StatementNode
{
	public string $name;
	public string $mode;
	public ExpressionNode $args;
	public FragmentNode $blocks;
	public int|string|null $layer;


	/** @return \Generator<int, ?array, array{AreaNode, ?Tag}, static> */
	public static function create(Tag $tag, TemplateParser $parser): \Generator
	{
		$tag->outputMode = $tag::OutputRemoveIndentation;
		$tag->expectArguments();

		$node = new static;
		[$node->name, $mode] = $tag->parser->fetchWordWithModifier(['block', 'file']);
		$node->mode = $mode ?? (preg_match('~^[\w-]+$~DA', $node->name) ? 'block' : 'file');
		$node->args = $tag->parser->parseExpression();

		$prevIndex = $parser->blockLayer;
		$parser->blockLayer = $node->layer = count($parser->blocks);
		$parser->blocks[$parser->blockLayer] = [];
		[$node->blocks] = yield;

		foreach ($node->blocks->children as $child) {
			if (!$child instanceof ImportNode && !$child instanceof BlockNode && !$child instanceof TextNode) {
				throw new CompileException('Unexpected content inside {embed} tags.', $child->position);
			}
		}

		$parser->blockLayer = $prevIndex;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		$imports = '';
		foreach ($this->blocks->children as $child) {
			if ($child instanceof ImportNode) {
				$imports .= $child->print($context);
			} else {
				$child->print($context);
			}
		}

		return $this->mode === 'file'
			? $context->format(
				<<<'XX'
					$this->enterBlockLayer(%dump, get_defined_vars()) %line; %raw
					try {
						$this->createTemplate(%word, %array, "embed")->renderToContentType(%dump) %1.line;
					} finally {
						$this->leaveBlockLayer();
					}

					XX,
				$this->layer,
				$this->position,
				$imports,
				$this->name,
				$this->args,
				$context->getEscaper()->export(),
			)
			: $context->format(
				<<<'XX'
					$this->enterBlockLayer(%dump, get_defined_vars()) %line; %raw
					$this->copyBlockLayer();
					try {
						$this->renderBlock(%raw, %array, %dump) %1.line;
					} finally {
						$this->leaveBlockLayer();
					}

					XX,
				$this->layer,
				$this->position,
				$imports,
				$context->format(Helpers::isNameDynamic($this->name) ? '%word' : '%dump', $this->name),
				$this->args,
				$context->getEscaper()->export(),
			);
	}


	public function &getIterator(): \Generator
	{
		yield $this->blocks;
	}
}
