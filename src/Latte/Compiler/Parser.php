<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;


/**
 * Latte parser.
 */
class Parser
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
		CONTENT_XHTML = Engine::CONTENT_XHTML,
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

	/** @var string default macro tag syntax */
	public $defaultSyntax = 'latte';

	/** @var array<string, array{string, string}> */
	public $syntaxes = [
		'latte' => ['\{(?![\s\'"{}])', '\}'], // {...}
		'double' => ['\{\{(?![\s\'"{}])', '\}\}'], // {{...}}
		'off' => ['\{(?=/syntax\})', '\}'], // {/syntax}
	];

	/** @var string[] */
	private $delimiters;

	/** @var string source template */
	private $input;

	/** @var Token[] */
	private $output;

	/** @var int  position on source template */
	private $offset;

	/** @var int */
	private $line;

	/** @var array{string, mixed} */
	private $context = [self::CONTEXT_HTML_TEXT, null];

	/** @var string|null */
	private $lastHtmlTag;

	/** @var string|null used by filter() */
	private $syntaxEndTag;

	/** @var int */
	private $syntaxEndLevel = 0;

	/** @var bool */
	private $xmlMode;


	/**
	 * Process all {macros} and <tags/>.
	 * @return Token[]
	 */
	public function parse(string $input): array
	{
		if (Helpers::startsWith($input, "\u{FEFF}")) { // BOM
			$input = substr($input, 3);
		}

		if (preg_match('#[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]#', $input, $m, PREG_OFFSET_CAPTURE)) {
			trigger_error('Template contains control character \x' . dechex(ord($m[0][0])) . ' on line ' . (substr_count($input, "\n", 0, $m[0][1]) + 1) . '.', E_USER_WARNING);
			$input = preg_replace('#[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]#', '', $input);
		}

		$this->input = $input = str_replace("\r\n", "\n", $input);
		$this->offset = 0;
		$this->line = 1;
		$this->output = [];

		if (!preg_match('##u', $input)) {
			preg_match('#(?:[\x00-\x7F]|[\xC0-\xDF][\x80-\xBF]|[\xE0-\xEF][\x80-\xBF]{2}|[\xF0-\xF7][\x80-\xBF]{3})*+#A', $input, $m);
			$this->line += substr_count($m[0], "\n");
			throw new \InvalidArgumentException('Template is not valid UTF-8 stream.');
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
			$this->addToken(Token::TEXT, substr($this->input, $this->offset));
		}

		return $this->output;
	}


	/**
	 * Handles CONTEXT_HTML_TEXT.
	 */
	private function contextHtmlText(): bool
	{
		$matches = $this->match('~
			(?:(?<=\n|^)[ \t]*)?<(?P<closing>/?)(?P<tag>' . self::RE_TAG_NAME . ')|  ##  begin of HTML tag <tag </tag - ignores <!DOCTYPE
			<(?P<htmlcomment>!(?:--(?!>))?|\?)|     ##  begin of <!, <!--, <!DOCTYPE, <?
			(?P<macro>' . $this->delimiters[0] . ')
		~xsi');

		if (!empty($matches['htmlcomment'])) { // <! <?
			$this->addToken(Token::HTML_TAG_BEGIN, $matches[0]);
			$end = $matches['htmlcomment'] === '!--'
				? '--'
				: ($matches['htmlcomment'] === '?' && $this->xmlMode ? '\?' : '');
			$this->setContext(self::CONTEXT_HTML_COMMENT, $end);
			return true;

		} elseif (!empty($matches['tag'])) { // <tag or </tag
			$token = $this->addToken(Token::HTML_TAG_BEGIN, $matches[0]);
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
			(?P<macro>' . $this->delimiters[0] . ')
		~xsi');

		if (empty($matches['tag'])) {
			return $this->processMacro($matches);
		}

		// </tag
		$token = $this->addToken(Token::HTML_TAG_BEGIN, $matches[0]);
		$token->name = $this->lastHtmlTag;
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
			$this->addToken(Token::HTML_TAG_END, $matches[0]);
			$empty = strpos($matches[0], '/') !== false;
			$this->setContext(!$this->xmlMode && !$empty && in_array($this->lastHtmlTag, ['script', 'style'], true) ? self::CONTEXT_HTML_CDATA : self::CONTEXT_HTML_TEXT);
			return true;

		} elseif (isset($matches['attr']) && $matches['attr'] !== '') { // HTML attribute
			$token = $this->addToken(Token::HTML_ATTRIBUTE_BEGIN, $matches[0]);
			$token->name = $matches['attr'];
			$token->value = $matches['value'] ?? '';

			if ($token->value === '"' || $token->value === "'") { // attribute = "'
				if (Helpers::startsWith($token->name, self::N_PREFIX)) {
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
		$this->addToken(Token::HTML_ATTRIBUTE_END, $matches[0]);
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
			(?P<macro>' . $this->delimiters[0] . ')
		~xsi');

		if (empty($matches['htmlcomment'])) {
			return $this->processMacro($matches);
		}

		// -->
		$this->addToken(Token::HTML_TAG_END, $matches[0]);
		$this->setContext(self::CONTEXT_HTML_TEXT);
		return true;
	}


	/**
	 * Handles CONTEXT_NONE.
	 */
	private function contextNone(): bool
	{
		$matches = $this->match('~
			(?P<macro>' . $this->delimiters[0] . ')
		~xsi');
		return $this->processMacro($matches);
	}


	/**
	 * Handles CONTEXT_MACRO.
	 */
	private function contextMacro(): bool
	{
		$matches = $this->match('~
			(?P<comment>\*.*?\*' . $this->delimiters[1] . '\n{0,2})|
			(?P<macro>(?>
				' . self::RE_STRING . '|
				\{(?>' . self::RE_STRING . '|[^\'"{}])*+\}|
				[^\'"{}]+
			)++)
			' . $this->delimiters[1] . '
			(?P<rmargin>[ \t]*(?=\n))?
		~xsiA');

		if (!empty($matches['macro'])) {
			$token = $this->addToken(Token::MACRO_TAG, $this->context[1][1] . $matches[0]);
			[$token->name, $token->value, $token->modifiers, $token->empty, $token->closing] = $this->parseMacroTag($matches['macro']);
			$this->context = $this->context[1][0];
			return true;

		} elseif (!empty($matches['comment'])) {
			$this->addToken(Token::COMMENT, $this->context[1][1] . $matches[0]);
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
		$this->setContext(self::CONTEXT_MACRO, [$this->context, $matches['macro']]);
		return true;
	}


	/**
	 * Matches next token.
	 * @return string[]
	 */
	private function match(string $re): array
	{
		if (!preg_match($re, $this->input, $matches, PREG_OFFSET_CAPTURE, $this->offset)) {
			if (preg_last_error()) {
				throw new RegexpException(null, preg_last_error());
			}

			return [];
		}

		$value = substr($this->input, $this->offset, $matches[0][1] - $this->offset);
		if ($value !== '') {
			$this->addToken(Token::TEXT, $value);
		}

		$this->offset = $matches[0][1] + strlen($matches[0][0]);
		foreach ($matches as $k => $v) {
			$matches[$k] = $v[0];
		}

		return $matches;
	}


	/**
	 * @param  string  $type  Parser::CONTENT_HTML, CONTENT_XHTML, CONTENT_XML or CONTENT_TEXT
	 * @return static
	 */
	public function setContentType(string $type)
	{
		if (in_array($type, [self::CONTENT_HTML, self::CONTENT_XHTML, self::CONTENT_XML], true)) {
			$this->setContext(self::CONTEXT_HTML_TEXT);
			$this->xmlMode = $type === self::CONTENT_XML;
		} else {
			$this->setContext(self::CONTEXT_NONE);
		}

		return $this;
	}


	/**
	 * @param  mixed  $quote
	 * @return static
	 */
	public function setContext(string $context, $quote = null)
	{
		$this->context = [$context, $quote];
		return $this;
	}


	/**
	 * Changes macro tag delimiters.
	 * @return static
	 */
	public function setSyntax(string $type)
	{
		$type = $type ?: $this->defaultSyntax;
		if (!isset($this->syntaxes[$type])) {
			throw new \InvalidArgumentException("Unknown syntax '$type'");
		}

		$this->setDelimiters($this->syntaxes[$type][0], $this->syntaxes[$type][1]);
		return $this;
	}


	/**
	 * Changes macro tag delimiters (as regular expression).
	 * @return static
	 */
	public function setDelimiters(string $left, string $right)
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
			(
				(?P<name>\?|[a-z]\w*+(?:[.:-]\w+)*+(?!::|\(|\\\\))|   ## ?, name, /name, but not function( or class:: or namespace\
				(?P<shortname>=|_(?!_)|)      ## expression, =expression, ...
			)(?P<args>(?:' . self::RE_STRING . '|[^\'"])*?)
			(?P<modifiers>(?<!\|)\|[a-z](?P<modArgs>(?:' . self::RE_STRING . '|(?:\((?P>modArgs)\))|[^\'"/()]|/(?=.))*+))?
			(?P<empty>/?$)
		()$~Disx', $tag, $match)) {
			if (preg_last_error()) {
				throw new RegexpException(null, preg_last_error());
			}

			return null;
		}

		if ($match['name'] === '') {
			$match['name'] = $match['shortname'] ?: ($match['closing'] ? '' : '=');
		}

		return [$match['name'], trim($match['args']), $match['modifiers'], (bool) $match['empty'], (bool) $match['closing']];
	}


	private function addToken(string $type, string $text): Token
	{
		$this->output[] = $token = new Token;
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
	protected function filter(Token $token): void
	{
		if ($token->type === Token::MACRO_TAG && $token->name === '/syntax') {
			$this->setSyntax($this->defaultSyntax);
			$token->type = Token::COMMENT;

		} elseif ($token->type === Token::MACRO_TAG && $token->name === 'syntax') {
			$this->setSyntax($token->value);
			$token->type = Token::COMMENT;

		} elseif ($token->type === Token::HTML_ATTRIBUTE_BEGIN && $token->name === 'n:syntax') {
			$this->setSyntax($token->value);
			$this->syntaxEndTag = $this->lastHtmlTag;
			$this->syntaxEndLevel = 1;
			$token->type = Token::COMMENT;

		} elseif ($token->type === Token::HTML_TAG_BEGIN && $this->lastHtmlTag === $this->syntaxEndTag) {
			$this->syntaxEndLevel++;

		} elseif (
			$token->type === Token::HTML_TAG_END
			&& $this->lastHtmlTag === ('/' . $this->syntaxEndTag)
			&& --$this->syntaxEndLevel === 0
		) {
			$this->setSyntax($this->defaultSyntax);

		} elseif ($token->type === Token::MACRO_TAG && $token->name === 'contentType') {
			if (strpos($token->value, 'html') !== false) {
				$this->setContentType(self::CONTENT_HTML);
			} elseif (strpos($token->value, 'xml') !== false) {
				$this->setContentType(self::CONTENT_XML);
			} else {
				$this->setContentType(self::CONTENT_TEXT);
			}
		}
	}
}
