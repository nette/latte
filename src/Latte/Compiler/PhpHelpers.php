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
						if (!strspn($lastChar, ';{}:/')) {
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
				} elseif ($token === '}' || $token === ']') {
					$level--;
					$php .= "\x08";
				} elseif ($token === ';' && !(($tokens[$n + 1][0] ?? null) === T_WHITESPACE)) {
					$token .= "\n" . str_repeat("\t", $level); // indent last line
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


	public static function dump($value): string
	{
		if (is_array($value)) {
			$s = "[\n";
			foreach ($value as $k => $v) {
				$v = is_array($v) && (!$v || array_keys($v) === range(0, count($v) - 1))
					? '[' . implode(', ', array_map(function ($s): string { return var_export($s, true); }, $v)) . ']'
					: var_export($v, true);
				$s .= "\t\t" . var_export($k, true) . ' => ' . $v . ",\n";
			}
			return $s . "\t]";
		}
		return var_export($value, true);
	}
}
