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
use Latte\Runtime as LR;
use function is_array;


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
			echo %raw::attrs($ʟ_tmp, %dump, %dump?) %line;',
			$this->args,
			self::class,
			$context->getEscaper()->getContentType() === Latte\ContentType::Xml,
			$context->migrationWarnings ?: null,
			$this->position,
		);
	}


	public static function attrs(mixed $attrs, bool $xml, bool $migrationWarnings = false): string
	{
		$attrs = $attrs === [$attrs[0] ?? null] ? $attrs[0] : $attrs; // checks if the value is an array, e.g. n:attr="$attrs"
		if (!is_array($attrs)) {
			return '';
		}

		$res = '';
		foreach ($attrs as $name => $value) {
			$attr = $xml ? self::formatXmlAttribute($name, $value) : self::formatHtmlAttribute($name, $value, $migrationWarnings);
			$res .= $attr ? ' ' . $attr : '';
		}

		return $res;
	}


	public static function formatHtmlAttribute(mixed $name, mixed $value, bool $migrationWarnings = false): string
	{
		LR\HtmlHelpers::validateAttributeName($name);
		$type = LR\HtmlHelpers::classifyAttributeType($name);
		if ($value === null || ($value === false && $type !== 'data' && $type !== 'aria')) {
			return '';
		} elseif ($value === true && $type === '') {
			return $name;
		} elseif ($migrationWarnings && is_array($value) && $type === 'data') {
			LR\HtmlHelpers::triggerMigrationWarning($name, "array value: previously it rendered as $name=\"val1 val2 ...\", now the attribute is JSON-encoded");
		}
		return LR\HtmlHelpers::{"format{$type}Attribute"}($name, $value, $migrationWarnings);
	}


	public static function formatXmlAttribute(mixed $name, mixed $value): string
	{
		LR\XmlHelpers::validateAttributeName($name);
		return $value === false ? '' : LR\XmlHelpers::formatAttribute($name, $value);
	}


	public function &getIterator(): \Generator
	{
		yield $this->args;
	}
}
