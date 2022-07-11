<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\Compiler\Nodes\Php\Expression\VariableNode;
use Latte\Compiler\Nodes\Php\SuperiorTypeNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\Token;
use Latte\Compiler\VariableScope;


/**
 * {varType type $var}
 */
class VarTypeNode extends StatementNode
{
	public VariableNode $variable;
	public SuperiorTypeNode $type;
	public bool $isParameterType = false;


	public static function create(Tag $tag): static
	{
		$tag->expectArguments();
		$type = $tag->parser->parseType();
		if (!$type) {
			$tag->parser->stream->throwUnexpectedException();
		}
		$token = $tag->parser->stream->consume(Token::Php_Variable);

		$node = new static;
		$node->type = $type;
		$node->variable = new VariableNode(substr($token->text, 1));
		$node->isParameterType = $tag->isInHead();
		return $node;
	}


	public function print(PrintContext $context): string
	{
		if ($this->isParameterType) {
			$scope = $context->getVariableScope();
			return $scope->addExpression($this->variable, $this->type) . "\n";
		} elseif (is_string($this->variable->name)) {
			return VariableScope::printComment($this->variable->name, $this->type?->type) . "\n";
		} else {
			return '';
		}
	}


	public function &getIterator(): \Generator
	{
		yield $this->variable;
		yield $this->type;
	}
}
