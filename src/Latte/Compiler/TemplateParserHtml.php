<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte;
use Latte\CompileException;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\Html;
use Latte\ContentType;
use Latte\Helpers;
use Latte\SecurityViolationException;


/**
 * Template parser extension for HTML.
 */
final class TemplateParserHtml
{
	use Latte\Strict;

	/** @var array<string, callable(Tag, TemplateParser): (Node|\Generator|void)> */
	private array /*readonly*/ $attrParsers;
	private ?Html\ElementNode $element = null;
	private TemplateParser /*readonly*/ $parser;


	public function __construct(TemplateParser $parser, array $attrParsers)
	{
		$this->parser = $parser;
		$this->attrParsers = $attrParsers;
	}


	public function getElement(): ?Html\ElementNode
	{
		return $this->element;
	}


	public function inTextResolve(): ?Node
	{
		$token = $this->parser->getStream()->peek();
		return match ($token->type) {
			Token::HTML_TAG_BEGIN => $this->parseMarkup(),
			default => $this->parser->inTextResolve(),
		};
	}


	public function inTagResolve(): ?Node
	{
		$token = $this->parser->getStream()->peek();
		return match ($token->type) {
			Token::HTML_ATTRIBUTE_BEGIN => $this->parseAttribute(),
			Token::HTML_ATTRIBUTE_END => $this->parseAttributeEnd(),
			Token::HTML_TAG_END => null,
			default => $this->parser->inTextResolve(),
		};
	}


	private function parseMarkup(): ?Node
	{
		$token = $this->parser->getStream()->peek();
		if ($token->closing
			&& $this->element
			&& $this->parser->peekTag() === $this->element->data->tag
			&& (strcasecmp($token->name, $this->element->name) === 0
				|| !in_array($token->name, $this->element->data->unclosedTags ?? [], true))
		) {
			return null; // go back to parseElement()

		} elseif ($token->text === '<!--') {
			return $this->parseComment();

		} elseif ($token->closing || $token->text === '<?' || $token->text === '<!') {
			return $this->parseBogusTag();

		} else {
			return $this->parseElement();
		}
	}


	private function parseElement(): Node
	{
		$res = new FragmentNode;
		$res->append($this->extractIndentation());
		$res->append($this->parseTag($this->element));
		$elem = $this->element;

		$stream = $this->parser->getStream();
		$void = $this->resolveVoidness($elem);
		$attrs = $this->prepareNAttrs($elem->nAttributes, $void);
		$outerNodes = $this->openNAttrNodes($attrs[Tag::PrefixNone] ?? []);
		$tagNodes = $this->openNAttrNodes($attrs[Tag::PrefixTag] ?? []);
		$elem->tagNode = $this->finishNAttrNodes($elem->tagNode, $tagNodes);
		$elem->captureTagName = (bool) $tagNodes;

		if (!$void) {
			$content = new FragmentNode;
			if (str_ends_with($stream->peek(-1)->text, "\n")) {
				$content->append(new Nodes\TextNode("\n"));
			}

			$innerNodes = $this->openNAttrNodes($attrs[Tag::PrefixInner] ?? []);
			$elem->data->tag = $this->parser->peekTag();
			$frag = $this->parser->parseFragment([$this, 'inTextResolve']);
			$content->append($this->finishNAttrNodes($frag, $innerNodes));

			$token = $this->parser->getStream()->peek();
			if ($this->isClosingTag($token, $elem->name)) {
				$elem->content = $content;
				$elem->content->append($this->extractIndentation());
				$this->parseTag();

			} elseif ($outerNodes || $innerNodes || $tagNodes
				|| ($this->parser->getContentType() === ContentType::Html && in_array(strtolower($elem->name), ['script', 'style'], true))
			) {
				$stream->throwUnexpectedException(
					addendum: ", expecting </{$elem->name}> for element started on line {$elem->position->line}",
				);
			} else { // element collapsed to tags
				$res->append($content);
				$this->element = $elem->parent;
				if ($this->element && !$token->is(Token::HTML_TAG_BEGIN)) {
					$this->element->data->unclosedTags[] = $elem->name;
				}
				return $res;
			}
		}

		if (str_ends_with($stream->peek(-1)->text, "\n")) {
			$res->append(new Nodes\TextNode("\n"));
		}

		$res = $this->finishNAttrNodes($res, $outerNodes);
		$this->element = $elem->parent;
		return $res;
	}


