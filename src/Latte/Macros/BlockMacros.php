<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Macros;

use Latte;
use Latte\MacroNode;
use Latte\PhpWriter;
use Latte\CompileException;


/**
 * Block macros.
 */
class BlockMacros extends MacroSet
{
	/** @var array */
	private $namedBlocks = [];

	/** @var array */
	private $blockTypes = [];

	/** @var bool */
	private $extends;


	public static function install(Latte\Compiler $compiler)
	{
		$me = new static($compiler);
		$me->addMacro('include', [$me, 'macroInclude']);
		$me->addMacro('includeblock', [$me, 'macroIncludeBlock']);
		$me->addMacro('extends', [$me, 'macroExtends']);
		$me->addMacro('layout', [$me, 'macroExtends']);
		$me->addMacro('block', [$me, 'macroBlock'], [$me, 'macroBlockEnd'], NULL, self::AUTO_CLOSE);
		$me->addMacro('define', [$me, 'macroBlock'], [$me, 'macroBlockEnd']);
		$me->addMacro('snippet', [$me, 'macroBlock'], [$me, 'macroBlockEnd']);
		$me->addMacro('snippetArea', [$me, 'macroBlock'], [$me, 'macroBlockEnd']);
		$me->addMacro('ifset', [$me, 'macroIfset'], '}');
		$me->addMacro('elseifset', [$me, 'macroIfset'], '}');
	}


	/**
	 * Initializes before template parsing.
	 * @return void
	 */
	public function initialize()
	{
		$this->namedBlocks = [];
		$this->blockTypes = [];
		$this->extends = NULL;
	}


	/**
	 * Finishes template parsing.
	 * @return array(prolog, epilog)
	 */
	public function finalize()
	{
		if ($this->namedBlocks) {
			$functions = [];
			foreach ($this->namedBlocks as $name => $code) {
				$cleanName = trim(preg_replace('#\W+#', '_', $name), '_');
				if (!$cleanName || in_array(strtolower("block$cleanName"), array_map('strtolower', $functions))) {
					$cleanName .=  '_' . substr(md5($name), 0, 5);
				}
				$functions[$name] = 'block' . ucfirst($cleanName);
				$code = "\n?>$code<?php";
				if ($name[0] === '_') { // snippet
					$code = "\n\$_control->redrawControl(" . var_export((string) substr($name, 1), TRUE) . ", FALSE);\n$code";
				}
				if (strpos($code, '$') !== FALSE) {
					$code = 'unset($_args["this"]); foreach ($_args as $__k => $__v) $$__k = $__v;' . $code;
				}
				$this->getCompiler()->addMethod($functions[$name], $code, '$_b, $_args');
			}
			$this->getCompiler()->addProperty('blocks', $functions);
			$this->getCompiler()->addProperty('blockTypes', $this->blockTypes);
		}


		$epilog = $prolog = [];
		if ($this->namedBlocks || $this->extends) {
			$prolog[] = '// template extending';

			$prolog[] = '$_l->extends = '
				. ($this->extends ? $this->extends : 'empty($_g->extended) && isset($_control) && $_control instanceof Nette\Application\UI\Presenter ? $_control->findLayoutTemplateFile() : NULL')
				. '; $_g->extended = TRUE;';

			$prolog[] = 'if ($_l->extends) { ob_start(function () {});}';
			if (!$this->namedBlocks) {
				$epilog[] = 'if ($_l->extends) { ob_end_clean(); return $this->renderChildTemplate($_l->extends, get_defined_vars());}';
			}
		}

		return [implode("\n\n", $prolog), implode("\n", $epilog)];
	}


	/********************* macros ****************d*g**/


	/**
	 * {include #block}
	 */
	public function macroInclude(MacroNode $node, PhpWriter $writer)
	{
		$destination = $node->tokenizer->fetchWord(); // destination [,] [params]
		if (!preg_match('~#|[\w-]+\z~A', $destination)) {
			return FALSE;
		}

		$destination = ltrim($destination, '#');
		$parent = $destination === 'parent';
		if ($destination === 'parent' || $destination === 'this') {
			for ($item = $node->parentNode; $item && $item->name !== 'block' && !isset($item->data->name); $item = $item->parentNode);
			if (!$item) {
				throw new CompileException("Cannot include $destination block outside of any block.");
			}
			$destination = $item->data->name;
		}

		$name = strpos($destination, '$') === FALSE ? var_export($destination, TRUE) : $destination;
		if (isset($this->namedBlocks[$destination]) && !$parent) {
			$cmd = "call_user_func(reset(\$_b->blocks[$name]), \$_b, %node.array? + get_defined_vars())";
		} else {
			$cmd = 'Latte\Macros\BlockMacrosRuntime::callBlock' . ($parent ? 'Parent' : '') . "(\$_b, $name, %node.array? + " . ($parent ? 'get_defined_vars()' : '$this->params') . ')';
		}

		$node->modifiers = preg_replace('#\|nocheck\s?(?=\||\z)#i', '', $node->modifiers, -1, $found);
		if (!$found && !preg_match('#\|?escape(?:html|htmlcomment|ical|js|url|xml)?\s?(?=\||\z)#i', $node->modifiers)) {
			$cmd = "if (" . var_export($this->exportBlockType($node), TRUE) . " !== \$_b->types[$name]) { "
				. "trigger_error('Incompatible context for including block $destination.', E_USER_WARNING); }\n"
				. $cmd;
		}

		if ($node->modifiers) {
			return $writer->write("ob_start(function () {}); $cmd; echo %modify(ob_get_clean())");
		} else {
			return $writer->write($cmd);
		}
	}


