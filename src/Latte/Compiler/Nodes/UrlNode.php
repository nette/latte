<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes;

use Latte\Compiler\PrintContext;


class UrlNode extends AreaNode
{
	public function __construct(
		public AreaNode $value,
		public ?AreaNode $query = null,
		public ?AreaNode $fragment = null,
	) {
		$this->position = $value->position;
	}


	public function print(PrintContext $context): string
	{
		$escaper = $context->beginEscape();
		$escaper->enterContentType($escaper::Url);
		$res = $this->value->print($context);
		$escaper->enterContentType($escaper::UrlQuery);
		$res .= $this->query?->print($context);
		$context->restoreEscape();
		return $res;
	}


	public function &getIterator(): \Generator
	{
		yield $this->value;
		if ($this->query) {
			yield $this->query;
		}
	}
}
