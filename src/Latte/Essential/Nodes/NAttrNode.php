<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte;
use Latte\Compiler\Nodes\Php\Expression\ArrayNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Runtime\Filters;
use function implode, is_array, is_string, strncmp;


/**
 * n:attr="..."
 */
final class NAttrNode extends StatementNode
{
	public ArrayNode $args;


	public static function create(Tag $tag): static
	{
		$tag->expectArguments();
		$node = new static;
		$node->args = $tag->parser->parseArguments();
		return $node;
	}


	public function print(PrintContext $context): string
	{
		return $context->format(
			'$ʟ_tmp = %node;
			echo %raw::attrs($ʟ_tmp, %dump) %line;',
			$this->args,
			self::class,
			$context->getEscaper()->getContentType() === Latte\ContentType::Xml,
			$this->position,
		);
	}


	public static function attrs(mixed $attrs, bool $xml): string
	{
		$attrs = $attrs === [$attrs[0] ?? null] ? $attrs[0] : $attrs; // checks if the value is an array, e.g. n:attr="$attrs"
		if (!is_array($attrs)) {
			return '';
		}

		$res = '';
		foreach ($attrs as $name => $value) {
			$attr = $xml ? self::formatXmlAttribute($name, $value) : self::formatHtmlAttribute($name, $value);
			$res .= $attr ? ' ' . $attr : '';
		}

		return $res;
	}


	public static function formatHtmlAttribute(string $name, mixed $value): ?string
	{
		if ($value === null || $value === false) {
			return null;

		} elseif ($value === true) {
			return $name;

		} elseif (is_array($value)) {
			$tmp = null;
			foreach ($value as $k => $v) {
				if ($v != null) { // intentionally ==, skip nulls & empty string
					//  composite 'style' vs. 'others'
					$tmp[] = $v === true
						? $k
						: (is_string($k) ? $k . ':' . $v : $v);
				}
			}

			if ($tmp === null) {
				return null;
			}

			$value = implode($name === 'style' || !strncmp($name, 'on', 2) ? ';' : ' ', $tmp);

		} else {
			$value = (string) $value;
		}

		return $name . '="' . Filters::escapeHtmlAttr($value) . '"';
	}


	public static function formatXmlAttribute(string $name, mixed $value): ?string
	{
		if ($value === null || $value === false) {
			return null;

		} elseif ($value === true) {
			return $name . '="' . $name . '"';

		} elseif (is_array($value)) {
			$value = array_filter($value); // intentionally ==, skip nulls & empty string
			if (!$value) {
				return null;
			}

			$value = implode(' ', $value);

		} else {
			$value = (string) $value;
		}

		return $name . '="' . Filters::escapeXmlAttr($value) . '"';
	}


	public function &getIterator(): \Generator
	{
		yield $this->args;
	}
}
