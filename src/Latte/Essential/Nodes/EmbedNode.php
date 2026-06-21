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
use function array_pop, array_shift, count, end, preg_match;


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
		$layer = $parser->blockLayer = $node->layer = count($parser->blocks);
		$parser->blocks[$parser->blockLayer] = [];

		// push the implicit {block default} onto the tag stack so {include parent/this} in loose content resolve to it
		$defaultBlock = $node->createDefaultBlock($layer, $tag);
		$parser->pushTag($defaultBlock->block->tag);
		[$node->blocks] = yield;
		$parser->popTag();

		// Content not wrapped in a {block} becomes the implicit {block default} block.
		$kept = $loose = [];
		foreach ($node->blocks->children as $child) {
			$child instanceof ImportNode || $child instanceof BlockNode
				? $kept[] = $child
				: $loose[] = $child;
		}

		// ignore whitespace-only text surrounding the blocks, but keep it in between
		while ($loose && $loose[0] instanceof TextNode && $loose[0]->isWhitespace()) {
			array_shift($loose);
		}
		while ($loose && ($last = end($loose)) instanceof TextNode && $last->isWhitespace()) {
			array_pop($loose);
		}

		if ($loose) {
			if (isset($parser->blocks[$layer]['default'])) {
				throw new CompileException(
					'Cannot combine loose content with an explicit {block default} inside {embed}; both define the default block.',
					$loose[0]->position ?? $tag->position,
				);
			}

			$parser->blocks[$layer]['default'] = $defaultBlock->block;
			$defaultBlock->content = new FragmentNode($loose);
			$kept[] = $defaultBlock;
			$node->blocks->children = $kept;
		}

		$parser->blockLayer = $prevIndex;
		return $node;
	}


	/**
	 * Creates the implicit {block default} that wraps loose content.
	 */
	private function createDefaultBlock(int|string $layer, Tag $tag): BlockNode
	{
		// tag name 'block' is load-bearing: TemplateGenerator uses it to give the block access to the caller's variables
		$blockTag = new Tag('block', [new Token(Token::End, '', $tag->position)], $tag->position, prefix: $tag->prefix);
		$node = $blockTag->node = new BlockNode;
		$node->block = new Block(new StringNode('default'), $layer, $blockTag);
		$node->modifier = new ModifierNode([], position: $tag->position);
		$node->position = $tag->position;
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
