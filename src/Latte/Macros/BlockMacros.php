<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Macros;

use Latte;
use Latte\CompileException;
use Latte\Helpers;
use Latte\MacroNode;
use Latte\PhpHelpers;
use Latte\PhpWriter;
use Latte\Runtime\Block;
use Latte\Runtime\SnippetDriver;
use Latte\Runtime\Template;


/**
 * Block macros.
 */
class BlockMacros extends MacroSet
{
	/** @var string */
	public $snippetAttribute = 'id';

	/** @var Block[][] */
	private $blocks;

	/** @var int  current layer */
	private $index;

	/** @var string|bool|null */
	private $extends;

	/** @var string[] */
	private $imports;

	/** @var array[] */
	private $placeholders;


	public static function install(Latte\Compiler $compiler): void
	{
		$me = new static($compiler);
		$me->addMacro('include', [$me, 'macroInclude']);
		$me->addMacro('includeblock', [$me, 'macroIncludeBlock']); // deprecated
		$me->addMacro('import', [$me, 'macroImport'], null, null, self::ALLOWED_IN_HEAD);
		$me->addMacro('extends', [$me, 'macroExtends'], null, null, self::ALLOWED_IN_HEAD);
		$me->addMacro('layout', [$me, 'macroExtends'], null, null, self::ALLOWED_IN_HEAD);
		$me->addMacro('snippet', [$me, 'macroSnippet'], [$me, 'macroBlockEnd']); // must be before block
		$me->addMacro('block', [$me, 'macroBlock'], [$me, 'macroBlockEnd'], null, self::AUTO_CLOSE);
		$me->addMacro('define', [$me, 'macroDefine'], [$me, 'macroBlockEnd']);
		$me->addMacro('embed', [$me, 'macroEmbed'], [$me, 'macroEmbedEnd']);
		$me->addMacro('snippetArea', [$me, 'macroSnippetArea'], [$me, 'macroBlockEnd']);
		$me->addMacro('ifset', [$me, 'macroIfset'], '}');
		$me->addMacro('elseifset', [$me, 'macroIfset']);
	}


	/**
	 * Initializes before template parsing.
	 * @return void
	 */
	public function initialize()
	{
		$this->blocks = [[]];
		$this->index = Template::LAYER_TOP;
		$this->extends = null;
		$this->imports = [];
		$this->placeholders = [];
	}


	/**
	 * Finishes template parsing.
	 */
	public function finalize()
	{
		$compiler = $this->getCompiler();
		foreach ($this->placeholders as $key => [$index, $blockName]) {
			$block = $this->blocks[$index][$blockName] ?? $this->blocks[Template::LAYER_LOCAL][$blockName] ?? null;
			$compiler->placeholders[$key] = $block && !$block->hasParameters
				? 'get_defined_vars()'
				: '[]';
		}

		$meta = [];
		foreach ($this->blocks as $layer => $blocks) {
			foreach ($blocks as $name => $block) {
				$compiler->addMethod(
					$method = $this->generateMethodName($name),
					'?>' . $compiler->expandTokens($block->code) . '<?php',
					'array $ʟ_args',
					'void'
				);
				$meta[$layer][$name] = $block->contentType === $compiler->getContentType()
					? $method
					: [$method, $block->contentType];
			}
		}

		if ($meta) {
			$compiler->addConstant('BLOCKS', $meta);
		}

		return [
			($this->extends === null ? '' : '$this->parentName = ' . $this->extends . ';') . implode($this->imports),
		];
	}


	/********************* macros ****************d*g**/


