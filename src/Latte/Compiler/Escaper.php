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
use Latte\Runtime\Filters;


/**
 * Context-aware escaping.
 */
final class Escaper
{
	use Latte\Strict;

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
		HtmlAttributeQuoted = 'html/attr',
		HtmlAttributeUnquoted = 'html/unquoted-attr';

	private const Convertors = [
		self::Text => [
			self::HtmlText => 'escapeHtmlText',
			self::HtmlAttributeQuoted => 'escapeHtmlAttr',
			self::HtmlAttributeQuoted . '+' . self::JavaScript => 'escapeHtmlAttr',
			self::HtmlAttributeQuoted . '+' . self::Css => 'escapeHtmlAttr',
			self::HtmlAttributeQuoted . '+' . self::Url => 'escapeHtmlAttr',
			self::HtmlComment => 'escapeHtmlComment',
			'xml' => 'escapeXml',
			'xml/attr' => 'escapeXml',
		],
		self::JavaScript => [
			self::HtmlText => 'escapeHtmlText',
			self::HtmlAttributeQuoted => 'escapeHtmlAttr',
			self::HtmlAttributeQuoted . '+' . self::JavaScript => 'escapeHtmlAttr',
			self::HtmlRawText . '+' . self::JavaScript => 'convertJSToHtmlRawText',
			self::HtmlComment => 'escapeHtmlComment',
		],
		self::Css => [
			self::HtmlText => 'escapeHtmlText',
			self::HtmlAttributeQuoted => 'escapeHtmlAttr',
			self::HtmlAttributeQuoted . '+' . self::Css => 'escapeHtmlAttr',
			self::HtmlRawText . '+' . self::Css => 'convertJSToHtmlRawText',
			self::HtmlComment => 'escapeHtmlComment',
		],
		self::HtmlText => [
			self::HtmlAttributeQuoted => 'convertHtmlToHtmlAttr',
			self::HtmlAttributeQuoted . '+' . self::JavaScript => 'convertHtmlToHtmlAttr',
			self::HtmlAttributeQuoted . '+' . self::Css => 'convertHtmlToHtmlAttr',
			self::HtmlAttributeQuoted . '+' . self::Url => 'convertHtmlToHtmlAttr',
			self::HtmlComment => 'escapeHtmlComment',
			self::HtmlAttributeUnquoted => 'convertHtmlToUnquotedAttr',
			self::HtmlRawText . '+' . self::HtmlText => 'convertHtmlToHtmlRawText',
		],
		self::HtmlAttributeQuoted => [
			self::HtmlText => 'convertHtmlToHtmlAttr',
			self::HtmlAttributeUnquoted => 'convertHtmlAttrToUnquotedAttr',
		],
		self::HtmlAttributeQuoted . '+' . self::Url => [
			self::HtmlText => 'convertHtmlToHtmlAttr',
			self::HtmlAttributeQuoted => 'nop',
		],
		self::HtmlAttributeUnquoted => [
			self::HtmlText => 'convertHtmlToHtmlAttr',
		],
	];

	private string $state = '';
	private string $tag = '';
	private string $quote = '';
	private string $subState = '';


	public function __construct(
		private string $contentType,
	) {
		$this->state = $this->contentType;
	}


	public function getContentType(): string
	{
		return $this->contentType;
	}


	public function getState(): string
	{
		return $this->state . ($this->subState ? '+' . $this->subState : '');
	}


	/** @deprecated use getState() */
	public function export(): string
	{
		return $this->getState();
	}


	public function enterContentType(string $type): void
	{
		$this->contentType = $this->state = $type;
	}


	public function enterHtmlText(?ElementNode $node): void
	{
		if (
			$this->contentType === ContentType::Html
			&& $node
			&& in_array($name = strtolower($node->name), ['script', 'style'], true)
		) {
			$this->state = self::HtmlRawText;
			$this->subState = match (true) {
				$name === 'style' => self::Css,
				self::isJSScript($node) => self::JavaScript,
				self::isHtmlScript($node) => self::HtmlText,
				default => self::Text,
			};
		} else {
			$this->state = self::HtmlText;
			$this->subState = '';
		}
	}


	public function enterHtmlTag(string $name): void
	{
		$this->state = self::HtmlTag;
		$this->tag = strtolower($name);
	}


