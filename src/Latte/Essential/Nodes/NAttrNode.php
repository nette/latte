<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte;
use Latte\Compiler\Nodes\Php\Expression\ArrayNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * n:attr="..."
 */
final class NAttrNode extends StatementNode
{
	public ArrayNode $args;


	public static function create(Tag $tag): static
	{
		$tag->expectArguments();
		$node = new static;
		$node->args = $tag->parser->parseArguments();
		return $node;
	}


	public function print(PrintContext $context): string
	{
		// [$ʟ_tmp[0] ?? null] === $ʟ_tmp checks if the value is an array, e.g. n:attr="$attrs"
		$html = $context->getEscaper()->getContentType() === Latte\ContentType::Html;
		return $context->format(
			<<<'XX'
				$ʟ_tmp = %node;
				$ʟ_tmp = [$ʟ_tmp[0] ?? null] === $ʟ_tmp ? $ʟ_tmp[0] : $ʟ_tmp;
				foreach ((array) $ʟ_tmp as $ʟ_nm => $ʟ_v) {
					if ($ʟ_tmp = LR\%raw::formatAttribute($ʟ_nm, $ʟ_v)) {
						echo ' ', $ʟ_tmp %line;
					}
				}

				XX,
			$this->args,
			$html ? 'HtmlHelpers' : 'XmlHelpers',
			$this->position,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->args;
	}
}
