<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler;

use Latte;
use Latte\Compiler\Nodes\Html\ElementNode;
use Latte\ContentType;
use Latte\Runtime\{Helpers, HtmlHelpers};
use function is_string, sprintf, strlen;


/**
 * Context-aware escaping.
 */
class Escaper
{
	public const
		Text = 'text',
		JavaScript = 'js',
		Css = 'css',
		ICal = 'ical',
		RcData = 'rcdata';

	public const
		HtmlText = 'html',
		HtmlComment = 'html/comment',
		HtmlBogusTag = 'html/bogus',
		HtmlRawText = 'html/raw',
		HtmlTag = 'html/tag',
		HtmlAttribute = 'html/attr';

	/** XML uses the HTML states, export() names them after XML */
	public const
		XmlText = 'xml',
		XmlComment = 'xml/comment',
		XmlAttribute = 'xml/attr';

	private const Convertors = [
		self::Text => [
			self::HtmlText => 'HtmlHelpers::escapeText',
			self::HtmlAttribute => 'HtmlHelpers::escapeAttr',
			self::HtmlAttribute . '/' . self::JavaScript => 'HtmlHelpers::escapeAttr',
			self::HtmlAttribute . '/' . self::Css => 'HtmlHelpers::escapeAttr',
			self::HtmlAttribute . '/' . self::HtmlText => 'HtmlHelpers::escapeHtmlAttr',
			self::HtmlText . '/' . self::RcData => 'HtmlHelpers::escapeRcData',
			self::HtmlComment => 'HtmlHelpers::escapeComment',
			self::XmlText => 'XmlHelpers::escapeText',
			self::XmlAttribute => 'XmlHelpers::escapeAttr',
			self::XmlComment => 'HtmlHelpers::escapeComment',
		],
		self::JavaScript => [
			self::HtmlText => 'HtmlHelpers::escapeText',
			self::HtmlAttribute => 'HtmlHelpers::escapeAttr',
			self::HtmlAttribute . '/' . self::JavaScript => 'HtmlHelpers::escapeAttr',
			self::HtmlAttribute . '/' . self::HtmlText => 'HtmlHelpers::escapeHtmlAttr',
			self::HtmlText . '/' . self::RcData => 'HtmlHelpers::escapeRcData',
			self::HtmlRawText . '/' . self::JavaScript => 'HtmlHelpers::convertJSToRawText',
			self::HtmlComment => 'HtmlHelpers::escapeComment',
			self::XmlText => 'XmlHelpers::escapeText',
			self::XmlAttribute => 'XmlHelpers::escapeAttr',
			self::XmlComment => 'HtmlHelpers::escapeComment',
		],
		self::Css => [
			self::HtmlText => 'HtmlHelpers::escapeText',
			self::HtmlAttribute => 'HtmlHelpers::escapeAttr',
			self::HtmlAttribute . '/' . self::Css => 'HtmlHelpers::escapeAttr',
			self::HtmlAttribute . '/' . self::HtmlText => 'HtmlHelpers::escapeHtmlAttr',
			self::HtmlText . '/' . self::RcData => 'HtmlHelpers::escapeRcData',
			self::HtmlRawText . '/' . self::Css => 'HtmlHelpers::convertJSToRawText',
			self::HtmlComment => 'HtmlHelpers::escapeComment',
			self::XmlText => 'XmlHelpers::escapeText',
			self::XmlAttribute => 'XmlHelpers::escapeAttr',
			self::XmlComment => 'HtmlHelpers::escapeComment',
		],
		self::HtmlText => [
			self::Text => 'HtmlHelpers::convertHtmlToText',
			self::HtmlAttribute => 'HtmlHelpers::convertHtmlToAttr',
			self::HtmlAttribute . '/' . self::JavaScript => 'HtmlHelpers::convertHtmlToAttr',
			self::HtmlAttribute . '/' . self::Css => 'HtmlHelpers::convertHtmlToAttr',
			self::HtmlAttribute . '/' . self::HtmlText => 'HtmlHelpers::escapeAttr',
			self::HtmlText . '/' . self::RcData => 'Helpers::nop',
			self::HtmlComment => 'HtmlHelpers::escapeComment',
			self::HtmlRawText . '/' . self::HtmlText => 'HtmlHelpers::convertHtmlToRawText',
		],
		self::HtmlAttribute => [
			self::HtmlText => 'HtmlHelpers::convertAttrToHtml',
		],
		self::HtmlText . '/' . self::RcData => [
			self::HtmlText => 'HtmlHelpers::convertRcDataToHtml',
		],
		self::XmlText => [
			self::Text => 'HtmlHelpers::convertHtmlToText',
			self::XmlAttribute => 'XmlHelpers::convertXmlToAttr',
			self::XmlComment => 'HtmlHelpers::escapeComment',
		],
		self::XmlAttribute => [
			self::XmlText => 'Helpers::nop', // XML escapes attribute values and text alike
		],
	];

	private string $state = '';
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


