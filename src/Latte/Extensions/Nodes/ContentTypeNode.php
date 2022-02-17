<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Extensions\Nodes;

use Latte\CompileException;
use Latte\Compiler\Compiler;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\Parser;
use Latte\Compiler\TagInfo;
use Latte\Engine;


/**
 * {contentType ...}
 */
class ContentTypeNode extends StatementNode
{
	public bool $allowedInHead = true;
	public string $latteType;
	public ?string $mimeType = null;


	public static function parse(TagInfo $tag, Parser $parser): self
	{
		$tag->validate(true);
		$type = $tag->args;

		if (!$tag->isInHead()
			&& !($tag->htmlElement?->startTag->getName() === 'script' && str_contains($type, 'html'))
		) {
			throw new CompileException('{contentType} is allowed only in template header.');
		}

		$node = new self;
		$node->latteType = match (true) {
			str_contains($type, 'html') => Engine::CONTENT_HTML,
			str_contains($type, 'xml') => Engine::CONTENT_XML,
			str_contains($type, 'javascript') => Engine::CONTENT_JS,
			str_contains($type, 'css') => Engine::CONTENT_CSS,
			str_contains($type, 'calendar') => Engine::CONTENT_ICAL,
			default => Engine::CONTENT_TEXT
		};
		$parser->setContentType($node->latteType);

		if (strpos($type, '/') && !$tag->htmlElement) {
			$node->mimeType = $type;
		}
		return $node;
	}


	public function compile(Compiler $compiler): string
	{
		$compiler->setContentType($this->latteType);

		return $this->mimeType
			? $compiler->write(
				<<<'XX'
					if (empty($this->global->coreCaptured) && in_array($this->getReferenceType(), ['extends', null], true)) {
						header(%var) %line;
					}

					XX,
				'Content-Type: ' . $this->mimeType,
				$this->line,
			)
			: '';
	}
}
