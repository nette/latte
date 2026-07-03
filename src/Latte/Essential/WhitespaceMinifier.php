<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Essential;

use Latte;
use Latte\ContentType;
use Latte\Runtime\HtmlHelpers;
use function ltrim, ob_end_flush, ob_get_level, ob_start, preg_last_error, preg_last_error_msg, preg_match, preg_replace, preg_replace_callback, rtrim, str_contains, str_ends_with, str_repeat, strcspn, strlen, strspn, strtolower, substr, substr_count;
use const PHP_OUTPUT_HANDLER_FINAL, PREG_OFFSET_CAPTURE;


/**
 * Streaming whitespace minifier for HTML, XML and plain text, usable as an output buffer handler.
 * @internal
 */
final class WhitespaceMinifier
{
	/** elements around which whitespace is insignificant and can be removed entirely (lowercase) */
	private const WhitespaceInsensitiveElements = [
		'address' => 1, 'area' => 1, 'article' => 1, 'aside' => 1, 'base' => 1, 'blockquote' => 1, 'body' => 1,
		'br' => 1, 'caption' => 1, 'col' => 1, 'colgroup' => 1, 'dd' => 1, 'details' => 1, 'dialog' => 1,
		'div' => 1, 'dl' => 1, 'dt' => 1, 'fieldset' => 1, 'figcaption' => 1, 'figure' => 1, 'footer' => 1,
		'form' => 1, 'frame' => 1, 'frameset' => 1, 'h1' => 1, 'h2' => 1, 'h3' => 1, 'h4' => 1, 'h5' => 1,
		'h6' => 1, 'head' => 1, 'header' => 1, 'hgroup' => 1, 'hr' => 1, 'html' => 1, 'legend' => 1, 'li' => 1,
		'link' => 1, 'main' => 1, 'menu' => 1, 'nav' => 1, 'ol' => 1, 'optgroup' => 1, 'option' => 1, 'p' => 1,
		'param' => 1, 'pre' => 1, 'search' => 1, 'section' => 1, 'source' => 1, 'summary' => 1, 'table' => 1,
		'tbody' => 1, 'td' => 1, 'tfoot' => 1, 'th' => 1, 'thead' => 1, 'title' => 1, 'tr' => 1, 'track' => 1,
		'ul' => 1,
	];

	/** elements whose content is passed through verbatim */
	private const RawTextElements = ['pre' => 1, 'textarea' => 1, 'script' => 1, 'style' => 1];