	/**
	 * {include [block] name [,] [params]}
	 * @return string|false
	 */
	public function macroInclude(MacroNode $node, PhpWriter $writer)
	{
		$node->validate(true, [], true);
		$node->replaced = false;

		$tmp = $node->tokenizer->joinUntil('=');
		if ($node->tokenizer->isNext('=')) {
			trigger_error('The assignment in the {' . $node->name . ' ' . $tmp . '= ...} looks like an error.', E_USER_NOTICE);
		}
		$node->tokenizer->reset();

		[$name, $mod] = $node->tokenizer->fetchWordWithModifier(['block', 'file']);
		if ($mod !== 'block') {
			if ($mod === 'file' || !$name || !preg_match('~#|[\w-]+$~DA', $name)) {
				return false; // {include file}
			}
			$name = ltrim($name, '#');
		}

		$noEscape = Helpers::removeFilter($node->modifiers, 'noescape');
		if (!$noEscape && Helpers::removeFilter($node->modifiers, 'escape')) {
			trigger_error('Tag ' . $node->getNotation() . ' provides auto-escaping, remove |escape.');
		}
		if ($node->modifiers && !$noEscape) {
			$node->modifiers .= '|escape';
		}

		if ($node->tokenizer->nextToken('from')) {
			$node->tokenizer->nextToken($node->tokenizer::T_WHITESPACE);
			return $writer->write(
				'$this->createTemplate(%node.word, %node.array? + $this->params, "include")->renderToContentType(%raw, %word);',
				$node->modifiers
					? $writer->write('function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }')
					: PhpHelpers::dump($noEscape ? null : implode($node->context)),
				$name
			);
		}

		$parent = $name === 'parent';
		if ($name === 'parent' || $name === 'this') {
			$item = $node->closest(['block', 'define'], function ($node) { return isset($node->data->name); });
			if (!$item) {
				throw new CompileException("Cannot include $name block outside of any block.");
			}
			$name = $item->data->name;
		}

		$key = uniqid() . '$iterator'; // to fool CoreMacros::macroEndForeach
		$this->placeholders[$key] = [$this->index, $name];
		$phpName = $this->isDynamic($name)
			? $writer->formatWord($name)
			: PhpHelpers::dump($name);

		return $writer->write(
			'$this->renderBlock' . ($parent ? 'Parent' : '')
			. '($ʟ_nm = ' . $phpName . ', '
			. '%node.array? + ' . $key
			. ($node->modifiers
				? ', function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }'
				: ($noEscape || $parent ? '' : ', ' . PhpHelpers::dump(implode($node->context))))
			. ');'
		);
	}


	/**
	 * {includeblock "file"}
	 * @deprecated
	 */
	public function macroIncludeBlock(MacroNode $node, PhpWriter $writer): string
	{
		//trigger_error('Macro {includeblock} is deprecated, use {include 'file.latte' with blocks} or similar macro {import}.', E_USER_DEPRECATED);
		$node->replaced = false;
		$node->validate(true);
		return $writer->write(
			'ob_start(function () {});
			$this->createTemplate(%node.word, %node.array? + get_defined_vars(), "includeblock")->renderToContentType(%var);
			echo rtrim(ob_get_clean());',
			implode($node->context)
		);
	}


	/**
	 * {import "file"}
	 */
	public function macroImport(MacroNode $node, PhpWriter $writer): string
	{
		$node->validate(true);
		$file = $node->tokenizer->fetchWord();
		$this->checkExtraArgs($node);
		$code = $writer->write('$this->createTemplate(%word, $this->params, "import")->render();', $file);
		if ($this->getCompiler()->isInHead()) {
			$this->imports[] = $code;
			return '';
		} elseif ($node->parentNode && $node->parentNode->name === 'embed') {
			return "} $code if (false) {";
		} else {
			return $code;
		}
	}


	/**
	 * {extends none | $var | "file"}
	 */
	public function macroExtends(MacroNode $node, PhpWriter $writer): void
	{
		$node->validate(true);
		if ($node->parentNode) {
			throw new CompileException($node->getNotation() . ' must not be inside other tags.');
		} elseif ($this->extends !== null) {
			throw new CompileException('Multiple ' . $node->getNotation() . ' declarations are not allowed.');
		} elseif ($node->args === 'none') {
			$this->extends = 'false';
		} else {
			$this->extends = $writer->write('%node.word%node.args');
		}
		if (!$this->getCompiler()->isInHead()) {
			trigger_error($node->getNotation() . ' must be placed in template head.', E_USER_WARNING);
		}
	}


