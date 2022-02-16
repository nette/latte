<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\CompileException;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\Html;
use Latte\Compiler\Nodes\TextNode;
use Latte\Helpers;
use Latte\Policy;
use Latte\Runtime\Template;
use Latte\SecurityViolationException;
use Latte\Strict;


/**
 * Latte parser.
 */
class Parser
{
	use Strict;

	public const
		LOCATION_HEAD = 1,
		LOCATION_TEXT = 2,
		LOCATION_TAG = 3;

	private const
		PREFIX_INNER = 'inner',
		PREFIX_TAG = 'tag',
		PREFIX_NONE = '';

	/** @var Block[][] */
	public array $blocks = [[]];
	public int $layer = Template::LAYER_TOP;

	/** @var array<string, callable(TagInfo, self): Node> */
	private array $tagParsers = [];

	/** @var array<string, callable(TagInfo, self): Node> */
	private array $attrParsers = [];

	private TokenStream $stream;
	private ?Lexer $lexer = null;
	private ?Policy $policy = null;
	private string $contentType = Compiler::CONTENT_HTML;
	private ?Html\ElementNode $htmlElement = null;
	private ?int $htmlDepth = null;
	private int $tagDepth = 0;
	private int $counter = 0;
	private int $location = self::LOCATION_HEAD;
	private array $filters = [];
	private ?TagInfo $tagInfo = null;
	private $context;


	/** @param  array<string, callable(TagInfo, self): Node>  $parsers */
	public function addParsers(array $parsers): static
	{
		foreach ($parsers as $name => $parser) {
			if (str_starts_with($name, 'n:')) {
				$this->attrParsers[substr($name, 2)] = $parser;
			} else {
				$this->tagParsers[$name] = $parser;
				if (Helpers::toReflection($parser)->isGenerator()) {
					$this->attrParsers[$name] = $parser;
					$this->attrParsers[self::PREFIX_INNER . '-' . $name] = $parser;
					$this->attrParsers[self::PREFIX_TAG . '-' . $name] = $parser;
				}
			}
		}

		return $this;
	}


	/**
	 * Parses tokens to nodes.
	 * @throws CompileException
	 */
	public function parse(TokenStream $stream, Lexer $lexer): Node
	{
		$this->stream = $stream;
		$this->lexer = $lexer;
		$node = $this->parseFragment([$this, 'htmlTextContext']);
		if ($token = $this->stream->current()) {
			throw new CompileException('Unexpected ' . trim($token->text));
		}
		return $node;
	}


	public function setPolicy(?Policy $policy): static
	{
		$this->policy = $policy;
		return $this;
	}


	public function setContentType(string $type): static
	{
		$this->contentType = $type;
		$this->lexer?->setContentType($type);
		return $this;
	}


	public function getContentType(): string
	{
		return $this->contentType;
	}


	/**
	 * Returns current line number.
	 */
	public function getLine(): ?int
	{
		return $this->stream?->current()?->line;
	}


	/** @internal */
	public function getStream(): TokenStream
	{
		return $this->stream;
	}


	/** @internal */
	public function getLexer(): Lexer
	{
		return $this->lexer;
	}


	private function parseFragment(callable $context): FragmentNode
	{
		$res = new FragmentNode;
		$prev = $this->context;
		$this->context = $context;
		while ($this->stream->current()) {
			if ($node = $context()) {
				$res->append($node);
			} else {
				break;
			}
		}

		$this->context = $prev;
		return $res;
	}


	private function htmlTextContext(): ?Node
	{
		$this->location = min(self::LOCATION_TEXT, $this->location);
		$token = $this->stream->current();
		return match ($token->type) {
			LegacyToken::TEXT => $this->parseText(),
			LegacyToken::COMMENT => $this->parseLatteComment(),
			LegacyToken::MACRO_TAG => $this->parseLatteMarkup(),
			LegacyToken::HTML_TAG_BEGIN => $this->parseHtmlMarkup(),
		};
	}


