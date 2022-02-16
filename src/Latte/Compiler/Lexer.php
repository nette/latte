<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\CompileException;
use Latte\Engine;
use Latte\RegexpException;
use Latte\Strict;


/**
 * Latte lexer.
 */
class Lexer
{
	use Strict;

	/** @internal regular expression for single & double quoted PHP string */
	public const RE_STRING = '\'(?:\\\\.|[^\'\\\\])*+\'|"(?:\\\\.|[^"\\\\])*+"';

	/** @internal HTML tag name for Latte needs (actually is [a-zA-Z][^\s/>]*) */
	public const RE_TAG_NAME = '[a-zA-Z][a-zA-Z0-9:_.-]*';

	/** @internal special HTML attribute prefix */
	public const N_PREFIX = 'n:';

	/** Context-aware escaping content types */
	public const
		CONTENT_HTML = Engine::CONTENT_HTML,
		CONTENT_XML = Engine::CONTENT_XML,
		CONTENT_TEXT = Engine::CONTENT_TEXT;

	/** @internal states */
	public const
		CONTEXT_NONE = 'none',
		CONTEXT_MACRO = 'macro',
		CONTEXT_HTML_TEXT = 'htmlText',
		CONTEXT_HTML_TAG = 'htmlTag',
		CONTEXT_HTML_ATTRIBUTE = 'htmlAttribute',
		CONTEXT_HTML_COMMENT = 'htmlComment',
		CONTEXT_HTML_CDATA = 'htmlCData';

	/** default macro tag syntax */
	public string $defaultSyntax = 'latte';

	/** @var array<string, array{string, string}> */
	public array $syntaxes = [
		'latte' => ['\{(?![\s\'"{}])', '\}'], // {...}
		'double' => ['\{\{(?![\s\'"{}])', '\}\}'], // {{...}}
		'off' => ['\{(?=/syntax\})', '\}'], // {/syntax}
	];

	/** @var string[] */
	private array $delimiters;

	/** source template */
	private string $input;

	/** position on source template */
	private int $offset;

	private int $line;

	private string $state = self::CONTEXT_HTML_TEXT;

	private bool $xmlMode = false;


	/**
	 * Process all {macros} and <tags/>.
	 */
	public function tokenize(string $input): TokenStream
	{
		$this->input = $this->normalize($input);
		$this->offset = 0;
		$this->line = 1;
		$this->setSyntax($this->defaultSyntax);
		return new TokenStream($this->loop());
	}


