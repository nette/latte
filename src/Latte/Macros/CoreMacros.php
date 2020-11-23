<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Macros;

use Latte;
use Latte\CompileException;
use Latte\Engine;
use Latte\Helpers;
use Latte\MacroNode;
use Latte\PhpHelpers;
use Latte\PhpWriter;


/**
 * Basic macros for Latte.
 */
class CoreMacros extends MacroSet
{
	/** @var array<string, int[]> */
	private $overwrittenVars;

	/** @var string|null */
	private $printTemplate;

	/** @var int */
	private $idCounter = 0;


	public static function install(Latte\Compiler $compiler): void
	{
		$me = new static($compiler);

		$me->addMacro('if', [$me, 'macroIf'], [$me, 'macroEndIf']);
		$me->addMacro('else', [$me, 'macroElse']);
		$me->addMacro('elseif', [$me, 'macroElseIf']);
		$me->addMacro('ifset', 'if (isset(%node.args)) {', '}');
		$me->addMacro('elseifset', [$me, 'macroElseIf']);
		$me->addMacro('ifcontent', [$me, 'macroIfContent'], [$me, 'macroEndIfContent']);
		$me->addMacro('ifchanged', [$me, 'macroIfChanged'], '}');

		$me->addMacro('switch', '$__switch = (%node.args); if (false) {', '}');
		$me->addMacro('case', [$me, 'macroCase']);

		$me->addMacro('foreach', '', [$me, 'macroEndForeach']);
		$me->addMacro('for', 'for (%node.args) {', '}');
		$me->addMacro('while', [$me, 'macroWhile'], [$me, 'macroEndWhile']);
		$me->addMacro('continueIf', [$me, 'macroBreakContinueIf']);
		$me->addMacro('breakIf', [$me, 'macroBreakContinueIf']);
		$me->addMacro('skipIf', [$me, 'macroBreakContinueIf']);
		$me->addMacro('first', 'if ($iterator->isFirst(%node.args)) {', '}');
		$me->addMacro('last', 'if ($iterator->isLast(%node.args)) {', '}');
		$me->addMacro('sep', 'if (!$iterator->isLast(%node.args)) {', '}');

		$me->addMacro('try', [$me, 'macroTry'], '}');
		$me->addMacro('rollback', [$me, 'macroRollback']);

		$me->addMacro('var', [$me, 'macroVar']);
		$me->addMacro('default', [$me, 'macroVar']);
		$me->addMacro('dump', [$me, 'macroDump']);
		$me->addMacro('debugbreak', [$me, 'macroDebugbreak']);
		$me->addMacro('l', '?>{<?php');
		$me->addMacro('r', '?>}<?php');

		$me->addMacro('_', [$me, 'macroTranslate'], [$me, 'macroTranslate']);
		$me->addMacro('=', [$me, 'macroExpr']);

		$me->addMacro('capture', [$me, 'macroCapture'], [$me, 'macroCaptureEnd']);
		$me->addMacro('spaceless', [$me, 'macroSpaceless'], [$me, 'macroSpaceless']);
		$me->addMacro('include', [$me, 'macroInclude']);
		$me->addMacro('sandbox', [$me, 'macroSandbox']);
		$me->addMacro('contentType', [$me, 'macroContentType'], null, null, self::ALLOWED_IN_HEAD);
		$me->addMacro('php', [$me, 'macroExpr']);
		$me->addMacro('do', [$me, 'macroExpr']);

		$me->addMacro('class', null, null, [$me, 'macroClass']);
		$me->addMacro('attr', null, null, [$me, 'macroAttr']);

		$me->addMacro('varType', [$me, 'macroVarType'], null, null, self::ALLOWED_IN_HEAD);
		$me->addMacro('varPrint', [$me, 'macroVarPrint'], null, null, self::ALLOWED_IN_HEAD);
		$me->addMacro('templateType', [$me, 'macroTemplateType'], null, null, self::ALLOWED_IN_HEAD);
		$me->addMacro('templatePrint', [$me, 'macroTemplatePrint'], null, null, self::ALLOWED_IN_HEAD);
	}


