<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Escaper;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\ModifierNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\ContentType;


/**
 * {capture $var} ... {/capture}
 * Captures block output into variable.
 */
class CaptureNode extends StatementNode
{
	public ExpressionNode $variable;
	public ModifierNode $modifier;
	public AreaNode $content;


	/** @return \Generator<int, ?list<string>, array{AreaNode, ?Tag}, static> */
	public static function create(Tag $tag): \Generator
	{
		$tag->expectArguments();
		$variable = $tag->parser->parseExpression();
		if (!$variable->isWritable()) {
			$text = '';
			$i = 0;
			while ($token = $tag->parser->stream->tryPeek(--$i)) {
				$text = $token->text . $text;
			}

			throw new CompileException("It is not possible to write into '$text' in " . $tag->getNotation(), $tag->position);
		}
		$node = $tag->node = new static;
		$node->variable = $variable;
		$node->modifier = $tag->parser->parseModifier();
		[$node->content] = yield;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		$escaper = $context->getEscaper();
		$inHtmlText = $escaper->getState() === Escaper::HtmlText;
		$res = $context->format(
			<<<'XX'
				ob_start(fn() => '') %line;
				try {
					%node
				} finally {
					$ʟ_tmp = %raw;
				}
				$ʟ_fi = new LR\FilterInfo(%dump); %node = %modifyContent($ʟ_tmp);

				XX,
			$this->position,
			$this->content,
			$inHtmlText
				? 'ob_get_length() ? new LR\Html(ob_get_clean()) : ob_get_clean()'
				: 'ob_get_clean()',
			$escaper->export(),
			$this->variable,
			$this->modifier,
		);

		if ($inHtmlText && $this->modifier->filters) {
			// filters returned a plain string, wrap it back unless they changed the content type,
			// empty string stays plain like in the unfiltered branch
			$res .= $context->format(
				<<<'XX'
					if ($ʟ_fi->contentType === %dump && %node !== '') %node = new LR\Html(%node);

					XX,
				ContentType::Html,
				$this->variable,
				$this->variable,
				$this->variable,
			);
		}

		return $res . "\n";
	}


	public function &getIterator(): \Generator
	{
		yield $this->variable;
		yield $this->modifier;
		yield $this->content;
	}
}
