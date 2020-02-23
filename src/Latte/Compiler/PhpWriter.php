<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;


/**
 * PHP code generator helpers.
 */
class PhpWriter
{
	use Strict;

	/** @var MacroTokens */
	private $tokens;

	/** @var string */
	private $modifiers;

	/** @var array|null */
	private $context;

	/** @var array */
	private $functions = [];


	public static function using(MacroNode $node, Compiler $compiler = null): self
	{
		$me = new static($node->tokenizer, null, $node->context);
		$me->modifiers = &$node->modifiers;
		$me->functions = $compiler ? $compiler->getFunctions() : [];
		return $me;
	}


	public function __construct(MacroTokens $tokens, string $modifiers = null, array $context = null)
	{
		$this->tokens = $tokens;
		$this->modifiers = $modifiers;
		$this->context = $context;
	}


	/**
	 * Expands %node.word, %node.array, %node.args, %escape(), %modify(), %var, %raw, %word in code.
	 */
	public function write(string $mask, ...$args): string
	{
		$mask = preg_replace('#%(node|\d+)\.#', '%$1_', $mask);
		$mask = preg_replace_callback('#%escape(\(([^()]*+|(?1))+\))#', function ($m) {
			return $this->escapePass(new MacroTokens(substr($m[1], 1, -1)))->joinAll();
		}, $mask);
		$mask = preg_replace_callback('#%modify(Content)?(\(([^()]*+|(?2))+\))#', function ($m) {
			return $this->formatModifiers(substr($m[2], 1, -1), (bool) $m[1]);
		}, $mask);

		$pos = $this->tokens->position;
		$word = null;
		if (strpos($mask, '%node_word') !== false) {
			$word = $this->tokens->fetchWord();
			if ($word === null) {
				throw new CompileException('Invalid content of macro');
			}
		}

		$code = preg_replace_callback('#([,+]\s*)?%(node_|\d+_|)(word|var|raw|array|args)(\?)?(\s*\+\s*)?()#',
		function ($m) use ($word, &$args) {
			[, $l, $source, $format, $cond, $r] = $m;

			switch ($source) {
				case 'node_':
					$arg = $word; break;
				case '':
					$arg = current($args); next($args); break;
				default:
					$arg = $args[(int) $source]; break;
			}

			switch ($format) {
				case 'word':
					$code = $this->formatWord($arg); break;
				case 'args':
					$code = $this->formatArgs(); break;
				case 'array':
					$code = $this->formatArray();
					$code = $cond && $code === '[]' ? '' : $code; break;
				case 'var':
					$code = var_export($arg, true); break;
				case 'raw':
					$code = (string) $arg; break;
			}

			if ($cond && $code === '') {
				return $r ? $l : $r;
			} else {
				return $l . $code . $r;
			}
		}, $mask);

		$this->tokens->position = $pos;
		return $code;
	}


	/**
	 * Formats modifiers calling.
	 */
	public function formatModifiers(string $var, bool $isContent = false): string
	{
		$tokens = new MacroTokens(ltrim($this->modifiers, '|'));
		$tokens = $this->preprocess($tokens);
		$tokens = $this->modifierPass($tokens, $var, $isContent);
		$tokens = $this->quotingPass($tokens);
		return $tokens->joinAll();
	}


	/**
	 * Formats macro arguments to PHP code. (It advances tokenizer to the end as a side effect.)
	 */
	public function formatArgs(MacroTokens $tokens = null): string
	{
		$tokens = $this->preprocess($tokens);
		$tokens = $this->quotingPass($tokens);
		return $tokens->joinAll();
	}


	/**
	 * Formats macro arguments to PHP array. (It advances tokenizer to the end as a side effect.)
	 */
	public function formatArray(MacroTokens $tokens = null): string
	{
		$tokens = $this->preprocess($tokens);
		$tokens = $this->expandCastPass($tokens);
		$tokens = $this->quotingPass($tokens);
		return $tokens->joinAll();
	}


	/**
	 * Formats parameter to PHP string.
	 */
	public function formatWord(string $s): string
	{
		return (is_numeric($s) || preg_match('#^\$|[\'"]|^(true|TRUE)$|^(false|FALSE)$|^(null|NULL)$|^[\w\\\\]{3,}::[A-Z0-9_]{2,}$#D', $s))
			? $this->formatArgs(new MacroTokens($s))
			: '"' . $s . '"';
	}


