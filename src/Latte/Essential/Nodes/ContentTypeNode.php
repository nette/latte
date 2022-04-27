<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;
use Latte\Context;


/**
 * {contentType ...}
 */
class ContentTypeNode extends StatementNode
{
	public string $latteType;
	public ?string $mimeType = null;


	public static function create(Tag $tag, TemplateParser $parser): static
	{
		$tag->expectArguments();
		$type = $tag->args;

		if (!$tag->isInHead() && !($tag->htmlElement?->name === 'script' && str_contains($type, 'html'))) {
			throw new CompileException('{contentType} is allowed only in template header.', $tag->position);
		}

		$node = new static;
		$node->latteType = match (true) {
			str_contains($type, 'html') => Context::Html,
			str_contains($type, 'xml') => Context::Xml,
			str_contains($type, 'javascript') => Context::JavaScript,
			str_contains($type, 'css') => Context::Css,
			str_contains($type, 'calendar') => Context::ICal,
			default => Context::Text
		};
		$parser->setContentType($node->latteType);

		if (strpos($type, '/') && !$tag->htmlElement) {
			$node->mimeType = $type;
		}
		return $node;
	}


	public function print(PrintContext $context): string
	{
		$context->setContentType($this->latteType);

		return $this->mimeType
			? $context->format(
				<<<'XX'
					if (empty($this->global->coreCaptured) && in_array($this->getReferenceType(), ['extends', null], true)) {
						header(%dump) %line;
					}

					XX,
				'Content-Type: ' . $this->mimeType,
				$this->position,
			)
			: '';
	}
}