	/**
	 * {includeblock "file"}
	 */
	public function macroIncludeBlock(MacroNode $node, PhpWriter $writer)
	{
		if ($node->modifiers) {
			throw new CompileException("Modifiers are not allowed in {{$node->name}}");
		}
		return $writer->write(
			'ob_start(function () {}); $_g->includingBlock = isset($_g->includingBlock) ? ++$_g->includingBlock : 1; $this->renderChildTemplate(%node.word, %node.array? + get_defined_vars()); $_g->includingBlock--; echo rtrim(ob_get_clean())'
		);
	}


	/**
	 * {extends auto | none | $var | "file"}
	 */
	public function macroExtends(MacroNode $node, PhpWriter $writer)
	{
		if ($node->modifiers) {
			throw new CompileException("Modifiers are not allowed in {{$node->name}}");
		}
		if (!$node->args) {
			throw new CompileException("Missing destination in {{$node->name}}");
		}
		if (!empty($node->parentNode)) {
			throw new CompileException("{{$node->name}} must be placed outside any macro.");
		}
		if ($this->extends !== NULL) {
			throw new CompileException("Multiple {{$node->name}} declarations are not allowed.");
		}
		if ($node->args === 'none') {
			$this->extends = 'FALSE';
		} elseif ($node->args === 'auto') {
			$this->extends = '$_presenter->findLayoutTemplateFile()';
		} else {
			$this->extends = $writer->write('%node.word%node.args');
		}
		return;
	}


	/**
	 * {block [[#]name]}
	 * {snippet [name [,]] [tag]}
	 * {snippetArea [name]}
	 * {define [#]name}
	 */
	public function macroBlock(MacroNode $node, PhpWriter $writer)
	{
		$name = $node->tokenizer->fetchWord();

		if ($node->name === '#') {
			trigger_error('Shortcut {#block} is deprecated.', E_USER_DEPRECATED);

		} elseif ($node->name === 'block' && $name === FALSE) { // anonymous block
			return $node->modifiers === '' ? '' : 'ob_start(function () {})';
		}

		$node->data->name = $name = ltrim($name, '#');
		if ($name == NULL) {
			if ($node->name === 'define') {
				throw new CompileException('Missing block name.');
			}

		} elseif (strpos($name, '$') !== FALSE) { // dynamic block/snippet
			if ($node->name === 'snippet') {
				for ($parent = $node->parentNode; $parent && !($parent->name === 'snippet' || $parent->name === 'snippetArea'); $parent = $parent->parentNode);
				if (!$parent) {
					throw new CompileException('Dynamic snippets are allowed only inside static snippet/snippetArea.');
				}
				$parent->data->dynamic = TRUE;
				$node->data->leave = TRUE;
				$node->closingCode = "<?php \$_l->dynSnippets[\$_l->dynSnippetId] = ob_get_flush() ?>";

				if ($node->prefix) {
					$node->attrCode = $writer->write("<?php echo ' id=\"' . (\$_l->dynSnippetId = \$_control->getSnippetId({$writer->formatWord($name)})) . '\"' ?>");
					return $writer->write('ob_start()');
				}
				$tag = trim($node->tokenizer->fetchWord(), '<>');
				$tag = $tag ? $tag : 'div';
				$node->closingCode .= "\n</$tag>";
				return $writer->write("?>\n<$tag id=\"<?php echo \$_l->dynSnippetId = \$_control->getSnippetId({$writer->formatWord($name)}) ?>\"><?php ob_start()");

			} else {
				$node->data->leave = TRUE;
				$node->data->func = 'block' . ucfirst(preg_replace('#\W#', '', $name)) . '_' . substr(md5($name), 0, 5);
				$fname = $writer->formatWord($name);
				$node->closingCode = '<?php ' . ($node->name === 'define' ? '' : "call_user_func(reset(\$_b->blocks[$fname]), \$_b, get_defined_vars())") . ' ?>';
				$blockType = var_export($this->exportBlockType($node), TRUE);
				return "Latte\\Macros\\BlockMacrosRuntime::checkType($blockType, \$_b->types, $fname);"
					. "\$_b->blocks[$fname][] = [\$this, '{$node->data->func}'];";
			}
		}

		// static snippet/snippetArea
		if ($node->name === 'snippet' || $node->name === 'snippetArea') {
			if ($node->prefix && isset($node->htmlNode->attrs['id'])) {
				throw new CompileException('Cannot combine HTML attribute id with n:snippet.');
			}
			$node->data->name = $name = '_' . $name;
		}

		if (isset($this->namedBlocks[$name])) {
			throw new CompileException("Cannot redeclare static {$node->name} '$name'");
		}

		$prolog = $this->namedBlocks ? '' : "if (\$_l->extends) { ob_end_clean(); return \$this->renderChildTemplate(\$_l->extends, get_defined_vars()); }\n";
		$this->namedBlocks[$name] = TRUE;
		$this->blockTypes[$name] = $this->exportBlockType($node);

		$include = 'call_user_func(reset($_b->blocks[%var]), $_b, ' . (($node->name === 'snippet' || $node->name === 'snippetArea') ? '$this->params' : 'get_defined_vars()') . ')';
		if ($node->modifiers) {
			$include = "ob_start(function () {}); $include; echo %modify(ob_get_clean())";
		}

		if ($node->name === 'snippet') {
			if ($node->prefix) {
				$node->attrCode = $writer->write('<?php echo \' id="\' . $_control->getSnippetId(%var) . \'"\' ?>', (string) substr($name, 1));
				return $writer->write($prolog . $include, $name);
			}
			$tag = trim($node->tokenizer->fetchWord(), '<>');
			$tag = $tag ? $tag : 'div';
			return $writer->write("$prolog ?>\n<$tag id=\"<?php echo \$_control->getSnippetId(%var) ?>\"><?php $include ?>\n</$tag><?php ",
				(string) substr($name, 1), $name
			);

		} elseif ($node->name === 'define') {
			return $prolog;

		} else { // block, snippetArea
			return $writer->write($prolog . $include, $name);
		}
	}


