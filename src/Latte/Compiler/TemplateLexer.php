<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\CompileException;
use Latte\Context;
use Latte\RegexpException;
use Latte\Strict;


final class TemplateLexer
{
	use Strict;

	/** regular expression for single & double quoted PHP string */
	public const ReString = '\'(?:\\\\.|[^\'\\\\])*+\'|"(?:\\\\.|[^"\\\\])*+"';

	/** HTML tag name for Latte needs (actually is [a-zA-Z][^\s/>]*) */
	public const ReTagName = '[a-zA-Z][a-zA-Z0-9:_.-]*';

	/** special HTML attribute prefix */
	public const NPrefix = 'n:';
	private const StateEnd = 'end';

	/** @var array<string, array{string, string}> */
	public array $syntaxes = [
		'latte' => ['\{(?![\s\'"{}])', '\}'], // {...}
		'double' => ['\{\{(?![\s\'"{}])', '\}\}'], // {{...}}
		'off' => ['\{(?=/syntax\})', '\}'], // {/syntax}
	];

	/** @var string[] */
	private array $delimiters;
	private string $input;

	/** @var LegacyToken[] */
	private array $tokens;
	private int $offset;
	private Position $position;
	private array $states;
	private ?string $syntaxEndTag = null;
	private int $syntaxEndLevel = 0;
	private ?string $lastHtmlTag;
	private bool $xmlMode = false;


	public function tokenize(string $template, string $contentType = Context::Html): array
	{
		$this->position = new Position(1, 1, 0);
		$this->input = $this->normalize($template);
		$this->offset = 0;
		$this->states = $this->tokens = [];
		$this->lastHtmlTag = $this->syntaxEndTag = null;
		$this->setContentType($contentType);
		$this->setSyntax(null);

		do {
			$state = $this->states[0];
			$this->{$state['name']}(...$state['args']);
		} while ($this->states[0]['name'] !== self::StateEnd);

		if ($this->offset < strlen($this->input)) {
			$this->send($this->createToken(LegacyToken::TEXT, substr($this->input, $this->offset)));
		}

		return $this->tokens;
	}


