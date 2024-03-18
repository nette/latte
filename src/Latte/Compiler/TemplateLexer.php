<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\CompileException;


final class TemplateLexer
{
	public const
		StatePlain = 'Plain',
		StateLatteTag = 'LatteTag',
		StateLatteComment = 'LatteComment',
		StateHtmlText = 'HtmlText',
		StateHtmlTag = 'HtmlTag',
		StateHtmlQuotedValue = 'HtmlQuotedValue',
		StateHtmlQuotedNAttrValue = 'HtmlQuotedNAttrValue',
		StateHtmlRawText = 'HtmlRawText',
		StateHtmlComment = 'HtmlComment',
		StateHtmlBogus = 'HtmlBogus';

	/** HTML tag name for Latte needs (actually is [a-zA-Z][^\s/>]*) */
	public const ReTagName = '[a-zA-Z][a-zA-Z0-9:_.-]*';

	/** special HTML attribute prefix */
	public const NPrefix = 'n:';

	/** HTML attribute name/value (\p{C} means \x00-\x1F except space) */
	private const ReAttrName = '[^\p{C} "\'<>=`/]';

	private string $openDelimiter = '';
	private string $closeDelimiter = '';
	private array $delimiters = [];
	private TagLexer $tagLexer;

	/** @var array<array{name: string, args: mixed[]}> */
	private array $states = [];
	private string $input;
	private Position $position;


	public function __construct()
	{
		$this->position = new Position;
		$this->setState(self::StatePlain);
		$this->setSyntax(null);
		$this->tagLexer = new TagLexer;
	}


	/** @return \Generator<Token> */
	public function tokenize(string $template): \Generator
	{
		$this->input = $this->normalize($template);

		do {
			$offset = $this->position->offset;
			$state = $this->states[0];
			$tokens = $this->{"state$state[name]"}(...$state['args']);
			yield from $tokens;

		} while ($offset !== $this->position->offset);

		if ($offset < strlen($this->input)) {
			throw new CompileException("Unexpected '" . substr($this->input, $offset, 10) . "'", $this->position);
		}

		yield new Token(Token::End, '', $this->position);
	}


