<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte;
use Latte\Compiler\Nodes\Html\ElementNode;
use Latte\ContentType;
use Latte\Runtime\Helpers;
use Latte\Runtime\HtmlHelpers;
use function in_array, is_string, str_starts_with, strtolower;


/**
 * Context-aware escaping.
 */
final class Escaper
{
	public const
		Text = 'text',
		JavaScript = 'js',
		Css = 'css',
		ICal = 'ical';

	public const
		HtmlText = 'html',
		HtmlComment = 'html/comment',
		HtmlBogusTag = 'html/bogus',
		HtmlRawText = 'html/raw',
		HtmlTag = 'html/tag',
		HtmlAttribute = 'html/attr';

	private const Convertors = [
		self::Text => [
			self::HtmlText => 'HtmlHelpers::escapeText',
			self::HtmlAttribute => 'HtmlHelpers::escapeAttr',
			self::HtmlAttribute . '/' . self::JavaScript => 'HtmlHelpers::escapeAttr',
			self::HtmlAttribute . '/' . self::Css => 'HtmlHelpers::escapeAttr',
			self::HtmlComment => 'HtmlHelpers::escapeComment',
			'xml' => 'XmlHelpers::escapeText',
			'xml/attr' => 'XmlHelpers::escapeAttr',
		],
		self::JavaScript => [
			self::HtmlText => 'HtmlHelpers::escapeText',
			self::HtmlAttribute => 'HtmlHelpers::escapeAttr',
			self::HtmlAttribute . '/' . self::JavaScript => 'HtmlHelpers::escapeAttr',
			self::HtmlRawText . '/' . self::JavaScript => 'HtmlHelpers::convertJSToRawText',
			self::HtmlComment => 'HtmlHelpers::escapeComment',
		],
		self::Css => [
			self::HtmlText => 'HtmlHelpers::escapeText',
			self::HtmlAttribute => 'HtmlHelpers::escapeAttr',
			self::HtmlAttribute . '/' . self::Css => 'HtmlHelpers::escapeAttr',
			self::HtmlRawText . '/' . self::Css => 'HtmlHelpers::convertJSToRawText',
			self::HtmlComment => 'HtmlHelpers::escapeComment',
		],
		self::HtmlText => [
			self::Text => 'HtmlHelpers::convertHtmlToText',
			self::HtmlAttribute => 'HtmlHelpers::convertHtmlToAttr',
			self::HtmlAttribute . '/' . self::JavaScript => 'HtmlHelpers::convertHtmlToAttr',
			self::HtmlAttribute . '/' . self::Css => 'HtmlHelpers::convertHtmlToAttr',
			self::HtmlComment => 'HtmlHelpers::escapeComment',
			self::HtmlRawText . '/' . self::HtmlText => 'HtmlHelpers::convertHtmlToRawText',
		],
		self::HtmlAttribute => [
			self::HtmlText => 'HtmlHelpers::convertAttrToHtml',
		],
	];

	private string $state = '';
	private string $tag = '';
	private string $subType = '';


	public function __construct(
		private string $contentType,
	) {
		$this->state = in_array($contentType, [ContentType::Html, ContentType::Xml], true)
			? self::HtmlText
			: $contentType;
	}


	public function getContentType(): string
	{
		return $this->contentType;
	}


	public function getState(): string
	{
		return $this->state;
	}


	public function export(): string
	{
		return $this->state . ($this->subType ? '/' . $this->subType : '');
	}


	public function enterContentType(string $type): void
	{
		$this->contentType = $this->state = $type;
	}


	public function enterHtmlText(ElementNode $el): void
	{
		if ($el->isRawText()) {
			$this->state = self::HtmlRawText;
			if ($el->is('script')) {
				$type = $el->getAttribute('type');
				$this->subType = $type === true || $type === null
					? self::JavaScript
					: HtmlHelpers::classifyScriptType($type);
			} elseif ($el->is('style')) {
				$this->subType = self::Css;
			}

		} else {
			$this->state = self::HtmlText;
			$this->subType = '';
		}
	}


	public function enterHtmlTag(string $name): void
	{
		$this->state = self::HtmlTag;
		$this->tag = $name;
	}


	public function enterHtmlRaw(string $subType): void
	{
		$this->state = self::HtmlRawText;
		$this->subType = $subType;
	}


