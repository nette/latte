<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte;


/**
 * Latte parser.
 */
class Parser
{
	use Strict;

	/** @internal regular expression for single & double quoted PHP string */
	const RE_STRING = '\'(?:\\\\.|[^\'\\\\])*+\'|"(?:\\\\.|[^"\\\\])*+"';

	/** @internal special HTML attribute prefix */
	const N_PREFIX = 'n:';

	/** Context-aware escaping content types */
	const
		CONTENT_HTML = Engine::CONTENT_HTML,
		CONTENT_XHTML = Engine::CONTENT_XHTML,
		CONTENT_XML = Engine::CONTENT_XML,
		CONTENT_TEXT = Engine::CONTENT_TEXT;

	/** @internal states */
	const
		CONTEXT_NONE = 'none',
		CONTEXT_MACRO = 'macro',
		CONTEXT_HTML_TEXT = 'htmlText',
		CONTEXT_HTML_TAG = 'htmlTag',
		CONTEXT_HTML_ATTRIBUTE = 'htmlAttribute',
		CONTEXT_HTML_COMMENT = 'htmlComment',
		CONTEXT_HTML_CDATA = 'htmlCData';

	/** @var string default macro tag syntax */
	public $defaultSyntax = 'latte';

	/** @deprecated */
	public $shortNoEscape;

	/** @var array */
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

	/** @var array */
	private $context = [self::CONTEXT_HTML_TEXT, null];

	/** @var string */
	private $lastHtmlTag;

	/** @var string used by filter() */
	private $syntaxEndTag;

	/** @var int */
	private $syntaxEndLevel = 0;

	/** @var bool */
	private $xmlMode;


	/**
	 * Process all {macros} and <tags/>.
	 * @param  string
	 * @return Token[]
	 */
	public function parse($input)
	{
		if (Helpers::startsWith($input, "\xEF\xBB\xBF")) { // BOM
			$input = substr($input, 3);
		}

		$this->input = $input = str_replace("\r\n", "\n", $input);
		$this->offset = 0;
		$this->output = [];

		if (!preg_match('##u', $input)) {
			preg_match('#(?:[\x00-\x7F]|[\xC0-\xDF][\x80-\xBF]|[\xE0-\xEF][\x80-\xBF]{2}|[\xF0-\xF7][\x80-\xBF]{3})*+#A', $input, $m);
			$this->offset = strlen($m[0]) + 1;
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
			throw new CompileException('Malformed macro');
		}

		if ($this->offset < strlen($input)) {
			$this->addToken(Token::TEXT, substr($this->input, $this->offset));
		}
		return $this->output;
	}


	/**
	 * Handles CONTEXT_HTML_TEXT.
	 */
	private function contextHtmlText()
	{
		$matches = $this->match('~
			(?:(?<=\n|^)[ \t]*)?<(?P<closing>/?)(?P<tag>[a-z][a-z0-9:]*)|  ##  begin of HTML tag <tag </tag - ignores <!DOCTYPE
			<(?P<htmlcomment>!(?:--(?!>))?|\?(?!=|php))|     ##  begin of <!, <!--, <!DOCTYPE, <?, but not <?php and <?=
			(?P<macro>' . $this->delimiters[0] . ')
		~xsi');

		if (!empty($matches['htmlcomment'])) { // <! <?
			$this->addToken(Token::HTML_TAG_BEGIN, $matches[0]);
			$end = $matches['htmlcomment'] === '!--' ? '--' : ($matches['htmlcomment'] === '?' && $this->xmlMode ? '\?' : '');
			$this->setContext(self::CONTEXT_HTML_COMMENT, $end);

		} elseif (!empty($matches['tag'])) { // <tag or </tag
			$token = $this->addToken(Token::HTML_TAG_BEGIN, $matches[0]);
			$token->name = $matches['tag'];
			$token->closing = (bool) $matches['closing'];
			$this->lastHtmlTag = $matches['closing'] . strtolower($matches['tag']);
			$this->setContext(self::CONTEXT_HTML_TAG);

		} else {
			return $this->processMacro($matches);
		}
	}


	/**
	 * Handles CONTEXT_HTML_CDATA.
	 */
	private function contextHtmlCData()
	{
		$matches = $this->match('~
			</(?P<tag>' . $this->lastHtmlTag . ')(?![a-z0-9:])| ##  end HTML tag </tag
			(?P<macro>' . $this->delimiters[0] . ')
		~xsi');

		if (!empty($matches['tag'])) { // </tag
			$token = $this->addToken(Token::HTML_TAG_BEGIN, $matches[0]);
			$token->name = $this->lastHtmlTag;
			$token->closing = true;
			$this->lastHtmlTag = '/' . $this->lastHtmlTag;
			$this->setContext(self::CONTEXT_HTML_TAG);
		} else {
			return $this->processMacro($matches);
		}
	}


	/**
	 * Handles CONTEXT_HTML_TAG.
	 */
	private function contextHtmlTag()
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

		} elseif (isset($matches['attr']) && $matches['attr'] !== '') { // HTML attribute
			$token = $this->addToken(Token::HTML_ATTRIBUTE_BEGIN, $matches[0]);
			$token->name = $matches['attr'];
			$token->value = isset($matches['value']) ? $matches['value'] : '';

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
		} else {
			return $this->processMacro($matches);
		}
	}


