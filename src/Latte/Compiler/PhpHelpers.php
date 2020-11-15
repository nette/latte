<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;


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
			if (is_array($token)) {
				[$name, $token] = ($tmp = $token);
				if ($name === T_INLINE_HTML) {
					$res .= $token;

				} elseif ($name === T_OPEN_TAG) {
					$openLevel = $level;

				} elseif ($name === T_CLOSE_TAG) {
					$next = $tokens[$n + 1] ?? null;
					if (is_array($next) && $next[0] === T_OPEN_TAG) { // remove ?)<?php
						if (!strspn($lastChar, ';{:/' . ($specialBrace ? '' : '}'))) {
							$php = rtrim($php) . ($lastChar = ';') . "\n" . str_repeat("\t", $level);
						} elseif (substr($next[1], -1) === "\n") {
							$php .= "\n" . str_repeat("\t", $level);
						}
						$tokens->next();

					} else {
						if (trim($php) !== '' || substr($res, -1) === '<') { // skip <?php ?) but preserve <<?php
							$inline = strpos($php, "\n") === false && strlen($res) - strrpos($res, "\n") < $lineLength;
							$res .= '<?php' . ($inline ? ' ' : "\n" . str_repeat("\t", $openLevel));
							if (is_array($next) && strpos($next[1], "\n") === false) {
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
					if ($tokens[$n + 1] === ':' && $lastChar === '}') {
						$php .= ';'; // semicolon needed in if(): ... if() ... else:
					}
					$lastChar = '';
					$php .= $token;

				} elseif ($name === T_DOC_COMMENT || $name === T_COMMENT) {
					$php .= preg_replace("#\n[ \t]*+(?!\n)#", "\n" . str_repeat("\t", $level), $token);

				} elseif ($name === T_WHITESPACE) {
					$prev = $tokens[$n - 1];
					$lines = substr_count($token, "\n");
					if ($prev === '{' || $prev === '}' || $prev === ';' || $lines) {
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
					if (($tokens[$n + 1][0] ?? null) !== T_WHITESPACE) {
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


	/**
	 * @param  mixed  $value
	 */
	public static function dump($value, bool $multiline = false): string
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


	public static function inlineHtmlToEcho(string $source): string
	{
		$res = '';
		$tokens = token_get_all($source);

		for ($i = 0; $i < \count($tokens); $i++) {
			$token = $tokens[$i];
			if (is_array($token)) {
				if ($token[0] === T_INLINE_HTML) {
					$str = $token[1];
					$n = $i + 1;
					while (isset($tokens[$n])) {
						if ($tokens[$n][0] === T_INLINE_HTML) {
							$str .= $tokens[$n][1];
							$i = $n;
						} elseif (
							$tokens[$n][0] !== T_OPEN_TAG
							&& $tokens[$n][0] !== T_CLOSE_TAG
							&& $tokens[$n][0] !== T_WHITESPACE
						) {
							break;
						}
						$n++;
					}

					$export = $str === "\n" ? '"\n"' : var_export($str, true);
					$res .= "<?php echo $export ?>";
					continue;
				}
				$res .= $token[1];
			} else {
				$res .= $token;
			}
		}
		return $res;
	}
}