	/** @return \Generator<LegacyToken> */
	private function loop(): \Generator
	{
		$lastHtmlTag = null;

		choice:
		switch ($this->state) {
			case self::CONTEXT_NONE: goto statePlain;
			case self::CONTEXT_HTML_TEXT: goto stateHtmlText;
			case self::CONTEXT_HTML_TAG: goto stateHtmlTag;
			case self::CONTEXT_HTML_ATTRIBUTE: goto stateHtmlAttribute;
			case self::CONTEXT_HTML_COMMENT: goto stateHtmlComment;
			case self::CONTEXT_HTML_CDATA: goto stateHtmlRCData;
		}

		stateHtmlText:
		$this->state = self::CONTEXT_HTML_TEXT;
		$matches = yield from $this->match('~
			(?:(?<=\n|^)[ \t]*)?<(?P<closing>/?)(?P<tag>' . self::RE_TAG_NAME . ')|  ##  begin of HTML tag <tag </tag - ignores <!DOCTYPE
			<(?P<htmlcomment>!(?:--(?!>))?|\?)|     ##  begin of <!, <!--, <!DOCTYPE, <?
			(?P<indent>(?<=\n|^)[ \t]*)?(?P<macro>' . $this->delimiters[0] . ')
		~xsi');

		if (!empty($matches['htmlcomment'])) { // <! <?
			yield $this->createToken(LegacyToken::HTML_TAG_BEGIN, $matches[0]);
			$stateValue = $matches['htmlcomment'] === '!--'
				? '--'
				: ($matches['htmlcomment'] === '?' && $this->xmlMode ? '\?' : '');
			goto stateHtmlComment;

		} elseif (!empty($matches['tag'])) { // <tag or </tag
			$token = $this->createToken(LegacyToken::HTML_TAG_BEGIN, $matches[0]);
			$token->name = $matches['tag'];
			$token->closing = (bool) $matches['closing'];
			$lastHtmlTag = $matches['closing'] . strtolower($matches['tag']);
			yield $token;
			goto stateHtmlTag;

		} elseif (!empty($matches['macro'])) {
			goto stateLatte;

		} else {
			goto end;
		}


		stateHtmlRCData:
		$this->state = self::CONTEXT_HTML_CDATA;
		$matches = yield from $this->match('~
			</(?P<tag>' . $lastHtmlTag . ')(?=[\s/>])| ##  end HTML tag </tag
			(?P<indent>(?<=\n|^)[ \t]*)?(?P<macro>' . $this->delimiters[0] . ')
		~xsi');

		if (!empty($matches['tag'])) { // </tag
			$token = $this->createToken(LegacyToken::HTML_TAG_BEGIN, $matches[0]);
			$token->name = $matches['tag'];
			$token->closing = true;
			$lastHtmlTag = '/' . $lastHtmlTag;
			yield $token;
			goto stateHtmlTag;

		} elseif (!empty($matches['macro'])) {
			goto stateLatte;

		} else {
			goto end;
		}

		stateHtmlTag:
		$this->state = self::CONTEXT_HTML_TAG;
		$matches = yield from $this->match('~
			(?P<end>\s?/?>)([ \t]*\n)?|  ##  end of HTML tag
			(?P<macro>' . $this->delimiters[0] . ')|
			\s*(?P<attr>[^\s"\'>/={]+)(?:\s*=\s*(?P<value>["\']|[^\s"\'=<>`{]+))? ## beginning of HTML attribute
		~xsi');

		if (!empty($matches['end'])) { // end of HTML tag />
			$token = $this->createToken(LegacyToken::HTML_TAG_END, $matches[0]);
			$empty = str_contains($matches[0], '/');
			yield $token;
			if (!$this->xmlMode && !$empty && in_array($lastHtmlTag, ['script', 'style'], true)) {
				goto stateHtmlRCData;
			}
			goto stateHtmlText;

		} elseif (isset($matches['attr']) && $matches['attr'] !== '') { // HTML attribute
			$token = $this->createToken(LegacyToken::HTML_ATTRIBUTE_BEGIN, $matches[0]);
			$token->name = $matches['attr'];
			$token->value = $matches['value'] ?? '';

			if ($token->value === '"' || $token->value === "'") { // attribute = "'
				if (str_starts_with($token->name, self::N_PREFIX)) {
					$token->value = '';
					if ($m = yield from $this->match('~(.*?)' . $matches['value'] . '~xsi')) {
						$token->value = $m[1];
						$token->text .= $m[0];
					}
				} else {
					$stateValue = $matches['value'];
					yield $token;
					goto stateHtmlAttribute;
				}
			}
			yield $token;
			goto stateHtmlTag;

		} elseif (!empty($matches['macro'])) {
			goto stateLatte;

		} else {
			goto end;
		}

		stateHtmlAttribute:
		$this->state = self::CONTEXT_HTML_ATTRIBUTE;
		$matches = yield from $this->match('~
			(?P<quote>' . $stateValue . ')|  ##  end of HTML attribute
			(?P<macro>' . $this->delimiters[0] . ')
		~xsi');

		if (!empty($matches['quote'])) {
			yield $this->createToken(LegacyToken::HTML_ATTRIBUTE_END, $matches[0]);
			goto stateHtmlTag;

		} elseif (!empty($matches['macro'])) {
			goto stateLatte;

		} else {
			goto end;
		}

		stateHtmlComment:
		$this->state = self::CONTEXT_HTML_COMMENT;
		$matches = yield from $this->match('~
			(?P<htmlcomment>' . $stateValue . '>)|   ##  end of HTML comment
			(?P<indent>(?<=\n|^)[ \t]*)?(?P<macro>' . $this->delimiters[0] . ')
		~xsi');

		if (!empty($matches['htmlcomment'])) { // -->
			yield $this->createToken(LegacyToken::HTML_TAG_END, $matches[0]);
			goto stateHtmlText;

		} elseif (!empty($matches['macro'])) {
			goto stateLatte;

		} else {
			goto end;
		}

		statePlain:
		$this->state = self::CONTEXT_NONE;
		$matches = yield from $this->match('~
			(?P<indent>(?<=\n|^)[ \t]*)?(?P<macro>' . $this->delimiters[0] . ')
		~xsi');

		if (!empty($matches['macro'])) {
			goto stateLatte;

		} else {
			goto end;
		}

		stateLatte:
		[$delimiter, $indent] = [$matches['macro'], $matches['indent'] ?? null];
		$matches = yield from $this->match('~
			(?P<comment>\*.*?\*' . $this->delimiters[1] . ')(?P<newline>\n{0,2})|
			(?P<macro>(?>
				' . self::RE_STRING . '|
				\{(?>' . self::RE_STRING . '|[^\'"{}])*+\}|
				[^\'"{}]+
			)++)
			' . $this->delimiters[1] . '
			(?P<rmargin>[ \t]*\n)?
		~xsiA');

		if (!empty($matches['macro'])) {
			$token = $this->createToken(LegacyToken::MACRO_TAG, $indent . $delimiter . $matches[0]);
			[$token->name, $token->value, $token->empty, $token->closing] = $this->parseMacroTag($matches['macro']);
			$token->indentation = $indent;
			$token->newline = isset($matches['rmargin']);
			yield $token;
			goto choice;

		} elseif (!empty($matches['comment'])) {
			$token = $this->createToken(LegacyToken::COMMENT, $indent . $delimiter . $matches[0]);
			$token->indentation = $indent;
			$token->newline = (bool) $matches['newline'];
			yield $token;
			goto choice;

		} else {
			throw new CompileException('Malformed tag contents.');
		}

		end:
		if ($this->offset < strlen($this->input)) {
			yield $this->createToken(LegacyToken::TEXT, substr($this->input, $this->offset));
		}
	}


	/**
	 * Matches next token.
	 */
	private function match(string $re): \Generator
	{
		if (!preg_match($re, $this->input, $matches, PREG_OFFSET_CAPTURE | PREG_UNMATCHED_AS_NULL, $this->offset)) {
			if (preg_last_error()) {
				throw new RegexpException(null, preg_last_error());
			}

			return [];
		}

		$value = substr($this->input, $this->offset, $matches[0][1] - $this->offset);
		if ($value !== '') {
			yield $this->createToken(LegacyToken::TEXT, $value);
		}

		$this->offset = $matches[0][1] + strlen($matches[0][0]);
		foreach ($matches as $k => $v) {
			$matches[$k] = $v[0];
		}

		return $matches;
	}


	/**
	 * @param  string  $type  Lexer::CONTENT_HTML, CONTENT_XML or CONTENT_TEXT
	 */
	public function setContentType(string $type): static
	{
		if (in_array($type, [self::CONTENT_HTML, self::CONTENT_XML], true)) {
			$this->setState(self::CONTEXT_HTML_TEXT);
			$this->xmlMode = $type === self::CONTENT_XML;
		} else {
			$this->setState(self::CONTEXT_NONE);
		}

		return $this;
	}


	public function setState(string $state): static
	{
		$this->state = $state;
		return $this;
	}


	/**
	 * Changes macro tag delimiters.
	 */
	public function setSyntax(?string $type): static
	{
		$type ??= $this->defaultSyntax;
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
	 * @return array{string, string, bool, bool}|null
	 * @internal
	 */
	public function parseMacroTag(string $tag): ?array
	{
		if (!preg_match('~^
			(?P<closing>/?)
			(?P<name>=|_(?!_)|[a-z]\\w*+(?:[.:-]\\w+)*+(?!::|\\(|\\\\)|)   ## name, /name, but not function( or class:: or namespace\\
			(?P<args>(?:' . self::RE_STRING . '|[^\'"])*?)
			(?P<empty>/?$)
		()$~Disx', $tag, $match)) {
			if (preg_last_error()) {
				throw new RegexpException(null, preg_last_error());
			}

			return null;
		}

		if ($match['name'] === '') {
			$match['name'] = $match['closing'] ? '' : '=';
		}

		return [$match['name'], trim($match['args']), (bool) $match['empty'], (bool) $match['closing']];
	}


	private function createToken(string $type, string $text): LegacyToken
	{
		$token = new LegacyToken;
		$token->type = $type;
		$token->text = $text;
		$token->line = $this->line;
		$this->line += substr_count($token->text, "\n");
		return $token;
	}


	public function getLine(): int
	{
		return $this->line;
	}


	private function normalize(string $s): string
	{
		if (str_starts_with($s, "\u{FEFF}")) { // BOM
			$s = substr($s, 3);
		}

		$s = str_replace("\r\n", "\n", $s);

		if (!preg_match('##u', $s)) {
			preg_match('#(?:[\x00-\x7F]|[\xC0-\xDF][\x80-\xBF]|[\xE0-\xEF][\x80-\xBF]{2}|[\xF0-\xF7][\x80-\xBF]{3})*+#A', $s, $m);
			$this->line = substr_count($m[0], "\n") + 1;
			throw new CompileException('Template is not valid UTF-8 stream.');

		} elseif (preg_match('#[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]#', $s, $m, PREG_OFFSET_CAPTURE)) {
			$this->line = substr_count($s, "\n", 0, $m[0][1]) + 1;
			throw new CompileException('Template contains control character \x' . dechex(ord($m[0][0])));
		}
		return $s;
	}
}