	/**
	 * Handles CONTEXT_HTML_ATTRIBUTE.
	 */
	private function contextHtmlAttribute()
	{
		$matches = $this->match('~
			(?P<quote>' . $this->context[1] . ')|  ##  end of HTML attribute
			(?P<macro>' . $this->delimiters[0] . ')
		~xsi');

		if (!empty($matches['quote'])) { // (attribute end) '"
			$this->addToken(Token::HTML_ATTRIBUTE_END, $matches[0]);
			$this->setContext(self::CONTEXT_HTML_TAG);
		} else {
			return $this->processMacro($matches);
		}
	}


	/**
	 * Handles CONTEXT_HTML_COMMENT.
	 */
	private function contextHtmlComment()
	{
		$matches = $this->match('~
			(?P<htmlcomment>' . $this->context[1] . '>)|   ##  end of HTML comment
			(?P<macro>' . $this->delimiters[0] . ')
		~xsi');

		if (!empty($matches['htmlcomment'])) { // -->
			$this->addToken(Token::HTML_TAG_END, $matches[0]);
			$this->setContext(self::CONTEXT_HTML_TEXT);
		} else {
			return $this->processMacro($matches);
		}
	}


	/**
	 * Handles CONTEXT_NONE.
	 */
	private function contextNone()
	{
		$matches = $this->match('~
			(?P<macro>' . $this->delimiters[0] . ')
		~xsi');
		return $this->processMacro($matches);
	}


	/**
	 * Handles CONTEXT_MACRO.
	 */
	private function contextMacro()
	{
		$matches = $this->match('~
			(?P<comment>\\*.*?\\*' . $this->delimiters[1] . '\n{0,2})|
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
			list($token->name, $token->value, $token->modifiers, $token->empty, $token->closing) = $this->parseMacroTag($matches['macro']);
			$this->context = $this->context[1][0];

		} elseif (!empty($matches['comment'])) {
			$this->addToken(Token::COMMENT, $this->context[1][1] . $matches[0]);
			$this->context = $this->context[1][0];

		} else {
			throw new CompileException('Malformed macro');
		}
	}


	private function processMacro($matches)
	{
		if (!empty($matches['macro'])) { // {macro} or {* *}
			$this->setContext(self::CONTEXT_MACRO, [$this->context, $matches['macro']]);
		} else {
			return false;
		}
	}


	/**
	 * Matches next token.
	 * @param  string
	 * @return array
	 */
	private function match($re)
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
	 * @param  string  Parser::CONTENT_HTML, CONTENT_XHTML, CONTENT_XML or CONTENT_TEXT
	 * @return static
	 */
	public function setContentType($type)
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
	 * @return static
	 */
	public function setContext($context, $quote = null)
	{
		$this->context = [$context, $quote];
		return $this;
	}


	/**
	 * Changes macro tag delimiters.
	 * @param  string
	 * @return static
	 */
	public function setSyntax($type)
	{
		$type = $type ?: $this->defaultSyntax;
		if (isset($this->syntaxes[$type])) {
			$this->setDelimiters($this->syntaxes[$type][0], $this->syntaxes[$type][1]);
		} else {
			throw new \InvalidArgumentException("Unknown syntax '$type'");
		}
		return $this;
	}


	/**
	 * Changes macro tag delimiters.
	 * @param  string  left regular expression
	 * @param  string  right regular expression
	 * @return static
	 */
	public function setDelimiters($left, $right)
	{
		$this->delimiters = [$left, $right];
		return $this;
	}


	/**
	 * Parses macro tag to name, arguments a modifiers parts.
	 * @param  string {name arguments | modifiers}
	 * @return array|null
	 * @internal
	 */
	public function parseMacroTag($tag)
	{
		if (!preg_match('~^
			(?P<closing>/?)
			(
				(?P<name>\?|[a-z]\w*+(?:[.:]\w+)*+(?!::|\(|\\\\))|   ## ?, name, /name, but not function( or class:: or namespace\
				(?P<noescape>!?)(?P<shortname>[=\~#%^&_]?)      ## !expression, !=expression, ...
			)(?P<args>(?:' . self::RE_STRING . '|[^\'"])*?)
			(?P<modifiers>(?<!\|)\|[a-z](?P<modArgs>(?:' . self::RE_STRING . '|(?:\((?P>modArgs)\))|[^\'"/()]|/(?=.))*+))?
			(?P<empty>/?\z)
		()\z~isx', $tag, $match)) {
			if (preg_last_error()) {
				throw new RegexpException(null, preg_last_error());
			}
			return null;
		}
		if ($match['name'] === '') {
			$match['name'] = $match['shortname'] ?: ($match['closing'] ? '' : '=');
			if ($match['noescape']) {
				trigger_error("The noescape shortcut {!...} is deprecated, use {...|noescape} modifier on line {$this->getLine()}.", E_USER_DEPRECATED);
				$match['modifiers'] .= '|noescape';
			}
		}
		return [$match['name'], trim($match['args']), $match['modifiers'], (bool) $match['empty'], (bool) $match['closing']];
	}


	private function addToken($type, $text)
	{
		$this->output[] = $token = new Token;
		$token->type = $type;
		$token->text = $text;
		$token->line = $this->getLine() - substr_count(ltrim($text), "\n");
		return $token;
	}


	public function getLine()
	{
		return $this->offset
			? substr_count(substr($this->input, 0, $this->offset - 1), "\n") + 1
			: 1;
	}


	/**
	 * Process low-level macros.
	 */
	protected function filter(Token $token)
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

		} elseif ($token->type === Token::HTML_TAG_END && $this->lastHtmlTag === ('/' . $this->syntaxEndTag) && --$this->syntaxEndLevel === 0) {
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