	/**
	 * Preprocessor for tokens. (It advances tokenizer to the end as a side effect.)
	 */
	public function preprocess(MacroTokens $tokens = null): MacroTokens
	{
		$tokens = $tokens === null ? $this->tokens : $tokens;
		$this->validateTokens($tokens);
		$tokens = $this->removeCommentsPass($tokens);
		$tokens = $this->replaceFunctionsPass($tokens);
		$tokens = $this->optionalChainingPass($tokens);
		$tokens = $this->shortTernaryPass($tokens);
		$tokens = $this->inlineModifierPass($tokens);
		$tokens = $this->inOperatorPass($tokens);
		return $tokens;
	}


	/** @throws CompileException */
	public function validateTokens(MacroTokens $tokens): void
	{
		$brackets = [];
		$pos = $tokens->position;
		while ($tokens->nextToken()) {
			if ($tokens->isCurrent('?>')) {
				throw new CompileException('Forbidden ?> inside macro');

			} elseif ($tokens->isCurrent('(', '[', '{')) {
				static $counterpart = ['(' => ')', '[' => ']', '{' => '}'];
				$brackets[] = $counterpart[$tokens->currentValue()];

			} elseif ($tokens->isCurrent(')', ']', '}') && $tokens->currentValue() !== array_pop($brackets)) {
				throw new CompileException('Unexpected ' . $tokens->currentValue());

			} elseif (
				$tokens->isCurrent('function', 'class', 'interface', 'trait')
				&& $tokens->isNext($tokens::T_SYMBOL, '&')
				|| $tokens->isCurrent('return', 'yield')
				&& !$brackets
			) {
				throw new CompileException("Forbidden keyword '{$tokens->currentValue()}' inside macro.");
			}
		}
		if ($brackets) {
			throw new CompileException('Missing ' . array_pop($brackets));
		}
		$tokens->position = $pos;
	}


	/**
	 * Removes PHP comments.
	 */
	public function removeCommentsPass(MacroTokens $tokens): MacroTokens
	{
		$res = new MacroTokens;
		while ($tokens->nextToken()) {
			if (!$tokens->isCurrent($tokens::T_COMMENT)) {
				$res->append($tokens->currentToken());
			}
		}
		return $res;
	}


	/**
	 * Replace global functions with custom ones.
	 */
	public function replaceFunctionsPass(MacroTokens $tokens): MacroTokens
	{
		$res = new MacroTokens;
		while ($tokens->nextToken()) {
			$name = $tokens->currentValue();
			if (
				$tokens->isCurrent($tokens::T_SYMBOL)
				&& ($orig = $this->functions[strtolower($name)] ?? null)
				&& $tokens->isNext('(')
				&& !$tokens->isPrev('::', '->', '\\')
			) {
				if ($name !== $orig) {
					trigger_error("Case mismatch on function name '$name', correct name is '$orig'.", E_USER_WARNING);
				}
				$res->append('($this->global->fn->' . $orig . ')');
			} else {
				$res->append($tokens->currentToken());
			}
		}
		return $res;
	}


	/**
	 * Simplified ternary expressions without third part.
	 */
	public function shortTernaryPass(MacroTokens $tokens): MacroTokens
	{
		$res = new MacroTokens;
		$inTernary = $tmp = [];
		$errors = 0;
		while ($tokens->nextToken()) {
			if ($tokens->isCurrent('?') && $tokens->isNext() && !$tokens->isNext(',', ')', ']', '|')) {
				$inTernary[] = $tokens->depth;
				$tmp[] = $tokens->isNext('[');

			} elseif ($tokens->isCurrent(':')) {
				array_pop($inTernary);
				array_pop($tmp);

			} elseif ($tokens->isCurrent(',', ')', ']', '|') && end($inTernary) === $tokens->depth + $tokens->isCurrent(')', ']')) {
				$res->append(' : null');
				array_pop($inTernary);
				$errors += array_pop($tmp);
			}
			$res->append($tokens->currentToken());
		}

		if ($inTernary) {
			$errors += array_pop($tmp);
			$res->append(' : null');
		}
		if ($errors) {
			$tokens->reset();
			trigger_error('Short ternary operator requires parentheses around array in ' . $tokens->joinAll(), E_USER_DEPRECATED);
		}
		return $res;
	}


