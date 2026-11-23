<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Essential\Nodes;

use Latte\Compiler\{NodeHelpers, PhpHelpers, PrintContext, Tag};
use Latte\Compiler\Nodes\Php\Expression\ArrayNode;
use Latte\Compiler\Nodes\Php\{ExpressionNode, ModifierNode};
use Latte\Compiler\Nodes\StatementNode;


/**
 * {include 'file.latte' [with blocks]}
 */
class IncludeFileNode extends StatementNode
{
	public ExpressionNode $file;
	public ArrayNode $args;
	public ModifierNode $modifier;
	public string $mode;


	public static function create(Tag $tag): static
	{
		$tag->outputMode = $tag::OutputRemoveIndentation;

		$tag->expectArguments();
		$node = new static;
		$tag->parser->tryConsumeTokenBeforeUnquotedString('file');
		$node->file = $tag->parser->parseUnquotedStringOrExpression();
		$node->mode = 'include';

		$stream = $tag->parser->stream;
		if ($stream->tryConsume('with')) {
			$stream->consume('blocks');
			$node->mode = 'includeblock';
		}

		$stream->tryConsume(',');
		$node->args = $tag->parser->parseArguments();
		NodeHelpers::checkArgumentOrder($node->args);
		$node->modifier = $tag->parser->parseModifier();
		$node->modifier->escape = !$node->modifier->removeFilter('noescape');
		return $node;
	}


	public function print(PrintContext $context): string
	{
		$escaper = $context->getEscaper();
		$content = $this->modifier->filters ? $context->format('%modifyContent($s)', $this->modifier) : '$s';
		$content = $this->modifier->escape ? $content : $escaper->escapeMandatory($content, $this->position);
		return $context->format(
			'$this->createTemplate(%raw, %node? + $this->params, %dump)->renderToContentType(%raw) %line;',
			$context->ensureString($this->file, 'Template name'),
			$this->args,
			$this->mode,
			$content !== '$s'
				? $context->format('function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %raw; }', $content)
				: PhpHelpers::dump($this->modifier->escape ? $escaper->export() : null),
			$this->position,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->file;
		yield $this->args;
		yield $this->modifier;
	}
}