	/**
	 * {block [local] [name]}
	 */
	public function macroBlock(MacroNode $node, PhpWriter $writer): string
	{
		[$name, $local] = $node->tokenizer->fetchWordWithModifier('local');
		$layer = $local ? Template::LAYER_LOCAL : null;
		$data = $node->data;
		$data->name = ltrim((string) $name, '#');
		$this->checkExtraArgs($node);

		if ($data->name === '') {
			if ($node->modifiers === '') {
				return '';
			}
			$node->modifiers .= '|escape';
			$node->closingCode = $writer->write(
				'<?php $ʟ_fi = new LR\FilterInfo(%var); echo %modifyContent(ob_get_clean()); ?>',
				$node->context[0]
			);
			return 'ob_start(function () {});';
		}

		if (Helpers::removeFilter($node->modifiers, 'escape')) {
			trigger_error('Tag ' . $node->getNotation() . ' provides auto-escaping, remove |escape.');
		}
		if (Helpers::startsWith((string) $node->context[1], Latte\Compiler::CONTEXT_HTML_ATTRIBUTE)) {
			$node->context[1] = '';
			$node->modifiers .= '|escape';
		} elseif ($node->modifiers) {
			$node->modifiers .= '|escape';
		}

		$renderArgs = $writer->write(
			'get_defined_vars()'
			. ($node->modifiers ? ', function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }' : '')
		);

		if ($this->isDynamic($data->name)) {
			$node->closingCode = $writer->write('<?php $this->renderBlock($ʟ_nm, %raw); ?>', $renderArgs);
			return $this->beginDynamicBlockOrDefine($node, $writer, $layer);
		}

		if (!preg_match('#^[a-z]#iD', $data->name)) {
			throw new CompileException("Block name must start with letter a-z, '$data->name' given.");
		}

		$extendsCheck = $this->blocks[Template::LAYER_TOP] || count($this->blocks) > 1 || $node->parentNode;
		$block = $this->addBlock($node, $layer);

		$data->after = function () use ($node, $block) {
			$this->extractMethod($node, $block);
		};

		return $writer->write(
			($extendsCheck ? '' : 'if ($this->getParentName()) { return get_defined_vars();} ')
			. '$this->renderBlock(%var, %raw)',
			$data->name,
			$renderArgs
		);
	}


	/**
	 * {define [local] name}
	 */
	public function macroDefine(MacroNode $node, PhpWriter $writer): string
	{
		if ($node->modifiers) { // modifier may be union|type
			$node->setArgs($node->args . $node->modifiers);
			$node->modifiers = '';
		}
		$node->validate(true);

		[$name, $local] = $node->tokenizer->fetchWordWithModifier('local');
		$layer = $local ? Template::LAYER_LOCAL : null;
		$data = $node->data;
		$data->name = ltrim((string) $name, '#');

		if ($this->isDynamic($data->name)) {
			$node->closingCode = '<?php ?>';
			return $this->beginDynamicBlockOrDefine($node, $writer, $layer);
		}

		if (!preg_match('#^[a-z]#iD', $data->name)) {
			throw new CompileException("Block name must start with letter a-z, '$data->name' given.");
		}

		$tokens = $node->tokenizer;
		$params = [];
		while ($tokens->isNext()) {
			if ($tokens->nextToken($tokens::T_SYMBOL, '?', 'null', '\\')) { // type
				$tokens->nextAll($tokens::T_SYMBOL, '\\', '|', '[', ']', 'null');
			}
			$param = $tokens->consumeValue($tokens::T_VARIABLE);
			$default = $tokens->nextToken('=')
				? $tokens->joinUntilSameDepth(',')
				: 'null';
			$params[] = $writer->write(
				'%raw = $ʟ_args[%var] ?? $ʟ_args[%var] ?? %raw;',
				$param,
				count($params),
				substr($param, 1),
				$default
			);
			if ($tokens->isNext()) {
				$tokens->consumeValue(',');
			}
		}

		$extendsCheck = $this->blocks[Template::LAYER_TOP] || count($this->blocks) > 1 || $node->parentNode;
		$block = $this->addBlock($node, $layer);
		$block->hasParameters = (bool) $params;

		$data->after = function () use ($node, $block, $params) {
			$params = $params ? implode('', $params) : null;
			$this->extractMethod($node, $block, $params);
		};

		return $extendsCheck
			? ''
			: 'if ($this->getParentName()) { return get_defined_vars();} ';
	}


	private function beginDynamicBlockOrDefine(MacroNode $node, PhpWriter $writer, ?string $layer): string
	{
		$this->checkExtraArgs($node);
		$data = $node->data;
		$func = $this->generateMethodName($data->name);

		$data->after = function () use ($node, $func) {
			$node->content = rtrim($node->content, " \t");
			$this->getCompiler()->addMethod(
				$func,
				$this->getCompiler()->expandTokens("extract(\$ʟ_args);\n?>{$node->content}<?php"),
				'array $ʟ_args',
				'void'
			);
			$node->content = '';
		};

		return $writer->write(
			'$this->addBlock($ʟ_nm = %word, %var, [[$this, %var]], %var);',
			$data->name,
			implode($node->context),
			$func,
			$layer
		);
	}