	/**
	 * Optional Chaining $var?->prop?->elem[1]?->call()?->item
	 */
	public function optionalChainingPass(MacroTokens $tokens): MacroTokens
	{
		$startDepth = $tokens->depth;
		$res = new MacroTokens;

		while ($tokens->depth >= $startDepth && $tokens->nextToken()) {
			if (!$tokens->isCurrent($tokens::T_VARIABLE) || $tokens->isPrev('::', '$')) {
				$res->append($tokens->currentToken());
				continue;
			}

			$addBraces = '';
			$expr = new MacroTokens([$tokens->currentToken()]);
			$rescue = null;

			do {
				if ($tokens->nextToken('?')) {
					if ($tokens->isNext() && (!$tokens->isNext($tokens::T_CHAR) || $tokens->isNext('(', '[', '{', ':', '!', '@', '\\'))) {  // is it ternary operator?
						$expr->append($addBraces . ' ?');
						break;
					}

					$rescue = [$res->tokens, $expr->tokens, $tokens->position, $addBraces];

					if (!$tokens->isNext('->', '::')) {
						$expr->prepend('(');
						$expr->append(' ?? null)' . $addBraces);
						break;
					}

					$expr->prepend('(($_tmp = ');
					$expr->append(' ?? null) === null ? null : ');
					$res->tokens = array_merge($res->tokens, $expr->tokens);
					$expr = new MacroTokens('$_tmp');
					$addBraces .= ')';

				} elseif ($tokens->nextToken('->', '::')) {
					$expr->append($tokens->currentToken());
					if (!$tokens->nextToken($tokens::T_SYMBOL, $tokens::T_VARIABLE)) {
						$expr->append($addBraces);
						break;
					}
					$expr->append($tokens->currentToken());

				} elseif ($tokens->nextToken('[', '(')) {
					$expr->tokens = array_merge($expr->tokens, [$tokens->currentToken()], $this->optionalChainingPass($tokens)->tokens);
					if ($rescue && $tokens->isNext(':')) { // it was ternary operator
						[$res->tokens, $expr->tokens, $tokens->position, $addBraces] = $rescue;
						$expr->append($addBraces . ' ?');
						break;
					}

				} else {
					$expr->append($addBraces);
					break;
				}
			} while (true);

			$res->tokens = array_merge($res->tokens, $expr->tokens);
		}

		return $res;
	}


	/**
	 * Pseudocast (expand).
	 */
	public function expandCastPass(MacroTokens $tokens): MacroTokens
	{
		$res = new MacroTokens('[');
		$expand = null;
		while ($tokens->nextToken()) {
			if ($tokens->isCurrent('(expand)') && $tokens->depth === 0) {
				$expand = true;
				$res->append('],');
			} elseif ($expand && $tokens->isCurrent(',') && !$tokens->depth) {
				$expand = false;
				$res->append(', [');
			} else {
				$res->append($tokens->currentToken());
			}
		}

		if ($expand === null) {
			$res->append(']');
		} else {
			$res->prepend('array_merge(')->append($expand ? ', [])' : '])');
		}
		return $res;
	}


	/**
	 * Quotes symbols to strings.
	 */
	public function quotingPass(MacroTokens $tokens): MacroTokens
	{
		$res = new MacroTokens;
		while ($tokens->nextToken()) {
			$res->append($tokens->isCurrent($tokens::T_SYMBOL)
				&& (!$tokens->isPrev() || $tokens->isPrev(',', '(', '[', '=>', ':', '?', '.', '<', '>', '<=', '>=', '===', '!==', '==', '!=', '<>', '&&', '||', '=', 'and', 'or', 'xor', '??'))
				&& (!$tokens->isNext() || $tokens->isNext(',', ';', ')', ']', '=>', ':', '?', '.', '<', '>', '<=', '>=', '===', '!==', '==', '!=', '<>', '&&', '||', 'and', 'or', 'xor', '??'))
				&& !preg_match('#^[A-Z_][A-Z0-9_]{2,}$#', $tokens->currentValue())
				? "'" . $tokens->currentValue() . "'"
				: $tokens->currentToken()
			);
		}
		return $res;
	}