	private function htmlTagContext(): ?Node
	{
		$this->location = self::LOCATION_TAG;
		$token = $this->stream->current();
		return match ($token->type) {
			LegacyToken::TEXT => $this->parseText(),
			LegacyToken::COMMENT => $this->parseLatteComment(),
			LegacyToken::MACRO_TAG => $this->parseLatteMarkup(),
			LegacyToken::HTML_ATTRIBUTE_BEGIN => $this->parseHtmlAttribute(),
			LegacyToken::HTML_ATTRIBUTE_END => $this->parseHtmlAttributeEnd(),
			LegacyToken::HTML_TAG_END => null,
		};
	}


	private function parseText(): Nodes\TextNode
	{
		$token = $this->stream->consume(LegacyToken::TEXT);
		if ($this->location === self::LOCATION_HEAD && trim($token->text) !== '') {
			$this->location = self::LOCATION_TEXT;
		}
		return new Nodes\TextNode($token->text, $token->line);
	}


	private function parseLatteComment(): Node
	{
		$token = $this->stream->consume(LegacyToken::COMMENT);
		if ($token->indentation === null && $token->newline) {
			return new Nodes\TextNode("\n");
		}
		return new Nodes\NopNode;
	}


	private function parseLatteMarkup(): ?Node
	{
		$token = $this->stream->current();

		if ($token->closing
			|| (isset($this->filters[$this->tagDepth]) && in_array($token->name, $this->filters[$this->tagDepth], true))
		) {
			return null;

		} else {
			return $this->parseLatteElement();
		}
	}


	private function parseLatteElement(): Node
	{
		$token = $endToken = $this->stream->consume(LegacyToken::MACRO_TAG);
		$tag = $this->pushTag($this->createTagInfo($token));
		$this->tagDepth++;

		$parser = $this->getParser($tag->name);
		$res = $parser($tag, $this);
		if ($res instanceof \Generator) {
			$this->location = max(self::LOCATION_TEXT, $this->location);
			$res->rewind();
			while ($res->valid()) {
				$this->filters[$this->tagDepth] = $res->current() ?: null;
				$content = $this->parseFragment($this->context);
				$endTag = ($endToken = $this->stream->tryConsume(LegacyToken::MACRO_TAG))
					? $this->pushTag($this->createTagInfo($endToken))
					: null;
				$res->send([$content, $endTag]);
				if ($endTag) {
					$this->popTag();
				}
			}

			if ($token !== $endToken) {
				$this->checkEndTag($tag, $endTag);
			}
			unset($this->filters[$this->tagDepth]);
			$node = $res->getReturn();

		} else {
			if ($token->empty) {
				throw new CompileException("Unexpected /} in tag {$token->text}");
			}
			$node = $res;
			$this->location = max($node instanceof Nodes\StatementNode && $node->allowedInHead ? self::LOCATION_HEAD : self::LOCATION_TEXT, $this->location);
		}

		if (!$node instanceof Node) {
			throw new CompileException("Unexpected value returned by {{$tag->name}} parser.");
		}

		$this->tagDepth--;
		$this->popTag();

		$node->line = $token->line;
		$replaced = !$node instanceof Nodes\StatementNode || $node->replaced;
		$res = new FragmentNode;
		if ($token->indentation && ($replaced || !$token->newline)) {
			$res->append(new Nodes\TextNode($token->indentation));
		}

		$res->append($node);

		if ($endToken?->newline && ($replaced || $endToken?->indentation === null)) {
			$res->append(new Nodes\TextNode("\n"));
		}

		return $res;
	}


	private function createTagInfo(LegacyToken $token): TagInfo
	{
		return new TagInfo(
			line: $token->line,
			closing: $token->closing,
			name: $token->name,
			args: $token->value,
			empty: $token->empty,
			location: $this->location,
			htmlElement: $this->htmlElement,
		);
	}


	private function parseHtmlMarkup(): ?Node
	{
		$token = $this->stream->current();
		if ($token->closing && $this->tagDepth === $this->htmlDepth) {
			return null; // go back to parseHtmlElement()

		} elseif ($token->closing) {
			return $this->parseHtmlTag();

		} elseif ($token->text === '<!--') {
			return $this->parseHtmlComment();

		} elseif ($token->text === '<?' || $token->text === '<!') {
			return $this->parseBogusHtmlTag();

		} else {
			return $this->parseHtmlElement();
		}
	}