	/**
	 * Returns the current escaping state as a string (e.g. "html/attr/js", "xml/attr").
	 */
	public function export(): string
	{
		$state = $this->contentType === ContentType::Xml && str_starts_with($this->state, self::HtmlText)
			? self::XmlText . substr($this->state, strlen(self::HtmlText))
			: $this->state;
		return $state . ($this->subType ? '/' . $this->subType : '');
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
				$this->subType = is_string($type)
					? HtmlHelpers::classifyScriptType($type)
					: self::JavaScript; // missing or dynamic type attribute
			} elseif ($el->is('style')) {
				$this->subType = self::Css;
			}

		} else {
			$this->state = self::HtmlText;
			$this->subType = $this->contentType === ContentType::Html && self::isRcData($el) ? self::RcData : '';
		}
	}


	public function enterHtmlTag(string $name): void
	{
		$this->state = self::HtmlTag;
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
			} elseif ($name === 'srcdoc') {
				$this->subType = self::HtmlText;
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
				self::HtmlText => match ($this->subType) {
					'' => 'LR\HtmlHelpers::escapeText(' . $str . ')',
					self::RcData => 'LR\HtmlHelpers::escapeRcData(' . $str . ')',
				},
				self::HtmlTag => 'LR\HtmlHelpers::escapeTag(' . $str . ')',
				self::HtmlAttribute => match ($this->subType) {
					'' => 'LR\HtmlHelpers::escapeAttr(' . $str . ')',
					self::JavaScript => 'LR\HtmlHelpers::escapeAttr(LR\Helpers::escapeJs(' . $str . '))',
					self::Css => 'LR\HtmlHelpers::escapeAttr(LR\Helpers::escapeCss(' . $str . '))',
					self::HtmlText => 'LR\HtmlHelpers::escapeHtmlAttr(' . $str . ')',
				},
				self::HtmlComment => 'LR\HtmlHelpers::escapeComment(' . $str . ')',
				self::HtmlBogusTag => 'LR\HtmlHelpers::escapeTag(' . $str . ')',
				self::HtmlRawText => match ($this->subType) {
					self::Text => 'LR\HtmlHelpers::convertJSToRawText(' . $str . ')', // sanitization, escaping is not possible
					self::HtmlText => 'LR\HtmlHelpers::escapeRawHtml(' . $str . ')',
					self::JavaScript => 'LR\Helpers::escapeJs(' . $str . ')',
					self::Css => 'LR\Helpers::escapeCss(' . $str . ')',
					default => throw new \LogicException("Unknown raw text subtype '$this->subType'."),
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


	/**
	 * Applies mandatory escaping that cannot be suppressed (e.g. quotes in attributes).
	 */
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


	/**
	 * Generates code that converts content between content types at runtime using FilterInfo.
	 */
	public function escapeContent(string $str): string
	{
		return 'LR\Helpers::convertTo($ʟ_fi, '
			. var_export($this->export(), return: true) . ', '
			. $str
			. ')';
	}


	/**
	 * Returns a callable that converts content from $source type to $dest type, or null if unsupported.
	 */
	public static function getConvertor(string $source, string $dest): ?callable
	{
		return match (true) {
			$source === $dest => Helpers::nop(...),
			isset(self::Convertors[$source][$dest]) => 'Latte\Runtime\\' . self::Convertors[$source][$dest],
			default => self::getXmlHtmlConvertor($source, $dest),
		};
	}


	/**
	 * XML contexts used to be exported as HTML ones, so XML and HTML converted into each other
	 * like HTML into HTML. Keeps that working, with a deprecation when it converts.
	 */
	private static function getXmlHtmlConvertor(string $source, string $dest): ?callable
	{
		$isXml = fn(string $type): bool => $type === self::XmlText || str_starts_with($type, self::XmlText . '/');
		$isHtml = fn(string $type): bool => $type === self::HtmlText || str_starts_with($type, self::HtmlText . '/');
		if (!($isXml($source) && $isHtml($dest)) && !($isHtml($source) && $isXml($dest))) {
			return null;
		}

		$toHtml = fn(string $type): string => $isXml($type) ? self::HtmlText . substr($type, strlen(self::XmlText)) : $type;
		$convertor = self::getConvertor($toHtml($source), $toHtml($dest));
		return $convertor === null ? null : function (string $s) use ($convertor, $source, $dest): string {
			trigger_error(sprintf(
				'Converting content type %s into %s is deprecated, XML and HTML will be incompatible.',
				strtoupper($source),
				strtoupper($dest),
			), E_USER_DEPRECATED);
			return $convertor($s);
		};
	}


	/**
	 * Is the element inside <textarea> or <title>, whose content the browser does not parse as HTML?
	 */
	private static function isRcData(ElementNode $el): bool
	{
		$res = false;
		for (; $el; $el = $el->parent) {
			if ($el->is('svg') || $el->is('math')) {
				return false; // <title> in SVG is an ordinary element
			}
			$res = $res || $el->is('textarea') || $el->is('title');
		}
		return $res;
	}
}