	/**
	 * {/block}
	 * {/snippet}
	 * {/snippetArea}
	 * {/define}
	 */
	public function macroBlockEnd(MacroNode $node, PhpWriter $writer)
	{
		if (isset($node->data->name)) { // block, snippet, define
			if ($asInner = $node->name === 'snippet' && $node->prefix === MacroNode::PREFIX_NONE) {
				$node->content = $node->innerContent;
			}

			if (empty($node->data->leave)) {
				if ($node->name === 'snippetArea' && empty($node->data->dynamic)) {
					$node->content = "<?php \$_control->snippetMode = isset(\$_snippetMode) && \$_snippetMode; ?>{$node->content}<?php \$_control->snippetMode = FALSE; ?>";
				}
				if (!empty($node->data->dynamic)) {
					$node->content .= '<?php if (isset($_l->dynSnippets)) return $_l->dynSnippets; ?>';
				}
				if ($node->name === 'snippetArea') {
					$node->content .= '<?php return FALSE; ?>';
				}
				$this->namedBlocks[$node->data->name] = $tmp = preg_replace('#^\n+|(?<=\n)[ \t]+\z#', '', $node->content);
				$node->content = substr_replace($node->content, $node->openingCode . "\n", strspn($node->content, "\n"), strlen($tmp));
				$node->openingCode = '<?php ?>';

			} elseif (isset($node->data->func)) {
				$node->content = rtrim($node->content, " \t");
				$this->getCompiler()->addMethod(
					$node->data->func,
					'unset($_args["this"]); foreach ($_args as $__k => $__v) $$__k = $__v;' . "\n?>$node->content<?php",
					'$_b, $_args'
				);
				$node->content = '';
			}

			if ($asInner) { // n:snippet -> n:inner-snippet
				$node->innerContent = $node->openingCode . $node->content . $node->closingCode;
				$node->closingCode = $node->openingCode = '<?php ?>';
			}

		} elseif ($node->modifiers) { // anonymous block with modifier
			return $writer->write('echo %modify(ob_get_clean())');
		}
	}


	/**
	 * {ifset #block}
	 * {elseifset #block}
	 */
	public function macroIfset(MacroNode $node, PhpWriter $writer)
	{
		if ($node->modifiers) {
			throw new CompileException("Modifiers are not allowed in {{$node->name}}");
		}
		if (!preg_match('~#|[\w-]+\z~A', $node->args)) {
			return FALSE;
		}
		$list = [];
		while (($name = $node->tokenizer->fetchWord()) !== FALSE) {
			$list[] = preg_match('~#|[\w-]+\z~A', $name)
				? '$_b->blocks["' . ltrim($name, '#') . '"]'
				: $writer->formatArgs(new Latte\MacroTokens($name));
		}
		return ($node->name === 'elseifset' ? '} else' : '')
			. 'if (isset(' . implode(', ', $list) . ')) {';
	}


	private function exportBlockType(MacroNode $node)
	{
		if ($node->prefix === MacroNode::PREFIX_INNER && ($tag = strtolower($node->htmlNode->name)) === 'script') {
			$content = Latte\Compiler::CONTENT_JS;
		} elseif ($node->prefix === MacroNode::PREFIX_INNER && $tag === 'style') {
			$content = Latte\Compiler::CONTENT_CSS;
		} elseif ($node->prefix) {
			$content = '';
		} else {
			$content = $this->getCompiler()->getContext();
		}
		return $this->getCompiler()->getContentType() . implode((array) $content);
	}

}
