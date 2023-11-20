<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\Compiler\Nodes\Html\ElementNode;
use Latte\ContentType;
use Latte\Runtime\Filters;


/**
 * Context-aware escaping.
 */
final class Escaper
{
	public const
		Text = 'text',
		JavaScript = 'js',
		Css = 'css',
		ICal = 'ical',
		Url = 'url';

	public const
		HtmlText = 'html',
		HtmlComment = 'html/comment',
		HtmlBogusTag = 'html/bogus',
		HtmlRawText = 'html/raw',
		HtmlTag = 'html/tag',
		HtmlAttribute = 'html/attr';

	private const Convertors = [
		self::Text => [
			self::HtmlText => 'escapeHtmlText',
			self::HtmlAttribute => 'escapeHtmlAttr',
			self::HtmlAttribute . '/' . self::JavaScript => 'escapeHtmlAttr',
			self::HtmlAttribute . '/' . self::Css => 'escapeHtmlAttr',
			self::HtmlAttribute . '/' . self::Url => 'escapeHtmlAttr',
			self::HtmlComment => 'escapeHtmlComment',
			'xml' => 'escapeXml',
			'xml/attr' => 'escapeXml',
		],
		self::JavaScript => [
			self::HtmlText => 'escapeHtmlText',
			self::HtmlAttribute => 'escapeHtmlAttr',
			self::HtmlAttribute . '/' . self::JavaScript => 'escapeHtmlAttr',
			self::HtmlRawText . '/' . self::JavaScript => 'convertJSToHtmlRawText',
			self::HtmlComment => 'escapeHtmlComment',
		],
		self::Css => [
			self::HtmlText => 'escapeHtmlText',
			self::HtmlAttribute => 'escapeHtmlAttr',
			self::HtmlAttribute . '/' . self::Css => 'escapeHtmlAttr',
			self::HtmlRawText . '/' . self::Css => 'convertJSToHtmlRawText',
			self::HtmlComment => 'escapeHtmlComment',
		],
		self::HtmlText => [
			self::HtmlAttribute => 'convertHtmlToHtmlAttr',
			self::HtmlAttribute . '/' . self::JavaScript => 'convertHtmlToHtmlAttr',
			self::HtmlAttribute . '/' . self::Css => 'convertHtmlToHtmlAttr',
			self::HtmlAttribute . '/' . self::Url => 'convertHtmlToHtmlAttr',
			self::HtmlComment => 'escapeHtmlComment',
			self::HtmlRawText . '/' . self::HtmlText => 'convertHtmlToHtmlRawText',
		],
		self::HtmlAttribute => [
			self::HtmlText => 'convertHtmlToHtmlAttr',
		],
		self::HtmlAttribute . '/' . self::Url => [
			self::HtmlText => 'convertHtmlToHtmlAttr',
			self::HtmlAttribute => 'nop',
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
			$this->subType = self::Text;
			if ($el->is('script')) {
				$type = $el->getAttribute('type');
				if ($type === true || $type === null
					|| is_string($type) && preg_match('#((application|text)/(((x-)?java|ecma|j|live)script|json)|application/.+\+json|text/plain|module|importmap|)$#Ai', $type)
				) {
					$this->subType = self::JavaScript;

				} elseif (is_string($type) && preg_match('#text/((x-)?template|html)$#Ai', $type)) {
					$this->subType = self::HtmlText;
				}

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
		$this->tag = strtolower($name);
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
			} elseif ((in_array($name, ['href', 'src', 'action', 'formaction'], true)
				|| ($name === 'data' && $this->tag === 'object'))
			) {
				$this->subType = self::Url;
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
				self::HtmlText => 'LR\Filters::escapeHtmlText(' . $str . ')',
				self::HtmlTag => 'LR\Filters::escapeHtmlTag(' . $str . ')',
				self::HtmlAttribute => match ($this->subType) {
					'',
					self::Url => 'LR\Filters::escapeHtmlAttr(' . $str . ')',
					self::JavaScript => 'LR\Filters::escapeHtmlAttr(LR\Filters::escapeJs(' . $str . '))',
					self::Css => 'LR\Filters::escapeHtmlAttr(LR\Filters::escapeCss(' . $str . '))',
				},
				self::HtmlComment => 'LR\Filters::escapeHtmlComment(' . $str . ')',
				self::HtmlBogusTag => 'LR\Filters::escapeHtml(' . $str . ')',
				self::HtmlRawText => match ($this->subType) {
					self::Text => 'LR\Filters::convertJSToHtmlRawText(' . $str . ')', // sanitization, escaping is not possible
					self::HtmlText => 'LR\Filters::escapeHtmlRawTextHtml(' . $str . ')',
					self::JavaScript => 'LR\Filters::escapeJs(' . $str . ')',
					self::Css => 'LR\Filters::escapeCss(' . $str . ')',
				},
				default => throw new \LogicException("Unknown context $this->contentType, $this->state."),
			},
			ContentType::Xml => match ($this->state) {
				self::HtmlText,
				self::HtmlBogusTag => 'LR\Filters::escapeXml(' . $str . ')',
				self::HtmlAttribute => 'LR\Filters::escapeXml(' . $str . ')',
				self::HtmlComment => 'LR\Filters::escapeHtmlComment(' . $str . ')',
				self::HtmlTag => 'LR\Filters::escapeXmlTag(' . $str . ')',
				default => throw new \LogicException("Unknown context $this->contentType, $this->state."),
			},
			ContentType::JavaScript => 'LR\Filters::escapeJs(' . $str . ')',
			ContentType::Css => 'LR\Filters::escapeCss(' . $str . ')',
			ContentType::ICal => 'LR\Filters::escapeIcal(' . $str . ')',
			ContentType::Text => '($this->filters->escape)(' . $str . ')',
			default => throw new \LogicException("Unknown content-type $this->contentType."),
		};
	}


	public function escapeMandatory(string $str): string
	{
		return match ($this->contentType) {
			ContentType::Html => match ($this->state) {
				self::HtmlAttribute => "LR\\Filters::escapeHtmlQuotes($str)",
				self::HtmlRawText => match ($this->subType) {
					self::HtmlText => 'LR\Filters::convertHtmlToHtmlRawText(' . $str . ')',
					default => "LR\\Filters::convertJSToHtmlRawText($str)",
				},
				self::HtmlComment => 'LR\Filters::escapeHtmlComment(' . $str . ')',
				default => $str,
			},
			ContentType::Xml => match ($this->state) {
				self::HtmlAttribute => "LR\\Filters::escapeHtmlQuotes($str)",
				self::HtmlComment => 'LR\Filters::escapeHtmlComment(' . $str . ')',
				default => $str,
			},
			default => $str,
		};
	}


	public function check(string $str): string
	{
		if ($this->state === self::HtmlAttribute && $this->subType === self::Url) {
			$str = 'LR\Filters::safeUrl(' . $str . ')';
		}
		return $str;
	}


	public static function getConvertor(string $source, string $dest): ?callable
	{
		return match (true) {
			$source === $dest => [Filters::class, 'nop'],
			isset(self::Convertors[$source][$dest]) => [Filters::class, self::Convertors[$source][$dest]],
			default => null,
		};
	}
}
