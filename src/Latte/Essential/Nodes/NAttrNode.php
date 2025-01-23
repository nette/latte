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
		return $context->format(
			'$ʟ_tmp = %node;
			%raw::attrs(is_array($ʟ_tmp[0] ?? null) ? $ʟ_tmp[0] : $ʟ_tmp, %dump) %line;',
			$this->args,
			self::class,
			$context->getEscaper()->getContentType() === Latte\ContentType::Xml,
			$this->position,
		);
	}


	/** @internal */
	public static function attrs($attrs, bool $xml): void
	{
		foreach ((is_array($attrs) ? $attrs : []) as $name => $value) {
			$tmp = $xml
				? Latte\Runtime\Filters::renderXmlAttribute($name, $value)
				: Latte\Runtime\Filters::renderHtmlAttribute($name, $value);
			if ($tmp !== null) {
				echo ' ', $tmp;
			}
		}
	}


	public function &getIterator(): \Generator
	{
		yield $this->args;
	}
}
