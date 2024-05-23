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
use Latte\ContentType;


/**
 * {contentType ...}
 */
class ContentTypeNode extends StatementNode
{
	public string $contentType;
	public ?string $mimeType = null;
	public bool $inScript;


	public static function create(Tag $tag, TemplateParser $parser): static
	{
		$tag->expectArguments();
		while (!$tag->parser->stream->consume()->isEnd());
		$type = trim($tag->parser->text);

		if (!$tag->isInHead() && !($tag->htmlElement?->is('script') && str_contains($type, 'html'))) {
			throw new CompileException('{contentType} is allowed only in template header.', $tag->position);
		}

		$node = new static;
		$node->inScript = (bool) $tag->htmlElement;
		$node->contentType = match (true) {
			str_contains($type, 'html') => ContentType::Html,
			str_contains($type, 'xml') => ContentType::Xml,
			str_contains($type, 'javascript') => ContentType::JavaScript,
			str_contains($type, 'css') => ContentType::Css,
			str_contains($type, 'calendar') => ContentType::ICal,
			default => ContentType::Text
		};
		$parser->setContentType($node->contentType);

		if (strpos($type, '/') && !$tag->htmlElement) {
			$node->mimeType = $type;
		}
		return $node;
	}


	public function print(PrintContext $context): string
	{
		if ($this->inScript) {
			$context->getEscaper()->enterHtmlRaw($this->contentType);
			return '';
		}

		$context->beginEscape()->enterContentType($this->contentType);

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


	public function &getIterator(): \Generator
	{
		false && yield;
	}
}
