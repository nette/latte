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
	/** @var string[] */
	public array $parts;


	public function __construct(
		string|array|self $name,
		public ?Position $position = null,
	) {
		$this->parts = self::prepareName($name);
	}


	public static function fromString(string $name): self
	{
		if (str_starts_with($name, '\\')) {
			return new FullyQualifiedNameNode(substr($name, 1));

		} elseif (str_starts_with($name, 'namespace\\')) {
			return new RelativeNameNode(substr($name, strlen('namespace\\')));
		}

		return new self($name);
	}


	public function isUnqualified(): bool
	{
		return count($this->parts) === 1;
	}


	public function isQualified(): bool
	{
		return 1 < count($this->parts);
	}


	public function isFullyQualified(): bool
	{
		return false;
	}


	public function isRelative(): bool
	{
		return false;
	}


	public function __toString(): string
	{
		return implode('\\', $this->parts);
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


	private static function prepareName(string|array|self $name): array
	{
		if ($name === '' || $name === []) {
			throw new \InvalidArgumentException('Name cannot be empty');
		} elseif (is_string($name)) {
			return explode('\\', $name);
		} elseif (is_array($name)) {
			return $name;
		} else {
			return $name->parts;
		}
	}


	public function print(PrintContext $context): string
	{
		return $this->isKeyword()
			? '\\' . implode('\\', $this->parts)
			: implode('\\', $this->parts);
	}
}
