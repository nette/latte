<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Macros;

use Latte;
use Latte\CompileException;
use Latte\Compiler\PhpHelpers;
use Latte\Compiler\PhpWriter;
use Latte\Compiler\Tag;
use Latte\Engine;
use Latte\Helpers;


/**
 * Basic macros for Latte.
 */
class CoreMacros extends MacroSet
{
	/** @var array<string, int[]> */
	private array $overwrittenVars;
	private ?string $printTemplate = null;
	private int $idCounter = 0;


	public static function install(Latte\Compiler\Compiler $compiler): void
	{
		$me = new static($compiler);

		$me->addMacro('if', [$me, 'macroIf'], [$me, 'macroEndIf']);
		$me->addMacro('else', [$me, 'macroElse']);
		$me->addMacro('elseif', [$me, 'macroElseIf']);
		$me->addMacro('ifset', 'if (isset(%node.args)) %node.line {', '}');
		$me->addMacro('elseifset', [$me, 'macroElseIf']);
		$me->addMacro('ifcontent', [$me, 'macroIfContent'], [$me, 'macroEndIfContent']);
		$me->addMacro('ifchanged', [$me, 'macroIfChanged'], '}');

		$me->addMacro('switch', '$ʟ_switch = (%node.args) %node.line; if (false) {', '}');
		$me->addMacro('case', [$me, 'macroCase']);

		$me->addMacro('foreach', '', [$me, 'macroEndForeach']);
		$me->addMacro('iterateWhile', [$me, 'macroIterateWhile'], [$me, 'macroEndIterateWhile']);
		$me->addMacro('for', 'for (%node.args) %node.line {', '}');
		$me->addMacro('while', [$me, 'macroWhile'], [$me, 'macroEndWhile']);
		$me->addMacro('continueIf', [$me, 'macroBreakContinueIf']);
		$me->addMacro('breakIf', [$me, 'macroBreakContinueIf']);
		$me->addMacro('skipIf', [$me, 'macroBreakContinueIf']);
		$me->addMacro('first', 'if ($iterator->isFirst(%node.args)) %node.line {', '}');
		$me->addMacro('last', 'if ($iterator->isLast(%node.args)) %node.line {', '}');
		$me->addMacro('sep', 'if (!$iterator->isLast(%node.args)) %node.line {', '}');

		$me->addMacro('try', [$me, 'macroTry'], '}');
		$me->addMacro('rollback', [$me, 'macroRollback']);

		$me->addMacro('var', [$me, 'macroVar']);
		$me->addMacro('default', [$me, 'macroVar']);
		$me->addMacro('dump', [$me, 'macroDump']);
		$me->addMacro('debugbreak', [$me, 'macroDebugbreak']);
		$me->addMacro('trace', 'LR\Tracer::throw() %node.line;');
		$me->addMacro('l', '?>{<?php');
		$me->addMacro('r', '?>}<?php');

		$me->addMacro('_', [$me, 'macroTranslate'], [$me, 'macroTranslate']);
		$me->addMacro('translate', [$me, 'macroTranslate'], [$me, 'macroTranslate']);
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
		$me->addMacro('tag', [$me, 'macroTag'], [$me, 'macroTagEnd']);

		$me->addMacro('parameters', [$me, 'macroParameters'], null, null, self::ALLOWED_IN_HEAD);
		$me->addMacro('varType', [$me, 'macroVarType'], null, null, self::ALLOWED_IN_HEAD);
		$me->addMacro('varPrint', [$me, 'macroVarPrint'], null, null, self::ALLOWED_IN_HEAD);
		$me->addMacro('templateType', [$me, 'macroTemplateType'], null, null, self::ALLOWED_IN_HEAD);
		$me->addMacro('templatePrint', [$me, 'macroTemplatePrint'], null, null, self::ALLOWED_IN_HEAD);
	}


	public function beforeCompile(): void
	{
		$this->overwrittenVars = [];
		$this->idCounter = 0;
	}