	private function statePlain(): void
	{
		$m = $this->match('~
			(?P<macro>' . $this->delimiters[0] . ')
		~xsi');

		if (!empty($m['macro'])) {
			$this->pushState('stateLatte', $m['macro']);

		} else {
			$this->setState(self::StateEnd);
		}
	}


	private function stateLatte(string $delimiter): void
	{
		$this->popState();
		$m = $this->match('~
			(?P<comment>\*.*?\*' . $this->delimiters[1] . '\n{0,2})|
			(?P<macro>(?>
				' . self::ReString . '|
				\{(?>' . self::ReString . '|[^\'"{}])*+\}|
				[^\'"{}]+
			)++)
			' . $this->delimiters[1] . '
			(?P<rmargin>[ \t]*(?=\n))?
		~xsiA');

		if (!empty($m['macro'])) {
			$token = $this->createToken(LegacyToken::MACRO_TAG, $delimiter . $m[0]);
			[$token->name, $token->value, $token->modifiers, $token->empty, $token->closing] = $this->parseMacroTag($m['macro']);
			$this->send($token);

		} elseif (!empty($m['comment'])) {
			$this->send($this->createToken(LegacyToken::COMMENT, $delimiter . $m[0]));

		} else {
			throw new CompileException('Malformed tag contents.', $this->position);
		}
	}


	private function stateHtmlText(): void
	{
		$m = $this->match('~
			(?:(?<=\n|^)[ \t]*)?<(?P<closing>/?)(?P<tag>' . self::ReTagName . ')|  ##  begin of HTML tag <tag </tag - ignores <!DOCTYPE
			<(?P<htmlcomment>!(?:--(?!>))?|\?)|     ##  begin of <!, <!--, <!DOCTYPE, <?
			(?P<macro>' . $this->delimiters[0] . ')
		~xsi');

		if (!empty($m['htmlcomment'])) { // <! <?
			$this->send($this->createToken(LegacyToken::HTML_TAG_BEGIN, $m[0]));
			$ending = $m['htmlcomment'] === '!--'
				? '--'
				: ($m['htmlcomment'] === '?' && $this->xmlMode ? '\?' : '');
			$this->setState('stateHtmlComment', $ending);

		} elseif (!empty($m['tag'])) { // <tag or </tag
			$token = $this->createToken(LegacyToken::HTML_TAG_BEGIN, $m[0]);
			$token->name = $m['tag'];
			$token->closing = (bool) $m['closing'];
			$this->send($token);
			$this->setState('stateHtmlTag', $m['closing'] . strtolower($m['tag']));

		} elseif (!empty($m['macro'])) {
			$this->pushState('stateLatte', $m['macro']);

		} else {
			$this->setState(self::StateEnd);
		}
	}


	private function stateHtmlTag(?string $tagName = null): void
	{
		$this->lastHtmlTag = $tagName;
		$m = $this->match('~
			(?P<end>\s?/?>)([ \t]*\n)?|  ##  end of HTML tag
			(?P<macro>' . $this->delimiters[0] . ')|
			\s*(?P<attr>[^\s"\'>/={]+)(?:\s*=\s*(?P<value>["\']|[^\s"\'=<>`{]+))? ## beginning of HTML attribute
		~xsi');

		if (!empty($m[self::StateEnd])) { // end of HTML tag />
			$token = $this->createToken(LegacyToken::HTML_TAG_END, $m[0]);
			$empty = str_contains($m[0], '/');
			$this->send($token);
			if (!$this->xmlMode && !$empty && in_array($tagName, ['script', 'style'], true)) {
				$this->setState('stateHtmlRCData', $tagName);
			} else {
				$this->setState('stateHtmlText');
			}

		} elseif (isset($m['attr']) && $m['attr'] !== '') { // HTML attribute
			$token = $this->createToken(LegacyToken::HTML_ATTRIBUTE_BEGIN, $m[0]);
			$token->name = $m['attr'];
			$token->value = $m['value'] ?? '';

			if ($token->value === '"' || $token->value === "'") { // attribute = "'
				if (str_starts_with($token->name, self::NPrefix)) {
					$token->value = '';
					if ($m2 = $this->match('~(.*?)' . $m['value'] . '~xsi')) {
						$token->value = $m2[1];
						$token->text .= $m2[0];
					}
				} else {
					$this->send($token);
					$this->pushState('stateHtmlAttribute', $m['value']);
					return;
				}
			}
			$this->send($token);
			$this->setState('stateHtmlTag');

		} elseif (!empty($m['macro'])) {
			$this->pushState('stateLatte', $m['macro']);

		} else {
			$this->setState(self::StateEnd);
		}
	}


	private function stateHtmlAttribute(string $quote): void
	{
		$m = $this->match('~
			(?P<quote>' . $quote . ')|  ##  end of HTML attribute
			(?P<macro>' . $this->delimiters[0] . ')
		~xsi');

		if (!empty($m['quote'])) {
			$this->send($this->createToken(LegacyToken::HTML_ATTRIBUTE_END, $m[0]));
			$this->popState();

		} elseif (!empty($m['macro'])) {
			$this->pushState('stateLatte', $m['macro']);
		}
	}


	private function stateHtmlRCData(): void
	{
		$m = $this->match('~
			</(?P<tag>' . $this->lastHtmlTag . ')(?=[\s/>])| ##  end HTML tag </tag
			(?P<macro>' . $this->delimiters[0] . ')
		~xsi');

		if (!empty($m['tag'])) { // </tag
			$token = $this->createToken(LegacyToken::HTML_TAG_BEGIN, $m[0]);
			$token->name = $m['tag'];
			$token->closing = true;
			$this->send($token);
			$this->setState('stateHtmlTag');

		} elseif (!empty($m['macro'])) {
			$this->pushState('stateLatte', $m['macro']);

		} else {
			$this->setState(self::StateEnd);
		}
	}


	private function stateHtmlComment(string $ending): void
	{
		$m = $this->match('~
			(?P<htmlcomment>' . $ending . '>)|   ##  end of HTML comment
			(?P<macro>' . $this->delimiters[0] . ')
		~xsi');

		if (!empty($m['htmlcomment'])) { // -->
			$this->send($this->createToken(LegacyToken::HTML_TAG_END, $m[0]));
			$this->setState('stateHtmlText');

		} elseif (!empty($m['macro'])) {
			$this->pushState('stateLatte', $m['macro']);

		} else {
			$this->setState(self::StateEnd);
		}
	}


	/**
	 * Matches next token.
	 * @return string[]
	 */
	private function match(string $re): array
	{
		if (!preg_match($re, $this->input, $matches, PREG_OFFSET_CAPTURE, $this->offset)) {
			if (preg_last_error()) {
				throw new RegexpException;
			}

			return [];
		}

		$value = substr($this->input, $this->offset, $matches[0][1] - $this->offset);
		if ($value !== '') {
			$this->send($this->createToken(LegacyToken::TEXT, $value));
		}

		$this->offset = $matches[0][1] + strlen($matches[0][0]);
		foreach ($matches as $k => $v) {
			$matches[$k] = $v[0];
		}

		return $matches;
	}


	public function setContentType(string $type): static
	{
		if ($type === Context::Html || $type === Context::Xml) {
			$this->setState('stateHtmlText');
			$this->xmlMode = $type === Context::Xml;
		} else {
			$this->setState('statePlain');
		}

		return $this;
	}


	private function setState(string $state, ...$args)
	{
		$this->states[0] = ['name' => $state, 'args' => $args];
	}


	private function pushState(string $state, ...$args): void
	{
		array_unshift($this->states, null);
		$this->setState($state, ...$args);
	}


	private function popState(): void
	{
		array_shift($this->states);
	}


	/**
	 * Changes macro tag delimiters.
	 */
	public function setSyntax(?string $type): static
	{
		$type ??= 'latte';
		if (!isset($this->syntaxes[$type])) {
			throw new \InvalidArgumentException("Unknown syntax '$type'");
		}

		$this->setDelimiters($this->syntaxes[$type][0], $this->syntaxes[$type][1]);
		return $this;
	}


	/**
	 * Changes macro tag delimiters (as regular expression).
	 */
	public function setDelimiters(string $left, string $right): static
	{
		$this->delimiters = [$left, $right];
		return $this;
	}


	/**
	 * Parses macro tag to name, arguments a modifiers parts.
	 * @param  string  $tag  {name arguments | modifiers}
	 * @return array{string, string, string, bool, bool}|null
	 * @internal
	 */
	public function parseMacroTag(string $tag): ?array
	{
		if (!preg_match('~^
			(?P<closing>/?)
			(?P<name>=|_(?!_)|[a-z]\w*+(?:[.:-]\w+)*+(?!::|\(|\\\\)|)   ## name, /name, but not function( or class:: or namespace\
			(?P<args>(?:' . self::ReString . '|[^\'"])*?)
			(?P<modifiers>(?<!\|)\|[a-z](?P<modArgs>(?:' . self::ReString . '|(?:\((?P>modArgs)\))|[^\'"/()]|/(?=.))*+))?
			(?P<empty>/?$)
		()$~Disx', $tag, $match)) {
			if (preg_last_error()) {
				throw new RegexpException;
			}

			return null;
		}

		if ($match['name'] === '') {
			$match['name'] = $match['closing'] ? '' : '=';
		}

		return [$match['name'], trim($match['args']), $match['modifiers'], (bool) $match['empty'], (bool) $match['closing']];
	}


	private function createToken(string $type, string $text): LegacyToken
	{
		$token = new LegacyToken;
		$token->type = $type;
		$token->text = $text;
		$token->position = $this->position;
		$this->position = $this->position->advance($text);
		return $token;
	}


	private function send(LegacyToken $token): void
	{
		$this->filter($token);
		$this->tokens[] = $token;
	}


	/**
	 * Process low-level macros.
	 */
	protected function filter(LegacyToken $token): void
	{
		if ($token->type === LegacyToken::MACRO_TAG && $token->name === 'syntax') {
			$this->setSyntax($token->closing ? null : $token->value);
			$token->type = LegacyToken::COMMENT;

		} elseif ($token->type === LegacyToken::HTML_ATTRIBUTE_BEGIN && $token->name === 'n:syntax') {
			$this->setSyntax($token->value);
			$this->syntaxEndTag = $this->lastHtmlTag;
			$this->syntaxEndLevel = 1;
			$token->type = LegacyToken::COMMENT;

		} elseif ($token->type === LegacyToken::HTML_TAG_BEGIN && $this->lastHtmlTag === $this->syntaxEndTag) {
			$this->syntaxEndLevel++;

		} elseif (
			$token->type === LegacyToken::HTML_TAG_END
			&& $this->lastHtmlTag === ('/' . $this->syntaxEndTag)
			&& --$this->syntaxEndLevel === 0
		) {
			$this->setSyntax(null);

		} elseif ($token->type === LegacyToken::MACRO_TAG && $token->name === 'contentType') {
			if (str_contains($token->value, 'html')) {
				$this->setContentType(Context::Html);
			} elseif (str_contains($token->value, 'xml')) {
				$this->setContentType(Context::Xml);
			} else {
				$this->setContentType(Context::Text);
			}
		}
	}


	private function normalize(string $str): string
	{
		if (str_starts_with($str, "\u{FEFF}")) { // BOM
			$str = substr($str, 3);
		}

		$str = str_replace("\r\n", "\n", $str);

		if (!preg_match('##u', $str)) {
			preg_match('#(?:[\x00-\x7F]|[\xC0-\xDF][\x80-\xBF]|[\xE0-\xEF][\x80-\xBF]{2}|[\xF0-\xF7][\x80-\xBF]{3})*+#A', $str, $m);
			throw new CompileException('Template is not valid UTF-8 stream.', $this->position->advance($m[0]));

		} elseif (preg_match('#(.*?)([\x00-\x08\x0B\x0C\x0E-\x1F\x7F])#s', $str, $m)) {
			throw new CompileException('Template contains control character \x' . dechex(ord($m[2])), $this->position->advance($m[1]));
		}
		return $str;
	}
}