	/**
	 * Syntax $entry in [item1, item2].
	 */
	public function inOperatorPass(MacroTokens $tokens): MacroTokens
	{
		while ($tokens->nextToken()) {
			if ($tokens->isCurrent($tokens::T_VARIABLE)) {
				$start = $tokens->position;
				$depth = $tokens->depth;
				$expr = $arr = [];

				$expr[] = $tokens->currentToken();
				while ($tokens->isNext($tokens::T_VARIABLE, $tokens::T_SYMBOL, $tokens::T_NUMBER, $tokens::T_STRING, '[', ']', '(', ')', '->')
					&& !$tokens->isNext('in')) {
					$expr[] = $tokens->nextToken();
				}

				if ($depth === $tokens->depth && $tokens->nextValue('in') && ($arr[] = $tokens->nextToken('['))) {
					while ($tokens->isNext()) {
						$arr[] = $tokens->nextToken();
						if ($tokens->isCurrent(']') && $tokens->depth === $depth) {
							$new = array_merge($tokens->parse('in_array('), $expr, $tokens->parse(', '), $arr, $tokens->parse(', true)'));
							array_splice($tokens->tokens, $start, $tokens->position - $start + 1, $new);
							$tokens->position = $start + count($new) - 1;
							continue 2;
						}
					}
				}
				$tokens->position = $start;
			}
		}
		return $tokens->reset();
	}


	/**
	 * Process inline filters ($var|filter)
	 */
	public function inlineModifierPass(MacroTokens $tokens): MacroTokens
	{
		$result = new MacroTokens;
		while ($tokens->nextToken()) {
			if ($tokens->isCurrent('(', '[')) {
				$result->tokens = array_merge($result->tokens, $this->inlineModifierInner($tokens));
			} else {
				$result->append($tokens->currentToken());
			}
		}
		return $result;
	}


	private function inlineModifierInner(MacroTokens $tokens): array
	{
		$isFunctionOrArray = $tokens->isPrev($tokens::T_VARIABLE, $tokens::T_SYMBOL) || $tokens->isCurrent('[');
		$result = new MacroTokens;
		$args = new MacroTokens;
		$modifiers = new MacroTokens;
		$current = $args;
		$anyModifier = false;
		$result->append($tokens->currentToken());

		while ($tokens->nextToken()) {
			if ($tokens->isCurrent('(', '[')) {
				$current->tokens = array_merge($current->tokens, $this->inlineModifierInner($tokens));

			} elseif ($current !== $modifiers && $tokens->isCurrent('|')) {
				$anyModifier = true;
				$current = $modifiers;

			} elseif ($tokens->isCurrent(')', ']') || ($isFunctionOrArray && $tokens->isCurrent(','))) {
				$partTokens = count($modifiers->tokens)
					? $this->modifierPass($modifiers, $args->tokens)->tokens
					: $args->tokens;
				$result->tokens = array_merge($result->tokens, $partTokens);
				if ($tokens->isCurrent(',')) {
					$result->append($tokens->currentToken());
					$args = new MacroTokens;
					$modifiers = new MacroTokens;
					$current = $args;
					continue;
				} elseif ($isFunctionOrArray || !$anyModifier) {
					$result->append($tokens->currentToken());
				} else {
					array_shift($result->tokens);
				}
				return $result->tokens;

			} else {
				$current->append($tokens->currentToken());
			}
		}
		throw new CompileException('Unbalanced brackets.');
	}