	public function enterHtmlAttribute(?string $name = null): void
	{
		$this->state = self::HtmlAttribute;
		$this->subType = '';

		if ($this->contentType === ContentType::Html && is_string($name)) {
			$name = strtolower($name);
			if (str_starts_with($name, 'on')) {
				$this->subType = self::JavaScript;
			} elseif ($name === 'style') {
				$this->subType = self::Css;
			}
		}
	}


	public function enterHtmlBogusTag(): void
	{
		$this->state = self::HtmlBogusTag;
	}


	public function enterHtmlComment(): void
	{
		$this->state = self::HtmlComment;
	}


	public function escape(string $str): string
	{
		return match ($this->contentType) {
			ContentType::Html => match ($this->state) {
				self::HtmlText => 'LR\HtmlHelpers::escapeText(' . $str . ')',
				self::HtmlTag => 'LR\HtmlHelpers::escapeTag(' . $str . ')',
				self::HtmlAttribute => match ($this->subType) {
					'' => 'LR\HtmlHelpers::escapeAttr(' . $str . ')',
					self::JavaScript => 'LR\HtmlHelpers::escapeAttr(LR\Helpers::escapeJs(' . $str . '))',
					self::Css => 'LR\HtmlHelpers::escapeAttr(LR\Helpers::escapeCss(' . $str . '))',
				},
				self::HtmlComment => 'LR\HtmlHelpers::escapeComment(' . $str . ')',
				self::HtmlBogusTag => 'LR\HtmlHelpers::escapeTag(' . $str . ')',
				self::HtmlRawText => match ($this->subType) {
					self::Text => 'LR\HtmlHelpers::convertJSToRawText(' . $str . ')', // sanitization, escaping is not possible
					self::HtmlText => 'LR\HtmlHelpers::escapeRawHtml(' . $str . ')',
					self::JavaScript => 'LR\Helpers::escapeJs(' . $str . ')',
					self::Css => 'LR\Helpers::escapeCss(' . $str . ')',
				},
				default => throw new \LogicException("Unknown context $this->contentType, $this->state."),
			},
			ContentType::Xml => match ($this->state) {
				self::HtmlText => 'LR\XmlHelpers::escapeText(' . $str . ')',
				self::HtmlBogusTag => 'LR\XmlHelpers::escapeTag(' . $str . ')',
				self::HtmlAttribute => 'LR\XmlHelpers::escapeAttr(' . $str . ')',
				self::HtmlComment => 'LR\HtmlHelpers::escapeComment(' . $str . ')',
				self::HtmlTag => 'LR\XmlHelpers::escapeTag(' . $str . ')',
				default => throw new \LogicException("Unknown context $this->contentType, $this->state."),
			},
			ContentType::JavaScript => 'LR\Helpers::escapeJs(' . $str . ')',
			ContentType::Css => 'LR\Helpers::escapeCss(' . $str . ')',
			ContentType::ICal => 'LR\Helpers::escapeIcal(' . $str . ')',
			ContentType::Text => '($this->filters->escape)(' . $str . ')',
			default => throw new \LogicException("Unknown content-type $this->contentType."),
		};
	}


	public function escapeMandatory(string $str, ?Position $position = null): string
	{
		return match ($this->contentType) {
			ContentType::Html => match ($this->state) {
				self::HtmlAttribute => "LR\\HtmlHelpers::escapeQuotes($str)",
				self::HtmlRawText => match ($this->subType) {
					self::HtmlText => 'LR\HtmlHelpers::convertHtmlToRawText(' . $str . ')',
					default => "LR\\HtmlHelpers::convertJSToRawText($str)",
				},
				self::HtmlComment => throw new Latte\CompileException('Using |noescape is not allowed in this context.', $position),
				default => $str,
			},
			ContentType::Xml => match ($this->state) {
				self::HtmlAttribute => "LR\\HtmlHelpers::escapeQuotes($str)",
				self::HtmlComment => throw new Latte\CompileException('Using |noescape is not allowed in this context.', $position),
				default => $str,
			},
			default => $str,
		};
	}


	public function escapeContent(string $str): string
	{
		return 'LR\Helpers::convertTo($ʟ_fi, '
			. var_export($this->export(), true) . ', '
			. $str
			. ')';
	}


	public static function getConvertor(string $source, string $dest): ?callable
	{
		return match (true) {
			$source === $dest => Helpers::nop(...),
			isset(self::Convertors[$source][$dest]) => 'Latte\Runtime\\' . self::Convertors[$source][$dest],
			default => null,
		};
	}
}
