<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php;

use Latte\Compiler\Node;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class NameNode extends Node
{
	public const
		KindNormal = 1,
		KindFullyQualified = 2;

	/** @var string[] */
	public array $parts;


	public function __construct(
		string|array $name,
		public int $kind = self::KindNormal,
		public ?Position $position = null,
	) {
		if ($name === '' || $name === []) {
			throw new \InvalidArgumentException('Name cannot be empty');

		} elseif (is_string($name)) {
			if (str_starts_with($name, '\\')) {
				$this->kind = self::KindFullyQualified;
				$name = substr($name, 1);
			} elseif (str_starts_with($name, 'namespace\\')) {
				throw new \InvalidArgumentException('Relative name is not supported');
			} else {
				$this->kind = self::KindNormal;
			}
			$this->parts = explode('\\', $name);

		} else {
			$this->parts = $name;
		}
	}


	public function isKeyword(): bool
	{
		static $keywords;
		$keywords ??= array_flip([
			'include', 'include_once', 'eval', 'require', 'require_once', 'or', 'xor', 'and',
			'instanceof', 'new', 'clone', 'exit', 'if', 'elseif', 'else', 'endif', 'echo', 'do', 'while',
			'endwhile', 'for', 'endfor', 'foreach', 'endforeach', 'declare', 'enddeclare', 'as', 'try', 'catch',
			'finally', 'throw', 'use', 'insteadof', 'global', 'var', 'unset', 'isset', 'empty', 'continue', 'goto',
			'function', 'const', 'return', 'print', 'yield', 'list', 'switch', 'endswitch', 'case', 'default',
			'break', 'array', 'callable', 'extends', 'implements', 'namespace', 'trait', 'interface', 'class',
			'static', 'abstract', 'final', 'private', 'protected', 'public', 'fn', 'match', 'self', 'parent',
		]);
		return count($this->parts) === 1
			&& (isset($keywords[strtolower($this->parts[0])]) || str_starts_with($this->parts[0], '__'));
	}


	public function print(PrintContext $context): string
	{
		return $this->toCodeString();
	}


	public function __toString(): string
	{
		return implode('\\', $this->parts);
	}


	public function toCodeString(): string
	{
		$prefix = match ($this->kind) {
			self::KindNormal => $this->isKeyword() ? 'namespace\\' : '',
			self::KindFullyQualified => '\\',
		};
		return $prefix . implode('\\', $this->parts);
	}


	public function &getIterator(): \Generator
	{
		false && yield;
	}
}