	/**
	 * Initializes before template parsing.
	 * @return void
	 */
	public function initialize()
	{
		$this->overwrittenVars = [];
		$this->idCounter = 0;
	}


	/**
	 * Finishes template parsing.
	 */
	public function finalize()
	{
		if ($this->printTemplate) {
			return ["(new Latte\\Runtime\\Blueprint)->printClass(\$this, {$this->printTemplate}); exit;"];
		}

		$code = '';
		if ($this->overwrittenVars) {
			$vars = array_map(function ($l) { return implode(', ', $l); }, $this->overwrittenVars);
			$code .= 'foreach (array_intersect_key(' . Latte\PhpHelpers::dump($vars) . ', $this->params) as $__v => $__l) { '
				. 'trigger_error("Variable \$$__v overwritten in foreach on line $__l"); } ';
		}
		$code = $code
			? 'if (!$this->getReferringTemplate() || $this->getReferenceType() === "extends") { ' . $code . '}'
			: '';
		return [$code];
	}


	/********************* macros ****************d*g**/


	/**
	 * {if ...}
	 */
	public function macroIf(MacroNode $node, PhpWriter $writer): string
	{
		$node->validate(null);
		if ($node->data->capture = ($node->args === '')) {
			return 'ob_start(function () {})';
		}
		if ($node->prefix === $node::PREFIX_TAG) {
			for ($id = 0, $tmp = $node->htmlNode; $tmp = $tmp->parentNode; $id++);
			$node->htmlNode->data->id = $node->htmlNode->data->id ?? $id;
			return $writer->write(
				$node->htmlNode->closing
					? 'if ($__if[%var]) {'
					: 'if ($__if[%var] = (%node.args)) {',
				$node->htmlNode->data->id
			);
		}
		return $writer->write('if (%node.args) {');
	}


	/**
	 * {/if ...}
	 */
	public function macroEndIf(MacroNode $node, PhpWriter $writer): string
	{
		if (!$node->data->capture) {
			return '}';
		}

		$node->validate('condition');
		return $writer->write(
			'if (%node.args) '
			. (isset($node->data->else)
				? '{ ob_end_clean(); echo ob_get_clean(); }'
				: 'echo ob_get_clean();')
			. ' else '
			. (isset($node->data->else)
				? '{ $__tmp = ob_get_clean(); ob_end_clean(); echo $__tmp; }'
				: 'ob_end_clean();')
		);
	}


	/**
	 * {else}
	 */
	public function macroElse(MacroNode $node, PhpWriter $writer): string
	{
		if ($node->args !== '' && Helpers::startsWith($node->args, 'if')) {
			throw new CompileException('Arguments are not allowed in {else}, did you mean {elseif}?');
		}
		$node->validate(false, ['if', 'ifset', 'foreach', 'ifchanged', 'try']);

		$parent = $node->parentNode;
		if (isset($parent->data->else)) {
			throw new CompileException('Tag ' . $parent->getNotation() . ' may only contain one {else} clause.');
		}

		$parent->data->else = true;
		if ($parent->name === 'if' && $parent->data->capture) {
			return 'ob_start(function () {})';

		} elseif ($parent->name === 'foreach') {
			return '$iterations++; } if ($iterator->isEmpty()) {';

		} elseif ($parent->name === 'ifchanged' && $parent->data->capture) {
			$res = '?>' . $parent->closingCode . '<?php else {';
			$parent->closingCode = '<?php } ?>';
			return $res;

		} elseif ($parent->name === 'try') {
			$node->openingCode = $parent->data->code;
			$parent->closingCode = '<?php } ?>';
			return '';
		}
		return '} else {';
	}


