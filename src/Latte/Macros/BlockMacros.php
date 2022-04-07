<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Macros;

use Latte;
use Latte\CompileException;
use Latte\Compiler\Block;
use Latte\Compiler\PhpHelpers;
use Latte\Compiler\PhpWriter;
use Latte\Compiler\Tag;
use Latte\Helpers;
use Latte\Runtime\SnippetDriver;
use Latte\Runtime\Template;


/**
 * Block macros.
 */
class BlockMacros extends MacroSet
{
	public string $snippetAttribute = 'id';

	/** @var Block[][] */
	private array $blocks;

	/** current layer */
	private int $index;

	private string|bool|null $extends = null;

	/** @var string[] */
	private array $imports;

	/** @var array[] */
	private array $placeholders;


	public static function install(Latte\Compiler\TemplateGenerator $compiler): void
	{
		$me = new static($compiler);
		$me->addMacro('include', [$me, 'macroInclude']);
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


	public function beforeCompile(): void
	{
		$this->blocks = [[]];
		$this->index = Template::LayerTop;
		$this->extends = null;
		$this->imports = [];
		$this->placeholders = [];
	}


	public function finalize()
	{
		$compiler = $this->getCompiler();
		foreach ($this->placeholders as $key => [$index, $blockName]) {
			$block = $this->blocks[$index][$blockName] ?? $this->blocks[Template::LayerLocal][$blockName] ?? null;
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
					'void',
					$block->comment,
				);
				$meta[$layer][$name] = $block->contentType === $compiler->getContentType()
					? $method
					: [$method, $block->contentType];
			}
		}

		if ($meta) {
			$compiler->addConstant('Blocks', $meta);
		}