	private function parseHtmlElement(): Node
	{
		[$prevDepth, $this->htmlDepth] = [$this->htmlDepth, $this->tagDepth];
		$res = $elem = $this->htmlElement = new Html\ElementNode($this->htmlElement);
		$this->parseHtmlTag($elem);

		$void = $this->resolveVoidness($elem);
		if ($elem->nAttrs) {
			$res = $this->applyNAttributes($elem, $elem->nAttrs, $void);

		} elseif (!$void) {
			$elem->content = $this->parseFragment([$this, 'htmlTextContext']);
			if ($this->isClosingTag($this->stream->current(), $elem->startTag->getName())) {
				$elem->endTag = $this->parseHtmlTag();
			} else { // element collapsed to tags
				$res = new FragmentNode([$elem->startTag, $elem->content]);
			}
		}

		$this->htmlElement = $elem->parent;
		$this->htmlDepth = $prevDepth;
		return $res;
	}


	private function parseHtmlTag(?Html\ElementNode $elem = null): Html\TagNode
	{
		$beginToken = $this->stream->consume(LegacyToken::HTML_TAG_BEGIN);
		$node = new Html\TagNode(
			name: new TextNode($beginToken->name),
			closing: str_contains($beginToken->text, '/'),
			indentation: substr($beginToken->text, 0, strpos($beginToken->text, '<')),
			line: $beginToken->line,
		);
		if ($elem) {
			$elem->startTag = $node;
			$elem->line = $node->line;
		}
		$node->attrs = $this->parseFragment([$this, 'htmlTagContext']);
		$endToken = $this->stream->consume(LegacyToken::HTML_TAG_END);
		$node->selfClosing = str_contains($endToken->text, '/');
		$node->newline = $endToken->text[-1] === "\n";
		return $node;
	}


	private function parseBogusHtmlTag(): Html\BogusTagNode
	{
		$beginToken = $this->stream->consume(LegacyToken::HTML_TAG_BEGIN);
		$attrs = $this->parseFragment([$this, 'htmlTagContext']);
		$endToken = $this->stream->consume(LegacyToken::HTML_TAG_END);
		return new Html\BogusTagNode(
			openDelimiter: $beginToken->text,
			content: $attrs,
			endDelimiter: $endToken->text,
			line: $beginToken->line,
		);
	}


	private function resolveVoidness(Html\ElementNode $elem): bool
	{
		$tag = $elem->startTag;
		$empty = $tag->selfClosing;
		if ($this->contentType !== Compiler::CONTENT_HTML) {
			return $empty;
		} elseif (isset(Helpers::$emptyElements[$elem->startTag->getName()])) {
			return true;
		} elseif ($tag->selfClosing) { // auto-correct
			$elem->content = new Nodes\NopNode;
			$elem->endTag = new Html\TagNode($tag->name, closing: true, newline: $tag->newline);
			$tag->selfClosing = false;
			$tag->newline = false;
		}

		return $empty;
	}


	private function parseHtmlAttribute(): Node
	{
		$token = $this->stream->consume(LegacyToken::HTML_ATTRIBUTE_BEGIN);

		if (str_starts_with($token->name, Lexer::N_PREFIX)) {
			$name = substr($token->name, strlen(Lexer::N_PREFIX));
			if ($this->tagDepth !== $this->htmlDepth) {
				throw new CompileException("Attribute n:$name must not appear inside {tags}");

			} elseif (isset($this->htmlElement->nAttrs[$name])) {
				throw new CompileException("Found multiple attributes n:$name.");
			}

			$this->htmlElement->nAttrs[$name] = $this->createTagInfoFromAttr($token);
			return new Nodes\NopNode;
		}

		$node = new Html\AttributeNode(
			name: $token->name,
			text: $token->text,
			quote: in_array($token->value, ['"', "'"], true) ? $token->value : null,
			line: $token->line,
		);

		if ($token->value === '"' || $token->value === "'") {
			$node->value = $this->parseFragment(fn() => match ($this->stream->current()->type) {
				LegacyToken::TEXT => $this->parseText(),
				LegacyToken::COMMENT => $this->parseLatteComment(),
				LegacyToken::MACRO_TAG => $this->parseLatteMarkup(),
				LegacyToken::HTML_ATTRIBUTE_END => null,
			});
		}

		return $node;
	}