	private function statePlain(): array
	{
		return $this->match('~
			(?<Text>.+?)??
			(?<Indentation>(?<=\n|^)[ \t]+)?
			(
				(?<Latte_TagOpen>' . $this->openDelimiter . '(?!\*))|      # {tag
				(?<Latte_CommentOpen>' . $this->openDelimiter . '\*)|      # {* comment
				$
			)
		~xsiAuD');
	}


	private function stateLatteTag(): array
	{
		$tokens[] = $this->match('~
			(?<Slash>/)?
			(?<Latte_Name> = | _(?!_) | [a-z]\w*+(?:[.:-]\w+)*+(?!::|\(|\\\\))?   # name, /name, but not function( or class:: or namespace\
		~xsiAu');

		$tokens[] = $this->tagLexer->tokenizePartially($this->input, $this->position);

		$tokens[] = $this->match('~
			(?<Slash>/)?
			(?<Latte_TagClose>' . $this->closeDelimiter . ')
			(?<Newline>[ \t]*\R)?
		~xsiAu');

		return array_merge(...$tokens);
	}


	private function stateLatteComment(): array
	{
		return $this->match('~
			(?<Text>.+?)??
			(
				(?<Latte_CommentClose>\*' . $this->closeDelimiter . ')(?<Newline>[ \t]*\R{1,2})?|
				$
			)
		~xsiAu');
	}


	private function stateHtmlText(): array
	{
		return $this->match('~(?J)
			(?<Text>.+?)??
			(
				(?<Indentation>(?<=\n|^)[ \t]+)?(?<Html_TagOpen><)(?<Slash>/)?(?=[a-z]|' . $this->openDelimiter . ')|  # < </ tag
				(?<Html_CommentOpen><!--(?!>|->))|                                                      # <!-- comment
				(?<Html_BogusOpen><[?!])|                                                               # <!doctype <?xml or error
				(?<Indentation>(?<=\n|^)[ \t]+)?(?<Latte_TagOpen>' . $this->openDelimiter . '(?!\*))|   # {tag
				(?<Indentation>(?<=\n|^)[ \t]+)?(?<Latte_CommentOpen>' . $this->openDelimiter . '\*)|   # {* comment
				$
			)
		~xsiAuD');
	}


	private function stateHtmlTag(): array
	{
		return $this->match('~(?J)
			(?<Equals>=)
				(?<Whitespace>\s+)?
				(?<Html_Name>(?:(?!' . $this->openDelimiter . ')' . self::ReAttrName . '|/)+)?  # HTML attribute value can contain /
			|
			(?<Whitespace>\s+)|                                        # whitespace
			(?<Quote>["\'])|
			(?<Slash>/)?(?<Html_TagClose>>)(?<Newline>[ \t]*\R)?|      # > />
			(?<Html_Name>(?:(?!' . $this->openDelimiter . ')' . self::ReAttrName . ')+)|  # HTML attribute name/value
			(?<Latte_TagOpen>' . $this->openDelimiter . '(?!\*))|      # {tag
			(?<Latte_CommentOpen>' . $this->openDelimiter . '\*)       # {* comment
		~xsiAu');
	}


	private function stateHtmlQuotedValue(string $quote): array
	{
		return $this->match('~
			(?<Text>.+?)??
			(
				(?<Quote>' . $quote . ')|
				(?<Latte_TagOpen>' . $this->openDelimiter . '(?!\*))|      # {tag
				(?<Latte_CommentOpen>' . $this->openDelimiter . '\*)|      # {* comment
				$
			)
		~xsiAu');
	}


	private function stateHtmlQuotedNAttrValue(string $quote): array
	{
		return $this->match('~
			(?<Text>.+?)??
			(
				(?<Quote>' . $quote . ')|
				$
			)
		~xsiAu');
	}


	private function stateHtmlRawText(string $tagName): array
	{
		return $this->match('~
			(?<Text>.+?)??
			(?<Indentation>(?<=\n|^)[ \t]+)?
			(
				(?<Html_TagOpen><)(?<Slash>/)(?<Html_Name>' . preg_quote($tagName, '~') . ')|  # </tag
				(?<Latte_TagOpen>' . $this->openDelimiter . '(?!\*))|                          # {tag
				(?<Latte_CommentOpen>' . $this->openDelimiter . '\*)|                          # {* comment
				$
			)
		~xsiAu');
	}


	private function stateHtmlComment(): array
	{
		return $this->match('~(?J)
			(?<Text>.+?)??
			(
				(?<Html_CommentClose>-->)|                                                              # -->
				(?<Indentation>(?<=\n|^)[ \t]+)?(?<Latte_TagOpen>' . $this->openDelimiter . '(?!\*))|   # {tag
				(?<Indentation>(?<=\n|^)[ \t]+)?(?<Latte_CommentOpen>' . $this->openDelimiter . '\*)|   # {* comment
				$
			)
		~xsiAu');
	}


	private function stateHtmlBogus(): array
	{
		return $this->match('~
			(?<Text>.+?)??
			(
				(?<Html_TagClose>>)|                                       # >
				(?<Latte_TagOpen>' . $this->openDelimiter . '(?!\*))|      # {tag
				(?<Latte_CommentOpen>' . $this->openDelimiter . '\*)|      # {* comment
				$
			)
		~xsiAu');
	}


	/**
	 * Matches next token.
	 * @return Token[]
	 */
	private function match(string $re): array
	{
		preg_match($re, $this->input, $matches, PREG_UNMATCHED_AS_NULL, $this->position->offset);
		if (preg_last_error()) {
			throw new CompileException(preg_last_error_msg());
		}

		$tokens = [];
		foreach ($matches as $k => $v) {
			if ($v !== null && !\is_int($k)) {
				$tokens[] = new Token(\constant(Token::class . '::' . $k), $v, $this->position);
				$this->position = $this->position->advance($v);
			}
		}

		return $tokens;
	}


	public function setState(string $state, ...$args): void
	{
		$this->states[0] = ['name' => $state, 'args' => $args];
	}


	public function pushState(string $state, ...$args): void
	{
		array_unshift($this->states, null);
		$this->setState($state, ...$args);
	}


	public function popState(): void
	{
		array_shift($this->states);
	}


	public function getState(): string
	{
		return $this->states[0]['name'];
	}


	/**
	 * Changes tag delimiters.
	 */
	public function setSyntax(?string $type, ?string $endTag = null): static
	{
		$left = '\{(?![\s\'"{}])';
		$end = $endTag ? '\{/' . preg_quote($endTag, '~') . '\}' : null;

		$this->delimiters[] = [$this->openDelimiter, $this->closeDelimiter];
		[$this->openDelimiter, $this->closeDelimiter] = match ($type) {
			null => [$left, '\}'], // {...}
			'off' => [$endTag ? '(?=' . $end . ')\{' : '(?!x)x', '\}'],
			'double' => $endTag // {{...}}
				? ['(?:\{' . $left . '|(?=' . $end . ')\{)', '\}(?:\}|(?<=' . $end . '))']
				: ['\{' . $left, '\}\}'],
			default => throw new \InvalidArgumentException("Unknown syntax '$type'"),
		};
		return $this;
	}


	public function popSyntax(): void
	{
		[$this->openDelimiter, $this->closeDelimiter] = array_pop($this->delimiters);
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