	/** tokens starting with '<'; whitespace and text are handled by strcspn spans;
	 * possible first characters must be listed in TagStartChars and every alternative
	 * must end with '>' - the chunk-boundary guard in handle() relies on it */
	private const TokenPattern = <<<'XX'
		~
			(?<comment> <!-- .*? --> )
			| (?<special> <!\[CDATA\[ .*? ]]> | <! (?!\[CDATA\[|--) [^>]* > | <\? .*? \?> )
			| < (?<slash> /? ) (?<name> [a-z_:][^ \t\r\n/>]*+ ) (?<attrs> (?: [^"'>]++ | "[^"]*+" | '[^']*+' )*+ ) >
		~xsiA
		XX;

	/** characters that may follow '<' at the start of a markup token; keep in sync with TokenPattern */
	private const TagStartChars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_:/!?';

	private static int $depth = 0;
	private static ?int $level = null;

	private string $carry = '';
	private bool $pendingSpace = false;
	private int $pendingNewlines = 0;
	private bool $suppressSpace = true;
	private ?string $rawTag = null;


	public function __construct(
		private string $contentType = ContentType::Html,
	) {
	}


	/**
	 * Starts minifying the output buffer; nested calls are ignored (ob buffering is process-global).
	 */
	public static function start(string $contentType): void
	{
		if (self::$depth++ === 0 && ob_start([new self($contentType), 'handle'], 4096)) {
			self::$level = ob_get_level();
		}
	}


	public static function end(): void
	{
		if (self::$depth < 1) {
			self::$depth = 0;
			return;
		} elseif (--self::$depth > 0 || self::$level === null) {
			return;
		}
		while (ob_get_level() >= self::$level) {
			if (!ob_end_flush()) { // ours plus buffers leaked by user code inside the region
				break; // a non-flushable buffer, give up instead of looping forever
			}
		}
		self::$level = null;
	}


	/**
	 * One-shot minification.
	 */
	public function minify(string $s): string
	{
		$this->reset();
		return $this->handle($s, PHP_OUTPUT_HANDLER_FINAL);
	}


	private function reset(): void
	{
		$this->carry = '';
		$this->pendingSpace = false;
		$this->pendingNewlines = 0;
		$this->suppressSpace = true;
		$this->rawTag = null;
	}


	/**
	 * Output buffer handler; holds back an undecidable tail until more input arrives.
	 */
	public function handle(string $s, int $phase): string
	{
		$final = (bool) ($phase & PHP_OUTPUT_HANDLER_FINAL);
		if ($this->carry !== '' && !$final && $this->rawTag === null && !str_contains($s, '>')) {
			// an incomplete '<' construct can only be completed by a chunk containing '>';
			// applies to markup carry only: raw-mode carry is a short closing-tag tail and its body must keep streaming
			$this->carry .= $s;
			return '';
		}

		$s = $this->carry . $s;
		$this->carry = '';
		if ($this->contentType !== ContentType::Html && $this->contentType !== ContentType::Xml) {
			return $this->processText($s);
		}

		$res = '';
		$pos = 0;
		$len = strlen($s);
		while ($pos < $len) {
			$pos = $this->rawTag === null
				? $this->processMarkup($s, $pos, $final, $res)
				: $this->processRawText($this->rawTag, $s, $pos, $final, $res);
		}
		return $res;
	}


	private function processText(string $s): string
	{
		$keepNewlines = !str_contains($this->contentType, '/attr');
		$res = '';
		$pos = 0;
		$len = strlen($s);
		while ($pos < $len) {
			if ($n = strspn($s, " \t\r\n", $pos)) {
				if (!$this->suppressSpace) {
					$this->pendingSpace = true;
					if ($keepNewlines) {
						$this->pendingNewlines += substr_count($s, "\n", $pos, $n);
					}
				}
			} else {
				$n = strcspn($s, " \t\r\n", $pos);
				$res .= $this->flushSpace() . substr($s, $pos, $n);
				$this->suppressSpace = false;
			}
			$pos += $n;
		}
		return $res;
	}


	private function processMarkup(string $s, int $pos, bool $final, string &$res): int
	{
		$len = strlen($s);
		while ($pos < $len) {
			if ($n = strcspn($s, '<', $pos)) { // the whole span between tags at once
				$span = substr($s, $pos, $n);
				$pos += $n;
				$trimmed = ltrim($span, " \t\r\n");
				if ($trimmed === '') {
					$this->pendingSpace = $this->pendingSpace || !$this->suppressSpace;
					continue;
				}
				$this->pendingSpace = $this->pendingSpace || (!$this->suppressSpace && strlen($trimmed) !== strlen($span));
				$res .= $this->flushSpace();
				$body = rtrim($trimmed, " \t\r\n");
				$this->pendingSpace = strlen($body) !== strlen($trimmed);
				$res .= preg_replace('~[ \t\r\n]++~', ' ', $body);
				$this->suppressSpace = false;

			} else { // '<'
				if (strspn($s, self::TagStartChars, $pos + 1, 1) || (!$final && $pos + 1 === $len)) {
					if (preg_match(self::TokenPattern, $s, $m, 0, $pos)) {
						$pos += strlen($m[0]);
						if (($m['name'] ?? '') !== '') {
							$this->processTag($m['slash'] !== '', $m['name'], $m['attrs'], $res);
							if ($this->rawTag !== null) {
								return $pos; // the content must pass through verbatim
							}
						} else { // comment, doctype, CDATA, processing instruction
							$res .= $this->flushSpace() . $m[0];
						}
						continue;

					} elseif (preg_last_error()) {
						throw new Latte\RuntimeException(preg_last_error_msg());

					} elseif (!$final) {
						$this->carry = substr($s, $pos); // an incomplete construct, wait for the next chunk
						return $len;
					}
				}
				$res .= $this->flushSpace() . '<'; // literal '<' in text, or an unterminated construct degrading to text
				$this->suppressSpace = false;
				$pos++;
			}
		}
		return $pos;
	}


	private function processTag(bool $closing, string $name, string $attrs, string &$res): void
	{
		$lower = strtolower($name);
		if ($this->contentType === ContentType::Xml || isset(self::WhitespaceInsensitiveElements[$lower])) {
			$this->pendingSpace = false;
			$this->suppressSpace = true;

		} elseif ($closing) {
			// pending space is held across an inline closing tag and materializes after it
			$this->suppressSpace = false;

		} elseif (HtmlHelpers::isVoidElement($lower) || str_ends_with($attrs, '/')) {
			// an inline void element renders content, adjacent spaces are significant
			$res .= $this->flushSpace();
			$this->suppressSpace = false;

		} elseif ($this->pendingSpace) {
			// space before an inline opening tag makes whitespace right after it redundant
			$res .= ' ';
			$this->pendingSpace = false;
			$this->suppressSpace = true;
		}

		$res .= '<' . ($closing ? '/' : '') . $name . $this->formatAttrs($attrs) . '>';
		if (!$closing && $this->contentType === ContentType::Html && isset(self::RawTextElements[$lower])) {
			$this->rawTag = $lower;
		}
	}


	private function processRawText(string $rawTag, string $s, int $pos, bool $final, string &$res): int
	{
		if (preg_match('~</(' . $rawTag . ')(?=[ \t\r\n/>])([^>]*+)>~i', $s, $m, PREG_OFFSET_CAPTURE, $pos)) {
			$res .= substr($s, $pos, $m[0][1] - $pos)
				. '</' . $m[1][0] . $this->formatAttrs($m[2][0]) . '>';
			$this->pendingSpace = false;
			$this->suppressSpace = isset(self::WhitespaceInsensitiveElements[$rawTag]);
			$this->rawTag = null;
			return $m[0][1] + strlen($m[0][0]);

		} elseif (!$final && preg_match(self::getRawTailPattern($rawTag), $s, $m, PREG_OFFSET_CAPTURE, $pos)) {
			$res .= substr($s, $pos, $m[0][1] - $pos);
			$this->carry = $m[0][0];

		} else {
			$res .= substr($s, $pos);
		}
		return strlen($s);
	}


	/**
	 * Builds a pattern matching a possible beginning of the closing tag at the end of the buffer.
	 */
	private static function getRawTailPattern(string $name): string
	{
		static $cache = [];
		if (!isset($cache[$name])) {
			$re = '(?:[ \t\r\n/][^>]*)?';
			for ($i = strlen($name) - 1; $i >= 0; $i--) {
				$re = '(?:' . $name[$i] . $re . ')?';
			}
			$cache[$name] = '~<(?:/' . $re . ')?$~iD';
		}
		return $cache[$name];
	}


	/**
	 * Collapses whitespace between attributes, keeps quoted values, drops whitespace before '>'.
	 */
	private function formatAttrs(string $attrs): string
	{
		if ($attrs === '') {
			return '';
		}
		$attrs = preg_replace_callback(
			'~[ \t\r\n]++|"[^"]*+"|\'[^\']*+\'~',
			fn($m) => $m[0][0] === '"' || $m[0][0] === "'" ? $m[0] : ' ',
			$attrs,
		);
		return rtrim($attrs);
	}


	private function flushSpace(): string
	{
		if ($this->pendingNewlines) {
			$s = str_repeat("\n", $this->pendingNewlines);
		} elseif ($this->pendingSpace) {
			$s = ' ';
		} else {
			return '';
		}
		$this->pendingSpace = false;
		$this->pendingNewlines = 0;
		return $s;
	}
}
