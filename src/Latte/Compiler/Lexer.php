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

	/** @var LegacyToken[] */
	private array $output;

	/** position on source template */
	private int $offset;

	private int $line;

	/** @var array{string, mixed} */
	private array $context = [self::CONTEXT_HTML_TEXT, null];

	private ?string $lastHtmlTag = null;

	/** used by filter() */
	private ?string $syntaxEndTag = null;

	private int $syntaxEndLevel = 0;

	private bool $xmlMode = false;


	/**
	 * Process all {macros} and <tags/>.
	 */
	public function tokenize(string $input): TokenStream
	{
		if (str_starts_with($input, "\u{FEFF}")) { // BOM
			$input = substr($input, 3);
		}

		$this->input = $input = str_replace("\r\n", "\n", $input);
		$this->offset = 0;
		$this->line = 1;
		$this->output = [];

		if (!preg_match('##u', $input)) {
			preg_match('#(?:[\x00-\x7F]|[\xC0-\xDF][\x80-\xBF]|[\xE0-\xEF][\x80-\xBF]{2}|[\xF0-\xF7][\x80-\xBF]{3})*+#A', $input, $m);
			$this->line += substr_count($m[0], "\n");
			throw new CompileException('Template is not valid UTF-8 stream.');

		} elseif (preg_match('#[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]#', $input, $m, PREG_OFFSET_CAPTURE)) {
			$this->line += substr_count($input, "\n", 0, $m[0][1]);
			throw new CompileException('Template contains control character \x' . dechex(ord($m[0][0])));
		}

		$this->setSyntax($this->defaultSyntax);
		$this->lastHtmlTag = $this->syntaxEndTag = null;

		$tokenCount = 0;
		while ($this->offset < strlen($input)) {
			if ($this->{'context' . $this->context[0]}() === false) {
				break;
			}

			while ($tokenCount < count($this->output)) {
				$this->filter($this->output[$tokenCount++]);
			}
		}

		if ($this->context[0] === self::CONTEXT_MACRO) {
			throw new CompileException('Malformed tag.');
		}

		if ($this->offset < strlen($input)) {
			$this->addToken(LegacyToken::TEXT, substr($this->input, $this->offset));
		}

		return new TokenStream($this->output);
	}


	/**
	 * Handles CONTEXT_HTML_TEXT.
	 */
	private function contextHtmlText(): bool
	{
		$matches = $this->match('~
			(?:(?<=\n|^)[ \t]*)?<(?P<closing>/?)(?P<tag>' . self::RE_TAG_NAME . ')|  ##  begin of HTML tag <tag </tag - ignores <!DOCTYPE
			<(?P<htmlcomment>!(?:--(?!>))?|\?)|     ##  begin of <!, <!--, <!DOCTYPE, <?
			(?P<indent>(?<=\n|^)[ \t]*)?(?P<macro>' . $this->delimiters[0] . ')
		~xsi');

		if (!empty($matches['htmlcomment'])) { // <! <?
			$this->addToken(LegacyToken::HTML_TAG_BEGIN, $matches[0]);
			$end = $matches['htmlcomment'] === '!--'
				? '--'
				: ($matches['htmlcomment'] === '?' && $this->xmlMode ? '\?' : '');
			$this->setContext(self::CONTEXT_HTML_COMMENT, $end);
			return true;

		} elseif (!empty($matches['tag'])) { // <tag or </tag
			$token = $this->addToken(LegacyToken::HTML_TAG_BEGIN, $matches[0]);
			$token->name = $matches['tag'];
			$token->closing = (bool) $matches['closing'];
			$this->lastHtmlTag = $matches['closing'] . strtolower($matches['tag']);
			$this->setContext(self::CONTEXT_HTML_TAG);
			return true;

		} else {
			return $this->processMacro($matches);
		}
	}


	/**
	 * Handles CONTEXT_HTML_CDATA.
	 */
	private function contextHtmlCData(): bool
	{
		$matches = $this->match('~
			</(?P<tag>' . $this->lastHtmlTag . ')(?=[\s/>])| ##  end HTML tag </tag
			(?P<indent>(?<=\n|^)[ \t]*)?(?P<macro>' . $this->delimiters[0] . ')
		~xsi');

		if (empty($matches['tag'])) {
			return $this->processMacro($matches);
		}

		// </tag
		$token = $this->addToken(LegacyToken::HTML_TAG_BEGIN, $matches[0]);
		$token->name = $matches['tag'];
		$token->closing = true;
		$this->lastHtmlTag = '/' . $this->lastHtmlTag;
		$this->setContext(self::CONTEXT_HTML_TAG);
		return true;
	}


	/**
	 * Handles CONTEXT_HTML_TAG.
	 */
	private function contextHtmlTag(): bool
	{
		$matches = $this->match('~
			(?P<end>\s?/?>)([ \t]*\n)?|  ##  end of HTML tag
			(?P<macro>' . $this->delimiters[0] . ')|
			\s*(?P<attr>[^\s"\'>/={]+)(?:\s*=\s*(?P<value>["\']|[^\s"\'=<>`{]+))? ## beginning of HTML attribute
		~xsi');

		if (!empty($matches['end'])) { // end of HTML tag />
			$this->addToken(LegacyToken::HTML_TAG_END, $matches[0]);
			$empty = str_contains($matches[0], '/');
			$this->setContext(!$this->xmlMode && !$empty && in_array($this->lastHtmlTag, ['script', 'style'], true) ? self::CONTEXT_HTML_CDATA : self::CONTEXT_HTML_TEXT);
			return true;

		} elseif (isset($matches['attr']) && $matches['attr'] !== '') { // HTML attribute
			$token = $this->addToken(LegacyToken::HTML_ATTRIBUTE_BEGIN, $matches[0]);
			$token->name = $matches['attr'];
			$token->value = $matches['value'] ?? '';

			if ($token->value === '"' || $token->value === "'") { // attribute = "'
				if (str_starts_with($token->name, self::N_PREFIX)) {
					$token->value = '';
					if ($m = $this->match('~(.*?)' . $matches['value'] . '~xsi')) {
						$token->value = $m[1];
						$token->text .= $m[0];
					}
				} else {
					$this->setContext(self::CONTEXT_HTML_ATTRIBUTE, $matches['value']);
				}
			}

			return true;

		} else {
			return $this->processMacro($matches);
		}
	}


	/**
	 * Handles CONTEXT_HTML_ATTRIBUTE.
	 */
	private function contextHtmlAttribute(): bool
	{
		$matches = $this->match('~
			(?P<quote>' . $this->context[1] . ')|  ##  end of HTML attribute
			(?P<macro>' . $this->delimiters[0] . ')
		~xsi');

		if (empty($matches['quote'])) {
			return $this->processMacro($matches);
		}

		// (attribute end) '"
		$this->addToken(LegacyToken::HTML_ATTRIBUTE_END, $matches[0]);
		$this->setContext(self::CONTEXT_HTML_TAG);
		return true;
	}


	/**
	 * Handles CONTEXT_HTML_COMMENT.
	 */
	private function contextHtmlComment(): bool
	{
		$matches = $this->match('~
			(?P<htmlcomment>' . $this->context[1] . '>)|   ##  end of HTML comment
			(?P<indent>(?<=\n|^)[ \t]*)?(?P<macro>' . $this->delimiters[0] . ')
		~xsi');

		if (empty($matches['htmlcomment'])) {
			return $this->processMacro($matches);
		}

		// -->
		$this->addToken(LegacyToken::HTML_TAG_END, $matches[0]);
		$this->setContext(self::CONTEXT_HTML_TEXT);
		return true;
	}


	/**
	 * Handles CONTEXT_NONE.
	 */
	private function contextNone(): bool
	{
		$matches = $this->match('~
			(?P<indent>(?<=\n|^)[ \t]*)?(?P<macro>' . $this->delimiters[0] . ')
		~xsi');
		return $this->processMacro($matches);
	}


	/**
	 * Handles CONTEXT_MACRO.
	 */
	private function contextMacro(): bool
	{
		$matches = $this->match('~
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
			$token = $this->addToken(LegacyToken::MACRO_TAG, $this->context[1][2] . $this->context[1][1] . $matches[0]);
			[$token->name, $token->value, $token->empty, $token->closing] = $this->parseMacroTag($matches['macro']);
			$token->indentation = $this->context[1][2];
			$token->newline = isset($matches['rmargin']);
			$this->context = $this->context[1][0];
			return true;

		} elseif (!empty($matches['comment'])) {
			$token = $this->addToken(LegacyToken::COMMENT, $this->context[1][2] . $this->context[1][1] . $matches[0]);
			$token->indentation = $this->context[1][2];
			$token->newline = (bool) $matches['newline'];
			$this->context = $this->context[1][0];
			return true;

		} else {
			throw new CompileException('Malformed tag contents.');
		}
	}


	/**
	 * @param  string[]  $matches
	 */
	private function processMacro(array $matches): bool
	{
		if (empty($matches['macro'])) {
			return false;
		}

		// {macro} or {* *}
		$this->setContext(self::CONTEXT_MACRO, [$this->context, $matches['macro'], $matches['indent'] ?? null]);
		return true;
	}


	/**
	 * Matches next token.
	 * @return string[]
	 */
	private function match(string $re): array
	{
		if (!preg_match($re, $this->input, $matches, PREG_OFFSET_CAPTURE | PREG_UNMATCHED_AS_NULL, $this->offset)) {
			if (preg_last_error()) {
				throw new RegexpException(null, preg_last_error());
			}

			return [];
		}

		$value = substr($this->input, $this->offset, $matches[0][1] - $this->offset);
		if ($value !== '') {
			$this->addToken(LegacyToken::TEXT, $value);
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
			$this->setContext(self::CONTEXT_HTML_TEXT);
			$this->xmlMode = $type === self::CONTENT_XML;
		} else {
			$this->setContext(self::CONTEXT_NONE);
		}

		return $this;
	}


	public function setContext(string $context, mixed $quote = null): static
	{
		$this->context = [$context, $quote];
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


	private function addToken(string $type, string $text): LegacyToken
	{
		$this->output[] = $token = new LegacyToken;
		$token->type = $type;
		$token->text = $text;
		$token->line = $this->line;
		$this->line += substr_count($text, "\n");
		return $token;
	}


	public function getLine(): int
	{
		return $this->line;
	}


	/**
	 * Process low-level macros.
	 */
	protected function filter(LegacyToken $token): void
	{
		if ($token->type === LegacyToken::MACRO_TAG && $token->name === 'syntax') {
			$this->setSyntax($token->closing ? $this->defaultSyntax : $token->value);
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
			$this->setSyntax($this->defaultSyntax);

		} elseif ($token->type === LegacyToken::MACRO_TAG && $token->name === 'contentType') {
			if (str_contains($token->value, 'html')) {
				$this->setContentType(self::CONTENT_HTML);
			} elseif (str_contains($token->value, 'xml')) {
				$this->setContentType(self::CONTENT_XML);
			} else {
				$this->setContentType(self::CONTENT_TEXT);
			}
		}
	}
}
