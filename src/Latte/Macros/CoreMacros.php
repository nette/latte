<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Macros;

use Latte;
use Latte\CompileException;
use Latte\MacroNode;
use Latte\PhpWriter;


/**
 * Basic macros for Latte.
 *
 * - {if ?} ... {elseif ?} ... {else} ... {/if}
 * - {ifset ?} ... {elseifset ?} ... {/ifset}
 * - {for ?} ... {/for}
 * - {foreach ?} ... {/foreach}
 * - {$variable} with escaping
 * - {=expression} echo with escaping
 * - {?expression} evaluate PHP statement
 * - {_expression} echo translation with escaping
 * - {attr ?} HTML element attributes
 * - {capture ?} ... {/capture} capture block to parameter
 * - {var var => value} set template parameter
 * - {default var => value} set default template parameter
 * - {dump $var}
 * - {debugbreak}
 * - {contentType ...} HTTP Content-Type header
 * - {status ...} HTTP status
 * - {l} {r} to display { }
 */
class CoreMacros extends MacroSet
{


	public static function install(Latte\Compiler $compiler)
	{
		$me = new static($compiler);

		$me->addMacro('if', [$me, 'macroIf'], [$me, 'macroEndIf']);
		$me->addMacro('elseif', '} elseif (%node.args) {');
		$me->addMacro('else', [$me, 'macroElse']);
		$me->addMacro('ifset', 'if (isset(%node.args)) {', '}');
		$me->addMacro('elseifset', '} elseif (isset(%node.args)) {');
		$me->addMacro('ifcontent', [$me, 'macroIfContent'], [$me, 'macroEndIfContent']);

		$me->addMacro('switch', '$this->global->switch[] = (%node.args); if (FALSE) {', '} array_pop($this->global->switch)');
		$me->addMacro('case', '} elseif (end($this->global->switch) === (%node.args)) {');

		$me->addMacro('foreach', '', [$me, 'macroEndForeach']);
		$me->addMacro('for', 'for (%node.args) {', '}');
		$me->addMacro('while', 'while (%node.args) {', '}');
		$me->addMacro('continueIf', [$me, 'macroBreakContinueIf']);
		$me->addMacro('breakIf', [$me, 'macroBreakContinueIf']);
		$me->addMacro('first', 'if ($iterator->isFirst(%node.args)) {', '}');
		$me->addMacro('last', 'if ($iterator->isLast(%node.args)) {', '}');
		$me->addMacro('sep', 'if (!$iterator->isLast(%node.args)) {', '}');

		$me->addMacro('var', [$me, 'macroVar']);
		$me->addMacro('default', [$me, 'macroVar']);
		$me->addMacro('dump', [$me, 'macroDump']);
		$me->addMacro('debugbreak', [$me, 'macroDebugbreak']);
		$me->addMacro('l', '?>{<?php');
		$me->addMacro('r', '?>}<?php');

		$me->addMacro('_', [$me, 'macroTranslate'], [$me, 'macroTranslate']);
		$me->addMacro('=', [$me, 'macroExpr']);
		$me->addMacro('?', [$me, 'macroExpr']);

		$me->addMacro('capture', [$me, 'macroCapture'], [$me, 'macroCaptureEnd']);
		$me->addMacro('include', [$me, 'macroInclude']);
		$me->addMacro('use', [$me, 'macroUse']);
		$me->addMacro('contentType', [$me, 'macroContentType']);
		$me->addMacro('status', [$me, 'macroStatus']);
		$me->addMacro('php', [$me, 'macroExpr']);

		$me->addMacro('class', NULL, NULL, [$me, 'macroClass']);
		$me->addMacro('attr', NULL, NULL, [$me, 'macroAttr']);
	}


	/**
	 * Finishes template parsing.
	 * @return array(prolog, epilog)
	 */
	public function finalize()
	{
		return ['if ($this->initialize($_args)) return; extract($_args);'];
	}


	/********************* macros ****************d*g**/


	/**
	 * {if ...}
	 */
	public function macroIf(MacroNode $node, PhpWriter $writer)
	{
		if ($node->modifiers) {
			throw new CompileException("Modifiers are not allowed in {{$node->name}}");
		}
		if ($node->data->capture = ($node->args === '')) {
			return 'ob_start(function () {})';
		}
		if ($node->prefix === $node::PREFIX_TAG) {
			return $writer->write($node->htmlNode->closing ? 'if (array_pop($this->global->ifs)) {' : 'if ($this->global->ifs[] = (%node.args)) {');
		}
		return $writer->write('if (%node.args) {');
	}


