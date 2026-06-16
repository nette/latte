<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Block;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\Php\Expression\ArrayNode;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\ModifierNode;
use Latte\Compiler\Nodes\Php\Scalar\StringNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\Nodes\TextNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;
use Latte\Compiler\Token;
use function array_pop, array_shift, count, end, preg_match, trim;


/**
 * {embed 'file.latte'|#block} ... {/embed}
 */
class EmbedNode extends StatementNode
{
	public ExpressionNode $name;
	public string $mode;
	public ArrayNode $args;
	public FragmentNode $blocks;
	public int|string|null $layer;


	/** @return \Generator<int, ?list<string>, array{FragmentNode, ?Tag}, static> */
	public static function create(Tag $tag, TemplateParser $parser): \Generator
	{
		if ($tag->isNAttribute()) {
			throw new CompileException('Attribute n:embed is not supported.', $tag->position);
		}

		$tag->outputMode = $tag::OutputRemoveIndentation;
		$tag->expectArguments();

		$node = $tag->node = new static;
		$mode = $tag->parser->tryConsumeTokenBeforeUnquotedString('block', 'file')?->text;
		$node->name = $tag->parser->parseUnquotedStringOrExpression();
		$node->mode = $mode ?? ($node->name instanceof StringNode && preg_match('~[\w-]+$~DA', $node->name->value) ? 'block' : 'file');
		$tag->parser->stream->tryConsume(',');
		$node->args = $tag->parser->parseArguments();

		$prevIndex = $parser->blockLayer;
		$parser->blockLayer = $node->layer = count($parser->blocks);
		$parser->blocks[$parser->blockLayer] = [];
		[$node->blocks] = yield;

		// Content not wrapped in a {block} becomes the implicit {block default} block.
		$kept = $default = [];
		foreach ($node->blocks->children as $child) {
			if ($child instanceof ImportNode || $child instanceof BlockNode) {
				$kept[] = $child;
			} else {
				$default[] = $child;
			}
		}

		// ignore whitespace-only text surrounding the blocks, but keep it in between
		while ($default && $default[0] instanceof TextNode && trim($default[0]->content) === '') {
			array_shift($default);
		}
		while ($default && ($last = end($default)) instanceof TextNode && trim($last->content) === '') {
			array_pop($default);
		}

		if ($default) {
			if (isset($parser->blocks[$node->layer]['default'])) {
				throw new CompileException(
					'Cannot combine loose content with an explicit {block default} inside {embed}; both define the default block.',
					$default[0]->position ?? $tag->position,
				);
			}

			$blockTag = new Tag('block', [new Token(Token::End, '', $tag->position)], $tag->position, prefix: $tag->prefix);
			$block = new Block(new StringNode('default'), $node->layer, $blockTag);
			$parser->checkBlockIsUnique($block);
			$blockNode = new BlockNode;
			$blockNode->block = $block;
			$blockNode->modifier = new ModifierNode([], position: $tag->position);
			$blockNode->content = new FragmentNode($default);
			$blockNode->position = $tag->position;
			$kept[] = $blockNode;
			$node->blocks->children = $kept;
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
						$this->createTemplate(%raw, %node, "embed")->renderToContentType(%dump) %1.line;
					} finally {
						$this->leaveBlockLayer();
					}

					XX,
				$this->layer,
				$this->position,
				$imports,
				$context->ensureString($this->name, 'Template name'),
				$this->args,
				$context->getEscaper()->export(),
			)
			: $context->format(
				<<<'XX'
					$this->enterBlockLayer(%dump, get_defined_vars()) %line; %raw
					$this->copyBlockLayer();
					try {
						$this->renderBlock(%raw, %node, %dump) %1.line;
					} finally {
						$this->leaveBlockLayer();
					}

					XX,
				$this->layer,
				$this->position,
				$imports,
				$context->ensureString($this->name, 'Block name'),
				$this->args,
				$context->getEscaper()->export(),
			);
	}


	public function &getIterator(): \Generator
	{
		yield $this->name;
		yield $this->args;
		yield $this->blocks;
	}
}
