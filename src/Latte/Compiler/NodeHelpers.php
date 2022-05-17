<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte;


final class NodeHelpers
{
	use Latte\Strict;

	public static function toText(?Node $node): ?string
	{
		if ($node instanceof Nodes\FragmentNode) {
			$res = '';
			foreach ($node->children as $child) {
				if (($s = self::toText($child)) === null) {
					return null;
				}
				$res .= $s;
			}

			return $res;
		}

		return match (true) {
			$node instanceof Nodes\TextNode => $node->content,
			$node instanceof Nodes\Html\QuotedValue => self::toText($node->value),
			$node instanceof Nodes\NopNode => '',
			default => null,
		};
	}
}
