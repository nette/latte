<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte;


/**
 * HTML element node.
 */
class HtmlNode
{
	use Latte\Strict;

	public string $name;

	public bool $empty;

	/** @var string[] */
	public array $attrs = [];

	/** @var string[] */
	public array $macroAttrs = [];

	public bool $closing = false;

	public ?HtmlNode $parentNode = null;

	public ?string $attrCode = null;

	/** position of start tag in source template */
	public ?int $startLine = null;

	/** position of end tag in source template */
	public ?int $endLine = null;

	/** user data */
	public \stdClass $data;

	/** @internal */
	public string $innerMarker = '';


	public function __construct(string $name, ?self $parentNode = null)
	{
		$this->name = $name;
		$this->parentNode = $parentNode;
		$this->data = new \stdClass;
	}
}
