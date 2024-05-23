<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte;
use Latte\CompileException;
use Latte\Compiler\Nodes\Php\Expression\AuxiliaryNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;
use Latte\ContentType;


/**
 * n:tag="..."
 */
final class NTagNode extends StatementNode
{
	public static function create(Tag $tag, TemplateParser $parser): void
	{
		if (preg_match('(style$|script$)iA', $tag->htmlElement->name)) {
			throw new CompileException('Attribute n:tag is not allowed in <script> or <style>', $tag->position);
		}

		$tag->expectArguments();
		$tag->htmlElement->variableName = new AuxiliaryNode(
			fn(PrintContext $context, $newName) => $context->format(
				self::class . '::check(%dump, %node, %dump)',
				$tag->htmlElement->name,
				$newName,
				$parser->getContentType() === ContentType::Xml,
			),
			[$tag->parser->parseExpression()],
		);
	}


	public function print(PrintContext $context): string
	{
		throw new \LogicException('Cannot directly print');
	}


	public static function check(string $orig, mixed $new, bool $xml): mixed
	{
		if ($new === null) {
			return $orig;
		} elseif (!$xml
			&& is_string($new)
			&& isset(Latte\Helpers::$emptyElements[strtolower($orig)]) !== isset(Latte\Helpers::$emptyElements[strtolower($new)])
		) {
			throw new Latte\RuntimeException("Forbidden tag <$orig> change to <$new>");
		}

		return $new;
	}


	public function &getIterator(): \Generator
	{
		false && yield;
	}
}
