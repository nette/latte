<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;


/**
 * Simple lexical analyser.
 * @internal
 */
class Tokenizer
{
	use Strict;

	public const
		VALUE = 0,
		OFFSET = 1,
		TYPE = 2;

	/** @var string */
	private $re;

	/** @var int[] */
	private $types;


	/**
	 * @param  array<int, string>  $patterns  of [(int) symbol type => pattern]
	 * @param  string $flags  regular expression flag
	 */
	public function __construct(array $patterns, string $flags = '')
	{
		$this->re = '~(' . implode(')|(', $patterns) . ')~A' . $flags;
		$this->types = array_keys($patterns);
	}


	/**
	 * Tokenizes string.
	 * @return array<array{string, int, int}>
	 */
	public function tokenize(string $input): array
	{
		preg_match_all($this->re, $input, $tokens, PREG_SET_ORDER);
		if (preg_last_error()) {
			throw new RegexpException(null, preg_last_error());
		}

		$len = 0;
		$count = count($this->types);
		foreach ($tokens as &$match) {
			$type = null;
			for ($i = 1; $i <= $count; $i++) {
				if (!isset($match[$i])) {
					break;
				} elseif ($match[$i] !== '') {
					$type = $this->types[$i - 1];
					break;
				}
			}

			$match = [self::VALUE => $match[0], self::OFFSET => $len, self::TYPE => $type];
			$len += strlen($match[self::VALUE]);
		}

		if ($len !== strlen($input)) {
			[$line, $col] = $this->getCoordinates($input, $len);
			$token = str_replace("\n", '\n', substr($input, $len, 10));
			throw new CompileException("Unexpected '$token' on line $line, column $col.");
		}

		return $tokens;
	}


	/**
	 * Returns position of token in input string.
	 * @return array{int, int} of [line, column]
	 */
	public static function getCoordinates(string $text, int $offset): array
	{
		$text = substr($text, 0, $offset);
		return [substr_count($text, "\n") + 1, $offset - strrpos("\n" . $text, "\n") + 1];
	}
}