	private function parseHtmlAttributeEnd(): Html\AttributeNode
	{
		$token = $this->stream->consume(LegacyToken::HTML_ATTRIBUTE_END);
		return new Html\AttributeNode('', $token->text); // switches context to CONTEXT_HTML_TAG
	}


	private function parseHtmlComment(): Html\CommentNode
	{
		$this->location = self::LOCATION_TAG;
		$token = $this->stream->consume(LegacyToken::HTML_TAG_BEGIN);
		$node = new Html\CommentNode($this->parseFragment(fn() => match ($this->stream->current()->type) {
			LegacyToken::TEXT => $this->parseText(),
			LegacyToken::COMMENT => $this->parseLatteComment(),
			LegacyToken::MACRO_TAG => $this->parseLatteMarkup(),
			LegacyToken::HTML_TAG_END => null,
		}), $token->line);
		$this->stream->consume(LegacyToken::HTML_TAG_END);
		$this->location = self::LOCATION_TEXT;
		return $node;
	}


	private function createTagInfoFromAttr(LegacyToken $token): TagInfo
	{
		return new TagInfo(
			name: preg_replace('~n:(inner-|tag-|)~', '', $token->name),
			args: $token->value,
			line: $token->line,
			prefix: match (true) {
				str_starts_with($token->name, 'n:inner-') => TagInfo::PREFIX_INNER,
				str_starts_with($token->name, 'n:tag-') => TagInfo::PREFIX_TAG,
				default => TagInfo::PREFIX_NONE,
			},
			location: $this->location,
			htmlElement: $this->htmlElement,
		);
	}


	private function isClosingTag(?LegacyToken $token, string $name): bool
	{
		return $token
			&& $token->is(LegacyToken::HTML_TAG_BEGIN)
			&& $token->closing
			&& strcasecmp($name, $token->name) === 0;
	}


	private function applyNAttributes(Html\ElementNode $elem, array $nAttrs, bool $void): Node
	{
		$attrs = $this->sortNAttributes($nAttrs, $void);
		$outer = $this->openNAttrNodes($attrs[self::PREFIX_NONE] ?? []);
		if ($void) {
			return $this->finishNAttrNodes($elem, $outer);
		}

		if ($attrs[self::PREFIX_TAG] ?? null) {
			$elem->memoizeEndTag();
			$elem->startStmt = $this->finishNAttrNodes($elem->startStmt, $this->openNAttrNodes($attrs[self::PREFIX_TAG]));
		}

		$inner = $this->openNAttrNodes($attrs[self::PREFIX_INNER] ?? []);
		$elem->content = $this->finishNAttrNodes($this->parseFragment([$this, 'htmlTextContext']), $inner);

		$token = $this->stream->current();
		if (!$this->isClosingTag($token, $elem->startTag->getName())) {
			throw new CompileException('Unexpected ' . ($token ? $token->text : 'end')
				. ", expecting </{$elem->startTag->getName()}> for element started on line $elem->line");
		}

		$elem->endTag = $this->parseHtmlTag();
		return $this->finishNAttrNodes($elem, $outer);
	}


	private function sortNAttributes(array $attrs, bool $void): array
	{
		$res = [];
		foreach (array_reverse($this->attrParsers, true) as $name => $parser) {
			if ($tag = $attrs[$name] ?? null) {
				$prefix = substr($name, 0, (int) strpos($name, '-'));
				if (!$prefix || !$void) {
					$res[$prefix][] = $tag;
					unset($attrs[$name]);
				}
			}
		}

		if ($attrs) {
			$hint = Helpers::getSuggestion(array_keys($this->attrParsers), $k = key($attrs));
			throw new CompileException('Unexpected attribute n:' . ($hint ? "$k, did you mean n:$hint?" : implode(' and n:', array_keys($attrs))));
		}

		return $res;
	}


