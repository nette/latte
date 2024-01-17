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


	public function __construct(
		public string $name,
		public int $kind = self::KindNormal,
		public ?Position $position = null,
	) {
		if ($name === '') {
			throw new \InvalidArgumentException('Name cannot be empty');
		} elseif (str_starts_with($name, 'namespace\\')) {
			throw new \InvalidArgumentException('Relative name is not supported');
		} elseif (str_starts_with($name, '\\')) {
			$this->kind = self::KindFullyQualified;
			$this->name = substr($name, 1);
		} else {
			$this->kind = self::KindNormal;
		}
	}


	public function isKeyword(): bool
	{
		static $keywords;
		$keywords ??= array_flip([ // https://www.php.net/manual/en/reserved.keywords.php
			'__halt_compiler', '__class__', '__dir__', '__file__', '__function__', '__line__', '__method__', '__namespace__', '__trait__',
			'abstract', 'and', 'array', 'as', 'break', 'callable', 'case', 'catch', 'class', 'clone', 'const', 'continue', 'declare',
			'default', 'die', 'do', 'echo', 'else', 'elseif', 'empty', 'enddeclare', 'endfor', 'endforeach', 'endif', 'endswitch',
			'endwhile', 'eval', 'exit', 'extends', 'final', 'finally', 'fn', 'for', 'foreach', 'function', 'global', 'goto', 'if',
			'implements', 'include', 'include_once', 'instanceof', 'insteadof', 'interface', 'isset', 'list', 'match', 'namespace',
			'new', 'or', 'print', 'private', 'protected', 'public', 'readonly', 'require', 'require_once', 'return', 'static',
			'switch', 'throw', 'trait', 'try', 'unset', 'use', 'var', 'while', 'xor', 'yield',
			'parent', 'self', 'mixed', 'void', 'enum', // extra
		]);
		return isset($keywords[strtolower($this->name)]);
	}


	public function print(PrintContext $context): string
	{
		return $this->toCodeString();
	}


	public function __toString(): string
	{
		return $this->name;
	}


	public function toCodeString(): string
	{
		$prefix = match ($this->kind) {
			self::KindNormal => $this->isKeyword() ? 'namespace\\' : '',
			self::KindFullyQualified => '\\',
		};
		return $prefix . $this->name;
	}


	public function &getIterator(): \Generator
	{
		false && yield;
	}
}