	public function finalize()
	{
		if ($this->printTemplate) {
			return ["(new Latte\\Runtime\\Blueprint)->printClass(\$this, {$this->printTemplate}); exit;"];
		}

		$code = '';
		if ($this->overwrittenVars) {
			$vars = array_map(fn($l) => implode(', ', $l), $this->overwrittenVars);
			$code .= 'foreach (array_intersect_key(' . Latte\Compiler\PhpHelpers::dump($vars) . ', $this->params) as $ʟ_v => $ʟ_l) { '
				. 'trigger_error("Variable \$$ʟ_v overwritten in foreach on line $ʟ_l"); } ';
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
	public function macroIf(Tag $node, PhpWriter $writer): string
	{
		$node->validate(null);
		if ($node->data->capture = ($node->args === '')) {
			return $writer->write("ob_start(fn() => '') %node.line; try {");
		}

		if ($node->prefix === $node::PREFIX_TAG) {
			for ($id = 0, $tmp = $node->htmlNode; $tmp = $tmp->parentNode; $id++);
			$node->htmlNode->data->id ??= $id;
			return $writer->write(
				$node->htmlNode->closing
					? 'if ($ʟ_if[%var]) %node.line {'
					: 'if ($ʟ_if[%var] = (%node.args)) %node.line {',
				$node->htmlNode->data->id,
			);
		}

		return $writer->write('if (%node.args) %node.line {');
	}


	/**
	 * {/if ...}
	 */
	public function macroEndIf(Tag $node, PhpWriter $writer): string
	{
		if (!$node->data->capture) {
			return '}';
		}

		$node->validate('condition');

		if (isset($node->data->else)) {
			return $writer->write('
					} finally {
						$ʟ_ifB = ob_get_clean();
					}
				} finally {
					$ʟ_ifA = ob_get_clean();
				}
				echo (%node.args) ? $ʟ_ifA : $ʟ_ifB %node.line;
			');
		}

		return $writer->write('
			} finally {
				$ʟ_ifA = ob_get_clean();
			}
			if (%node.args) %node.line { echo $ʟ_ifA; }
		');
	}


	/**
	 * {else}
	 */
	public function macroElse(Tag $node, PhpWriter $writer): string
	{
		if ($node->args !== '' && str_starts_with($node->args, 'if')) {
			throw new CompileException('Arguments are not allowed in {else}, did you mean {elseif}?');
		}

		$node->validate(false, ['if', 'ifset', 'foreach', 'ifchanged', 'try', 'first', 'last', 'sep']);

		$parent = $node->parentNode;
		if (isset($parent->data->else)) {
			throw new CompileException('Tag ' . $parent->getNotation() . ' may only contain one {else} clause.');
		}

		$parent->data->else = true;
		if ($parent->name === 'if' && $parent->data->capture) {
			return $writer->write("ob_start(fn() => '') %node.line; try {");

		} elseif ($parent->name === 'foreach') {
			return $writer->write('$iterations++; } if ($iterator->isEmpty()) %node.line {');

		} elseif ($parent->name === 'ifchanged' && $parent->data->capture) {
			$res = '?>' . $parent->closingCode . $writer->write('<?php else %node.line {');
			$parent->closingCode = '<?php } ?>';
			return $res;

		} elseif ($parent->name === 'try') {
			$node->openingCode = $parent->data->codeCatch;
			$parent->closingCode = $parent->data->codeFinally;
			return '';
		}

		return $writer->write('} else %node.line {');
	}


	/**
	 * {elseif}
	 * {elseifset}
	 */
	public function macroElseIf(Tag $node, PhpWriter $writer): string
	{
		$node->validate(true, ['if', 'ifset']);
		if (isset($node->parentNode->data->else) || !empty($node->parentNode->data->capture)) {
			throw new CompileException('Tag ' . $node->getNotation() . ' is unexpected here.');
		}

		return $writer->write($node->name === 'elseif'
			? '} elseif (%node.args) %node.line {'
			: '} elseif (isset(%node.args)) %node.line {');
	}


	/**
	 * n:ifcontent
	 */
	public function macroIfContent(Tag $node, PhpWriter $writer): void
	{
		if (!$node->prefix || $node->prefix !== Tag::PREFIX_NONE) {
			throw new CompileException("Unknown {$node->getNotation()}, use n:{$node->name} attribute.");
		}
		if ($node->htmlNode->empty) {
			throw new CompileException("Unnecessary n:ifcontent on empty element <{$node->htmlNode->name}>");
		}

		$node->validate(false);
	}


	/**
	 * n:ifcontent
	 */
	public function macroEndIfContent(Tag $node, PhpWriter $writer): void
	{
		$id = ++$this->idCounter;
		$node->openingCode = "<?php ob_start(fn() => ''); try { ?>";
		$node->innerContent = '<?php ob_start(); try { ?>'
			. $node->innerContent
			. "<?php } finally { \$ʟ_ifc[$id] = rtrim(ob_get_flush()) === ''; } ?>";
		$node->closingCode = "<?php } finally { if (\$ʟ_ifc[$id] ?? null) { ob_end_clean(); } else { echo ob_get_clean(); } } ?>";
	}


	/**
	 * {ifchanged [...]}
	 */
	public function macroIfChanged(Tag $node, PhpWriter $writer): void
	{
		$node->validate(null);
		$id = $node->data->id = ++$this->idCounter;
		if ($node->data->capture = ($node->args === '')) {
			$node->openingCode = $writer->write("<?php ob_start(fn() => ''); try %node.line { ?>");
			$node->closingCode =
				'<?php } finally { $ʟ_tmp = ob_get_clean(); } '
				. "if ((\$ʟ_loc[$id] ?? null) !== \$ʟ_tmp) { echo \$ʟ_loc[$id] = \$ʟ_tmp; } ?>";
		} else {
			$node->openingCode = $writer->write(
				'<?php if (($ʟ_loc[%0_var] ?? null) !== ($ʟ_tmp = [%node.args])) { $ʟ_loc[%0_var] = $ʟ_tmp; ?>',
				$id,
			);
		}
	}


	/**
	 * {try}
	 */
	public function macroTry(Tag $node, PhpWriter $writer): void
	{
		$node->replaced = false;
		$node->validate(false);
		for ($id = 0, $tmp = $node; $tmp = $tmp->closest(['try']); $id++);
		$node->data->codeCatch = '<?php
			} catch (Throwable $ʟ_e) {
				ob_end_clean();
				if (!($ʟ_e instanceof LR\RollbackException) && isset($this->global->coreExceptionHandler)) {
					($this->global->coreExceptionHandler)($ʟ_e, $this);
				}
			?>';
		$node->data->codeFinally = $writer->write('<?php
				ob_start();
			} finally {
				echo ob_get_clean();
				$iterator = $ʟ_it = $ʟ_try[%0_var][0];
			} ?>', $id);
		$node->openingCode = $writer->write('<?php $ʟ_try[%var] = [$ʟ_it ?? null]; ob_start(fn() => \'\'); try %node.line { ?>', $id);
		$node->closingCode = $node->data->codeCatch . $node->data->codeFinally;
	}


	/**
	 * {rollback}
	 */
	public function macroRollback(Tag $node, PhpWriter $writer): string
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
	 * {translate|modifiers}
	 */
	public function macroTranslate(Tag $node, PhpWriter $writer): string
	{
		if ($node->closing) {
			if (!str_contains($node->content, '<?php')) {
				$tmp = $node->content;
				$node->content = '';
				return $writer->write(
					'$ʟ_fi = new LR\FilterInfo(%var);
					echo %modifyContent($this->filters->filterContent("translate", $ʟ_fi, %raw)) %node.line;',
					implode('', $node->context),
					PhpHelpers::dump($tmp),
				);
			}

			$node->openingCode = "<?php ob_start(fn() => ''); try { ?>" . $node->openingCode;
			return $writer->write(
				'} finally {
					$ʟ_tmp = ob_get_clean();
				}
				$ʟ_fi = new LR\FilterInfo(%var);
				echo %modifyContent($this->filters->filterContent("translate", $ʟ_fi, $ʟ_tmp)) %node.line;',
				implode('', $node->context),
			);

		} elseif ($node->empty = ($node->args !== '') && $node->name === '_') {
			return $writer->write('echo %modify(($this->filters->translate)(%node.args)) %node.line;');

		} elseif ($node->name === '_') {
			trigger_error("As a pair tag for translation, {translate} ... {/translate} should be used instead of {_} ... {/} (on line $node->startLine)", E_USER_DEPRECATED);
		}

		return '';
	}


