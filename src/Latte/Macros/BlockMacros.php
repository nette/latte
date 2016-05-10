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

		$cmd = '';
		if (strpos($destination, '$') === FALSE) {
			$phpName = var_export($destination, TRUE);
		} else {
			$phpName = '$_tmp';
			$cmd .= "\$_tmp = $destination;";
		}

		$node->modifiers = preg_replace('#\|nocheck\s?(?=\||\z)#i', '', $node->modifiers, -1, $noCheck);
		if (!$noCheck) {
			$type = $this->exportBlockType($node);
			$cmd .= "if (isset(\$this->blockTypes[$phpName]) && \$this->blockTypes[$phpName] !== '$type') { "
				. "trigger_error('Including block " . addcslashes($destination, "'") . " with content type ' . strtoupper(\$this->blockTypes[$phpName]) . ' into incompatible type " . strtoupper($type) . ".', E_USER_WARNING); }\n";
		}

		if (isset($this->namedBlocks[$destination]) && !$parent) {
			$cmd .= "call_user_func(reset(\$this->blockQueue[$phpName]), %node.array? + get_defined_vars())";
		} else {
			$cmd .= '$this->renderBlock' . ($parent ? 'Parent' : '') . "($phpName, %node.array? + " . ($parent ? 'get_defined_vars()' : '$this->params') . ')'; //  + ["_b" => $_bl]
		}

		if ($node->modifiers) {
			return $writer->write("ob_start(function () {}); $cmd; echo %modify(ob_get_clean())");
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
			throw new CompileException("Modifiers are not allowed in {{$node->name}}");
		}
		return $writer->write(
			'ob_start(function () {}); $this->createTemplate(%node.word, %node.array? + get_defined_vars(), "includeblock")->renderToContentType(%var); echo rtrim(ob_get_clean())',
			$this->exportBlockType($node)
		);
	}


	/**
	 * {import "file"}
	 */
	public function macroImport(MacroNode $node, PhpWriter $writer)
	{
		if ($node->modifiers) {
			throw new CompileException("Modifiers are not allowed in {{$node->name}}");
		}
		return $writer->write('$this->createTemplate(%node.word, [], "import")->render()');
	}


	/**
	 * {extends none | $var | "file"}
	 */
	public function macroExtends(MacroNode $node, PhpWriter $writer)
	{
		if ($node->modifiers) {
			throw new CompileException("Modifiers are not allowed in {{$node->name}}");
		} elseif (!$node->args) {
			throw new CompileException("Missing destination in {{$node->name}}");
		} elseif ($node->parentNode) {
			throw new CompileException("{{$node->name}} must be placed outside any macro.");
		} elseif ($this->extends !== NULL) {
			throw new CompileException("Multiple {{$node->name}} declarations are not allowed.");
		} elseif ($node->args === 'none') {
			$this->extends = FALSE;
			return $writer->write('$this->parentName = NULL');
		} else {
			$this->extends = TRUE;
			return $writer->write('$this->parentName = %node.word%node.args');
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
				$node->closingCode = "<?php \$this->local->dynSnippets[\$this->local->dynSnippetId] = ob_get_flush() ?>";

				if ($node->prefix) {
					$node->attrCode = $writer->write("<?php echo ' id=\"' . (\$this->local->dynSnippetId = \$_control->getSnippetId({$writer->formatWord($name)})) . '\"' ?>");
					return $writer->write('ob_start()');
				}
				$tag = trim($node->tokenizer->fetchWord(), '<>');
				$tag = $tag ? $tag : 'div';
				$node->closingCode .= "\n</$tag>";
				return $writer->write("?>\n<$tag id=\"<?php echo \$this->local->dynSnippetId = \$_control->getSnippetId({$writer->formatWord($name)}) ?>\"><?php ob_start()");

			} else {
				$node->data->leave = TRUE;
				$node->data->func = $this->generateMethodName($name);
				$fname = $writer->formatWord($name);
				$node->closingCode = '<?php ' . ($node->name === 'define' ? '' : "call_user_func(reset(\$this->blockQueue[$fname]), get_defined_vars())") . ' ?>';
				$blockType = var_export($this->exportBlockType($node), TRUE);
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
			$include = "ob_start(function () {}); $include; echo %modify(ob_get_clean())";
		}

		if ($node->name === 'snippet') {
			if ($node->prefix) {
				$node->attrCode = $writer->write('<?php echo \' id="\' . $_control->getSnippetId(%var) . \'"\' ?>', (string) substr($name, 1));
				return $writer->write($include, $name);
			}
			$tag = trim($node->tokenizer->fetchWord(), '<>');
			$tag = $tag ? $tag : 'div';
			return $writer->write("?>\n<$tag id=\"<?php echo \$_control->getSnippetId(%var) ?>\"><?php $include ?>\n</$tag><?php ",
				(string) substr($name, 1), $name
			);

		} elseif ($node->name === 'define') {
			$tokens = $node->tokenizer;
			$args = [];
			while ($tokens->nextToken()) {
				if ($tokens->isCurrent(MacroTokens::T_VARIABLE)) {
					$args[] = $tokens->currentValue();
					if ($tokens->isNext(',')) {
						$tokens->nextToken();
					}
				} elseif (!$tokens->isCurrent(MacroTokens::T_WHITESPACE, MacroTokens::T_COMMENT)) {
					throw new CompileException("Unexpected '{$tokens->currentValue()}' in {define $node->args}");
				}
			}
			if ($args) {
				$node->data->args = 'list(' . implode(', ', $args) . ') = $_args + [' . str_repeat('NULL, ', count($args)) . '];';
			}
			return;

		} else { // block, snippetArea
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
					$node->content = "<?php \$_control->snippetMode = isset(\$_snippetMode) && \$_snippetMode; ?>{$node->content}<?php \$_control->snippetMode = FALSE; ?>";
				}
				if (!empty($node->data->dynamic)) {
					$node->content .= '<?php if (isset($this->local->dynSnippets)) return $this->local->dynSnippets; ?>';
				}
				if ($node->name === 'snippetArea') {
					$node->content .= '<?php return FALSE; ?>';
				} elseif ($node->name === 'snippet') {
					$node->content = '<?php $_control->redrawControl(' . var_export((string) substr($node->data->name, 1), TRUE) . ", FALSE);\n\n?>" . $node->content;
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
			return $writer->write('echo %modify(ob_get_clean())');
		}
	}


	/**
	 * {ifset block}
	 * {elseifset block}
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
		$compiler = $this->getCompiler();
		$type = $compiler->getContentType();
		if ($node->prefix === MacroNode::PREFIX_INNER && ($tag = strtolower($node->htmlNode->name)) === 'script') {
			$context = $compiler::CONTENT_JS;
		} elseif ($node->prefix === MacroNode::PREFIX_INNER && $tag === 'style') {
			$context = $compiler::CONTENT_CSS;
		} elseif ($node->prefix) {
			$context = '';
		} else {
			$context = $compiler->getContext();
			if (in_array($type, [$compiler::CONTENT_HTML, $compiler::CONTENT_XHTML, $compiler::CONTENT_XML], TRUE) && $context === ['attr', NULL]) {
				$context = '';
			}
		}
		return $type . implode((array) $context);
	}

}
