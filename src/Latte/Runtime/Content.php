<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Runtime;


/**
 * String in the given content type, e.g. a rendered template.
 * T is not ContentType::*, because a single block has the type of its escaping context,
 * e.g. html/raw/js for a block in <script>.
 * @template T of string
 */
class Content implements \Stringable, \JsonSerializable
{
	public function __construct(
		private readonly string $value,
		/** @var T  see Latte\ContentType */
		public readonly string $contentType,
	) {
	}


	public function __toString(): string
	{
		return $this->value;
	}


	/** Encodes the string, not the public $contentType. */
	public function jsonSerialize(): string
	{
		return $this->value;
	}
}