		return [
			($this->extends === null ? '' : '$this->parentName = ' . $this->extends . ';') . implode('', $this->imports),
		];
	}


	/********************* macros ****************d*g**/


	/**
	 * {include [block] name [,] [params]}
	 */
	public function macroInclude(Tag $node, PhpWriter $writer): string|false
	{
		$node->validate(true, [], true);
		$node->replaced = false;

		$tmp = $node->tokenizer->joinUntil('=');
		if ($node->tokenizer->isNext('=') && !$node->tokenizer->depth) {
			trigger_error('The assignment in the {' . $node->name . ' ' . $tmp . '= ...} looks like an error.', E_USER_NOTICE);
		}

		$node->tokenizer->reset();

		[$name, $mod] = $node->tokenizer->fetchWordWithModifier(['block', 'file', '#']);
		if (!$mod && preg_match('~([\'"])[\w-]+\\1$~DA', $name)) {
			trigger_error("Change {include $name} to {include file $name} for clarity (on line $node->startLine)", E_USER_NOTICE);
		}
		if ($mod !== 'block' && $mod !== '#'
			&& ($mod === 'file' || !$name || !preg_match('~[\w-]+$~DA', $name))
		) {
			return false; // {include file}
		}

		if ($name === 'parent' && $node->modifiers !== '') {
			throw new CompileException('Filters are not allowed in {include parent}');
		}

		$noEscape = Helpers::removeFilter($node->modifiers, 'noescape');
		if ($node->modifiers && !$noEscape) {
			$node->modifiers .= '|escape';
		}

		if ($node->tokenizer->nextToken('from')) {
			$node->tokenizer->nextToken($node->tokenizer::T_WHITESPACE);
			return $writer->write(
				'$this->createTemplate(%node.word, %node.array? + $this->params, "include")->renderToContentType(%raw, %word) %node.line;',
				$node->modifiers
					? $writer->write('function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }')
					: PhpHelpers::dump($noEscape ? null : implode('', $node->context)),
				$name,
			);
		}

		$parent = $name === 'parent';
		if ($name === 'parent' || $name === 'this') {
			$item = $node->closest(['block', 'define'], fn($node) => $node->data->name !== '');
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
			. '(' . $phpName . ', '
			. '%node.array? + ' . $key
			. ($node->modifiers
				? ', function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }'
				: ($noEscape || $parent ? '' : ', ' . PhpHelpers::dump(implode('', $node->context))))
			. ') %node.line;',
		);
	}


	/**
	 * {import "file"}
	 */
	public function macroImport(Tag $node, PhpWriter $writer): string
	{
		$node->validate(true);
		$file = $node->tokenizer->fetchWord();
		$this->checkExtraArgs($node);
		$code = $writer->write('$this->createTemplate(%word, $this->params, "import")->render() %node.line;', $file);
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
	public function macroExtends(Tag $node, PhpWriter $writer): void
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
			throw new CompileException($node->getNotation() . ' must be placed in template head.');
		}
	}


	/**
	 * {block [local] [name]}
	 */
	public function macroBlock(Tag $node, PhpWriter $writer): string
	{
		[$name, $local] = $node->tokenizer->fetchWordWithModifier('local');
		$layer = $local ? Template::LayerLocal : null;
		$data = $node->data;
		$data->name = ltrim((string) $name, '#');
		$this->checkExtraArgs($node);

		if ($data->name === '') {
			if ($node->modifiers === '') {
				return '';
			}

			$node->modifiers .= '|escape';
			$node->closingCode = $writer->write(
				'<?php } finally { $ʟ_fi = new LR\FilterInfo(%var); echo %modifyContent(ob_get_clean()); } ?>',
				implode('', $node->context),
			);
			return $writer->write("ob_start(fn() => '') %node.line; try {");
		}

		if (str_starts_with((string) $node->context[1], Latte\Compiler\Escaper::HtmlAttribute)) {
			$node->context[1] = '';
			$node->modifiers .= '|escape';
		} elseif ($node->modifiers) {
			$node->modifiers .= '|escape';
		}

		$renderArgs = $writer->write(
			'get_defined_vars()'
			. ($node->modifiers ? ', function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }' : ''),
		);

		if ($this->isDynamic($data->name)) {
			$node->closingCode = $writer->write('<?php $this->renderBlock($ʟ_nm, %raw); ?>', $renderArgs);
			return $this->beginDynamicBlockOrDefine($node, $writer, $layer);
		}

		if (!preg_match('#^[a-z]#iD', $data->name)) {
			throw new CompileException("Block name must start with letter a-z, '$data->name' given.");
		}

		$extendsCheck = $this->blocks[Template::LayerTop] || count($this->blocks) > 1 || $node->parentNode;
		$block = $this->addBlock($node, $layer);

		$data->after = function () use ($node, $block) {
			$this->extractMethod($node, $block);
		};

		return $writer->write(
			($extendsCheck ? '' : 'if ($this->getParentName()) { return get_defined_vars(); } ')
			. '$this->renderBlock(%var, %raw) %node.line;',
			$data->name,
			$renderArgs,
		);
	}


	/**
	 * {define [local] name}
	 */
	public function macroDefine(Tag $node, PhpWriter $writer): string
	{
		if ($node->modifiers) { // modifier may be union|type
			$node->setArgs($node->args . $node->modifiers);
			$node->modifiers = '';
		}

		$node->validate(true);

		[$name, $local] = $node->tokenizer->fetchWordWithModifier('local');
		$layer = $local ? Template::LayerLocal : null;
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
		while ($tokens->isNext(...$tokens::SIGNIFICANT)) {
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
				$default,
			);
			if ($tokens->isNext(...$tokens::SIGNIFICANT)) {
				$tokens->consumeValue(',');
			}
		}

		$extendsCheck = $this->blocks[Template::LayerTop] || count($this->blocks) > 1 || $node->parentNode;
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


	private function beginDynamicBlockOrDefine(Tag $node, PhpWriter $writer, ?string $layer): string
	{
		$this->checkExtraArgs($node);
		$data = $node->data;
		$func = $this->generateMethodName($data->name);

		$data->after = function () use ($node, $func) {
			$node->content = rtrim($node->content, " \t");
			$this->getCompiler()->addMethod(
				$func,
				$this->getCompiler()->expandTokens("extract(\$ʟ_args); unset(\$ʟ_args);\n?>{$node->content}<?php"),
				'array $ʟ_args',
				'void',
				"{{$node->name} {$node->args}} on line {$node->startLine}",
			);
			$node->content = '';
		};

		return $writer->write(
			'$this->addBlock($ʟ_nm = %word, %var, [[$this, %var]], %var);',
			$data->name,
			implode('', $node->context),
			$func,
			$layer,
		);
	}


	/**
	 * {snippet [name]}
	 */
	public function macroSnippet(Tag $node, PhpWriter $writer): string
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

		if ($node->prefix && $node->prefix !== $node::PrefixNone) {
			trigger_error("Use n:snippet instead of {$node->getNotation()}", E_USER_DEPRECATED);
		}

		$block = $this->addBlock($node, Template::LayerSnippet);

		$data->after = function () use ($node, $writer, $data, $block) {
			if ($node->prefix === Tag::PrefixNone) { // n:snippet -> n:inner-snippet
				$node->content = $node->innerContent;
			}

			$node->content = $writer->write(
				'<?php $this->global->snippetDriver->enter(%word, %var);
				try { ?>%raw<?php } finally { $this->global->snippetDriver->leave(); } ?>',
				$data->name,
				SnippetDriver::TypeStatic,
				preg_replace('#(?<=\n)[ \t]+$#D', '', $node->content),
			);

			$this->extractMethod($node, $block);

			if ($node->prefix === Tag::PrefixNone) {
				$node->innerContent = $node->openingCode . $node->content . $node->closingCode;
				$node->closingCode = $node->openingCode = '<?php ?>';
			}
		};

		if ($node->prefix) {
			if (isset($node->htmlNode->macroAttrs['foreach'])) {
				throw new CompileException('Combination of n:snippet with n:foreach is invalid, use n:inner-foreach.');
			}

			$node->attrCode = $writer->write(
				"<?php echo ' {$this->snippetAttribute}=\"' . htmlspecialchars(\$this->global->snippetDriver->getHtmlId(%var)) . '\"' ?>",
				$data->name,
			);
			return $writer->write('$this->renderBlock(%var, [], null, %var)', $data->name, Template::LayerSnippet);
		}

		return $writer->write(
			"?>\n<div {$this->snippetAttribute}=\"<?php echo htmlspecialchars(\$this->global->snippetDriver->getHtmlId(%0_var)) ?>\">"
			. '<?php $this->renderBlock(%0_var, [], null, %1_var) %node.line; ?>'
			. "\n</div><?php ",
			$data->name,
			Template::LayerSnippet,
		);
	}


	private function beginDynamicSnippet(Tag $node, PhpWriter $writer): string
	{
		$data = $node->data;
		$node->closingCode = '<?php } finally { $this->global->snippetDriver->leave(); } ?>';

		if ($node->prefix) {
			if ($node->prefix === Tag::PrefixNone) { // n:snippet -> n:inner-snippet
				$data->after = function () use ($node) {
					$node->innerContent = $node->openingCode . $node->innerContent . $node->closingCode;
					$node->closingCode = $node->openingCode = '<?php ?>';
				};
			}

			$node->attrCode = $writer->write(
				"<?php echo ' {$this->snippetAttribute}=\"' . htmlspecialchars(\$this->global->snippetDriver->getHtmlId(\$ʟ_nm = %word)) . '\"' ?>",
				$data->name,
			);
			return $writer->write('$this->global->snippetDriver->enter($ʟ_nm, %var) %node.line; try {', SnippetDriver::TypeDynamic);
		}

		$node->closingCode .= "\n</div>";
		return $writer->write(
			"?>\n<div {$this->snippetAttribute}=\""
			. '<?php echo htmlspecialchars($this->global->snippetDriver->getHtmlId($ʟ_nm = %word)) ?>"'
			. '><?php $this->global->snippetDriver->enter($ʟ_nm, %var) %node.line; try {',
			$data->name,
			SnippetDriver::TypeDynamic,
		);
	}


	/**
	 * {snippetArea [name]}
	 */
	public function macroSnippetArea(Tag $node, PhpWriter $writer): string
	{
		$node->validate(null);
		$data = $node->data;
		$data->name = (string) $node->tokenizer->fetchWord();
		$this->checkExtraArgs($node);

		$block = $this->addBlock($node, Template::LayerSnippet);

		$data->after = function () use ($node, $writer, $data, $block) {
			$node->content = $writer->write(
				'<?php $this->global->snippetDriver->enter(%var, %var);
				try { ?>%raw<?php } finally { $this->global->snippetDriver->leave(); } ?>',
				$data->name,
				SnippetDriver::TypeArea,
				preg_replace('#(?<=\n)[ \t]+$#D', '', $node->content),
			);
			$this->extractMethod($node, $block);
		};
		return $writer->write('$this->renderBlock(%var, [], null, %var) %node.line;', $data->name, Template::LayerSnippet);
	}


	/**
	 * {/block}
	 * {/define}
	 * {/snippet}
	 * {/snippetArea}
	 */
	public function macroBlockEnd(Tag $node, PhpWriter $writer): string
	{
		if (isset($node->data->after)) {
			($node->data->after)();
		}

		return $node->name === 'define'
			? ' ' // consume next new line
			: '';
	}


	private function addBlock(Tag $node, ?string $layer = null): Block
	{
		$data = $node->data;
		if ($layer === Template::LayerSnippet
			? isset($this->blocks[$layer][$data->name])
			: (isset($this->blocks[Template::LayerLocal][$data->name]) || isset($this->blocks[$this->index][$data->name]))
		) {
			throw new CompileException("Cannot redeclare {$node->name} '{$data->name}'");
		}

		$block = $this->blocks[$layer ?? $this->index][$data->name] = new Block;
		$block->contentType = implode('', $node->context);
		$block->comment = "{{$node->name} {$node->args}} on line {$node->startLine}";
		return $block;
	}


	private function extractMethod(Tag $node, Block $block, ?string $params = null): void
	{
		if (preg_match('#\$|n:#', $node->content)) {
			$node->content = '<?php extract(' . ($node->name === 'block' && $node->closest(['embed']) ? 'end($this->varStack)' : '$this->params') . ');'
				. ($params ?? 'extract($ʟ_args);')
				. 'unset($ʟ_args);?>'
				. $node->content;
		}

		$block->code = preg_replace('#^\n+|(?<=\n)[ \t]+$#D', '', $node->content);
		$node->content = substr_replace($node->content, $node->openingCode . "\n", strspn($node->content, "\n"), strlen($block->code));
		$node->openingCode = '<?php ?>';
	}


	/**
	 * {embed [block|file] name [,] [params]}
	 */
	public function macroEmbed(Tag $node, PhpWriter $writer): void
	{
		$node->validate(true);
		$node->replaced = false;
		$node->data->prevIndex = $this->index;
		$this->index = count($this->blocks);
		$this->blocks[$this->index] = [];

		[$name, $mod] = $node->tokenizer->fetchWordWithModifier(['block', 'file']);
		if (!$mod && preg_match('~([\'"])[\w-]+\\1$~DA', $name)) {
			trigger_error("Change {embed $name} to {embed file $name} for clarity (on line $node->startLine)", E_USER_NOTICE);
		}
		$mod ??= (preg_match('~^[\w-]+$~DA', $name) ? 'block' : 'file');

		$node->openingCode = $writer->write(
			'<?php
			$this->enterBlockLayer(%0_var, get_defined_vars()) %node.line;
			if (false) { ?>',
			$this->index,
		);

		if ($mod === 'file') {
			$node->closingCode = $writer->write(
				'<?php }
				try { $this->createTemplate(%word, %node.array, "embed")->renderToContentType(%var) %node.line; }
				finally { $this->leaveBlockLayer(); } ?>' . "\n",
				$name,
				implode('', $node->context),
			);

		} else {
			$node->closingCode = $writer->write(
				'<?php }
				$this->copyBlockLayer();
				try { $this->renderBlock(%raw, %node.array, %var) %node.line; }
				finally { $this->leaveBlockLayer(); } ?>' . "\n",
				$this->isDynamic($name) ? $writer->formatWord($name) : PhpHelpers::dump($name),
				implode('', $node->context),
			);
		}
	}


	/**
	 * {/embed}
	 */
	public function macroEmbedEnd(Tag $node, PhpWriter $writer): void
	{
		$this->index = $node->data->prevIndex;
	}


	/**
	 * {ifset block}
	 * {elseifset block}
	 */
	public function macroIfset(Tag $node, PhpWriter $writer): string|false
	{
		$node->validate(true);
		if (!preg_match('~#|\w~A', $node->args)) {
			return false;
		}

		$list = [];
		while ([$name, $block] = $node->tokenizer->fetchWordWithModifier(['block', '#'])) {
			$list[] = $block || preg_match('~\w[\w-]*$~DA', $name)
				? '$this->hasBlock(' . $writer->formatWord($name) . ')'
				: 'isset(' . $writer->formatArgs(new Latte\Compiler\MacroTokens($name)) . ')';
		}

		return $writer->write(($node->name === 'elseifset' ? '} else' : '') . 'if (%raw) %node.line {', implode(' && ', $list));
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
		return str_contains($name, '$') || str_contains($name, ' ');
	}
}
