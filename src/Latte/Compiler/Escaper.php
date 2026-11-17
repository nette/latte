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
			self::HtmlAttribute . '/' . self::HtmlText => 'HtmlHelpers::escapeAttr',
			self::HtmlText . '/' . self::RcData => 'HtmlHelpers::convertHtmlToRcData',
			self::HtmlComment => 'HtmlHelpers::escapeComment',
			self::HtmlRawText . '/' . self::HtmlText => 'HtmlHelpers::convertHtmlToRawText',
		],
		self::HtmlAttribute => [
			self::HtmlText => 'HtmlHelpers::convertAttrToHtml',
			self::HtmlAttribute . '/' . self::HtmlText => 'HtmlHelpers::convertAttrToHtmlAttr',
		],
		self::HtmlAttribute . '/' . self::HtmlText => [
			self::HtmlText => 'HtmlHelpers::convertHtmlAttrToHtml',
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

	/** JS contexts where included text or HTML becomes a string literal, which makes no block types compatible */
	private const JsLiterals = [
		self::HtmlAttribute . '/' . self::JavaScript => 'HtmlHelpers::escapeJsAttr',
		self::HtmlRawText . '/' . self::JavaScript => 'Helpers::escapeJs',
		self::JavaScript => 'Helpers::escapeJs',
	];

	private string $state = '';
	private string $subType = '';


	public function __construct(
		private string $contentType,
		/** @var array<string, string> see HtmlHelpers::matchAttributeContentType() */
		private readonly array $attributeContentTypes = HtmlHelpers::AttributeContentTypes,
	) {
		$this->state = in_array($contentType, [ContentType::Html, ContentType::Xml], true)
			? self::HtmlText
			: $contentType;
	}


	public function getContentType(): string
	{
		return $this->contentType;
	}


	/** @return array<string, string> */
	public function getAttributeContentTypes(): array
	{
		return $this->attributeContentTypes;
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
			// ContentType::JavaScript, Css and Html equal the subTypes JavaScript, Css and HtmlText
			$this->subType = HtmlHelpers::matchAttributeContentType($name, $this->attributeContentTypes) ?? '';
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


	/**
	 * Generates code that converts trusted content into the current context and escapes anything else.
	 */
	public function escape(string $str): string
	{
		return '((is_object($ʟ_val = ' . $str . ') ? LR\Helpers::convertTrusted($ʟ_val, '
			. var_export($this->export(), return: true) . ') : null) ?? '
			. $this->callEscaper('$ʟ_val') . ')';
	}


	/**
	 * Generates code that calls the escaper of the current context.
	 */
	private function callEscaper(string $str): string
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
				self::HtmlText => match ($this->subType) {
					self::RcData => "LR\\HtmlHelpers::convertHtmlToRcData((string) $str)",
					default => $str,
				},
				self::HtmlAttribute => match ($this->subType) {
					self::HtmlText => "LR\\HtmlHelpers::escapeAttr((string) $str)",
					default => "LR\\HtmlHelpers::escapeQuotes($str)",
				},
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
		return self::findConvertor($source, $dest, literals: true);
	}


	/**
	 * Can a block of content type $type be overridden by a block of type $by?
	 */
	public static function canBeOverridden(string $type, string $by): bool
	{
		return self::findConvertor($type, $by, literals: false) !== null;
	}


	private static function findConvertor(string $source, string $dest, bool $literals): ?callable
	{
		return match (true) {
			$source === $dest => Helpers::nop(...),
			isset(self::Convertors[$source][$dest]) => 'Latte\Runtime\\' . self::Convertors[$source][$dest],
			$literals && in_array($source, [self::Text, self::HtmlText], true) && isset(self::JsLiterals[$dest])
				=> 'Latte\Runtime\\' . self::JsLiterals[$dest],
			$source === self::HtmlAttribute . '/' . self::HtmlText => ($convertor = self::findConvertor(self::HtmlText, $dest, $literals))
				? fn(string $s): string => $convertor(HtmlHelpers::convertHtmlAttrToHtml($s))
				: null,
			$source === self::HtmlText . '/' . self::RcData => ($convertor = self::findConvertor(self::HtmlText, $dest, $literals))
				? fn(string $s): string => $convertor(HtmlHelpers::convertRcDataToHtml($s))
				: null,
			default => self::getXmlHtmlConvertor($source, $dest, $literals),
		};
	}


	/**
	 * Converts XML and HTML into each other like HTML into HTML, which is deprecated.
	 */
	private static function getXmlHtmlConvertor(string $source, string $dest, bool $literals): ?callable
	{
		$isXml = fn(string $type): bool => $type === self::XmlText || str_starts_with($type, self::XmlText . '/');
		$isHtml = fn(string $type): bool => $type === self::HtmlText || str_starts_with($type, self::HtmlText . '/');
		if (!($isXml($source) && $isHtml($dest)) && !($isHtml($source) && $isXml($dest))) {
			return null;
		}

		$toHtml = fn(string $type): string => $isXml($type) ? self::HtmlText . substr($type, strlen(self::XmlText)) : $type;
		$twin = $source === self::XmlText
			? self::HtmlText . '/' . self::RcData // XML text does not separate braces, like the content of <textarea>
			: $toHtml($source);
		$convertor = self::findConvertor($twin, $toHtml($dest), $literals);
		if ($convertor !== null) {
			trigger_error(sprintf(
				'Converting content type %s into %s is deprecated, XML and HTML will be incompatible.',
				strtoupper($source),
				strtoupper($dest),
			), E_USER_DEPRECATED);
		}
		return $convertor;
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