	/**
	 * {elseif}
	 * {elseifset}
	 */
	public function macroElseIf(MacroNode $node, PhpWriter $writer): string
	{
		$node->validate(true, ['if', 'ifset']);
		if (isset($node->parentNode->data->else) || !empty($node->parentNode->data->capture)) {
			throw new CompileException('Tag ' . $node->getNotation() . ' is unexpected here.');
		}

		return $writer->write($node->name === 'elseif'
			? '} elseif (%node.args) {'
			: '} elseif (isset(%node.args)) {');
	}


	/**
	 * n:ifcontent
	 */
	public function macroIfContent(MacroNode $node, PhpWriter $writer): void
	{
		if (!$node->prefix || $node->prefix !== MacroNode::PREFIX_NONE) {
			throw new CompileException("Unknown {$node->getNotation()}, use n:{$node->name} attribute.");
		}
	}


	/**
	 * n:ifcontent
	 */
	public function macroEndIfContent(MacroNode $node, PhpWriter $writer): void
	{
		$node->openingCode = '<?php ob_start(function () {}); ?>';
		$node->innerContent = '<?php ob_start(); ?>'
			. $node->innerContent
			. '<?php $__ifc = ob_get_flush(); ?>';
		$node->closingCode = '<?php if (rtrim($__ifc) === "") { ob_end_clean(); } else { echo ob_get_clean(); } ?>';
	}


	/**
	 * {ifchanged [...]}
	 */
	public function macroIfChanged(MacroNode $node, PhpWriter $writer): void
	{
		$node->validate(null);
		$id = $node->data->id = ++$this->idCounter;
		if ($node->data->capture = ($node->args === '')) {
			$node->openingCode = '<?php ob_start(function () {}); try { ?>';
			$node->closingCode =
				'<?php } finally { $__tmp = ob_get_clean(); } '
				. "if ((\$__loc[$id] ?? null) !== \$__tmp) { echo \$__loc[$id] = \$__tmp; } ?>";
		} else {
			$node->openingCode = $writer->write(
				'<?php if (($__loc[%var] ?? null) !== ($__tmp = [%node.args])) { $__loc[%var] = $__tmp; ?>',
				$id,
				$id
			);
		}
	}


