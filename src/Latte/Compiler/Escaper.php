<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte;
use Latte\CompileException;
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
		HtmlComment = 'htmlComment',
		HtmlBogusTag = 'htmlBogus',
		HtmlCss = 'htmlCss',
		HtmlJavaScript = 'htmlJs',
		HtmlTag = 'htmlTag',
		HtmlAttribute = 'htmlAttr';

	private string $state = '';
	private string $tag = '';
	private string $quote = '';
	private string $subType = '';


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
		return $this->state;
	}


	public function export(): string
	{
		return $this->state . ucfirst($this->subType);
	}


	public function enterContentType(string $type): void
	{
		$this->contentType = $this->state = $type;
	}


	public function enterHtmlText(?ElementNode $node): void
	{
		$this->state = self::HtmlText;
		if ($this->contentType === ContentType::Html && $node) {
			$name = strtolower($node->name);
			if (
				($name === 'script' || $name === 'style')
				&& is_string($attr = $node->getAttribute('type') ?? 'css')
				&& preg_match('#(java|j|ecma|live)script|module|json|css|plain#i', $attr)
			) {
				$this->state = $name === 'script'
					? self::HtmlJavaScript
					: self::HtmlCss;
			}
		}
	}


	public function enterHtmlTag(string $name): void
	{
		$this->state = self::HtmlTag;
		$this->tag = strtolower($name);
	}


	public function enterHtmlAttribute(?string $name, ?string $quote): void
	{
		$this->state = self::HtmlTag;

		if ($this->contentType === ContentType::Html && is_string($name)) {
			$name = strtolower($name);
			if (str_starts_with($name, 'on')) {
				$this->state = self::HtmlAttribute;
				$this->subType = self::JavaScript;
			} elseif ($name === 'style') {
				$this->state = self::HtmlAttribute;
				$this->subType = self::Css;
			} elseif ((in_array($name, ['href', 'src', 'action', 'formaction'], true)
				|| ($name === 'data' && $this->tag === 'object'))
			) {
				$this->state = self::HtmlAttribute;
				$this->subType = self::Url;
			}
		}

		if ($quote) {
			$this->state = self::HtmlAttribute;
			$this->quote = $quote;
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


	public function escape(MacroTokens $tokens): MacroTokens
	{
		[$lq, $rq] = $this->state === self::HtmlAttribute && !$this->quote ? ["'\"' . ", " . '\"'"] : ['', ''];
		return match ($this->contentType) {
			ContentType::Html => match ($this->state) {
				self::HtmlText => $tokens->prepend('LR\Filters::escapeHtmlText(')->append(')'),
				self::HtmlTag => $tokens->prepend('LR\Filters::escapeHtmlAttrUnquoted(')->append(')'),
				self::HtmlAttribute => match ($this->subType) {
					'',
					self::Url => $tokens->prepend($lq . 'LR\Filters::escapeHtmlAttr(')->append(')' . $rq),
					self::JavaScript => $tokens->prepend($lq . 'LR\Filters::escapeHtmlAttr(LR\Filters::escapeJs(')->append('))' . $rq),
					self::Css => $tokens->prepend($lq . 'LR\Filters::escapeHtmlAttr(LR\Filters::escapeCss(')->append('))' . $rq),
				},
				self::HtmlComment => $tokens->prepend('LR\Filters::escapeHtmlComment(')->append(')'),
				self::HtmlBogusTag => $tokens->prepend('LR\Filters::escapeHtml(')->append(')'),
				self::HtmlJavaScript => $tokens->prepend('LR\Filters::escapeJs(')->append(')'),
				self::HtmlCss => $tokens->prepend('LR\Filters::escapeCss(')->append(')'),
				default => throw new CompileException("Unknown context $this->contentType, $this->state."),
			},
			ContentType::Xml => match ($this->state) {
				self::HtmlText,
				self::HtmlBogusTag => $tokens->prepend('LR\Filters::escapeXml(')->append(')'),
				self::HtmlAttribute => $tokens->prepend($lq . 'LR\Filters::escapeXml(')->append(')' . $rq),
				self::HtmlComment => $tokens->prepend('LR\Filters::escapeHtmlComment(')->append(')'),
				self::HtmlTag => $tokens->prepend('LR\Filters::escapeXmlAttrUnquoted(')->append(')'),
				default => throw new CompileException("Unknown context $this->contentType, $this->state."),
			},
			ContentType::JavaScript => $tokens->prepend('LR\Filters::escapeJs(')->append(')'),
			ContentType::Css => $tokens->prepend('LR\Filters::escapeCss(')->append(')'),
			ContentType::ICal => $tokens->prepend('LR\Filters::escapeIcal(')->append(')'),
			ContentType::Text => $tokens,
			'' => $tokens->prepend('($this->filters->escape)(')->append(')'),
			default => throw new CompileException("Unknown content-type $this->contentType."),
		};
	}


	public static function getConvertor(string $source, string $dest): ?callable
	{
		$table = [
			self::Text => [
				'html' => 'escapeHtmlText',
				'htmlAttr' => 'escapeHtmlAttr',
				'htmlAttrJs' => 'escapeHtmlAttr',
				'htmlAttrCss' => 'escapeHtmlAttr',
				'htmlAttrUrl' => 'escapeHtmlAttr',
				'htmlComment' => 'escapeHtmlComment',
				'xml' => 'escapeXml',
				'xmlAttr' => 'escapeXml',
			],
			self::JavaScript => [
				'html' => 'escapeHtmlText',
				'htmlAttr' => 'escapeHtmlAttr',
				'htmlAttrJs' => 'escapeHtmlAttr',
				'htmlJs' => 'escapeHtmlRawText',
				'htmlComment' => 'escapeHtmlComment',
			],
			self::Css => [
				'html' => 'escapeHtmlText',
				'htmlAttr' => 'escapeHtmlAttr',
				'htmlAttrCss' => 'escapeHtmlAttr',
				'htmlCss' => 'escapeHtmlRawText',
				'htmlComment' => 'escapeHtmlComment',
			],
			'html' => [
				'htmlAttr' => 'convertHtmlToHtmlAttr',
				'htmlAttrJs' => 'convertHtmlToHtmlAttr',
				'htmlAttrCss' => 'convertHtmlToHtmlAttr',
				'htmlAttrUrl' => 'convertHtmlToHtmlAttr',
				'htmlComment' => 'escapeHtmlComment',
			],
		];
		return isset($table[$source][$dest])
			? [Filters::class, $table[$source][$dest]]
			: null;
	}
}
