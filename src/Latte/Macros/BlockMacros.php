<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Macros;

use Latte;
use Latte\MacroNode;
use Latte\MacroTokens;
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
		$me->addMacro('includeblock', [$me, 'macroIncludeBlock']); // deprecated
		$me->addMacro('import', [$me, 'macroImport']);
		$me->addMacro('extends', [$me, 'macroExtends']);
		$me->addMacro('layout', [$me, 'macroExtends']);
		$me->addMacro('snippet', [$me, 'macroBlock'], [$me, 'macroBlockEnd']);
		$me->addMacro('block', [$me, 'macroBlock'], [$me, 'macroBlockEnd'], NULL, self::AUTO_CLOSE);
		$me->addMacro('define', [$me, 'macroBlock'], [$me, 'macroBlockEnd']);
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
	 */
	public function finalize()
	{
		$compiler = $this->getCompiler();
		$functions = [];
		foreach ($this->namedBlocks as $name => $code) {
			$compiler->addMethod(
				$functions[$name] = $this->generateMethodName($name),
				'?>' . $compiler->expandTokens($code) . '<?php',
				'$_args'
			);
		}

		if ($this->namedBlocks) {
			$compiler->addProperty('blocks', $functions);
			$compiler->addProperty('blockTypes', $this->blockTypes);
		}
	}


	/********************* macros ****************d*g**/


	/**
	 * {include block}
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

		$node->modifiers = preg_replace('#\|nocheck\s?(?=\||\z)#i', '', $node->modifiers, -1, $noCheck);
		$cmd = '$this->renderBlock' . ($parent ? 'Parent' : '') . '('
			. (strpos($destination, '$') === FALSE ? var_export($destination, TRUE) : $destination)
			. ', %node.array? + '
			. (isset($this->namedBlocks[$destination]) || $parent ? 'get_defined_vars()' : '$this->params')
			. ($noCheck || $parent ? '' : ', ' . var_export($this->exportBlockType($node), TRUE))
			. ');';

		if ($node->modifiers) {
			return $writer->write("ob_start(function () {}); $cmd; \$_fi = new LR\\FilterInfo(%var); echo %modifyContent(ob_get_clean());", $node->context[0]);
		} else {
			return $writer->write($cmd);
		}
	}


	/**
	 * {includeblock "file"}
	 * @deprecated
	 */
	public function macroIncludeBlock(MacroNode $node, PhpWriter $writer)
	{
		if ($node->modifiers) {
			throw new CompileException('Modifiers are not allowed in ' . $node->getNotation());
		}
		return $writer->write(
			'ob_start(function () {}); $this->createTemplate(%node.word, %node.array? + get_defined_vars(), "includeblock")->renderToContentType(%var); echo rtrim(ob_get_clean());',
			$this->exportBlockType($node)
		);
	}


	/**
	 * {import "file"}
	 */
	public function macroImport(MacroNode $node, PhpWriter $writer)
	{
		if ($node->modifiers) {
			throw new CompileException('Modifiers are not allowed in ' . $node->getNotation());
		}
		$destination = $node->tokenizer->fetchWord();
		$this->checkExtraArgs($node);
		return $writer->write('$this->createTemplate(%word, $this->params, "import")->render();', $destination);
	}


	/**
	 * {extends none | $var | "file"}
	 */
	public function macroExtends(MacroNode $node, PhpWriter $writer)
	{
		$notation = $node->getNotation();
		if ($node->modifiers) {
			throw new CompileException("Modifiers are not allowed in $notation");
		} elseif (!$node->args) {
			throw new CompileException("Missing destination in $notation");
		} elseif ($node->parentNode) {
			throw new CompileException("$notation must be placed outside any macro.");
		} elseif ($this->extends !== NULL) {
			throw new CompileException("Multiple $notation declarations are not allowed.");
		} elseif ($node->args === 'none') {
			$this->extends = FALSE;
			return $writer->write('$this->parentName = FALSE;');
		} else {
			$this->extends = TRUE;
			return $writer->write('$this->parentName = %node.word%node.args;');
		}
	}


	/**
	 * {block [name]}
	 * {snippet [name [,]] [tag]}
	 * {snippetArea [name]}
	 * {define name}
	 */
	public function macroBlock(MacroNode $node, PhpWriter $writer)
	{
		$name = $node->tokenizer->fetchWord();

		if ($node->name === 'block' && $name === FALSE) { // anonymous block
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
				$node->closingCode = "<?php \$this->global->dynSnippets[\$this->global->dynSnippetId] = ob_get_flush(); ?>";

				if ($node->prefix) {
					$node->attrCode = $writer->write("<?php echo ' id=\"' . (\$this->global->dynSnippetId = \$this->global->uiControl->getSnippetId({$writer->formatWord($name)})) . '\"' ?>");
					return $writer->write('ob_start();');
				}
				$tag = trim($node->tokenizer->fetchWord(), '<>');
				$tag = $tag ? $tag : 'div';
				$node->closingCode .= "\n</$tag>";
				$this->checkExtraArgs($node);
				return $writer->write("?>\n<$tag id=\"<?php echo \$this->global->dynSnippetId = \$this->global->uiControl->getSnippetId({$writer->formatWord($name)}) ?>\"><?php ob_start();");

			} else {
				$node->data->leave = TRUE;
				$node->data->func = $this->generateMethodName($name);
				$fname = $writer->formatWord($name);
				$node->closingCode = '<?php ' . ($node->name === 'define' ? '' : "call_user_func(reset(\$this->blockQueue[$fname]), get_defined_vars());") . ' ?>';
				$blockType = var_export($this->exportBlockType($node), TRUE);
				$this->checkExtraArgs($node);
				return "\$this->checkBlockContentType($blockType, $fname);"
					. "\$this->blockQueue[$fname][] = [\$this, '{$node->data->func}'];";
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

		$this->namedBlocks[$name] = TRUE;
		$this->blockTypes[$name] = $this->exportBlockType($node);

		$include = 'call_user_func(reset($this->blockQueue[%var]), ' . (($node->name === 'snippet' || $node->name === 'snippetArea') ? '$this->params' : 'get_defined_vars()') . ')';
		if ($node->modifiers) {
			$include = "ob_start(function () {}); $include; \$_fi = new LR\\FilterInfo('html'); echo %modifyContent(ob_get_clean())";
		}

		if ($node->name === 'snippet') {
			if ($node->prefix) {
				if (isset($node->htmlNode->macroAttrs['foreach'])) {
					trigger_error('Combination of n:snippet with n:foreach is invalid, use n:inner-foreach.', E_USER_WARNING);
				}
				$node->attrCode = $writer->write('<?php echo \' id="\' . $this->global->uiControl->getSnippetId(%var) . \'"\' ?>', (string) substr($name, 1));
				return $writer->write($include, $name);
			}
			$tag = trim($node->tokenizer->fetchWord(), '<>');
			$tag = $tag ? $tag : 'div';
			$this->checkExtraArgs($node);
			return $writer->write("?>\n<$tag id=\"<?php echo \$this->global->uiControl->getSnippetId(%var) ?>\"><?php $include ?>\n</$tag><?php ",
				(string) substr($name, 1), $name
			);

		} elseif ($node->name === 'define') {
			$tokens = $node->tokenizer;
			$args = [];
			while ($tokens->isNext()) {
				$args[] = $tokens->expectNextValue(MacroTokens::T_VARIABLE);
				if ($tokens->isNext()) {
					$tokens->expectNextValue(',');
				}
			}
			if ($args) {
				$node->data->args = 'list(' . implode(', ', $args) . ') = $_args + [' . str_repeat('NULL, ', count($args)) . '];';
			}
			return;

		} else { // block, snippetArea
			$this->checkExtraArgs($node);
			return $writer->write($include, $name);
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
					$node->content = "<?php \$this->global->uiControl->snippetMode = isset(\$_snippetMode) && \$_snippetMode; ?>{$node->content}<?php \$this->global->uiControl->snippetMode = FALSE; ?>";
				}
				if (!empty($node->data->dynamic)) {
					$node->content .= '<?php if (isset($this->global->dynSnippets)) return $this->global->dynSnippets; ?>';
				}
				if ($node->name === 'snippetArea') {
					$node->content .= '<?php return FALSE; ?>';
				} elseif ($node->name === 'snippet') {
					$node->content = '<?php $this->global->uiControl->redrawControl(' . var_export((string) substr($node->data->name, 1), TRUE) . ", FALSE);\n\n?>" . $node->content;
				}
				if (preg_match('#\$|n:#', $node->content)) {
					$node->content = '<?php ' . (isset($node->data->args) ? $node->data->args : 'extract($_args);') . ' ?>' . $node->content;
				}
				$this->namedBlocks[$node->data->name] = $tmp = preg_replace('#^\n+|(?<=\n)[ \t]+\z#', '', $node->content);
				$node->content = substr_replace($node->content, $node->openingCode . "\n", strspn($node->content, "\n"), strlen($tmp));
				$node->openingCode = '<?php ?>';

			} elseif (isset($node->data->func)) {
				$node->content = rtrim($node->content, " \t");
				$this->getCompiler()->addMethod(
					$node->data->func,
					"extract(\$_args);\n?>$node->content<?php",
					'$_args'
				);
				$node->content = '';
			}

			if ($asInner) { // n:snippet -> n:inner-snippet
				$node->innerContent = $node->openingCode . $node->content . $node->closingCode;
				$node->closingCode = $node->openingCode = '<?php ?>';
			}
			return ' '; // consume next new line

		} elseif ($node->modifiers) { // anonymous block with modifier
			return $writer->write('$_fi = new LR\FilterInfo(%var); echo %modifyContent(ob_get_clean());', $node->context[0]);
		}
	}


	/**
	 * {ifset block}
	 * {elseifset block}
	 */
	public function macroIfset(MacroNode $node, PhpWriter $writer)
	{
		if ($node->modifiers) {
			throw new CompileException('Modifiers are not allowed in ' . $node->getNotation());
		}
		if (!preg_match('~#|[\w-]+\z~A', $node->args)) {
			return FALSE;
		}
		$list = [];
		while (($name = $node->tokenizer->fetchWord()) !== FALSE) {
			$list[] = preg_match('~#|[\w-]+\z~A', $name)
				? '$this->blockQueue["' . ltrim($name, '#') . '"]'
				: $writer->formatArgs(new Latte\MacroTokens($name));
		}
		return ($node->name === 'elseifset' ? '} else' : '')
			. 'if (isset(' . implode(', ', $list) . ')) {';
	}


	private function generateMethodName($blockName)
	{
		$clean = trim(preg_replace('#\W+#', '_', $blockName), '_');
		$name = 'block' . ucfirst($clean);
		$methods = array_keys($this->getCompiler()->getMethods());
		if (!$clean || in_array(strtolower($name), array_map('strtolower', $methods))) {
			$name .=  '_' . substr(md5($blockName), 0, 5);
		}
		return $name;
	}


	private function exportBlockType(MacroNode $node)
	{
		$context = $node->context;
		if (in_array($context[0], [Latte\Engine::CONTENT_HTML, Latte\Engine::CONTENT_XHTML, Latte\Engine::CONTENT_XML], TRUE) && $context[1] === 'attr') {
			$context[1] = '';
		}
		return implode($context);
	}

}
