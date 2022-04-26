<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;


/**
 * Macro element node.
 */
class MacroNode
{
	use Strict;

	public const
		PREFIX_INNER = 'inner',
		PREFIX_TAG = 'tag',
		PREFIX_NONE = 'none';

	/** @var Macro */
	public $macro;

	/** @var string */
	public $name;

	/** @var bool */
	public $empty = false;

	/** @var string  raw arguments */
	public $args;

	/** @var string  raw modifier */
	public $modifiers;

	/** @var bool */
	public $closing = false;

	/** @var bool  has output? */
	public $replaced;

	/** @var MacroTokens */
	public $tokenizer;

	/** @var MacroNode|null */
	public $parentNode;

	/** @var string */
	public $openingCode;

	/** @var string */
	public $closingCode;

	/** @var string */
	public $attrCode;

	/** @var string */
	public $content;

	/** @var string */
	public $innerContent;

	/** @var \stdClass  user data */
	public $data;

	/** @var HtmlNode|null  closest HTML node */
	public $htmlNode;

	/** @var array{string, mixed} [contentType, context] */
	public $context;

	/** @var string|null  indicates n:attribute macro and type of prefix (PREFIX_INNER, PREFIX_TAG, PREFIX_NONE) */
	public $prefix;

	/** @var int  position of start tag in source template */
	public $startLine;

	/** @var int  position of end tag in source template */
	public $endLine;

	/** @var array{string, bool}|null */
	public $saved;


	public function __construct(
		Macro $macro,
		string $name,
		string $args = '',
		string $modifiers = '',
		?self $parentNode = null,
		?HtmlNode $htmlNode = null,
		?string $prefix = null,
	) {
		$this->macro = $macro;
		$this->name = $name;
		$this->modifiers = $modifiers;
		$this->parentNode = $parentNode;
		$this->htmlNode = $htmlNode;
		$this->prefix = $prefix;
		$this->data = new \stdClass;
		$this->setArgs($args);
	}


	public function setArgs(string $args): void
	{
		$this->args = $args;
		$this->tokenizer = new MacroTokens($args);
	}


	public function getNotation(): string
	{
		return $this->prefix
			? Parser::N_PREFIX . ($this->prefix === self::PREFIX_NONE ? '' : $this->prefix . '-') . $this->name
			: '{' . $this->name . '}';
	}


	/**
	 * @param  string[]  $names
	 */
	public function closest(array $names, ?callable $condition = null): ?self
	{
		$node = $this->parentNode;
		while ($node && (
			!in_array($node->name, $names, true)
			|| ($condition && !$condition($node))
		)) {
			$node = $node->parentNode;
		}

		return $node;
	}


	/**
	 * @param  string|bool|null  $arguments
	 * @param  string[]  $parents
	 * @throws CompileException
	 */
	public function validate($arguments, array $parents = [], bool $modifiers = false): void
	{
		if ($parents && (!$this->parentNode || !in_array($this->parentNode->name, $parents, true))) {
			throw new CompileException('Tag ' . $this->getNotation() . ' is unexpected here.');

		} elseif ($this->modifiers !== '' && !$modifiers) {
			throw new CompileException('Filters are not allowed in ' . $this->getNotation());

		} elseif ($arguments && $this->args === '') {
			$label = is_string($arguments) ? $arguments : 'arguments';
			throw new CompileException('Missing ' . $label . ' in ' . $this->getNotation());

		} elseif ($arguments === false && $this->args !== '') {
			throw new CompileException('Arguments are not allowed in ' . $this->getNotation());
		}
	}
}
