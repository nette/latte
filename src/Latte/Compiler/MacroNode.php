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

	public Macro $macro;
	public string $name;
	public bool $empty = false;
	public string $args;
	public string $modifiers;
	public bool $closing = false;
	public ?bool $replaced = null;
	public MacroTokens $tokenizer;
	public ?MacroNode $parentNode = null;
	public ?string $openingCode = null;
	public ?string $closingCode = null;
	public ?string $attrCode = null;
	public ?string $content = null;
	public string $innerContent = '';
	public \stdClass $data;

	/** closest HTML node */
	public ?HtmlNode $htmlNode = null;

	/** @var array{string, mixed} [contentType, context] */
	public ?array $context = null;

	/** indicates n:attribute macro and type of prefix (PREFIX_INNER, PREFIX_TAG, PREFIX_NONE) */
	public ?string $prefix = null;

	/** position of start tag in source template */
	public ?int $startLine = null;

	/** position of end tag in source template */
	public ?int $endLine = null;

	/** @var array{string, bool}|null */
	public ?array $saved = null;


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
	 * @param  string[]  $parents
	 * @throws CompileException
	 */
	public function validate(string|bool|null $arguments, array $parents = [], bool $modifiers = false): void
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
