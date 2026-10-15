<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Essential\Nodes;

use Latte\Compiler\{NodeHelpers, PrintContext, Tag};
use Latte\Compiler\Nodes\{AreaNode, NopNode, Php, StatementNode, TextNode};
use Latte\Compiler\Nodes\Php\ModifierNode;
use Latte\Essential\TranslatorExtension;
use function is_array, is_string;


/**
 * {translate} ... {/translate}
 * Translates block content.
 */
class TranslateNode extends StatementNode
{
	public AreaNode $content;
	public ModifierNode $modifier;


	/** @return \Generator<int, ?list<string>, array{AreaNode, ?Tag}, static|NopNode> */
	public static function create(Tag $tag, ?callable $translator): \Generator
	{
		$tag->outputMode = $tag::OutputKeepIndentation;

		$node = $tag->node = new static;
		$args = $tag->parser->parseArguments();
		$node->modifier = $tag->parser->parseModifier();
		$node->modifier->escape = !$node->modifier->removeFilter('noescape');
		if ($tag->void) {
			return new NopNode;
		}

		[$node->content] = yield;

		if ($text = NodeHelpers::toText($node->content)) {
			if ($translator
				&& is_array($values = TranslatorExtension::toValue($args))
				&& is_string($translation = $translator($text, ...$values))
			) {
				$node->content = new TextNode($translation);
				return $node;
			}
			$node->content = new TextNode($text);
		}

		array_unshift($node->modifier->filters, new Php\FilterNode(new Php\IdentifierNode('translate'), $args->toArguments()));

		return $node;
	}


	public function print(PrintContext $context): string
	{
		return $this->content instanceof TextNode
			? $context->format(
				<<<'XX'
					$ʟ_fi = new LR\FilterInfo(%dump);
					echo %modifyContent(%dump) %line;
					XX,
				$context->getEscaper()->export(),
				$this->modifier,
				$this->content->content,
				$this->position,
			)
			: $context->format(
				<<<'XX'
					ob_start(fn() => ''); try {
						%node
					} finally {
						$ʟ_tmp = ob_get_clean();
					}
					$ʟ_fi = new LR\FilterInfo(%dump);
					echo %modifyContent($ʟ_tmp) %line;
					XX,
				$this->content,
				$context->getEscaper()->export(),
				$this->modifier,
				$this->position,
			);
	}


	public function &getIterator(): \Generator
	{
		yield $this->content;
		yield $this->modifier;
	}
}