	/**
	 * {snippet [name]}
	 */
	public function macroSnippet(MacroNode $node, PhpWriter $writer): string
	{
		$node->validate(null);
		$data = $node->data;
		$data->name = (string) $node->tokenizer->fetchWord();
		$this->checkExtraArgs($node);

		if ($node->prefix && isset($node->htmlNode->attrs[$this->snippetAttribute])) {
			throw new CompileException("Cannot combine HTML attribute {$this->snippetAttribute} with n:snippet.");

		} elseif ($node->prefix && isset($node->htmlNode->macroAttrs['ifcontent'])) {
			throw new CompileException('Cannot combine n:ifcontent with n:snippet.');

		} elseif ($this->isDynamic($data->name)) {
			return $this->beginDynamicSnippet($node, $writer);

		} elseif ($data->name !== '' && !preg_match('#^[a-z]#iD', $data->name)) {
			throw new CompileException("Snippet name must start with letter a-z, '$data->name' given.");
		}

		$block = $this->addBlock($node, Template::LAYER_SNIPPET);

		$data->after = function () use ($node, $writer, $data, $block) {
			if ($node->prefix === MacroNode::PREFIX_NONE) { // n:snippet -> n:inner-snippet
				$node->content = $node->innerContent;
			}

			$node->content = $writer->write(
				'<?php $this->global->snippetDriver->enter(%word, %var);
				try { ?>%raw<?php } finally { $this->global->snippetDriver->leave(); } ?>',
				$data->name,
				SnippetDriver::TYPE_STATIC,
				preg_replace('#(?<=\n)[ \t]+$#D', '', $node->content)
			);

			$this->extractMethod($node, $block);

			if ($node->prefix === MacroNode::PREFIX_NONE) {
				$node->innerContent = $node->openingCode . $node->content . $node->closingCode;
				$node->closingCode = $node->openingCode = '<?php ?>';
			}
		};

		if ($node->prefix) {
			if (isset($node->htmlNode->macroAttrs['foreach'])) {
				trigger_error('Combination of n:snippet with n:foreach is invalid, use n:inner-foreach.', E_USER_WARNING);
			}
			$node->attrCode = $writer->write(
				"<?php echo ' {$this->snippetAttribute}=\"' . htmlspecialchars(\$this->global->snippetDriver->getHtmlId(%var)) . '\"' ?>",
				$data->name
			);
			return $writer->write('$this->renderBlock(%var, [], null, %var)', $data->name, Template::LAYER_SNIPPET);
		}

		return $writer->write(
			"?>\n<div {$this->snippetAttribute}=\"<?php echo htmlspecialchars(\$this->global->snippetDriver->getHtmlId(%0_var)) ?>\">"
			. '<?php $this->renderBlock(%0_var, [], null, %1_var) ?>'
			. "\n</div><?php ",
			$data->name,
			Template::LAYER_SNIPPET
		);
	}


	private function beginDynamicSnippet(MacroNode $node, PhpWriter $writer): string
	{
		$data = $node->data;
		$node->closingCode = '<?php } finally { $this->global->snippetDriver->leave(); } ?>';

		if ($node->prefix) {
			if ($node->prefix === MacroNode::PREFIX_NONE) { // n:snippet -> n:inner-snippet
				$data->after = function () use ($node) {
					$node->innerContent = $node->openingCode . $node->innerContent . $node->closingCode;
					$node->closingCode = $node->openingCode = '<?php ?>';
				};
			}
			$node->attrCode = $writer->write(
				"<?php echo ' {$this->snippetAttribute}=\"' . htmlspecialchars(\$this->global->snippetDriver->getHtmlId(\$ʟ_nm = %word)) . '\"' ?>",
				$data->name
			);
			return $writer->write('$this->global->snippetDriver->enter($ʟ_nm, %var); try {', SnippetDriver::TYPE_DYNAMIC);
		}

		$node->closingCode .= "\n</div>";
		return $writer->write(
			"?>\n<div {$this->snippetAttribute}=\""
			. '<?php echo htmlspecialchars($this->global->snippetDriver->getHtmlId($ʟ_nm = %word)) ?>"'
			. '><?php $this->global->snippetDriver->enter($ʟ_nm, %var); try {',
			$data->name,
			SnippetDriver::TYPE_DYNAMIC
		);
	}