	private function extractIndentation(): AreaNode
	{
		$token = $this->parser->getStream()->peek(0);
		return ($s = substr($token->text, 0, strpos($token->text, '<')))
			? new Nodes\TextNode($s, $token->position)
			: new Nodes\NopNode;
	}


	private function parseTag(&$elem = null): Html\ElementNode
	{
		$stream = $this->parser->getStream();
		$beginToken = $stream->consume(Token::HTML_TAG_BEGIN);
		$this->parser->location = $this->parser::LocationTag;
		$elem = new Html\ElementNode(
			name: $beginToken->name,
			position: $beginToken->position,
			parent: $this->element,
			data: (object) ['tag' => $this->parser->peekTag()],
		);
		$elem->attributes = $this->parser->parseFragment([$this, 'inTagResolve']);
		$endToken = $stream->consume(Token::HTML_TAG_END);
		$elem->selfClosing = str_contains($endToken->text, '/');
		$this->parser->location = $this->parser::LocationText;
		return $elem;
	}


	private function parseBogusTag(): Html\BogusTagNode
	{
		$stream = $this->parser->getStream();
		$beginToken = $stream->consume(Token::HTML_TAG_BEGIN);
		$this->parser->location = $this->parser::LocationTag;
		$attrs = $this->parser->parseFragment([$this, 'inTagResolve']);
		$endToken = $stream->consume(Token::HTML_TAG_END);
		$this->parser->location = $this->parser::LocationText;
		return new Html\BogusTagNode(
			openDelimiter: $beginToken->text,
			content: $attrs,
			endDelimiter: $endToken->text,
			position: $beginToken->position,
		);
	}


	private function resolveVoidness(Html\ElementNode $elem): bool
	{
		if ($this->parser->getContentType() !== ContentType::Html) {
			return $elem->selfClosing;
		} elseif (isset(Helpers::$emptyElements[strtolower($elem->name)])) {
			return true;
		} elseif ($elem->selfClosing) { // auto-correct
			$elem->content = new Nodes\NopNode;
			$elem->selfClosing = false;
			$last = end($elem->attributes->children);
			if ($last instanceof Nodes\TextNode && $last->isWhitespace()) {
				array_pop($elem->attributes->children);
			}
			return true;
		}

		return $elem->selfClosing;
	}


	private function parseAttribute(): Node
	{
		$stream = $this->parser->getStream();
		$token = $stream->consume(Token::HTML_ATTRIBUTE_BEGIN);

		if (str_starts_with($token->name, TemplateLexer::NPrefix)) {
			$name = substr($token->name, strlen(TemplateLexer::NPrefix));
			if ($this->parser->peekTag() !== $this->element->data->tag) {
				throw new CompileException("Attribute n:$name must not appear inside {tags}", $token->position);

			} elseif (isset($this->element->nAttributes[$name])) {
				throw new CompileException("Found multiple attributes n:$name.", $token->position);
			}

			$this->element->nAttributes[$name] = $tag = $this->createTagFromAttr($token);
			return $tag->data->node;
		}

		$node = new Html\AttributeNode(
			name: $token->name,
			text: $token->text,
			quote: in_array($token->value, ['"', "'"], true) ? $token->value : null,
			position: $token->position,
		);

		if ($token->value === '"' || $token->value === "'") {
			$node->value = $this->parser->parseFragment(fn() => match ($stream->peek()->type) {
				Token::HTML_ATTRIBUTE_END => null,
				default => $this->parser->inTextResolve(),
			});
		}

		return $node;
	}


