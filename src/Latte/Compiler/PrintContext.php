<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte;
use Latte\Compiler\Nodes\ExpressionNode;
use Latte\ContentType;
use Latte\Policy;
use Latte\SecurityViolationException;


/**
 * PHP printing helpers and context.
 */
final class PrintContext
{
	use Latte\Strict;

	public ?Policy $policy;
	public array $functions;
	public array $paramsExtraction = [];
	public string $initialization = '';
	public array $blocks = [];
	private int $counter = 0;
	private Escaper $escaper;

	/** @var Escaper[] */
	private array $escaperStack = [];


	public function __construct(string $contentType = ContentType::Html)
	{
		$this->escaper = new Escaper($contentType);
	}


	/**
	 * Expands %node, %dump, %raw, %args, %line, %escape(), %modify(), %modifyContent() in code.
	 */
	public function format(string $mask, mixed ...$args): string
	{
		$writer = PhpWriter::using($this);
		$pos = 0;
		$mask = preg_replace_callback(
			'#%([a-z])#',
			function ($m) use (&$pos) { return '%' . ($pos++) . '.' . $m[1]; },
			$mask,
		);

		$mask = preg_replace_callback(
			'#%(\d+)\.modify(Content)?(\(([^()]*+|(?-2))+\))#',
			function ($m) use ($writer, $args) {
				[, $pos, $content, $var] = $m;
				return $writer->formatModifiers($args[$pos], substr($var, 1, -1), (bool) $content);
			},
			$mask,
		);

		return preg_replace_callback(
			'#([,+]?\s*)?%(\d+)\.(node|word|dump|raw|array|args|line)(\?)?(\s*\+\s*)?()#',
			function ($m) use ($writer, $args) {
				[, $l, $pos, $format, $cond, $r] = $m;
				$arg = $args[$pos];

				switch ($format) {
					case 'node':
						$code = $arg ? $arg->print($this) : '';
						break;
					case 'word':
						if ($arg instanceof ExpressionNode) {
							$arg = $arg->text;
						}
						$code = $writer->formatWord($arg); break;
					case 'args':
						if ($arg instanceof ExpressionNode) {
							$arg = new MacroTokens($arg->text);
						}
						$code = $writer->formatArgs($arg); break;
					case 'array':
						if ($arg instanceof ExpressionNode) {
							$arg = new MacroTokens($arg->text);
						}
						$code = $writer->formatArray($arg);
						$code = $cond && $code === '[]' ? '' : $code; break;
					case 'dump':
						$code = PhpHelpers::dump($arg); break;
					case 'raw':
						$code = (string) $arg;
						break;
					case 'line':
						$l = trim($l);
						$line = (int) $arg->line;
						$code = $line ? " /* line $line */" : '';
						break;
				}

				if ($cond && $code === '') {
					return $r ? $l : $r;
				} else {
					return $l . $code . $r;
				}
			},
			$mask,
		);
	}


	public function beginEscape(): Escaper
	{
		$this->escaperStack[] = $this->escaper;
		return $this->escaper = clone $this->escaper;
	}


	public function restoreEscape(): void
	{
		$this->escaper = array_pop($this->escaperStack);
	}


	public function getEscaper(): Escaper
	{
		return clone $this->escaper;
	}


	public function addBlock(Block $block, ?Escaper $escaper = null): void
	{
		$block->escaping = ($escaper ?? $this->getEscaper())->export();
		$block->method = 'block' . ucfirst(trim(preg_replace('#\W+#', '_', $block->name), '_'));
		$lower = strtolower($block->method);
		$used = $this->blocks + ['block' => 1];
		$counter = null;
		while (isset($used[$lower . $counter])) {
			$counter++;
		}

		$block->method .= $counter;
		$this->blocks[$lower . $counter] = $block;
	}


	public function generateId(): int
	{
		return $this->counter++;
	}


	public function checkFilterIsAllowed(string $name): void
	{
		if ($this->policy && !$this->policy->isFilterAllowed($name)) {
			throw new SecurityViolationException("Filter |$name is not allowed.");
		}
	}
}
