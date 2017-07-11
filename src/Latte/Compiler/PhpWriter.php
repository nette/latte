<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

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


	public static function using(MacroNode $node)
	{
		$me = new static($node->tokenizer, null, $node->context);
		$me->modifiers = &$node->modifiers;
		return $me;
	}


	public function __construct(MacroTokens $tokens, $modifiers = null, array $context = null)
	{
		$this->tokens = $tokens;
		$this->modifiers = $modifiers;
		$this->context = $context;
	}


	/**
	 * Expands %node.word, %node.array, %node.args, %escape(), %modify(), %var, %raw, %word in code.
	 * @param  string
	 * @return string
	 */
	public function write($mask)
	{
		$mask = preg_replace('#%(node|\d+)\.#', '%$1_', $mask);
		$mask = preg_replace_callback('#%escape(\(([^()]*+|(?1))+\))#', function ($m) {
			return $this->escapePass(new MacroTokens(substr($m[1], 1, -1)))->joinAll();
		}, $mask);
		$mask = preg_replace_callback('#%modify(Content)?(\(([^()]*+|(?2))+\))#', function ($m) {
			return $this->formatModifiers(substr($m[2], 1, -1), (bool) $m[1]);
		}, $mask);

		$args = func_get_args();
		$pos = $this->tokens->position;
		$word = strpos($mask, '%node_word') === false ? null : $this->tokens->fetchWord();

		$code = preg_replace_callback('#([,+]\s*)?%(node_|\d+_|)(word|var|raw|array|args)(\?)?(\s*\+\s*)?()#',
		function ($m) use ($word, &$args) {
			list(, $l, $source, $format, $cond, $r) = $m;

			switch ($source) {
				case 'node_':
					$arg = $word; break;
				case '':
					$arg = next($args); break;
				default:
					$arg = $args[(int) $source + 1]; break;
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
	 * @param  string
	 * @return string
	 */
	public function formatModifiers($var, $isContent = false)
	{
		$tokens = new MacroTokens(ltrim($this->modifiers, '|'));
		$tokens = $this->preprocess($tokens);
		$tokens = $this->modifierPass($tokens, $var, $isContent);
		$tokens = $this->quotingPass($tokens);
		return $tokens->joinAll();
	}


	/**
	 * Formats macro arguments to PHP code. (It advances tokenizer to the end as a side effect.)
	 * @return string
	 */
	public function formatArgs(MacroTokens $tokens = null)
	{
		$tokens = $this->preprocess($tokens);
		$tokens = $this->quotingPass($tokens);
		return $tokens->joinAll();
	}


	/**
	 * Formats macro arguments to PHP array. (It advances tokenizer to the end as a side effect.)
	 * @return string
	 */
	public function formatArray(MacroTokens $tokens = null)
	{
		$tokens = $this->preprocess($tokens);
		$tokens = $this->expandCastPass($tokens);
		$tokens = $this->quotingPass($tokens);
		return $tokens->joinAll();
	}


	/**
	 * Formats parameter to PHP string.
	 * @param  string
	 * @return string
	 */
	public function formatWord($s)
	{
		return (is_numeric($s) || preg_match('#^\$|[\'"]|^(true|TRUE)\z|^(false|FALSE)\z|^(null|NULL)\z|^[\w\\\\]{3,}::[A-Z0-9_]{2,}\z#', $s))
			? $this->formatArgs(new MacroTokens($s))
			: '"' . $s . '"';
	}


	/**
	 * Preprocessor for tokens. (It advances tokenizer to the end as a side effect.)
	 * @return MacroTokens
	 */
	public function preprocess(MacroTokens $tokens = null)
	{
		$tokens = $tokens === null ? $this->tokens : $tokens;
		$this->validateTokens($tokens);
		$tokens = $this->removeCommentsPass($tokens);
		$tokens = $this->shortTernaryPass($tokens);
		$tokens = $this->inlineModifierPass($tokens);
		$tokens = $this->inOperatorPass($tokens);
		return $tokens;
	}


	/**
	 * @throws CompileException
	 * @return void
	 */
	public function validateTokens(MacroTokens $tokens)
	{
		$deprecatedVars = array_flip(['$template', '$_b', '$_l', '$_g', '$_args', '$_fi', '$_control', '$_presenter', '$_form', '$_input', '$_label', '$_snippetMode']);
		$brackets = [];
		$pos = $tokens->position;
		while ($tokens->nextToken()) {
			if ($tokens->isCurrent('?>')) {
				throw new CompileException('Forbidden ?> inside macro');

			} elseif ($tokens->isCurrent($tokens::T_VARIABLE) && isset($deprecatedVars[$tokens->currentValue()])) {
				trigger_error("Variable {$tokens->currentValue()} is deprecated.", E_USER_DEPRECATED);

			} elseif ($tokens->isCurrent($tokens::T_SYMBOL)
				&& !$tokens->isPrev('::') && !$tokens->isNext('::') && !$tokens->isPrev('->') && !$tokens->isNext('\\')
				&& preg_match('#^[A-Z0-9]{3,}$#', $val = $tokens->currentValue())
			) {
				trigger_error("Replace literal $val with constant('$val')", E_USER_DEPRECATED);

			} elseif ($tokens->isCurrent('(', '[', '{')) {
				static $counterpart = ['(' => ')', '[' => ']', '{' => '}'];
				$brackets[] = $counterpart[$tokens->currentValue()];

			} elseif ($tokens->isCurrent(')', ']', '}') && $tokens->currentValue() !== array_pop($brackets)) {
				throw new CompileException('Unexpected ' . $tokens->currentValue());

			} elseif ($tokens->isCurrent('function', 'class', 'interface', 'trait') && $tokens->isNext($tokens::T_SYMBOL, '&')
				|| $tokens->isCurrent('return', 'yield') && !$brackets
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
	 * @return MacroTokens
	 */
	public function removeCommentsPass(MacroTokens $tokens)
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
	 * Simplified ternary expressions without third part.
	 * @return MacroTokens
	 */
	public function shortTernaryPass(MacroTokens $tokens)
	{
		$res = new MacroTokens;
		$inTernary = [];
		while ($tokens->nextToken()) {
			if ($tokens->isCurrent('?')) {
				$inTernary[] = $tokens->depth;

			} elseif ($tokens->isCurrent(':')) {
				array_pop($inTernary);

			} elseif ($tokens->isCurrent(',', ')', ']', '|') && end($inTernary) === $tokens->depth + $tokens->isCurrent(')', ']')) {
				$res->append(' : NULL');
				array_pop($inTernary);
			}
			$res->append($tokens->currentToken());
		}

		if ($inTernary) {
			$res->append(' : NULL');
		}
		return $res;
	}


	/**
	 * Pseudocast (expand).
	 * @return MacroTokens
	 */
	public function expandCastPass(MacroTokens $tokens)
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
	 * @return MacroTokens
	 */
	public function quotingPass(MacroTokens $tokens)
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
	 * @return MacroTokens
	 */
	public function inOperatorPass(MacroTokens $tokens)
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
							$new = array_merge($tokens->parse('in_array('), $expr, $tokens->parse(', '), $arr, $tokens->parse(', TRUE)'));
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
	 * @return MacroTokens
	 */
	public function inlineModifierPass(MacroTokens $tokens)
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


	private function inlineModifierInner(MacroTokens $tokens)
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
	 * @param  MacroTokens
	 * @param  string|array
	 * @throws CompileException
	 * @return MacroTokens
	 */
	public function modifierPass(MacroTokens $tokens, $var, $isContent = false)
	{
		$inside = false;
		$res = new MacroTokens($var);
		while ($tokens->nextToken()) {
			if ($tokens->isCurrent($tokens::T_WHITESPACE)) {
				$res->append(' ');

			} elseif ($inside) {
				if ($tokens->isCurrent(':', ',')) {
					$res->append(', ');
					$tokens->nextAll($tokens::T_WHITESPACE);

				} elseif ($tokens->isCurrent('|')) {
					$res->append(')');
					$inside = false;

				} else {
					$res->append($tokens->currentToken());
				}
			} else {
				if ($tokens->isCurrent($tokens::T_SYMBOL)) {
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
							: 'call_user_func($this->filters->' . $name . ', '
						);
						$inside = true;
					}
				} else {
					throw new CompileException("Modifier name must be alphanumeric string, '{$tokens->currentValue()}' given.");
				}
			}
		}
		if ($inside) {
			$res->append(')');
		}
		return $res;
	}


	/**
	 * Escapes expression in tokens.
	 * @return MacroTokens
	 */
	public function escapePass(MacroTokens $tokens)
	{
		$tokens = clone $tokens;
		list($contentType, $context) = $this->context;
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
				return $tokens->prepend('call_user_func($this->filters->escape, ')->append(')');
			default:
				throw new CompileException("Unknown context $contentType.");
		}
	}
}