	/**
	 * @param  array<TagInfo>  $toOpen
	 * @return array<\Generator, int>
	 */
	private function openNAttrNodes(array $toOpen): array
	{
		$toClose = [];
		foreach ($toOpen as $tag) {
			$parser = $this->getParser($tag->name, true);
			$this->pushTag($tag);
			$gen = $parser($tag, $this);
			if (!$gen) {
				$this->popTag();
				continue;
			} elseif ($gen instanceof \Generator) {
				$gen->rewind();
				if ($gen->valid()) {
					$toClose[] = [$gen, $tag->line];
					continue;
				}
			}

			throw new CompileException("Unexpected value returned by {$tag->getNotation()} parser.");
		}

		return $toClose;
	}


	private function finishNAttrNodes(Node $node, array $toClose): Node
	{
		while ([$gen, $line] = array_pop($toClose)) {
			$gen->send([$node, null]);
			$node = $gen->getReturn();
			$node->line = $line;
			$this->popTag();
		}

		return $node;
	}


	/** @return callable(TagInfo, self): Node */
	private function getParser(string $name, bool $isAttr = false): callable
	{
		$parsers = $isAttr ? $this->attrParsers : $this->tagParsers;
		if (!isset($parsers[$name])) {
			$hint = (($t = Helpers::getSuggestion(array_keys($parsers), $name)) ? ", did you mean {{$t}}?" : '');
			if (!$isAttr
				&& $this->contentType === Compiler::CONTENT_HTML
				&& in_array($this->htmlElement?->startTag->getName(), ['script', 'style'], true)
			) {
				$hint .= ' (in JavaScript or CSS, try to put a space after bracket or use n:syntax=off)';
			}
			throw new CompileException("Unexpected tag {{$name}}$hint");
		}

		$this->checkTagIsAllowed($name, $isAttr);
		return $parsers[$name];
	}


	private function checkEndTag(TagInfo $start, ?TagInfo $end): void
	{
		if ($start->name === 'syntax'
			|| $start->name === 'block' && !$this->tagInfo->parent) { // TODO: hardcoded
			return;
		}

		if (!$end
			|| ($end->name !== $start->name && $end->name !== '')
			|| !$end->closing
			|| $end->modifiers
			|| ($end->args !== '' && $start->args !== '' && !str_starts_with($start->args . ' ', $end->args . ' ')) // TODO
		) {
			$tag = $end ? '{/' . $end->name . ($end->args ? ' ' . $end->args : '') . '}' : 'end';
			throw new CompileException("Unexpected $tag, expecting {/$start->name}");
		}
	}


	private function pushTag(TagInfo $tag): TagInfo
	{
		$tag->parent = $this->tagInfo;
		$this->tagInfo = $tag;
		return $tag;
	}


	private function popTag(): void
	{
		$this->tagInfo = $this->tagInfo->parent;
	}


	public function generateId(): int
	{
		return $this->counter++;
	}


	public function addBlock(string $name, ?string $layer, string $type): Block
	{
		if ($layer === Template::LAYER_SNIPPET
			? isset($this->blocks[$layer][$name])
			: (isset($this->blocks[Template::LAYER_LOCAL][$name]) || isset($this->blocks[$this->layer][$name]))
		) {
			throw new CompileException("Cannot redeclare {$type} '{$name}'");
		}

		$layer ??= $this->layer;
		return $this->blocks[$layer][$name] = new Block($name, $layer);
	}


	public function checkTagIsAllowed(string $name, bool $isAttr = false): void
	{
		if ($this->policy && !$this->policy->isMacroAllowed($name)) {
			$name = $isAttr ? 'n:' . $name : '{' . $name . '}';
			throw new SecurityViolationException("Tag $name is not allowed.");
		}
	}


	public function checkFilterIsAllowed(string $name): void
	{
		if ($this->policy && !$this->policy->isFilterAllowed($name)) {
			throw new SecurityViolationException("Filter |$name is not allowed.");
		}
	}
}