	/**
	 * {/if ...}
	 */
	public function macroEndIf(MacroNode $node, PhpWriter $writer)
	{
		if ($node->data->capture) {
			if ($node->args === '') {
				throw new CompileException('Missing condition in {if} macro.');
			}
			return $writer->write('if (%node.args) '
				. (isset($node->data->else) ? '{ ob_end_clean(); echo ob_get_clean(); }' : 'echo ob_get_clean();')
				. ' else '
				. (isset($node->data->else) ? '{ $this->global->else = ob_get_clean(); ob_end_clean(); echo $this->global->else; }' : 'ob_end_clean();')
			);
		}
		return '}';
	}


	/**
	 * {else}
	 */
	public function macroElse(MacroNode $node, PhpWriter $writer)
	{
		if ($node->modifiers) {
			throw new CompileException("Modifiers are not allowed in {{$node->name}}");
		} elseif ($node->args) {
			$hint = substr($node->args, 0, 2) === 'if' ? ', did you mean {elseif}?' : '';
			throw new CompileException("Arguments are not allowed in {{$node->name}}$hint");
		}
		$ifNode = $node->parentNode;
		if ($ifNode && $ifNode->name === 'if' && $ifNode->data->capture) {
			if (isset($ifNode->data->else)) {
				throw new CompileException('Macro {if} supports only one {else}.');
			}
			$ifNode->data->else = TRUE;
			return 'ob_start(function () {})';
		}
		return '} else {';
	}


	/**
	 * n:ifcontent
	 */
	public function macroIfContent(MacroNode $node, PhpWriter $writer)
	{
		if (!$node->prefix) {
			throw new CompileException("Unknown macro {{$node->name}}, use n:{$node->name} attribute.");
		} elseif ($node->prefix !== MacroNode::PREFIX_NONE) {
			throw new CompileException("Unknown attribute n:{$node->prefix}-{$node->name}, use n:{$node->name} attribute.");
		}
	}


	/**
	 * n:ifcontent
	 */
	public function macroEndIfContent(MacroNode $node, PhpWriter $writer)
	{
		$node->openingCode = '<?php ob_start(function () {}) ?>';
		$node->innerContent = '<?php ob_start() ?>' . $node->innerContent . '<?php $this->global->ifcontent = ob_get_flush() ?>';
		$node->closingCode = '<?php if (rtrim($this->global->ifcontent) === "") ob_end_clean(); else echo ob_get_clean() ?>';
	}


	/**
	 * {_$var |modifiers}
	 */
	public function macroTranslate(MacroNode $node, PhpWriter $writer)
	{
		if ($node->closing) {
			if (substr($node->modifiers, -7) === '|escape') {
				$node->modifiers = substr($node->modifiers, 0, -7);
			}
			return $writer->write('$_fi = new LR\FilterInfo(%var); echo %modifyContent($this->filters->filterContent("translate", $_fi, ob_get_clean()))', $node->context[0]);

		} elseif ($node->empty = ($node->args !== '')) {
			return $writer->write('echo %modify(call_user_func($this->filters->translate, %node.args))');

		} else {
			return 'ob_start(function () {})';
		}
	}


	/**
	 * {include "file" [,] [params]}
	 */
	public function macroInclude(MacroNode $node, PhpWriter $writer)
	{
		$node->modifiers = preg_replace('#\|nocheck\s?(?=\||\z)#i', '', $node->modifiers, -1, $noCheck);
		$code = $writer->write(
			'$this->createTemplate(%node.word, %node.array? + $this->params, "include")->renderToContentType(%var)',
			$noCheck ? NULL : implode('', $node->context)
		);
		if ($node->modifiers) {
			return $writer->write('ob_start(function () {}); %raw; $_fi = new LR\FilterInfo(%var); echo %modifyContent(ob_get_clean())', $code, $node->context[0]);
		} else {
			return $code;
		}
	}


	/**
	 * {use class MacroSet}
	 */
	public function macroUse(MacroNode $node, PhpWriter $writer)
	{
		if ($node->modifiers) {
			throw new CompileException("Modifiers are not allowed in {{$node->name}}");
		}
		call_user_func(Latte\Helpers::checkCallback([$node->tokenizer->fetchWord(), 'install']), $this->getCompiler())
			->initialize();
	}