	public function enterHtmlAttribute(?string $name = null, string $quote = ''): void
	{
		$this->enterHtmlAttributeQuote($quote);
		$this->subState = '';

		if ($this->contentType === ContentType::Html && is_string($name)) {
			$name = strtolower($name);
			if (str_starts_with($name, 'on')) {
				$this->subState = self::JavaScript;
			} elseif ($name === 'style') {
				$this->subState = self::Css;
			} elseif ((in_array($name, ['href', 'src', 'action', 'formaction'], true)
				|| ($name === 'data' && $this->tag === 'object'))
			) {
				$this->subState = self::Url;
			}
		}
	}


	public function enterHtmlAttributeQuote(string $quote = '"'): void
	{
		$this->state = $quote ? self::HtmlAttributeQuoted : self::HtmlAttributeUnquoted;
		$this->quote = $quote;
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
		[$lq, $rq] = $this->state === self::HtmlAttributeUnquoted ? ["'\"' . ", " . '\"'"] : ['', ''];
		return match ($this->contentType) {
			ContentType::Html => match ($this->state) {
				self::HtmlText => 'LR\Filters::escapeHtmlText(' . $str . ')',
				self::HtmlTag => 'LR\Filters::escapeHtmlTag(' . $str . ')',
				self::HtmlAttributeQuoted, self::HtmlAttributeUnquoted => match ($this->subState) {
					'',
					self::Url => $lq . 'LR\Filters::escapeHtmlAttr(' . $str . ')' . $rq,
					self::JavaScript => $lq . 'LR\Filters::escapeHtmlAttr(LR\Filters::escapeJs(' . $str . '))' . $rq,
					self::Css => $lq . 'LR\Filters::escapeHtmlAttr(LR\Filters::escapeCss(' . $str . '))' . $rq,
				},
				self::HtmlComment => 'LR\Filters::escapeHtmlComment(' . $str . ')',
				self::HtmlBogusTag => 'LR\Filters::escapeHtml(' . $str . ')',
				self::HtmlRawText => match ($this->subState) {
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
				self::HtmlAttributeQuoted, self::HtmlAttributeUnquoted  => $lq . 'LR\Filters::escapeXml(' . $str . ')' . $rq,
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


	public function escapeMandatory(string $str, ?Position $position = null): string
	{
		$quote = var_export($this->quote, true);
		return match ($this->contentType) {
			ContentType::Html => match ($this->state) {
				self::HtmlAttributeQuoted => "LR\\Filters::escapeHtmlChar($str, $quote)",
				self::HtmlRawText => match ($this->subState) {
					self::HtmlText => 'LR\Filters::convertHtmlToHtmlRawText(' . $str . ')',
					default => "LR\\Filters::convertJSToHtmlRawText($str)",
				},
				self::HtmlAttributeUnquoted, self::HtmlComment => throw new Latte\CompileException('Using |noescape is not allowed in this context.', $position),
				default => $str,
			},
			ContentType::Xml => match ($this->state) {
				self::HtmlAttributeQuoted => "LR\\Filters::escapeHtmlChar($str, $quote)",
				self::HtmlAttributeUnquoted, self::HtmlComment => throw new Latte\CompileException('Using |noescape is not allowed in this context.', $position),
				default => $str,
			},
			default => $str,
		};
	}


	public function check(string $str): string
	{
		if ($this->isHtmlAttribute() && $this->subState === self::Url) {
			$str = 'LR\Filters::safeUrl(' . $str . ')';
		}
		return $str;
	}


	public function isHtmlAttribute(): bool
	{
		return $this->state === self::HtmlAttributeQuoted || $this->state === self::HtmlAttributeUnquoted;
	}


	public static function getConvertor(string $source, string $dest): ?callable
	{
		if ($source === $dest) {
			return [Filters::class, 'nop'];
		}

		return isset(self::Convertors[$source][$dest])
			? [Filters::class, self::Convertors[$source][$dest]]
			: null;
	}


	public static function isJSScript(ElementNode $el): bool
	{
		$type = $el->getAttribute('type');
		return strcasecmp($el->name, 'script') === 0
			&& ($type === true || $type === null || $type === ''
				|| is_string($type) && preg_match('#((application|text)/(((x-)?java|ecma|j|live)script|json)|text/plain|module|importmap)$#Ai', $type));
	}


	private static function isHtmlScript(ElementNode $el): bool
	{
		$type = $el->getAttribute('type');
		return strcasecmp($el->name, 'script') === 0
			&& is_string($type) && preg_match('#text/((x-)?template|html)$#Ai', $type);

	}
}