	/**
	 * {snippetArea [name]}
	 */
	public function macroSnippetArea(MacroNode $node, PhpWriter $writer): string
	{
		$node->validate(null);
		$data = $node->data;
		$data->name = (string) $node->tokenizer->fetchWord();
		$this->checkExtraArgs($node);

		$block = $this->addBlock($node, Template::LAYER_SNIPPET);

		$data->after = function () use ($node, $writer, $data, $block) {
			$node->content = $writer->write(
				'<?php $this->global->snippetDriver->enter(%var, %var);
				try { ?>%raw<?php } finally { $this->global->snippetDriver->leave(); } ?>',
				$data->name,
				SnippetDriver::TYPE_AREA,
				preg_replace('#(?<=\n)[ \t]+$#D', '', $node->content)
			);
			$this->extractMethod($node, $block);
		};
		return $writer->write('$this->renderBlock(%var, [], null, %var)', $data->name, Template::LAYER_SNIPPET);
	}


	/**
	 * {/block}
	 * {/define}
	 * {/snippet}
	 * {/snippetArea}
	 */
	public function macroBlockEnd(MacroNode $node, PhpWriter $writer): string
	{
		if (isset($node->data->after)) {
			($node->data->after)();
		}
		return $node->name === 'define'
			? ' ' // consume next new line
			: '';
	}


	private function addBlock(MacroNode $node, string $layer = null): Block
	{
		$data = $node->data;
		if ($layer === Template::LAYER_SNIPPET
			? isset($this->blocks[$layer][$data->name])
			: (isset($this->blocks[Template::LAYER_LOCAL][$data->name]) || isset($this->blocks[$this->index][$data->name]))
		) {
			throw new CompileException("Cannot redeclare {$node->name} '{$data->name}'");
		}

		$block = $this->blocks[$layer ?? $this->index][$data->name] = new Block;
		$block->contentType = implode($node->context);
		return $block;
	}


	private function extractMethod(MacroNode $node, Block $block, string $params = null): void
	{
		if (preg_match('#\$|n:#', $node->content)) {
			$node->content = '<?php extract($this->params);' . ($params ?? 'extract($ʟ_args);') . '?>' . $node->content;
		}
		$block->code = preg_replace('#^\n+|(?<=\n)[ \t]+$#D', '', $node->content);
		$node->content = substr_replace($node->content, $node->openingCode . "\n", strspn($node->content, "\n"), strlen($block->code));
		$node->openingCode = '<?php ?>';
	}


	/**
	 * {embed "file"}
	 */
	public function macroEmbed(MacroNode $node, PhpWriter $writer): string
	{
		$node->validate(true);
		$node->replaced = true;
		$node->data->prevIndex = $this->index;
		$this->index = count($this->blocks);
		$this->blocks[$this->index] = [];

		return $writer->write(
			'$this->initBlockLayer(%0_var);
			$this->setBlockLayer(%0_var);
			if (false) {',
			$this->index
		);
	}


	/**
	 * {/embed}
	 */
	public function macroEmbedEnd(MacroNode $node, PhpWriter $writer): string
	{
		$this->index = $node->data->prevIndex;
		return $writer->write(
			'}
			try { $this->createTemplate(%node.word, %node.array, "embed")->renderToContentType(%var); }
			finally { $this->setBlockLayer(%var); }',
			implode($node->context),
			$this->index
		);
	}


	/**
	 * {ifset block}
	 * {elseifset block}
	 * @return string|false
	 */
	public function macroIfset(MacroNode $node, PhpWriter $writer)
	{
		$node->validate(true);
		if (!preg_match('~(#|block\s)|[\w-]+$~DA', $node->args)) {
			return false;
		}
		$list = [];
		while ([$name, $block] = $node->tokenizer->fetchWordWithModifier('block')) {
			$list[] = $block || preg_match('~#|[\w-]+$~DA', $name)
				? '$this->hasBlock(' . $writer->formatWord(ltrim($name, '#')) . ')'
				: 'isset(' . $writer->formatArgs(new Latte\MacroTokens($name)) . ')';
		}
		return ($node->name === 'elseifset' ? '} else' : '')
			. 'if (' . implode(' && ', $list) . ') {';
	}


	private function generateMethodName(string $blockName): string
	{
		$name = 'block' . ucfirst(trim(preg_replace('#\W+#', '_', $blockName), '_'));
		$lower = strtolower($name);
		$methods = array_change_key_case($this->getCompiler()->getMethods()) + ['block' => 1];
		$counter = null;
		while (isset($methods[$lower . $counter])) {
			$counter++;
		}
		return $name . $counter;
	}


	private function isDynamic(string $name): bool
	{
		return strpos($name, '$') !== false || strpos($name, ' ') !== false;
	}
}
