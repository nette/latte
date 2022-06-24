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
use Latte\Compiler\Token;


/**
 * {templateType ClassName}
 */
class TemplateTypeNode extends StatementNode
{
	public string $class;


	public static function create(Tag $tag): static
	{
		if (!$tag->isInHead()) {
			throw new CompileException('{templateType} is allowed only in template header.', $tag->position);
		}
		$tag->expectArguments('class name');
		$token = $tag->parser->stream->consume(Token::Php_Identifier, Token::Php_NameQualified, Token::Php_NameFullyQualified);
		if (!class_exists($token->text)) {
			throw new CompileException("Class '$token->text' used in {templateType} doesn't exist.", $token->position);
		}

		$node = new static;
		$node->class = $token->text;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		$scope = $context->getVariableScope();
		$rc = new \ReflectionClass($this->class);
		foreach ($rc->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
			$type = $this->parseAnnotation($property->getDocComment() ?: '') ?: (string) $property->getType();
			$scope->addVariable($property->getName(), $type);
		}

		return '';
	}


	private function parseAnnotation(string $comment): ?string
	{
		$comment = trim($comment, '/*');
		return preg_match('#@var ([^$]+)#', $comment, $m) ? trim($m[1]) : null;
	}
}
