<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\Compiler\Nodes\Php\Expression\AssignNode;
use Latte\Compiler\Nodes\Php\Expression\VariableNode;
use Latte\Compiler\Nodes\Php\Scalar\NullNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\Token;


/**
 * {var [type] $var = value, ...}
 * {default [type] $var = value, ...}
 */
class VarNode extends StatementNode
{
	public bool $default;

	/** @var AssignNode[] */
	public array $assignments = [];


	public static function create(Tag $tag): static
	{
		$tag->expectArguments();
		$node = new static;
		$node->default = $tag->name === 'default';
		$node->assignments = self::parseAssignments($tag, $node->default);
		return $node;
	}


	private static function parseAssignments(Tag $tag, bool $default): array
	{
		$stream = $tag->parser->stream;
		$res = [];
		do {
			$tag->parser->parseType();

			$save = $stream->getIndex();
			$expr = $stream->is(Token::Php_Variable) ? $tag->parser->parseExpression() : null;
			if ($expr instanceof VariableNode) {
				$res[] = new AssignNode($expr, new NullNode);
			} elseif ($expr instanceof AssignNode && (!$default || $expr->var instanceof VariableNode)) {
				$res[] = $expr;
			} else {
				$stream->seek($save);
				$stream->throwUnexpectedException(addendum: ' in ' . $tag->getNotation());
			}
		} while ($stream->tryConsume(',') && !$stream->peek()->isEnd());

		return $res;
	}


	public function print(PrintContext $context): string
	{
		$res = [];
		foreach ($this->assignments as $assign) {
			if ($this->default) {
				assert($assign->var instanceof VariableNode);
				$res[] = $context->format(
					'%node ??= array_key_exists(%raw, get_defined_vars()) ? null : %node',
					$assign->var,
					$context->encodeString($assign->var->name),
					$assign->expr,
				);
			} else {
				$res[] = $assign->print($context);
			}
		}

		return $context->format(
			'%raw %line;',
			implode('; ', $res),
			$this->position,
		);
	}


	public function &getIterator(): \Generator
	{
		foreach ($this->assignments as &$assign) {
			yield $assign;
		}
	}
}