	/**
	 * {include [file] "file" [with blocks] [,] [params]}
	 */
	public function macroInclude(Tag $node, PhpWriter $writer): string
	{
		[$file,] = $node->tokenizer->fetchWordWithModifier('file');
		$mode = 'include';
		if ($node->tokenizer->isNext('with') && !$node->tokenizer->isPrev(',')) {
			$node->tokenizer->consumeValue('with');
			$node->tokenizer->consumeValue('blocks');
			$mode = 'includeblock';
		}

		$node->replaced = false;
		$noEscape = Helpers::removeFilter($node->modifiers, 'noescape');
		if ($node->modifiers && !$noEscape) {
			$node->modifiers .= '|escape';
		}

		return $writer->write(
			'$this->createTemplate(%word, %node.array? + $this->params, %var)->renderToContentType(%raw) %node.line;',
			$file,
			$mode,
			$node->modifiers
				? $writer->write('function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }')
				: PhpHelpers::dump($noEscape ? null : implode('', $node->context)),
		);
	}


	/**
	 * {sandbox "file" [,] [params]}
	 */
	public function macroSandbox(Tag $node, PhpWriter $writer): string
	{
		$node->validate(null);
		$node->replaced = false;
		return $writer->write(
			'ob_start(fn() => \'\');
			try { $this->createTemplate(%node.word, %node.array, "sandbox")->renderToContentType(%var) %node.line; echo ob_get_clean(); }
			catch (\Throwable $ʟ_e) {
				if (isset($this->global->coreExceptionHandler)) { ob_end_clean(); ($this->global->coreExceptionHandler)($ʟ_e, $this); }
				else { echo ob_get_clean(); throw $ʟ_e; }
			}',
			implode('', $node->context),
		);
	}


