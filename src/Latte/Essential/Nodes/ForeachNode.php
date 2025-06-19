<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\AuxiliaryNode;
use Latte\Compiler\Nodes\NopNode;
use Latte\Compiler\Nodes\Php\Expression\VariableNode;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\ListNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\Nodes\TemplateNode;
use Latte\Compiler\NodeTraverser;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\TagParser;
use function array_map, array_unshift, implode, preg_match;


/**
 * {foreach $expr as $key => $value} & {else}
 */
class ForeachNode extends StatementNode
{
	public ExpressionNode $expression;
	public ?ExpressionNode $key = null;
	public bool $byRef = false;
	public ExpressionNode|ListNode $value;
	public AreaNode $content;
	public ?AreaNode $else = null;
	public ?Position $elseLine = null;
	public ?bool $iterator = null;
	public bool $checkArgs = true;


	/** @return \Generator<int, ?array, array{AreaNode, ?Tag}, static|NopNode> */
	public static function create(Tag $tag): \Generator
	{
		$tag->expectArguments();
		$node = $tag->node = new static;
		self::parseArguments($tag->parser, $node);

		$modifier = $tag->parser->parseModifier();
		foreach ($modifier->filters as $filter) {
			match ($filter->name->name) {
				'nocheck', 'noCheck' => $node->checkArgs = false,
				'noiterator', 'noIterator' => $node->iterator = false,
				default => throw new CompileException('Only modifiers |noiterator and |nocheck are allowed here.', $tag->position),
			};
		}

		if ($tag->void) {
			$node->content = new NopNode;
			return $node;
		}

		[$node->content, $nextTag] = yield ['else'];
		if ($nextTag?->name === 'else') {
			$node->elseLine = $nextTag->position;
			[$node->else] = yield;
		}

		return $node;
	}


	private static function parseArguments(TagParser $parser, self $node): void
	{
		$stream = $parser->stream;
		$node->expression = $parser->parseExpression();
		$stream->consume('as');
		[$node->key, $node->value, $node->byRef] = $parser->parseForeach();
	}


	public function print(PrintContext $context): string
	{
		$content = $this->content->print($context);
		$iterator = $this->else || ($this->iterator ?? preg_match('#\$iterator\W|\Wget_defined_vars\W#', $content));

		if ($this->else) {
			$content .= $context->format(
				'} if ($iterator->isEmpty()) %line { ',
				$this->elseLine,
			) . $this->else->print($context);
		}

		if ($iterator) {
			return $context->format(
				<<<'XX'
					foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator(%node, $ʟ_it ?? null) as %raw) %line {
						%raw
					}
					$iterator = $ʟ_it = $ʟ_it->getParent();


					XX,
				$this->expression,
				$this->printArgs($context),
				$this->position,
				$content,
			);

		} else {
			return $context->format(
				<<<'XX'
					foreach (%node as %raw) %line {
						%raw
					}


					XX,
				$this->expression,
				$this->printArgs($context),
				$this->position,
				$content,
			);
		}
	}


	private function printArgs(PrintContext $context): string
	{
		return ($this->key ? $this->key->print($context) . ' => ' : '')
			. ($this->byRef ? '&' : '')
			. $this->value->print($context);
	}


	public function &getIterator(): \Generator
	{
		yield $this->expression;
		if ($this->key) {
			yield $this->key;
		}
		yield $this->value;
		yield $this->content;
		if ($this->else) {
			yield $this->else;
		}
	}


	/**
	 * Pass: checks if foreach overrides template variables.
	 */
	public static function overwrittenVariablesPass(TemplateNode $node): void
	{
		$vars = [];
		(new NodeTraverser)->traverse($node, function (Node $node) use (&$vars) {
			if ($node instanceof self && $node->checkArgs) {
				foreach ([$node->key, $node->value] as $var) {
					if ($var instanceof VariableNode) {
						$vars[$var->name][] = $node->position->line;
					}
				}
			}
		});
		if ($vars) {
			array_unshift($node->head->children, new AuxiliaryNode(fn(PrintContext $context) => $context->format(
				<<<'XX'
					if (!$this->getReferringTemplate() || $this->getReferenceType() === 'extends') {
						foreach (array_intersect_key(%dump, $this->params) as $ʟ_v => $ʟ_l) {
							trigger_error("Variable \$$ʟ_v overwritten in foreach on line $ʟ_l");
						}
					}

					XX,
				array_map(fn($l) => implode(', ', $l), $vars),
			)));
		}
	}
}
