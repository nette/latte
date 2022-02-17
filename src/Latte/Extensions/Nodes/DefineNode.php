<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Extensions\Nodes;

use Latte\CompileException;
use Latte\Compiler\Block;
use Latte\Compiler\Compiler;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\NopNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\Parser;
use Latte\Compiler\TagInfo;
use Latte\Runtime\Template;


/**
 * {define [local] name}
 */
class DefineNode extends StatementNode
{
	public Block $block;
	public Node $content;
	public TagInfo $tag;
	public bool $extendsCheck;


	/** @return \Generator<int, ?array, array{FragmentNode, ?TagInfo}, self> */
	public static function parse(TagInfo $tag, Parser $parser): \Generator
	{
		if ($tag->modifiers) { // modifier may be union|type
			$tag->setArgs($tag->args . $tag->modifiers);
			$tag->modifiers = '';
		}

		$tag->validate(true);
		[$name, $local] = $tag->tokenizer->fetchWordWithModifier('local');
		$name = ltrim((string) $name, '#');
		$layer = $local ? Template::LAYER_LOCAL : null;

		$node = new self;
		$node->extendsCheck = $parser->blocks[Template::LAYER_TOP] || count($parser->blocks) > 1 || $tag->parent;
		$node->block = $parser->addBlock($name, $layer, 'define');
		$node->tag = $tag;
		$tag->data->block = $node->block; // TODO: pro include

		[$node->content] = $tag->empty
			? [new NopNode]
			: yield;

		if (Block::isDynamic($name)) {
			$tag->checkExtraArgs();
		} else {
			if (!preg_match('#^[a-z]#iD', $name)) {
				throw new CompileException("Block name must start with letter a-z, '$name' given.");
			}

			$node->block->parameters = self::parseParameters($tag->tokenizer);
		}

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


	public function compile(Compiler $compiler): string
	{
		return Block::isDynamic($this->block->name)
			? $this->compileDynamic($compiler)
			: $this->compileStatic($compiler);
	}


	private function compileStatic(Compiler $compiler): string
	{
		$compiler->addBlock($this->block, $this->content, $this->tag);
		return $this->extendsCheck
			? ''
			: 'if ($this->getParentName()) { return get_defined_vars();} ';
	}


	private function compileDynamic(Compiler $compiler): string
	{
		$tag = $this->tag;
		$compiler->addMethod(
			$method = $compiler->generateMethodName($this->block->name),
			'extract($ʟ_args); unset($ʟ_args);' . "\n\n" . $this->content->compile($compiler),
			'array $ʟ_args',
			'void',
			"{{$tag->name} {$tag->args}} on line {$tag->line}",
		);

		return $compiler->write(
			'$this->addBlock($ʟ_nm = %word, %var, [[$this, %var]], %var);',
			$this->block->name,
			implode('', $compiler->getContext()),
			$method,
			$this->block->layer,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->content;
	}
}