	/**
	 * Formats modifiers calling.
	 * @param  string|array  $var
	 * @throws CompileException
	 */
	public function modifierPass(MacroTokens $tokens, $var, bool $isContent = false): MacroTokens
	{
		$inside = false;
		$res = new MacroTokens($var);
		while ($tokens->nextToken()) {
			if ($tokens->isCurrent($tokens::T_WHITESPACE)) {
				$res->append(' ');

			} elseif ($inside) {
				if ($tokens->isCurrent(':', ',') && !$tokens->depth) {
					$res->append(', ');
					$tokens->nextAll($tokens::T_WHITESPACE);

				} elseif ($tokens->isCurrent('|') && !$tokens->depth) {
					$res->append(')');
					$inside = false;

				} else {
					$res->append($tokens->currentToken());
				}
			} elseif ($tokens->isCurrent($tokens::T_SYMBOL)) {
				if ($tokens->isCurrent('escape')) {
					if ($isContent) {
						$res->prepend('LR\Filters::convertTo($_fi, ' . var_export(implode($this->context), true) . ', ')
							->append(')');
					} else {
						$res = $this->escapePass($res);
					}
					$tokens->nextToken('|');
				} elseif (!strcasecmp($tokens->currentValue(), 'checkurl')) {
					$res->prepend('LR\Filters::safeUrl(');
					$inside = true;
				} else {
					$name = strtolower($tokens->currentValue());
					$res->prepend($isContent
						? '$this->filters->filterContent(' . var_export($name, true) . ', $_fi, '
						: '($this->filters->' . $name . ')('
					);
					$inside = true;
				}
			} else {
				throw new CompileException("Modifier name must be alphanumeric string, '{$tokens->currentValue()}' given.");
			}
		}
		if ($inside) {
			$res->append(')');
		}
		return $res;
	}


	/**
	 * Escapes expression in tokens.
	 */
	public function escapePass(MacroTokens $tokens): MacroTokens
	{
		$tokens = clone $tokens;
		[$contentType, $context] = $this->context;
		switch ($contentType) {
			case Compiler::CONTENT_XHTML:
			case Compiler::CONTENT_HTML:
				switch ($context) {
					case Compiler::CONTEXT_HTML_TEXT:
						return $tokens->prepend('LR\Filters::escapeHtmlText(')->append(')');
					case Compiler::CONTEXT_HTML_TAG:
					case Compiler::CONTEXT_HTML_ATTRIBUTE_UNQUOTED_URL:
						return $tokens->prepend('LR\Filters::escapeHtmlAttrUnquoted(')->append(')');
					case Compiler::CONTEXT_HTML_ATTRIBUTE:
					case Compiler::CONTEXT_HTML_ATTRIBUTE_URL:
						return $tokens->prepend('LR\Filters::escapeHtmlAttr(')->append(')');
					case Compiler::CONTEXT_HTML_ATTRIBUTE_JS:
						return $tokens->prepend('LR\Filters::escapeHtmlAttr(LR\Filters::escapeJs(')->append('))');
					case Compiler::CONTEXT_HTML_ATTRIBUTE_CSS:
						return $tokens->prepend('LR\Filters::escapeHtmlAttr(LR\Filters::escapeCss(')->append('))');
					case Compiler::CONTEXT_HTML_COMMENT:
						return $tokens->prepend('LR\Filters::escapeHtmlComment(')->append(')');
					case Compiler::CONTEXT_HTML_BOGUS_COMMENT:
						return $tokens->prepend('LR\Filters::escapeHtml(')->append(')');
					case Compiler::CONTEXT_HTML_JS:
					case Compiler::CONTEXT_HTML_CSS:
						return $tokens->prepend('LR\Filters::escape' . ucfirst($context) . '(')->append(')');
					default:
						throw new CompileException("Unknown context $contentType, $context.");
				}
				// break omitted
			case Compiler::CONTENT_XML:
				switch ($context) {
					case Compiler::CONTEXT_XML_TEXT:
					case Compiler::CONTEXT_XML_ATTRIBUTE:
					case Compiler::CONTEXT_XML_BOGUS_COMMENT:
						return $tokens->prepend('LR\Filters::escapeXml(')->append(')');
					case Compiler::CONTEXT_XML_COMMENT:
						return $tokens->prepend('LR\Filters::escapeHtmlComment(')->append(')');
					case Compiler::CONTEXT_XML_TAG:
						return $tokens->prepend('LR\Filters::escapeXmlAttrUnquoted(')->append(')');
					default:
						throw new CompileException("Unknown context $contentType, $context.");
				}
				// break omitted
			case Compiler::CONTENT_JS:
			case Compiler::CONTENT_CSS:
			case Compiler::CONTENT_ICAL:
				return $tokens->prepend('LR\Filters::escape' . ucfirst($contentType) . '(')->append(')');
			case Compiler::CONTENT_TEXT:
				return $tokens;
			case null:
				return $tokens->prepend('($this->filters->escape)(')->append(')');
			default:
				throw new CompileException("Unknown context $contentType.");
		}
	}
}