	/**
	 * {try}
	 */
	public function macroTry(MacroNode $node, PhpWriter $writer): void
	{
		$node->validate(false);
		for ($id = 0, $tmp = $node; $tmp = $tmp->closest(['try']); $id++);
		$node->data->code = $writer->write('<?php echo ob_get_clean();
			} catch (\Throwable $__e) {
			$iterator = $__it = $__try[%var][1];
			while (ob_get_level() > $__try[%var][0]) ob_end_clean();
			if (!($__e instanceof LR\RollbackException) && isset($this->global->coreExceptionHandler)) {
				($this->global->coreExceptionHandler)($__e, $this);
			} ?>', $id, $id);
		$node->openingCode = $writer->write('<?php $__try[%var] = [ob_get_level(), $__it ?? null]; ob_start(function () {}); try { ?>', $id);
		$node->closingCode = $node->data->code . '<?php } ?>';
	}


	/**
	 * {rollback}
	 */
	public function macroRollback(MacroNode $node, PhpWriter $writer): string
	{
		$parent = $node->closest(['try']);
		if (!$parent || isset($parent->data->catch)) {
			throw new CompileException('Tag {rollback} must be inside {try} ... {/try}.');
		}
		$node->validate(false);

		return $writer->write('throw new LR\RollbackException;');
	}


	/**
	 * {_$var |modifiers}
	 */
	public function macroTranslate(MacroNode $node, PhpWriter $writer): string
	{
		if ($node->closing) {
			if (strpos($node->content, '<?php') === false) {
				$value = PhpHelpers::dump($node->content);
				$node->content = '';
			} else {
				$node->openingCode = '<?php ob_start(function () {}) ?>' . $node->openingCode;
				$value = 'ob_get_clean()';
			}

			return $writer->write(
				'$__fi = new LR\FilterInfo(%var); echo %modifyContent($this->filters->filterContent("translate", $__fi, %raw))',
				$node->context[0],
				$value
			);

		} elseif ($node->empty = ($node->args !== '')) {
			return $writer->write('echo %modify(($this->filters->translate)(%node.args))');
		}
		return '';
	}


	/**
	 * {include [file] "file" [with blocks] [,] [params]}
	 */
	public function macroInclude(MacroNode $node, PhpWriter $writer): string
	{
		[$file, ] = $node->tokenizer->fetchWordWithModifier('file');
		$mode = 'include';
		if ($node->tokenizer->isNext('with') && !$node->tokenizer->isPrev(',')) {
			$node->tokenizer->consumeValue('with');
			$node->tokenizer->consumeValue('blocks');
			$mode = 'includeblock';
		}

		$node->replaced = false;
		$noEscape = Helpers::removeFilter($node->modifiers, 'noescape');
		if (!$noEscape && Helpers::removeFilter($node->modifiers, 'escape')) {
			trigger_error("Macro {{$node->name}} provides auto-escaping, remove |escape.");
		}
		if ($node->modifiers && !$noEscape) {
			$node->modifiers .= '|escape';
		}
		return $writer->write(
			'/* line ' . $node->startLine . ' */
			$this->createTemplate(%word, %node.array? + $this->params, %var)->renderToContentType(%raw);',
			$file,
			$mode,
			$node->modifiers
				? $writer->write('function ($s, $type) { $__fi = new LR\FilterInfo($type); return %modifyContent($s); }')
				: PhpHelpers::dump($noEscape ? null : implode($node->context))
		);
	}


	/**
	 * {sandbox "file" [,] [params]}
	 */
	public function macroSandbox(MacroNode $node, PhpWriter $writer): string
	{
		$node->validate(null);
		$node->replaced = false;
		return $writer->write(
			'/* line ' . $node->startLine . ' */
			ob_start(function () {});
			try { $this->createTemplate(%node.word, %node.array, "sandbox")->renderToContentType(%var); echo ob_get_clean(); }
			catch (\Throwable $__e) {
				if (isset($this->global->coreExceptionHandler)) { ob_end_clean(); ($this->global->coreExceptionHandler)($__e, $this); }
				else { echo ob_get_clean(); throw $__e; }
			}',
			implode($node->context)
		);
	}


	/**
	 * {capture $variable}
	 */
	public function macroCapture(MacroNode $node, PhpWriter $writer): string
	{
		$variable = $node->tokenizer->fetchWord();
		if (!$variable) {
			throw new CompileException('Missing variable in {capture}.');
		} elseif (!Helpers::startsWith($variable, '$')) {
			throw new CompileException("Invalid capture block variable '$variable'");
		}
		$this->checkExtraArgs($node);
		$node->data->variable = $variable;
		return 'ob_start(function () {})';
	}


	/**
	 * {/capture}
	 */
	public function macroCaptureEnd(MacroNode $node, PhpWriter $writer): string
	{
		$body = in_array($node->context[0], [Engine::CONTENT_HTML, Engine::CONTENT_XHTML], true)
			? 'ob_get_length() ? new LR\\Html(ob_get_clean()) : ob_get_clean()'
			: 'ob_get_clean()';
		return $writer->write(
			'$__fi = new LR\FilterInfo(%var); %raw = %modifyContent(%raw);',
			$node->context[0],
			$node->data->variable,
			$body
		);
	}


	/**
	 * {spaceless} ... {/spaceless}
	 */
	public function macroSpaceless(MacroNode $node): void
	{
		$node->validate(false);
		$node->openingCode = in_array($node->context[0], [Engine::CONTENT_HTML, Engine::CONTENT_XHTML], true)
			? "<?php ob_start('Latte\\Runtime\\Filters::spacelessHtmlHandler', 4096); ?>"
			: "<?php ob_start('Latte\\Runtime\\Filters::spacelessText', 4096); ?>";
		$node->closingCode = '<?php ob_end_flush(); ?>';
	}


	/**
	 * {while ...}
	 */
	public function macroWhile(MacroNode $node, PhpWriter $writer): string
	{
		$node->validate(null);
		if ($node->data->do = ($node->args === '')) {
			return 'do {';
		}
		return $writer->write('while (%node.args) {');
	}


	/**
	 * {/while ...}
	 */
	public function macroEndWhile(MacroNode $node, PhpWriter $writer): string
	{
		if ($node->data->do) {
			$node->validate(true);
			return $writer->write('} while (%node.args);');
		}
		return '}';
	}


	/**
	 * {foreach ...}
	 */
	public function macroEndForeach(MacroNode $node, PhpWriter $writer): void
	{
		$noCheck = Helpers::removeFilter($node->modifiers, 'nocheck');
		$noIterator = Helpers::removeFilter($node->modifiers, 'noiterator');
		if ($node->modifiers) {
			throw new CompileException('Only modifiers |noiterator and |nocheck are allowed here.');
		}
		$node->validate(true);
		$node->openingCode = '<?php $iterations = 0; ';
		$args = $writer->formatArgs();
		if (!$noCheck) {
			preg_match('#.+\s+as\s*\$(\w+)(?:\s*=>\s*\$(\w+))?#i', $args, $m);
			for ($i = 1; $i < count($m); $i++) {
				$this->overwrittenVars[$m[$i]][] = $node->startLine;
			}
		}
		if (
			!$noIterator
			&& preg_match('#\$iterator\W|\Wget_defined_vars\W#', $this->getCompiler()->expandTokens($node->content))
		) {
			$node->openingCode .= 'foreach ($iterator = $__it = new LR\CachingIterator('
				. preg_replace('#(.*)\s+as\s+#i', '$1, $__it ?? null) as ', $args, 1) . ') { ?>';
			$node->closingCode = '<?php $iterations++; } $iterator = $__it = $__it->getParent(); ?>';
		} else {
			$node->openingCode .= 'foreach (' . $args . ') { ?>';
			$node->closingCode = '<?php $iterations++; } ?>';
		}
	}


	/**
	 * {breakIf ...}
	 * {continueIf ...}
	 * {skipIf ...}
	 */
	public function macroBreakContinueIf(MacroNode $node, PhpWriter $writer): string
	{
		if ($node->name === 'skipIf') {
			$ancestors = ['foreach'];
			$cmd = '{ $iterator->skipRound(); continue; }';
		} else {
			$ancestors = ['for', 'foreach', 'while'];
			$cmd = str_replace('If', '', $node->name);
		}
		if (!$node->closest($ancestors)) {
			throw new CompileException('Tag ' . $node->getNotation() . ' is unexpected here.');
		}
		$node->validate('condition');

		if ($node->parentNode->prefix === $node::PREFIX_NONE) {
			return $writer->write("if (%node.args) { echo \"</{$node->parentNode->htmlNode->name}>\\n\"; $cmd; }");
		}
		return $writer->write("if (%node.args) $cmd;");
	}


	/**
	 * n:class="..."
	 */
	public function macroClass(MacroNode $node, PhpWriter $writer): string
	{
		if (isset($node->htmlNode->attrs['class'])) {
			throw new CompileException('It is not possible to combine class with n:class.');
		}
		$node->validate(true);
		return $writer->write('echo ($__tmp = array_filter(%node.array)) ? \' class="\' . %escape(implode(" ", array_unique($__tmp))) . \'"\' : "";');
	}


	/**
	 * n:attr="..."
	 */
	public function macroAttr(MacroNode $node, PhpWriter $writer): string
	{
		$node->validate(true);
		return $writer->write('$__tmp = %node.array; echo LR\Filters::htmlAttributes(isset($__tmp[0]) && is_array($__tmp[0]) ? $__tmp[0] : $__tmp);');
	}


	/**
	 * {dump ...}
	 */
	public function macroDump(MacroNode $node, PhpWriter $writer): string
	{
		$node->validate(null);
		$args = $writer->formatArgs();
		return $writer->write(
			'Tracy\Debugger::barDump(' . ($args ? "($args)" : 'get_defined_vars()') . ', %var);',
			$args ?: 'variables'
		);
	}


	/**
	 * {debugbreak ...}
	 */
	public function macroDebugbreak(MacroNode $node, PhpWriter $writer): string
	{
		$node->validate(null);
		if (function_exists($func = 'debugbreak') || function_exists($func = 'xdebug_break')) {
			return $writer->write($node->args === '' ? "$func()" : "if (%node.args) $func();");
		}
		return '';
	}


	/**
	 * {case ...}
	 */
	public function macroCase(MacroNode $node, PhpWriter $writer): string
	{
		$node->validate(true, ['switch']);
		if (isset($node->parentNode->data->default)) {
			throw new CompileException('Tag {default} must follow after {case} clause.');
		}
		return $writer->write('} elseif (in_array($__switch, %node.array, true)) {');
	}


	/**
	 * {var ...}
	 * {default ...}
	 * {default} in {switch}
	 */
	public function macroVar(MacroNode $node, PhpWriter $writer): string
	{
		if ($node->name === 'default' && $node->parentNode && $node->parentNode->name === 'switch') {
			$node->validate(false, ['switch']);
			if (isset($node->parentNode->data->default)) {
				throw new CompileException('Tag {switch} may only contain one {default} clause.');
			}
			$node->parentNode->data->default = true;
			return '} else {';

		} elseif ($node->modifiers) {
			$node->setArgs($node->args . $node->modifiers);
			$node->modifiers = '';
		}
		$node->validate(true);

		$var = true;
		$hasType = false;
		$tokens = $node->tokenizer;
		$res = new Latte\MacroTokens;
		while ($tokens->nextToken()) {
			if (
				$var
				&& $tokens->isCurrent($tokens::T_SYMBOL)
				&& (
					$tokens->isNext(',', '=>', '=')
					|| !$tokens->isNext()
				)
			) {
				trigger_error("Inside tag {{$node->name} {$node->args}} should be '{$tokens->currentValue()}' replaced with '\${$tokens->currentValue()}'", E_USER_DEPRECATED);

			} elseif ($var && !$hasType && $tokens->isCurrent($tokens::T_SYMBOL, '?', 'null', '\\')) { // type
				$tokens->nextToken();
				$tokens->nextAll($tokens::T_SYMBOL, '\\', '|', '[', ']', 'null');
				$hasType = true;
				continue;
			}

			if ($var && $tokens->isCurrent($tokens::T_SYMBOL, $tokens::T_VARIABLE)) {
				if ($node->name === 'default') {
					$res->append("'" . ltrim($tokens->currentValue(), '$') . "'");
				} else {
					$res->append('$' . ltrim($tokens->currentValue(), '$'));
				}
				$var = null;

			} elseif ($tokens->isCurrent('=', '=>') && $tokens->depth === 0) {
				if ($tokens->isCurrent('=>')) {
					trigger_error("Inside tag {{$node->name} {$node->args}} should be => replaced with =", E_USER_DEPRECATED);
				}
				$res->append($node->name === 'default' ? '=>' : '=');
				$var = false;

			} elseif ($tokens->isCurrent(',') && $tokens->depth === 0) {
				if ($var === null) {
					$res->append($node->name === 'default' ? '=>null' : '=null');
				}
				$res->append($node->name === 'default' ? ',' : ';');
				$var = true;
				$hasType = false;

			} elseif ($var === null && $node->name === 'default' && !$tokens->isCurrent($tokens::T_WHITESPACE)) {
				throw new CompileException("Unexpected '{$tokens->currentValue()}' in {default $node->args}");

			} else {
				$res->append($tokens->currentToken());
			}
		}
		if ($var === null) {
			$res->append($node->name === 'default' ? '=>null' : '=null');
		}
		$res = $writer->preprocess($res);
		$out = $writer->quotingPass($res)->joinAll();
		return $node->name === 'default'
			? "extract([$out], EXTR_SKIP)"
			: "$out;";
	}


	/**
	 * {= ...}
	 * {php ...}
	 * {do ...}
	 */
	public function macroExpr(MacroNode $node, PhpWriter $writer): string
	{
		$node->validate(true, [], $node->name === '=');
		return $writer->write(
			$node->name === '='
			? "echo %modify(%node.args) /* line {$node->startLine} */"
			: '%modify(%node.args);'
		);
	}


	/**
	 * {contentType ...}
	 */
	public function macroContentType(MacroNode $node, PhpWriter $writer): string
	{
		$node->validate(true);
		if (
			!$this->getCompiler()->isInHead()
			&& !($node->htmlNode && strtolower($node->htmlNode->name) === 'script' && strpos($node->args, 'html') !== false)
		) {
			throw new CompileException($node->getNotation() . ' is allowed only in template header.');
		}
		$compiler = $this->getCompiler();
		if (strpos($node->args, 'xhtml') !== false) {
			$type = $compiler::CONTENT_XHTML;
		} elseif (strpos($node->args, 'html') !== false) {
			$type = $compiler::CONTENT_HTML;
		} elseif (strpos($node->args, 'xml') !== false) {
			$type = $compiler::CONTENT_XML;
		} elseif (strpos($node->args, 'javascript') !== false) {
			$type = $compiler::CONTENT_JS;
		} elseif (strpos($node->args, 'css') !== false) {
			$type = $compiler::CONTENT_CSS;
		} elseif (strpos($node->args, 'calendar') !== false) {
			$type = $compiler::CONTENT_ICAL;
		} else {
			$type = $compiler::CONTENT_TEXT;
		}
		$compiler->setContentType($type);

		if (strpos($node->args, '/') && !$node->htmlNode) {
			return $writer->write(
				'if (empty($this->global->coreCaptured) && in_array($this->getReferenceType(), ["extends", null], true)) { header(%var); } ',
				'Content-Type: ' . $node->args
			);
		}
		return '';
	}


	/**
	 * {varType type $var}
	 */
	public function macroVarType(MacroNode $node): void
	{
		if ($node->modifiers) {
			$node->setArgs($node->args . $node->modifiers);
			$node->modifiers = '';
		}
		$node->validate(true);

		$type = trim($node->tokenizer->joinUntil($node->tokenizer::T_VARIABLE));
		$variable = $node->tokenizer->nextToken($node->tokenizer::T_VARIABLE);
		if (!$type || !$variable) {
			throw new CompileException('Unexpected content, expecting {varType type $var}.');
		}
	}


	/**
	 * {varPrint [all]}
	 */
	public function macroVarPrint(MacroNode $node): string
	{
		$vars = $node->tokenizer->fetchWord() === 'all'
			? 'get_defined_vars()'
			: 'array_diff_key(get_defined_vars(), $this->getParameters())';
		return "(new Latte\\Runtime\\Blueprint)->printVars($vars); exit;";
	}


	/**
	 * {templateType ClassName}
	 */
	public function macroTemplateType(MacroNode $node): void
	{
		if (!$this->getCompiler()->isInHead()) {
			throw new CompileException($node->getNotation() . ' is allowed only in template header.');
		}
		$node->validate('class name');
	}


	/**
	 * {templatePrint [ClassName]}
	 */
	public function macroTemplatePrint(MacroNode $node): void
	{
		$this->printTemplate = PhpHelpers::dump($node->tokenizer->fetchWord() ?: null);
	}
}
