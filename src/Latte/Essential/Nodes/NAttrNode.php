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
use function is_array;


/**
 * <div n:attr="class => $class, id => $id">
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
		$escaper = $context->getEscaper();
		$xml = $escaper->getContentType() === Latte\ContentType::Xml;
		$contexts = $xml ? [] : $escaper->getAttributeContexts();
		return $context->format(
			'$ʟ_tmp = %node;
			echo %raw::attrs($ʟ_tmp, %dump, %dump?%raw) %line;',
			$this->args,
			self::class,
			$xml,
			$context->hasFeature(Latte\Feature::MigrationWarnings) ?: null,
			$contexts ? ', contexts: ' . PhpHelpers::dump($contexts) : '',
			$this->position,
		);
	}


	/** @param  array<string, string>  $contexts  custom attribute contexts, see HtmlHelpers::matchAttributeContext() */
	public static function attrs(mixed $attrs, bool $xml, bool $migrationWarnings = false, array $contexts = []): string
	{
		$attrs = $attrs === [$attrs[0] ?? null] ? $attrs[0] : $attrs; // checks if the value is an array, e.g. n:attr="$attrs"
		if (!is_array($attrs)) {
			return '';
		}

		$res = '';
		foreach ($attrs as $name => $value) {
			$attr = $xml
				? self::formatXmlAttribute($name, $value)
				: self::formatHtmlAttribute($name, $value, $migrationWarnings, $contexts);
			$res .= $attr ? ' ' . $attr : '';
		}

		return $res;
	}


	/** @param  array<string, string>  $contexts */
	public static function formatHtmlAttribute(mixed $name, mixed $value, bool $migrationWarnings = false, array $contexts = []): string
	{
		LR\HtmlHelpers::validateAttributeName($name);
		$type = LR\HtmlHelpers::classifyAttributeType($name, $contexts);
		if ($value === null || ($value === false && $type !== 'data' && $type !== 'aria' && $type !== 'tristate')) {
			return '';
		} elseif ($value === true && $type === '') {
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