	/**
	 * {capture $variable}
	 */
	public function macroCapture(Tag $node, PhpWriter $writer): string
	{
		$variable = $node->tokenizer->fetchWord();
		if (!$variable) {
			throw new CompileException('Missing variable in {capture}.');
		} elseif (!str_starts_with($variable, '$')) {
			throw new CompileException("Invalid capture block variable '$variable'");
		}

		$this->checkExtraArgs($node);
		$node->data->variable = $variable;
		return $writer->write("ob_start(fn() => '') %node.line; try {");
	}


	/**
	 * {/capture}
	 */
	public function macroCaptureEnd(Tag $node, PhpWriter $writer): string
	{
		$body = implode('', $node->context) === Engine::CONTENT_HTML
			? 'ob_get_length() ? new LR\\Html(ob_get_clean()) : ob_get_clean()'
			: 'ob_get_clean()';
		return $writer->write(
			'} finally {
				$ʟ_tmp = %raw;
			}
			$ʟ_fi = new LR\FilterInfo(%var); %raw = %modifyContent($ʟ_tmp);',
			$body,
			implode('', $node->context),
			$node->data->variable,
		);
	}


	/**
	 * {spaceless} ... {/spaceless}
	 */
	public function macroSpaceless(Tag $node, PhpWriter $writer): void
	{
		$node->validate(false);
		$node->openingCode = $writer->write($node->context[0] === Engine::CONTENT_HTML
			? "<?php ob_start('Latte\\Runtime\\Filters::spacelessHtmlHandler', 4096) %node.line; try { ?>"
			: "<?php ob_start('Latte\\Runtime\\Filters::spacelessText', 4096) %node.line; try { ?>");
		$node->closingCode = '<?php } finally { ob_end_flush(); } ?>';
	}


	/**
	 * {while ...}
	 */
	public function macroWhile(Tag $node, PhpWriter $writer): string
	{
		$node->validate(null);
		if ($node->data->do = ($node->args === '')) {
			return $writer->write('do %node.line {');
		}

		return $writer->write('while (%node.args) %node.line {');
	}


	/**
	 * {/while ...}
	 */
	public function macroEndWhile(Tag $node, PhpWriter $writer): string
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
	public function macroEndForeach(Tag $node, PhpWriter $writer): void
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
			$args = preg_replace('#(.*)\s+as\s+#i', '$1, $ʟ_it ?? null) as ', $args, 1);
			$node->openingCode .= $writer->write('foreach ($iterator = $ʟ_it = new LR\CachingIterator(%raw) %node.line { ?>', $args);
			$node->closingCode = '<?php $iterations++; } $iterator = $ʟ_it = $ʟ_it->getParent(); ?>';
		} else {
			$node->openingCode .= $writer->write('foreach (%raw) %node.line { ?>', $args);
			$node->closingCode = '<?php $iterations++; } ?>';
		}
	}


	/**
	 * {iterateWhile ...}
	 */
	public function macroIterateWhile(Tag $node, PhpWriter $writer): void
	{
		if (!$node->closest(['foreach'])) {
			throw new CompileException('Tag ' . $node->getNotation() . ' must be inside {foreach} ... {/foreach}.');
		}

		$node->data->begin = $node->args !== '';
	}


	/**
	 * {/iterateWhile ...}
	 */
	public function macroEndIterateWhile(Tag $node, PhpWriter $writer): void
	{
		$node->validate(true);
		$foreach = $node->closest(['foreach']);
		$vars = preg_replace('#^.+\s+as\s+(?:(.+)=>)?(.+)$#i', '$1, $2', $foreach->args);
		$stmt = '
		 	if (!$iterator->hasNext()' . ($node->args ? $writer->write(' || !(%node.args)') : '') . ') {
		 		break;
		 	}
		 	$iterator->next();
		 	[' . $vars . '] = [$iterator->key(), $iterator->current()];
		';
		if ($node->data->begin) {
			$node->openingCode = $writer->write('<?php do %node.line { %raw ?>', $stmt);
			$node->closingCode = '<?php } while (true); ?>';
		} else {
			$node->openingCode = $writer->write('<?php do %node.line { ?>');
			$node->closingCode = "<?php $stmt } while (true); ?>";
		}
	}


	/**
	 * {breakIf ...}
	 * {continueIf ...}
	 * {skipIf ...}
	 */
	public function macroBreakContinueIf(Tag $node, PhpWriter $writer): string
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
			return $writer->write("if (%node.args) %node.line { echo \"</{$node->parentNode->htmlNode->name}>\\n\"; $cmd; }");
		}

		return $writer->write("if (%node.args) %node.line $cmd;");
	}


	/**
	 * n:class="..."
	 */
	public function macroClass(Tag $node, PhpWriter $writer): string
	{
		if (isset($node->htmlNode->attrs['class'])) {
			throw new CompileException('It is not possible to combine class with n:class.');
		}

		$node->validate(true);
		return $writer->write('echo ($ʟ_tmp = array_filter(%node.array)) ? \' class="\' . %escape(implode(" ", array_unique($ʟ_tmp))) . \'"\' : "" %node.line;');
	}


	/**
	 * n:attr="..."
	 */
	public function macroAttr(Tag $node, PhpWriter $writer): string
	{
		$node->validate(true);
		return $writer->write('$ʟ_tmp = %node.array; echo LR\Filters::htmlAttributes(isset($ʟ_tmp[0]) && is_array($ʟ_tmp[0]) ? $ʟ_tmp[0] : $ʟ_tmp) %node.line;');
	}


	/**
	 * n:tag="..."
	 */
	public function macroTag(Tag $node, PhpWriter $writer): void
	{
		if (!$node->prefix || $node->prefix !== Tag::PREFIX_NONE) {
			throw new CompileException("Unknown {$node->getNotation()}, use n:{$node->name} attribute.");

		} elseif (preg_match('(style$|script$)iA', $node->htmlNode->name)) {
			throw new CompileException("Attribute {$node->getNotation()} is not allowed in <script> or <style>");
		}

		$node->validate(true);
	}


	/**
	 * n:tag="..."
	 */
	public function macroTagEnd(Tag $node, PhpWriter $writer): void
	{
		for ($id = 0, $tmp = $node->htmlNode; $tmp = $tmp->parentNode; $id++);
		$node->htmlNode->data->id ??= $id;

		$node->openingCode = $writer->write('<?php
			$ʟ_tag[%0_var] = (%node.args) ?? %1_var;
			Latte\Runtime\Filters::checkTagSwitch(%1_var, $ʟ_tag[%0_var]);
		?>', $node->htmlNode->data->id, $node->htmlNode->name);

		$node->content = preg_replace(
			'~^(\s*<)' . Latte\Compiler\Parser::RE_TAG_NAME . '~',
			"\$1<?php echo \$ʟ_tag[{$node->htmlNode->data->id}]; ?>\n",
			$node->content,
		);
		$node->content = preg_replace(
			'~</' . Latte\Compiler\Parser::RE_TAG_NAME . '(\s*>\s*)$~',
			"</<?php echo \$ʟ_tag[{$node->htmlNode->data->id}]; ?>\n\$1",
			$node->content,
		);
	}


	/**
	 * {dump ...}
	 */
	public function macroDump(Tag $node, PhpWriter $writer): string
	{
		$node->validate(null);
		$args = $writer->formatArgs();
		return $writer->write(
			'Tracy\Debugger::barDump(' . ($args ? "($args)" : 'get_defined_vars()') . ', %var) %node.line;',
			$args ?: 'variables',
		);
	}


	/**
	 * {debugbreak ...}
	 */
	public function macroDebugbreak(Tag $node, PhpWriter $writer): string
	{
		$node->validate(null);
		if (function_exists($func = 'debugbreak') || function_exists($func = 'xdebug_break')) {
			return $writer->write(($node->args === '' ? '' : 'if (%node.args) ') . "$func() %node.line;");
		}

		return '';
	}


	/**
	 * {case ...}
	 */
	public function macroCase(Tag $node, PhpWriter $writer): string
	{
		$node->validate(true, ['switch']);
		if (isset($node->parentNode->data->default)) {
			throw new CompileException('Tag {default} must follow after {case} clause.');
		}

		return $writer->write('} elseif (in_array($ʟ_switch, %node.array, true)) %node.line {');
	}


	/**
	 * {var ...}
	 * {default ...}
	 * {default} in {switch}
	 */
	public function macroVar(Tag $node, PhpWriter $writer): string
	{
		if ($node->name === 'default' && $node->parentNode && $node->parentNode->name === 'switch') {
			$node->validate(false, ['switch']);
			if (isset($node->parentNode->data->default)) {
				throw new CompileException('Tag {switch} may only contain one {default} clause.');
			}

			$node->parentNode->data->default = true;
			return $writer->write('} else %node.line {');

		} elseif ($node->modifiers) {
			$node->setArgs($node->args . $node->modifiers);
			$node->modifiers = '';
		}

		$node->validate(true);

		$var = true;
		$hasType = false;
		$tokens = $node->tokenizer;
		$res = new Latte\Compiler\MacroTokens;
		while ($tokens->nextToken()) {
			if ($var && !$hasType && $tokens->isCurrent($tokens::T_SYMBOL, '?', 'null', '\\')) { // type
				$tokens->nextToken();
				$tokens->nextAll($tokens::T_SYMBOL, '\\', '|', '[', ']', 'null');
				$hasType = true;

			} elseif ($var && $tokens->isCurrent($tokens::T_VARIABLE)) {
				if ($node->name === 'default') {
					$res->append("'" . ltrim($tokens->currentValue(), '$') . "'");
				} else {
					$res->append('$' . ltrim($tokens->currentValue(), '$'));
				}

				$var = null;

			} elseif ($var === null && $tokens->isCurrent('=')) {
				$res->append($node->name === 'default' ? '=>' : '=');
				$var = false;

			} elseif (!$var && $tokens->isCurrent(',') && $tokens->depth === 0) {
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
		} elseif ($var === true) {
			throw new CompileException("Unexpected end in {{$node->name} {$node->args}}");
		}

		$res = $writer->preprocess($res);
		$writer->validateKeywords($res);
		$out = $writer->quotingPass($res)->joinAll();
		return $writer->write($node->name === 'default'
			? 'extract([%raw], EXTR_SKIP) %node.line;'
			: '%raw %node.line;', $out);
	}


	/**
	 * {= ...}
	 * {php ...}
	 * {do ...}
	 */
	public function macroExpr(Tag $node, PhpWriter $writer): string
	{
		$node->validate(true, [], $node->name === '=');
		return $writer->write(
			$node->name === '='
				? 'echo %modify(%node.args) %node.line;'
				: '%modify(%node.args) %node.line;',
		);
	}


	/**
	 * {contentType ...}
	 */
	public function macroContentType(Tag $node, PhpWriter $writer): string
	{
		$node->validate(true);
		if (
			!$this->getCompiler()->isInHead()
			&& !($node->htmlNode && strtolower($node->htmlNode->name) === 'script' && str_contains($node->args, 'html'))
		) {
			throw new CompileException($node->getNotation() . ' is allowed only in template header.');
		}

		$compiler = $this->getCompiler();
		if (str_contains($node->args, 'html')) {
			$type = $compiler::CONTENT_HTML;
		} elseif (str_contains($node->args, 'xml')) {
			$type = $compiler::CONTENT_XML;
		} elseif (str_contains($node->args, 'javascript')) {
			$type = $compiler::CONTENT_JS;
		} elseif (str_contains($node->args, 'css')) {
			$type = $compiler::CONTENT_CSS;
		} elseif (str_contains($node->args, 'calendar')) {
			$type = $compiler::CONTENT_ICAL;
		} else {
			$type = $compiler::CONTENT_TEXT;
		}

		$compiler->setContentType($type);

		if (strpos($node->args, '/') && !$node->htmlNode) {
			return $writer->write(
				'if (empty($this->global->coreCaptured) && in_array($this->getReferenceType(), ["extends", null], true)) { header(%var) %node.line; } ',
				'Content-Type: ' . $node->args,
			);
		}

		return '';
	}


	/**
	 * {parameters type $var, ...}
	 */
	public function macroParameters(Tag $node, PhpWriter $writer): void
	{
		if (!$this->getCompiler()->isInHead()) {
			throw new CompileException($node->getNotation() . ' is allowed only in template header.');
		}

		if ($node->modifiers) {
			$node->setArgs($node->args . $node->modifiers);
			$node->modifiers = '';
		}

		$node->validate(true);

		$tokens = $node->tokenizer;
		$writer->validateKeywords($tokens);
		$params = [];
		while ($tokens->isNext(...$tokens::SIGNIFICANT)) {
			if ($tokens->nextToken($tokens::T_SYMBOL, '?', 'null', '\\')) { // type
				$tokens->nextAll($tokens::T_SYMBOL, '\\', '|', '[', ']', 'null');
			}

			$param = $tokens->consumeValue($tokens::T_VARIABLE);
			$default = $tokens->nextToken('=')
				? $tokens->joinUntilSameDepth(',')
				: 'null';
			$params[] = $writer->write(
				'%raw = $this->params[%var] ?? $this->params[%var] ?? %raw;',
				$param,
				count($params),
				substr($param, 1),
				$default,
			);
			if ($tokens->isNext(...$tokens::SIGNIFICANT)) {
				$tokens->consumeValue(',');
			}
		}

		$this->getCompiler()->paramsExtraction = implode('', $params);
	}


	/**
	 * {varType type $var}
	 */
	public function macroVarType(Tag $node): void
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
	public function macroVarPrint(Tag $node): string
	{
		$vars = $node->tokenizer->fetchWord() === 'all'
			? 'get_defined_vars()'
			: 'array_diff_key(get_defined_vars(), $this->getParameters())';
		return "(new Latte\\Runtime\\Blueprint)->printVars($vars); exit;";
	}


	/**
	 * {templateType ClassName}
	 */
	public function macroTemplateType(Tag $node): void
	{
		if (!$this->getCompiler()->isInHead()) {
			throw new CompileException($node->getNotation() . ' is allowed only in template header.');
		}

		$node->validate('class name');
	}


	/**
	 * {templatePrint [ClassName]}
	 */
	public function macroTemplatePrint(Tag $node): void
	{
		$this->printTemplate = PhpHelpers::dump($node->tokenizer->fetchWord() ?: null);
	}
}