	/**
	 * {capture $variable}
	 */
	public function macroCapture(MacroNode $node, PhpWriter $writer)
	{
		$variable = $node->tokenizer->fetchWord();
		if (substr($variable, 0, 1) !== '$') {
			throw new CompileException("Invalid capture block variable '$variable'");
		}
		$node->data->variable = $variable;
		return 'ob_start(function () {})';
	}


	/**
	 * {/capture}
	 */
	public function macroCaptureEnd(MacroNode $node, PhpWriter $writer)
	{
		$body = $node->context[0] === Latte\Engine::CONTENT_HTML
			? "ob_get_length() ? new LR\\Html(ob_get_clean()) : ob_get_clean()"
			: 'ob_get_clean()';
		return $writer->write("\$_fi = new LR\\FilterInfo(%var); %raw = %modifyContent($body)", $node->context[0], $node->data->variable);
	}


	/**
	 * {foreach ...}
	 */
	public function macroEndForeach(MacroNode $node, PhpWriter $writer)
	{
		$node->modifiers = preg_replace('#\|nocheck\s?(?=\||\z)#i', '', $node->modifiers, -1, $noCheck);
		if ($node->modifiers && $node->modifiers !== '|noiterator') {
			throw new CompileException('Only modifiers |noiterator and |nocheck are allowed here.');
		}
		$node->openingCode = '<?php $iterations = 0; ';
		$args = $writer->formatArgs();
		preg_match('#.+\s+as\s*\$(\w+)(?:\s*=>\s*\$(\w+))?#i', $args, $m);
		if (!$noCheck) {
			for ($i = 1; $i < count($m); $i++) {
				$s = var_export($m[$i], TRUE);
				$node->openingCode .= "if (isset(\$this->params[$s])) trigger_error('Variable \${$m[$i]} overwritten in foreach.'); ";
			}
		}
		if ($node->modifiers !== '|noiterator' && preg_match('#\W(\$iterator|include|require|get_defined_vars)\W#', $this->getCompiler()->expandTokens($node->content))) {
			$node->openingCode .= 'foreach ($iterator = $this->global->its[] = new LR\CachingIterator('
				. preg_replace('#(.*)\s+as\s+#i', '$1) as ', $args, 1) . ') { ?>';
			$node->closingCode = '<?php $iterations++; } array_pop($this->global->its); $iterator = end($this->global->its) ?>';
		} else {
			$node->openingCode .= 'foreach (' . $args . ') { ?>';
			$node->closingCode = '<?php $iterations++; } ?>';
		}
	}


	/**
	 * {breakIf ...}
	 * {continueIf ...}
	 */
	public function macroBreakContinueIf(MacroNode $node, PhpWriter $writer)
	{
		if ($node->modifiers) {
			throw new CompileException("Modifiers are not allowed in {{$node->name}}");
		}
		$cmd = str_replace('If', '', $node->name);
		if ($node->parentNode && $node->parentNode->prefix === $node::PREFIX_NONE) {
			return $writer->write("if (%node.args) { echo \"</{$node->parentNode->htmlNode->name}>\\n\"; $cmd; }");
		}
		return $writer->write("if (%node.args) $cmd");
	}


	/**
	 * n:class="..."
	 */
	public function macroClass(MacroNode $node, PhpWriter $writer)
	{
		if (isset($node->htmlNode->attrs['class'])) {
			throw new CompileException('It is not possible to combine class with n:class.');
		}
		return $writer->write('if ($_tmp = array_filter(%node.array)) echo \' class="\', %escape(implode(" ", array_unique($_tmp))), \'"\'');
	}


	/**
	 * n:attr="..."
	 */
	public function macroAttr(MacroNode $node, PhpWriter $writer)
	{
		return $writer->write('echo LR\Filters::htmlAttributes(%node.array)');
	}


	/**
	 * {dump ...}
	 */
	public function macroDump(MacroNode $node, PhpWriter $writer)
	{
		if ($node->modifiers) {
			throw new CompileException("Modifiers are not allowed in {{$node->name}}");
		}
		$args = $writer->formatArgs();
		return $writer->write(
			'Tracy\Debugger::barDump(' . ($args ? "($args)" : 'get_defined_vars()'). ', %var)',
			$args ?: 'variables'
		);
	}


