<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Essential\Nodes;

use Latte;
use Latte\Compiler\Nodes\Php\Expression\ArrayNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\{PhpHelpers, PrintContext, Tag};
use Latte\Runtime as LR;
use function is_array, is_string;


/**
 * <div n:attr="class => $class, id => $id">
 */
final class NAttrNode extends StatementNode
{
	public ArrayNode $args;
	public string $elementName;


	public static function create(Tag $tag): static
	{
		assert($tag->htmlElement !== null);
		$tag->expectArguments();
		$node = new static;
		$node->args = $tag->parser->parseArguments();
		$node->elementName = $tag->htmlElement->name;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		$escaper = $context->getEscaper();
		$xml = $escaper->getContentType() === Latte\ContentType::Xml;
		$contentTypes = $escaper->getAttributeContentTypes();
		return $context->format(
			'$ʟ_tmp = %node;
			echo %raw::attrs($ʟ_tmp, %dump, %dump?%raw%raw) %line;',
			$this->args,
			self::class,
			$xml,
			$context->hasFeature(Latte\Feature::MigrationWarnings) ?: null,
			!$xml && $contentTypes !== LR\HtmlHelpers::AttributeContentTypes ? ', contentTypes: ' . PhpHelpers::dump($contentTypes) : '',
			$xml ? '' : ', tag: ' . PhpHelpers::dump($this->elementName),
			$this->position,
		);
	}


	/** @param  array<string, string>  $contentTypes  see HtmlHelpers::matchAttributeContentType() */
	public static function attrs(
		mixed $attrs,
		bool $xml,
		bool $migrationWarnings = false,
		array $contentTypes = LR\HtmlHelpers::AttributeContentTypes,
		string $tag = '',
	): string
	{
		$attrs = $attrs === [$attrs[0] ?? null] ? $attrs[0] : $attrs; // checks if the value is an array, e.g. n:attr="$attrs"
		if (!is_array($attrs)) {
			return '';
		}

		$res = '';
		foreach ($attrs as $name => $value) {
			$attr = $xml
				? self::formatXmlAttribute($name, $value)
				: self::formatHtmlAttribute($name, $value, $migrationWarnings, $contentTypes, $tag);
			$res .= $attr ? ' ' . $attr : '';
		}

		return $res;
	}


	/** @param  array<string, string>  $contentTypes */
	public static function formatHtmlAttribute(
		mixed $name,
		mixed $value,
		bool $migrationWarnings = false,
		array $contentTypes = LR\HtmlHelpers::AttributeContentTypes,
		string $tag = '',
	): string
	{
		LR\HtmlHelpers::validateAttributeName($name);
		if ((is_string($value) || $value instanceof \Stringable) && LR\HtmlHelpers::isUrlAttribute($tag, $name)) {
			$value = Latte\Essential\Filters::checkUrl($value);
		}

		$type = LR\HtmlHelpers::classifyAttributeType($name, $contentTypes);
		if ($value === null || ($value === false && $type !== 'data' && $type !== 'aria' && $type !== 'tristate')) {
			return '';
		} elseif ($value === true && ($type === '' || $type === 'js')) {
			return $name;
		} elseif ($migrationWarnings && is_array($value) && $type === 'data') {
			LR\HtmlHelpers::triggerMigrationWarning(
				$name,
				"array value: previously it rendered as $name=\"val1 val2 ...\", now the attribute is JSON-encoded",
			);
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
