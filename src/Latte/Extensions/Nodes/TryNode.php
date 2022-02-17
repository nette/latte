<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Extensions\Nodes;

use Latte\Compiler\Compiler;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\NopNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\TagInfo;


/**
 * {try} ... {else}
 */
class TryNode extends StatementNode
{
	public Node $try;
	public ?Node $else = null;


	/** @return \Generator<int, ?array, array{FragmentNode, ?TagInfo}, self> */
	public static function parse(TagInfo $tag): \Generator
	{
		$tag->validate(false);

		$node = new self;
		if ($tag->empty) {
			$node->try = new NopNode;
			return $node;
		}

		[$node->try, $nextTag] = yield ['else'];
		if ($nextTag?->name === 'else') {
			$nextTag->validate(false);
			[$node->else] = yield;
		}

		return $node;
	}


	public function compile(Compiler $compiler): string
	{
		return $compiler->write(
			<<<'XX'
				$ʟ_try[%var] = [$ʟ_it ?? null];
				ob_start(fn() => '');
				try %line {
					%raw
				} catch (Throwable $ʟ_e) {
					ob_end_clean();
					if (!($ʟ_e instanceof Latte\Extensions\RollbackException) && isset($this->global->coreExceptionHandler)) {
						($this->global->coreExceptionHandler)($ʟ_e, $this);
					}
					%raw
					ob_start();
				} finally {
					echo ob_get_clean();
					$iterator = $ʟ_it = $ʟ_try[%0_var][0];
				}
				XX,
			$compiler->generateId(),
			$this->line,
			$this->try->compile($compiler),
			$this->else?->compile($compiler),
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->try;
		if ($this->else) {
			yield $this->else;
		}
	}
}
