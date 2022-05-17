<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\Compiler\Block;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;
use Latte\Runtime\Template;


/**
 * {define [local] name}
 */
class DefineNode extends StatementNode
{
	public Block $block;
	public AreaNode $content;


	/** @return \Generator<int, ?array, array{AreaNode, ?Tag}, static> */
	public static function create(Tag $tag, TemplateParser $parser): \Generator
	{
		$tag->expectArguments();
		[$name, $local] = $tag->parser->fetchWordWithModifier('local');
		$name = ltrim((string) $name, '#');
		$layer = $local ? Template::LayerLocal : $parser->blockLayer;

		$node = new static;
		$node->block = new Block($name, $layer, $tag);

		if (!$node->block->isDynamic()) {
			$parser->checkBlockIsUnique($node->block);
			$tag->data->block = $node->block; // for {include}
			$node->block->parameters = self::parseParameters($tag->parser);
		}

		[$node->content] = yield;

		return $node;
	}


	private static function parseParameters($tokens): array
	{
		$params = [];
		while ($tokens->isNext(...$tokens::SIGNIFICANT)) {
			if ($tokens->nextValue($tokens::T_SYMBOL, '?', 'null', '\\')) { // type
				$tokens->nextAll($tokens::T_SYMBOL, '\\', '|', '[', ']', 'null');
			}

			$param = $tokens->consumeValue($tokens::T_VARIABLE);
			$default = $tokens->nextValue('=')
				? $tokens->joinUntilSameDepth(',')
				: 'null';
			$params[] = sprintf(
				'%s = $ʟ_args[%s] ?? $ʟ_args[%s] ?? %s;',
				$param,
				count($params),
				var_export(substr($param, 1), true),
				$default,
			);
			if ($tokens->isNext(...$tokens::SIGNIFICANT)) {
				$tokens->consumeValue(',');
			}
		}

		return $params;
	}


	public function print(PrintContext $context): string
	{
		return $this->block->isDynamic()
			? $this->printDynamic($context)
			: $this->printStatic($context);
	}


	private function printStatic(PrintContext $context): string
	{
		$context->addBlock($this->block);
		$this->block->content = $this->content->print($context); // must be compiled after is added
		return '';
	}


	private function printDynamic(PrintContext $context): string
	{
		$context->addBlock($this->block);
		$this->block->content = $this->content->print($context); // must be compiled after is added

		return $context->format(
			'$this->addBlock($ʟ_nm = %word, %dump, [[$this, %dump]], %dump);',
			$this->block->name,
			$context->getEscaper()->export(),
			$this->block->method,
			$this->block->layer,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->content;
	}
}
