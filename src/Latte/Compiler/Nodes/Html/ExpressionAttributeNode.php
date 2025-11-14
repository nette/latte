<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Html;

use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\ModifierNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;
use Latte\ContentType;
use Latte\Runtime as LR;


class ExpressionAttributeNode extends AreaNode
{
	public function __construct(
		public string $name,
		public ExpressionNode $value,
		public ModifierNode $modifier,
		public ?Position $position = null,
		public ?string $indentation = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		$this->modifier->escape = false;
		return $context->format(
			'echo %raw(%dump, %modify(%node), %dump?) %line;',
			$context->getEscaper()->getContentType() === ContentType::Html
				? 'LR\HtmlHelpers::format' . ucfirst(LR\HtmlHelpers::classifyAttributeType($this->name)) . 'Attribute'
				: 'LR\XmlHelpers::formatAttribute',
			$this->indentation . $this->name,
			$this->modifier,
			$this->value,
			$context->migrationWarnings ?: null,
			$this->value->position,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->value;
		yield $this->modifier;
	}
}
