<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential;

use Latte;
use Latte\Compiler\Nodes\Php;
use Latte\Compiler\Tag;
use Latte\Essential\Nodes\PrintNode;
use Nette\Localization\Translator;


/**
 * Extension for translations.
 */
final class TranslatorExtension extends Latte\Extension
{
	public function __construct(
		private /*?callable|Translator*/ $translator,
	) {
		if ($translator instanceof Translator) {
			$this->translator = [$translator, 'translate'];
		}
	}


	public function getTags(): array
	{
		return [
			'_' => [$this, 'parseTranslate'],
			'translate' => [Nodes\TranslateNode::class, 'create'],
		];
	}


	public function getFilters(): array
	{
		return [
			'translate' => fn(Latte\Runtime\FilterInfo $fi, ...$args): string => $this->translator
				? ($this->translator)(...$args)
				: $args[0],
		];
	}


	/**
	 * {_ ...}
	 */
	public function parseTranslate(Tag $tag): PrintNode
	{
		$tag->outputMode = $tag::OutputKeepIndentation;
		$tag->expectArguments();
		$node = new PrintNode;
		$node->expression = $tag->parser->parseExpression();
		$args = new Php\Expression\ArrayNode;
		if ($tag->parser->stream->tryConsume(',')) {
			$args = $tag->parser->parseArguments();
		}
		$node->modifier = $tag->parser->parseModifier();
		$node->modifier->escape = true;
		array_unshift($node->modifier->filters, new Php\FilterNode(new Php\IdentifierNode('translate'), $args->toArguments()));
		return $node;
	}
}
