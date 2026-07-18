<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler\Nodes\Php\Scalar;

use Latte\Compiler\Nodes\Php\ScalarNode;
use Latte\Compiler\PhpHelpers;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;
use function strtr, substr;


/**
 * String literal, single or double quoted.
 */
class StringNode extends ScalarNode
{
	public function __construct(
		public string $value,
		public ?Position $position = null,
		public ?Position $end = null,
	) {
	}


	public static function parse(string $str, ?Position $start, ?Position $end = null): static
	{
		$str = $str[0] === "'"
			? strtr(substr($str, 1, -1), ['\\\\' => '\\', "\\'" => "'"])
			: PhpHelpers::decodeEscapeSequences(substr($str, 1, -1), '"');
		return new static($str, $start, $end);
	}


	public function print(PrintContext $context): string
	{
		return $context->encodeString($this->value);
	}
}