	private function parseAttributeEnd(): Html\AttributeNode
	{
		$token = $this->parser->getStream()->consume(Token::HTML_ATTRIBUTE_END);
		return new Html\AttributeNode('', $token->text); // switches context to CONTEXT_HTML_TAG
	}


	private function parseComment(): Html\CommentNode
	{
		$stream = $this->parser->getStream();
		$this->parser->location = TemplateParser::LocationTag;
		$token = $stream->consume(Token::HTML_TAG_BEGIN);
		$node = new Html\CommentNode($this->parser->parseFragment(fn() => match ($stream->peek()->type) {
			Token::HTML_TAG_END => null,
			default => $this->parser->inTextResolve(),
		}), $token->position);
		$stream->consume(Token::HTML_TAG_END);
		$this->parser->location = TemplateParser::LocationText;
		return $node;
	}


	private function createTagFromAttr(Token $token): Tag
	{
		$token->position->column += strspn($token->text, ' ');
		return new Tag(
			name: preg_replace('~n:(inner-|tag-|)~', '', $token->name),
			args: $token->value,
			position: $token->position,
			prefix: match (true) {
				str_starts_with($token->name, 'n:inner-') => Tag::PrefixInner,
				str_starts_with($token->name, 'n:tag-') => Tag::PrefixTag,
				default => Tag::PrefixNone,
			},
			location: $this->parser->location,
			htmlElement: $this->element,
			data: (object) ['node' => new Nodes\TextNode('')], // TODO: better
		);
	}


	private function isClosingTag(?Token $token, string $name): bool
	{
		return $token
			&& $token->is(Token::HTML_TAG_BEGIN)
			&& $token->closing
			&& strcasecmp($name, $token->name) === 0;
	}


	private function prepareNAttrs(array $attrs, bool $void): array
	{
		$res = [];
		foreach ($this->attrParsers as $name => $foo) {
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
			throw new CompileException('Unexpected attribute n:'
				. ($hint ? "$k, did you mean n:$hint?" : implode(' and n:', array_keys($attrs))), $attrs[$k]->position);
		}

		return $res;
	}


	/**
	 * @param  array<Tag>  $toOpen
	 * @return array<array{\Generator, Tag}>
	 */
	private function openNAttrNodes(array $toOpen): array
	{
		$toClose = [];
		foreach ($toOpen as $tag) {
			$parser = $this->getAttrParser($tag->name, $tag->position);
			$this->parser->pushTag($tag);
			$res = $parser($tag, $this->parser);
			if ($res instanceof \Generator && $res->valid()) {
				$toClose[] = [$res, $tag];

			} elseif ($res instanceof Node) {
				$res->position = $tag->position;
				$tag->replaceNAttribute($res);
				$this->parser->popTag();

			} elseif (!$res) {
				$this->parser->popTag();

			} else {
				throw new CompileException("Unexpected value returned by {$tag->getNotation()} parser.", $tag->position);
			}
		}

		return $toClose;
	}


	/** @param  array<array{\Generator, Tag}>  $toClose */
	private function finishNAttrNodes(AreaNode $node, array $toClose): AreaNode
	{
		while ([$gen, $tag] = array_pop($toClose)) {
			$gen->send([$node, null]);
			$node = $gen->getReturn();
			$node->position = $tag->position;
			$this->parser->popTag();
		}

		return $node;
	}


	/** @return callable(Tag, TemplateParser): (Node|\Generator|void) */
	private function getAttrParser(string $name, Position $pos): callable
	{
		if (!isset($this->attrParsers[$name])) {
			$hint = ($t = Helpers::getSuggestion(array_keys($this->attrParsers), $name))
				? ", did you mean n:$t?"
				: '';
			throw new CompileException("Unknown n:{$name}{$hint}", $pos);
		} elseif (!$this->parser->isTagAllowed($name)) {
			throw new SecurityViolationException("Attribute n:$name is not allowed.");
		}
		return $this->attrParsers[$name];
	}
}
