<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Html;

use Latte\Compiler\NodeHelpers;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\PrintNode;
use Latte\Compiler\Nodes\TextNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;
use Latte\ContentType;


class AttributeNode extends AreaNode
{
	public function __construct(
		public AreaNode $name,
		public ?AreaNode $value = null,
		public ?string $quote = null,
		public ?Position $position = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		$res = $this->name->print($context);
		if (!$this->value) {
			return $res;

		} elseif (
			$this->name instanceof TextNode
			&& $this->value instanceof PrintNode
			&& !$this->value->modifier->filters
		) {
			return $context->format(
				'echo LR\%raw::formatAttribute(%dump, %node) %line;',
				$context->getEscaper()->getContentType() === ContentType::Html ? 'HtmlHelpers' : 'XmlHelpers',
				$this->name->content,
				$this->value->expression,
				$this->position,
			);
		}

		$res .= "echo '=';";
		$quote = $this->quote ?? ($this->value instanceof TextNode ? null : '"');
		$res .= $quote ? 'echo ' . var_export($quote, true) . ';' : '';

		$escaper = $context->beginEscape();
		$escaper->enterHtmlAttribute(NodeHelpers::toText($this->name));
		$res .= $this->value->print($context);
		$context->restoreEscape();
		$res .= $quote ? 'echo ' . var_export($quote, true) . ';' : '';
		return $res;
	}


	public function &getIterator(): \Generator
	{
		yield $this->name;
		if ($this->value) {
			yield $this->value;
		}
	}
}
