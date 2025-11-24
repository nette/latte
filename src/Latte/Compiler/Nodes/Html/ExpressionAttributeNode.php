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
		public ?string $indentation = null,
		public ?Position $position = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		if ($context->getEscaper()->getContentType() === ContentType::Html) {
			$type = $this->modifier->removeFilter('toggle') ? 'bool' : LR\HtmlHelpers::classifyAttributeType($this->name);
			$method = 'LR\HtmlHelpers::format' . ucfirst($type) . 'Attribute';
		} else {
			$method = 'LR\XmlHelpers::formatAttribute';
		}
		return $context->format(
			'echo %raw(%dump, %modify(%node), %dump?) %line;',
			$method,
			$this->indentation . $this->name,
			$this->modifier,
			$this->value,
			(!$this->modifier->removeFilter('accept') && $context->migrationWarnings) ?: null,
			$this->value->position,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->value;
		yield $this->modifier;
	}
}