	/**
	 * {debugbreak ...}
	 */
	public function macroDebugbreak(MacroNode $node, PhpWriter $writer)
	{
		if ($node->modifiers) {
			throw new CompileException("Modifiers are not allowed in {{$node->name}}");
		}
		if (function_exists($func = 'debugbreak') || function_exists($func = 'xdebug_break')) {
			return $writer->write($node->args == NULL ? "$func()" : "if (%node.args) $func()");
		}
	}


	/**
	 * {var ...}
	 * {default ...}
	 */
	public function macroVar(MacroNode $node, PhpWriter $writer)
	{
		if ($node->modifiers) {
			throw new CompileException("Modifiers are not allowed in {{$node->name}}");
		}
		if ($node->args === '' && $node->parentNode && $node->parentNode->name === 'switch') {
			return '} else {';
		}

		$var = TRUE;
		$tokens = $writer->preprocess();
		$res = new Latte\MacroTokens;
		while ($tokens->nextToken()) {
			if ($var && $tokens->isCurrent(Latte\MacroTokens::T_SYMBOL, Latte\MacroTokens::T_VARIABLE)) {
				if ($node->name === 'default') {
					$res->append("'" . ltrim($tokens->currentValue(), '$') . "'");
				} else {
					$res->append('$' . ltrim($tokens->currentValue(), '$'));
				}
				$var = NULL;

			} elseif ($tokens->isCurrent('=', '=>') && $tokens->depth === 0) {
				$res->append($node->name === 'default' ? '=>' : '=');
				$var = FALSE;

			} elseif ($tokens->isCurrent(',') && $tokens->depth === 0) {
				if ($var === NULL) {
					$res->append($node->name === 'default' ? '=>NULL' : '=NULL');
				}
				$res->append($node->name === 'default' ? ',' : ';');
				$var = TRUE;

			} elseif ($var === NULL && $node->name === 'default' && !$tokens->isCurrent(Latte\MacroTokens::T_WHITESPACE)) {
				throw new CompileException("Unexpected '{$tokens->currentValue()}' in {default $node->args}");

			} else {
				$res->append($tokens->currentToken());
			}
		}
		if ($var === NULL) {
			$res->append($node->name === 'default' ? '=>NULL' : '=NULL');
		}
		$out = $writer->quotingPass($res)->joinAll();
		return $node->name === 'default' ? "extract([$out], EXTR_SKIP)" : "$out;";
	}


	/**
	 * {= ...}
	 * {? ...}
	 */
	public function macroExpr(MacroNode $node, PhpWriter $writer)
	{
		return $writer->write(($node->name === '=' ? 'echo ' : '') . '%modify(%node.args)');
	}


	/**
	 * {contentType ...}
	 */
	public function macroContentType(MacroNode $node, PhpWriter $writer)
	{
		$compiler = $this->getCompiler();
		if ($node->modifiers) {
			throw new CompileException("Modifiers are not allowed in {{$node->name}}");
		} elseif (strpos($node->args, 'xhtml') !== FALSE) {
			$type = $compiler::CONTENT_XHTML;
		} elseif (strpos($node->args, 'html') !== FALSE) {
			$type = $compiler::CONTENT_HTML;
		} elseif (strpos($node->args, 'xml') !== FALSE) {
			$type = $compiler::CONTENT_XML;
		} elseif (strpos($node->args, 'javascript') !== FALSE) {
			$type = $compiler::CONTENT_JS;
		} elseif (strpos($node->args, 'css') !== FALSE) {
			$type = $compiler::CONTENT_CSS;
		} elseif (strpos($node->args, 'calendar') !== FALSE) {
			$type = $compiler::CONTENT_ICAL;
		} else {
			$type = $compiler::CONTENT_TEXT;
		}
		$compiler->setContentType($type);

		// temporary solution
		if (strpos($node->args, '/')) {
			return $writer->write('header(%var)', "Content-Type: $node->args");
		}
	}


	/**
	 * {status ...}
	 */
	public function macroStatus(MacroNode $node, PhpWriter $writer)
	{
		trigger_error('Macro {status} is deprecated.', E_USER_DEPRECATED);
		if ($node->modifiers) {
			throw new CompileException("Modifiers are not allowed in {{$node->name}}");
		}
		return $writer->write((substr($node->args, -1) === '?' ? 'if (!headers_sent()) ' : '') .
			'header((isset($_SERVER["SERVER_PROTOCOL"]) ? $_SERVER["SERVER_PROTOCOL"] : "HTTP/1.1") . " " . %0.var, TRUE, %0.var)', (int) $node->args
		);
	}

}
