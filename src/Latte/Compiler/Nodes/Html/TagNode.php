<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Html;

use Latte\Compiler\Nodes;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\PrintContext;
use Latte\ContentType;


/**
 * Helper node for rendering HTML tags with n:tag and n:tag* support.
 * Writes end tag to $ʟ_tag.
 */
class TagNode extends AreaNode
{
	public function __construct(
		public readonly ElementNode $element,
		public ?Nodes\Php\ExpressionNode $name = null,
	) {
	}


	public function print(PrintContext $context, ?bool $captureEnd = null): string
	{
		$captureEnd ??= (bool) $this->element->content;
		$context->beginEscape()->enterHtmlTag($this->element->name);

		$res = $this->name
			? $context->format(
				<<<'XX'
					$ʟ_tmp = LR\%raw::validateTagChange(%node, %dump);
					%raw
					echo '<', $ʟ_tmp %line;
					%node
					echo %dump;
					XX,
				$this->element->contentType === ContentType::Html ? 'HtmlHelpers' : 'XmlHelpers',
				$this->name,
				$this->element->name,
				$captureEnd ? '$ʟ_tag = \'</\' . $ʟ_tmp . \'>\' . $ʟ_tag;' : '',
				$this->element->position,
				$this->element->attributes,
				$this->element->selfClosing ? '/>' : '>',
			)
			: $context->format(
				'%raw echo %dump; %node echo %dump;',
				$captureEnd ? '$ʟ_tag = ' . $context->encodeString("</{$this->element->name}>") . ' . $ʟ_tag;' : '',
				'<' . $this->element->name,
				$this->element->attributes,
				$this->element->selfClosing ? '/>' : '>',
			);

		$context->restoreEscape();
		return $res;
	}


	public function &getIterator(): \Generator
	{
		if ($this->name) {
			yield $this->name;
		}
	}
}
