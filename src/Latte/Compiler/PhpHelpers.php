<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;


/**
 * PHP helpers.
 * @internal
 */
class PhpHelpers
{
	/**
	 * Optimizes code readability.
	 */
	public static function reformatCode(string $source): string
	{
		$res = $php = '';
		$lastChar = ';';
		$tokens = new \ArrayIterator(token_get_all($source));
		$level = $openLevel = 0;
		$lineLength = 100;
		$specialBrace = false;

		foreach ($tokens as $n => $token) {
			$next = $tokens[$n + 1] ?? [null, ''];

			if (is_array($token)) {
				[$name, $token] = ($tmp = $token);
				if ($name === T_INLINE_HTML) {
					$res .= $token;

				} elseif ($name === T_OPEN_TAG) {
					$openLevel = $level;

				} elseif ($name === T_CLOSE_TAG) {
					if ($next[0] === T_OPEN_TAG) { // remove ?)<?php
						if (!strspn($lastChar, ';{:/' . ($specialBrace ? '' : '}'))) {
							$php = rtrim($php) . ($lastChar = ';') . "\n" . str_repeat("\t", $level);
						} elseif (substr($next[1], -1) === "\n") {
							$php .= "\n" . str_repeat("\t", $level);
						}

						$tokens->next();

					} else {
						if (trim($php) !== '' || substr($res, -1) === '<') { // skip <?php ?) but preserve <<?php
							$inline = !str_contains($php, "\n") && strlen($res) - strrpos($res, "\n") < $lineLength;
							$res .= '<?php' . ($inline ? ' ' : "\n" . str_repeat("\t", $openLevel));
							if (!str_contains($next[1], "\n")) {
								$token = rtrim($token, "\n");
							} else {
								$php = rtrim($php, "\t");
							}

							$res .= $php . $token;
						}

						$php = '';
						$lastChar = ';';
					}
				} elseif ($name === T_ELSE || $name === T_ELSEIF) {
					if ($next === ':' && $lastChar === '}') {
						$php .= ';'; // semicolon needed in if(): ... if() ... else:
					}

					$lastChar = '';
					$php .= $token;

				} elseif ($name === T_DOC_COMMENT || $name === T_COMMENT) {
					$php .= preg_replace("#\n[ \t]*+(?!\n)#", "\n" . str_repeat("\t", $level), $token);

				} elseif ($name === T_WHITESPACE) {
					$prev = $tokens[$n - 1];
					$lines = substr_count($token, "\n");
					if ($prev === '}' && in_array($next[0], [T_ELSE, T_ELSEIF, T_CATCH, T_FINALLY], true)) {
						$token = ' ';
					} elseif ($prev === '{' || $prev === '}' || $prev === ';' || $lines) {
						$token = str_repeat("\n", max(1, $lines)) . str_repeat("\t", $level); // indent last line
					} elseif ($prev[0] === T_OPEN_TAG) {
						$token = '';
					}

					$php .= $token;

				} elseif ($name === T_OBJECT_OPERATOR) {
					$lastChar = '->';
					$php .= $token;

				} else {
					if (in_array($name, [T_CURLY_OPEN, T_DOLLAR_OPEN_CURLY_BRACES], true)) {
						$level++;
					}

					$lastChar = '';
					$php .= $token;
				}
			} else {
				if ($token === '{' || $token === '[') {
					$level++;
					if ($lastChar === '->' || $lastChar === '$') {
						$specialBrace = true;
					}
				} elseif ($token === '}' || $token === ']') {
					$level--;
					$php .= "\x08";

				} elseif ($token === ';') {
					$specialBrace = false;
					if ($next[0] !== T_WHITESPACE) {
						$token .= "\n" . str_repeat("\t", $level); // indent last line
					}
				}

				$lastChar = $token;
				$php .= $token;
			}
		}

		if ($php) {
			$res .= "<?php\n" . str_repeat("\t", $openLevel) . $php;
		}

		$res = str_replace(["\t\x08", "\x08"], '', $res);
		return $res;
	}


	public static function dump(mixed $value, bool $multiline = false): string
	{
		if (is_array($value)) {
			$indexed = $value && array_keys($value) === range(0, count($value) - 1);
			$s = '';
			foreach ($value as $k => $v) {
				$s .= $multiline
					? ($s === '' ? "\n" : '') . "\t" . ($indexed ? '' : self::dump($k) . ' => ') . self::dump($v) . ",\n"
					: ($s === '' ? '' : ', ') . ($indexed ? '' : self::dump($k) . ' => ') . self::dump($v);
			}

			return '[' . $s . ']';
		} elseif ($value === null) {
			return 'null';
		} else {
			return var_export($value, true);
		}
	}


	public static function optimizeEcho(string $source): string
	{
		$res = '';
		$tokens = token_get_all($source);
		$start = null;

		for ($i = 0; $i < \count($tokens); $i++) {
			$token = $tokens[$i];
			if ($token[0] === T_ECHO) {
				if (!$start) {
					$str = '';
					$start = strlen($res);
				}

			} elseif ($start && $token[0] === T_CONSTANT_ENCAPSED_STRING && $token[1][0] === "'") {
				$str .= stripslashes(substr($token[1], 1, -1));

			} elseif ($start && $token === ';') {
				if ($str !== '') {
					$res = substr_replace(
						$res,
						'echo ' . ($str === "\n" ? '"\n"' : var_export($str, true)),
						$start,
						strlen($res) - $start,
					);
				}

			} elseif ($token[0] !== T_WHITESPACE) {
				$start = null;
			}

			$res .= is_array($token) ? $token[1] : $token;
		}

		return $res;
	}
}
