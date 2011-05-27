<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Nette\Latte;

use Nette,
	Nette\Utils\Strings;



/**
 * Default macros for filter LatteFilter.
 *
 * - {$variable} with escaping
 * - {!$variable} without escaping
 * - {*comment*} will be removed
 * - {=expression} echo with escaping
 * - {!=expression} echo without escaping
 * - {?expression} evaluate PHP statement
 * - {_expression} echo translation with escaping
 * - {!_expression} echo translation without escaping
 * - {link destination ...} control link
 * - {plink destination ...} presenter link
 * - {if ?} ... {elseif ?} ... {else} ... {/if}
 * - {ifset ?} ... {elseifset ?} ... {/ifset}
 * - {for ?} ... {/for}
 * - {foreach ?} ... {/foreach}
 * - {include ?}
 * - {cache ?} ... {/cache} cached block
 * - {snippet ?} ... {/snippet ?} control snippet
 * - {attr ?} HTML element attributes
 * - {block|texy} ... {/block} block
 * - {contentType ...} HTTP Content-Type header
 * - {status ...} HTTP status
 * - {capture ?} ... {/capture} capture block to parameter
 * - {var var => value} set template parameter
 * - {default var => value} set default template parameter
 * - {dump $var}
 * - {debugbreak}
 * - {l} {r} to display { }
 *
 * @author     David Grudl
 */
class DefaultMacros extends Nette\Object
{
	/** @var array */
	public static $defaultMacros = array(
		'syntax' => '%:macroSyntax%',
		'/syntax' => '%:macroSyntax%',

		'block' => '<?php %:macroBlock% ?>',
		'/block' => '<?php %:macroBlockEnd% ?>',

		'capture' => '<?php %:macroCapture% ?>',
		'/capture' => '<?php %:macroCaptureEnd% ?>',

		'snippet' => '<?php %:macroSnippet% ?>',
		'/snippet' => '<?php %:macroSnippetEnd% ?>',

		'cache' => '<?php %:macroCache% ?>',
		'/cache' => '<?php $_l->tmp = array_pop($_l->g->caches); if (!$_l->tmp instanceof \stdClass) $_l->tmp->end(); } ?>',

		'if' => '<?php if (%%): ?>',
		'elseif' => '<?php elseif (%%): ?>',
		'else' => '<?php else: ?>',
		'/if' => '<?php endif ?>',
		'ifset' => '<?php if (isset(%:macroIfset%)): ?>',
		'/ifset' => '<?php endif ?>',
		'elseifset' => '<?php elseif (isset(%%)): ?>',
		'foreach' => '<?php foreach (%:macroForeach%): ?>',
		'/foreach' => '<?php endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>',
		'for' => '<?php for (%%): ?>',
		'/for' => '<?php endfor ?>',
		'while' => '<?php while (%%): ?>',
		'/while' => '<?php endwhile ?>',
		'continueIf' => '<?php if (%%) continue ?>',
		'breakIf' => '<?php if (%%) break ?>',
		'first' => '<?php if ($iterator->isFirst(%%)): ?>',
		'/first' => '<?php endif ?>',
		'last' => '<?php if ($iterator->isLast(%%)): ?>',
		'/last' => '<?php endif ?>',
		'sep' => '<?php if (!$iterator->isLast(%%)): ?>',
		'/sep' => '<?php endif ?>',

		'include' => '<?php %:macroInclude% ?>',
		'extends' => '<?php %:macroExtends% ?>',
		'layout' => '<?php %:macroExtends% ?>',

		'plink' => '<?php echo %:escape%(%:macroPlink%) ?>',
		'link' => '<?php echo %:escape%(%:macroLink%) ?>',
		'ifCurrent' => '<?php %:macroIfCurrent% ?>', // deprecated; use n:class="$presenter->linkCurrent ? ..."
		'/ifCurrent' => '<?php endif ?>',
		'widget' => '<?php %:macroControl% ?>',
		'control' => '<?php %:macroControl% ?>',

		'@href' => ' href="<?php echo %:escape%(%:macroLink%) ?>"',
		'@class' => '<?php if ($_l->tmp = trim(implode(" ", array_unique(%:formatArray%)))) echo \' class="\' . %:escape%($_l->tmp) . \'"\' ?>',
		'@attr' => '<?php if (($_l->tmp = (string) (%%)) !== \'\') echo \' @@="\' . %:escape%($_l->tmp) . \'"\' ?>',

		'attr' => '<?php echo Nette\Utils\Html::el(NULL)->%:macroAttr%attributes() ?>',
		'contentType' => '<?php %:macroContentType% ?>',
		'status' => '<?php $netteHttpResponse->setCode(%%) ?>',
		'var' => '<?php %:macroVar% ?>',
		'assign' => '<?php %:macroVar% ?>', // deprecated
		'default' => '<?php %:macroDefault% ?>',
		'dump' => '<?php %:macroDump% ?>',
		'debugbreak' => '<?php %:macroDebugbreak% ?>',
		'l' => '{',
		'r' => '}',

		'_' => '<?php echo %:macroTranslate% ?>',
		'=' => '<?php echo %:macroModifiers% ?>',
		'?' => '<?php %:macroModifiers% ?>',
	);

	/** @internal PHP identifier */
	const RE_IDENTIFIER = '[_a-zA-Z\x7F-\xFF][_a-zA-Z0-9\x7F-\xFF]*';

	/** @var Parser */
	public $parser;

	/** @var PhpWriter */
	public $writer;

	/** @var array */
	private $blocks = array();

	/** @var array */
	private $namedBlocks = array();

	/** @var bool */
	private $extends;

	/** @var string */
	private $uniq;

	/** @var int */
	private $cacheCounter;

	/** @internal block type */
	const BLOCK_NAMED = 1,
		BLOCK_CAPTURE = 2,
		BLOCK_ANONYMOUS = 3;



	/**
	 * Initializes parsing.
	 * @param  Parser
	 * @return void
	 */
	public function initialize($parser)
	{
		$this->writer = new PhpWriter;
		$this->parser = $parser;
		$this->blocks = array();
		$this->namedBlocks = array();
		$this->extends = NULL;
		$this->uniq = Strings::random();
		$this->cacheCounter = 0;
	}



	/**
	 * Finishes parsing.
	 * @param  string
	 * @return void
	 */
	public function finalize(& $s)
	{
		// blocks closing check
		if (count($this->blocks) === 1) { // auto-close last block
			$s .= $this->parser->macro('/block');
		}

		// extends support
		if ($this->namedBlocks || $this->extends) {
			$s = '<?php
if ($_l->extends) {
	ob_start();
} elseif (isset($presenter, $control) && $presenter->isAjax() && $control->isControlInvalid()) {
	return Nette\Latte\DefaultMacros::renderSnippets($control, $_l, get_defined_vars());
}
?>' . $s . '<?php
if ($_l->extends) {
	ob_end_clean();
	Nette\Latte\DefaultMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render();
}
';
		} else {
			$s = '<?php
if (isset($presenter, $control) && $presenter->isAjax() && $control->isControlInvalid()) {
	return Nette\Latte\DefaultMacros::renderSnippets($control, $_l, get_defined_vars());
}
?>' . $s;
		}

		// named blocks
		if ($this->namedBlocks) {
			$uniq = $this->uniq;
			foreach (array_reverse($this->namedBlocks, TRUE) as $name => $foo) {
				$code = & $this->namedBlocks[$name];
				$namere = preg_quote($name, '#');
				$s = Strings::replace($s,
					"#{block $namere} \?>(.*)<\?php {/block $namere}#sU",
					/*5.2* callback(*/function ($matches) use ($name, & $code, $uniq) {
						list(, $content) = $matches;
						$func = '_lb' . substr(md5($uniq . $name), 0, 10) . '_' . preg_replace('#[^a-z0-9_]#i', '_', $name);
						$code = "//\n// block $name\n//\n"
							. "if (!function_exists(\$_l->blocks[" . var_export($name, TRUE) . "][] = '$func')) { "
							. "function $func(\$_l, \$_args) { "
							. (PHP_VERSION_ID > 50208 ? 'extract($_args)' : 'foreach ($_args as $__k => $__v) $$__k = $__v') // PHP bug #46873
							. ($name[0] === '_' ? '; $control->validateControl(' . var_export(substr($name, 1), TRUE) . ')' : '') // snippet
							. "\n?>$content<?php\n}}";
						return '';
					}/*5.2* )*/
				);
			}
			$s = "<?php\n\n" . implode("\n\n\n", $this->namedBlocks) . "\n\n//\n// end of blocks\n//\n?>" . $s;
		}

		// internal state holder
		$s = "<?php\n"
			. '$_l = Nette\Latte\DefaultMacros::initRuntime($template, '
			. var_export($this->extends, TRUE) . ', ' . var_export($this->uniq, TRUE) . '); unset($_extends);'
			. "\n?>" . $s;
	}



	/********************* macros ****************d*g**/



	/**
	 * {_$var |modifiers}
	 */
	public function macroTranslate($var, $modifiers)
	{
		return $this->writer->formatModifiers($this->writer->formatArgs($var), '|translate' . $modifiers, $this->parser->escape);
	}



	/**
	 * {syntax ...}
	 */
	public function macroSyntax($var)
	{
		switch ($var) {
		case '':
		case 'latte':
			$this->parser->setDelimiters('\\{(?![\\s\'"{}])', '\\}'); // {...}
			break;

		case 'double':
			$this->parser->setDelimiters('\\{\\{(?![\\s\'"{}])', '\\}\\}'); // {{...}}
			break;

		case 'asp':
			$this->parser->setDelimiters('<%\s*', '\s*%>'); /* <%...%> */
			break;

		case 'python':
			$this->parser->setDelimiters('\\{[{%]\s*', '\s*[%}]\\}'); // {% ... %} | {{ ... }}
			break;

		case 'off':
			$this->parser->setDelimiters('[^\x00-\xFF]', '');
			break;

		default:
			throw new ParseException("Unknown syntax '$var'", 0, $this->parser->line);
		}
	}



	/**
	 * {include ...}
	 */
	public function macroInclude($content, $modifiers, $isDefinition = FALSE)
	{
		$destination = $this->writer->fetchWord($content); // destination [,] [params]
		$params = $this->writer->formatArray($content) . ($content ? ' + ' : '');

		if ($destination === NULL) {
			throw new ParseException("Missing destination in {include}", 0, $this->parser->line);

		} elseif ($destination[0] === '#') { // include #block
			$destination = ltrim($destination, '#');
			if (!Strings::match($destination, '#^\$?' . self::RE_IDENTIFIER . '$#')) {
				throw new ParseException("Included block name must be alphanumeric string, '$destination' given.", 0, $this->parser->line);
			}

			$parent = $destination === 'parent';
			if ($destination === 'parent' || $destination === 'this') {
				$item = end($this->blocks);
				while ($item && $item[0] !== self::BLOCK_NAMED) $item = prev($this->blocks);
				if (!$item) {
					throw new ParseException("Cannot include $destination block outside of any block.", 0, $this->parser->line);
				}
				$destination = $item[1];
			}
			$name = $destination[0] === '$' ? $destination : var_export($destination, TRUE);
			$params .= $isDefinition ? 'get_defined_vars()' : '$template->getParams()';
			$cmd = isset($this->namedBlocks[$destination]) && !$parent
				? "call_user_func(reset(\$_l->blocks[$name]), \$_l, $params)"
				: 'Nette\Latte\DefaultMacros::callBlock' . ($parent ? 'Parent' : '') . "(\$_l, $name, $params)";
			return $modifiers
				? "ob_start(); $cmd; echo " . $this->writer->formatModifiers('ob_get_clean()', $modifiers, $this->parser->escape)
				: $cmd;

		} else { // include "file"
			$destination = $this->writer->formatWord($destination);
			$cmd = 'Nette\Latte\DefaultMacros::includeTemplate(' . $destination . ', '
				. $params . '$template->getParams(), $_l->templates[' . var_export($this->uniq, TRUE) . '])';
			return $modifiers
				? 'echo ' . $this->writer->formatModifiers($cmd . '->__toString(TRUE)', $modifiers, $this->parser->escape)
				: $cmd . '->render()';
		}
	}



	/**
	 * {extends ...}
	 */
	public function macroExtends($content)
	{
		if (!$content) {
			throw new ParseException("Missing destination in {extends}", 0, $this->parser->line);
		}
		if (!empty($this->blocks)) {
			throw new ParseException("{extends} must be placed outside any block.", 0, $this->parser->line);
		}
		if ($this->extends !== NULL) {
			throw new ParseException("Multiple {extends} declarations are not allowed.", 0, $this->parser->line);
		}
		$this->extends = $content !== 'none';
		return $this->extends ? '$_l->extends = ' . ($content === 'auto' ? '$layout' : $this->writer->formatArgs($content)) : '';
	}



	/**
	 * {block ...}
	 */
	public function macroBlock($content, $modifiers)
	{
		$name = $this->writer->fetchWord($content); // block [,] [params]

		if ($name === NULL) { // anonymous block
			$this->blocks[] = array(self::BLOCK_ANONYMOUS, NULL, $modifiers);
			return $modifiers === '' ? '' : 'ob_start()';

		} else { // #block
			$name = ltrim($name, '#');
			if (!Strings::match($name, '#^' . self::RE_IDENTIFIER . '$#')) {
				throw new ParseException("Block name must be alphanumeric string, '$name' given.", 0, $this->parser->line);

			} elseif (isset($this->namedBlocks[$name])) {
				throw new ParseException("Cannot redeclare block '$name'", 0, $this->parser->line);
			}

			$top = empty($this->blocks);
			$this->namedBlocks[$name] = $name;
			$this->blocks[] = array(self::BLOCK_NAMED, $name, '');
			if ($name[0] === '_') { // snippet
				$tag = $this->writer->fetchWord($content);  // [name [,]] [tag]
				$tag = trim($tag, '<>');
				$namePhp = var_export(substr($name, 1), TRUE);
				$tag = $tag ? $tag : 'div';
				return "?><$tag id=\"<?php echo \$control->getSnippetId($namePhp) ?>\"><?php "
					. $this->macroInclude('#' . $name, $modifiers)
					. " ?></$tag><?php {block $name}";

			} elseif (!$top) {
				return $this->macroInclude('#' . $name, $modifiers, TRUE) . "{block $name}";

			} elseif ($this->extends) {
				return "{block $name}";

			} else {
				return 'if (!$_l->extends) { ' . $this->macroInclude('#' . $name, $modifiers, TRUE) . "; } {block $name}";
			}
		}
	}



	/**
	 * {/block}
	 */
	public function macroBlockEnd($content)
	{
		list($type, $name, $modifiers) = array_pop($this->blocks);

		if ($type === self::BLOCK_CAPTURE) { // capture - back compatibility
			$this->blocks[] = array($type, $name, $modifiers);
			return $this->macroCaptureEnd($content);

		} elseif ($type === self::BLOCK_NAMED) { // block
			return "{/block $name}";

		} else { // anonymous block
			return $modifiers === '' ? '' : 'echo ' . $this->writer->formatModifiers('ob_get_clean()', $modifiers, $this->parser->escape);
		}
	}



	/**
	 * {snippet ...}
	 */
	public function macroSnippet($content)
	{
		return $this->macroBlock('_' . $content, '');
	}



	/**
	 * {snippet ...}
	 */
	public function macroSnippetEnd($content)
	{
		return $this->macroBlockEnd('', '');
	}



	/**
	 * {capture ...}
	 */
	public function macroCapture($content, $modifiers)
	{
		$name = $this->writer->fetchWord($content); // $variable

		if (substr($name, 0, 1) !== '$') {
			throw new ParseException("Invalid capture block parameter '$name'", 0, $this->parser->line);
		}

		$this->blocks[] = array(self::BLOCK_CAPTURE, $name, $modifiers);
		return 'ob_start()';
	}



	/**
	 * {/capture}
	 */
	public function macroCaptureEnd($content)
	{
		list($type, $name, $modifiers) = array_pop($this->blocks);
		return $name . '=' . $this->writer->formatModifiers('ob_get_clean()', $modifiers, $this->parser->escape);
	}



	/**
	 * {cache ...}
	 */
	public function macroCache($content)
	{
		return 'if (Nette\Latte\DefaultMacros::createCache($netteCacheStorage, '
			. var_export($this->uniq . ':' . $this->cacheCounter++, TRUE)
			. ', $_l->g->caches' . $this->writer->formatArray($content, ', ') . ')) {';
	}



	/**
	 * {foreach ...}
	 */
	public function macroForeach($content)
	{
		return '$iterator = $_l->its[] = new Nette\Iterators\CachingIterator('
			. preg_replace('#(.*)\s+as\s+#i', '$1) as ', $this->writer->formatArgs($content), 1);
	}



	/**
	 * {ifset ...}
	 */
	public function macroIfset($content)
	{
		if (strpos($content, '#') === FALSE) {
			return $content;
		}
		$list = array();
		while (($name = $this->writer->fetchWord($content)) !== NULL) {
			$list[] = $name[0] === '#' ? '$_l->blocks["' . substr($name, 1) . '"]' : $name;
		}
		return implode(', ', $list);
	}



	/**
	 * {attr ...}
	 */
	public function macroAttr($content)
	{
		return Strings::replace($content . ' ', '#\)\s+#', ')->');
	}



	/**
	 * {contentType ...}
	 */
	public function macroContentType($content)
	{
		if (strpos($content, 'html') !== FALSE) {
			$this->parser->escape = 'Nette\Templating\DefaultHelpers::escapeHtml|';
			$this->parser->context = Parser::CONTEXT_TEXT;

		} elseif (strpos($content, 'xml') !== FALSE) {
			$this->parser->escape = 'Nette\Templating\DefaultHelpers::escapeXml';
			$this->parser->context = Parser::CONTEXT_NONE;

		} elseif (strpos($content, 'javascript') !== FALSE) {
			$this->parser->escape = 'Nette\Templating\DefaultHelpers::escapeJs';
			$this->parser->context = Parser::CONTEXT_NONE;

		} elseif (strpos($content, 'css') !== FALSE) {
			$this->parser->escape = 'Nette\Templating\DefaultHelpers::escapeCss';
			$this->parser->context = Parser::CONTEXT_NONE;

		} elseif (strpos($content, 'plain') !== FALSE) {
			$this->parser->escape = '';
			$this->parser->context = Parser::CONTEXT_NONE;

		} else {
			$this->parser->escape = '$template->escape';
			$this->parser->context = Parser::CONTEXT_NONE;
		}

		// temporary solution
		if (strpos($content, '/')) {
			return '$netteHttpResponse->setHeader("Content-Type", "' . $content . '")';
		}
	}



	/**
	 * {dump ...}
	 */
	public function macroDump($content)
	{
		return 'Nette\Diagnostics\Debugger::barDump('
			. ($content ? 'array(' . var_export($this->writer->formatArgs($content), TRUE) . " => $content)" : 'get_defined_vars()')
			. ', "Template " . str_replace(dirname(dirname($template->getFile())), "\xE2\x80\xA6", $template->getFile()))';
	}



	/**
	 * {debugbreak}
	 */
	public function macroDebugbreak()
	{
		return 'if (function_exists("debugbreak")) debugbreak(); elseif (function_exists("xdebug_break")) xdebug_break()';
	}



	/**
	 * {control ...}
	 */
	public function macroControl($content)
	{
		$pair = $this->writer->fetchWord($content); // control[:method]
		if ($pair === NULL) {
			throw new ParseException("Missing control name in {control}", 0, $this->parser->line);
		}
		$pair = explode(':', $pair, 2);
		$name = $this->writer->formatWord($pair[0]);
		$method = isset($pair[1]) ? ucfirst($pair[1]) : '';
		$method = Strings::match($method, '#^(' . self::RE_IDENTIFIER . '|)$#') ? "render$method" : "{\"render$method\"}";
		$param = $this->writer->formatArray($content);
		if (strpos($content, '=>') === FALSE) {
			$param = substr($param, 6, -1); // removes array()
		}
		return ($name[0] === '$' ? "if (is_object($name)) \$_ctrl = $name; else " : '')
			. '$_ctrl = $control->getWidget(' . $name . '); '
			. 'if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); '
			. "\$_ctrl->$method($param)";
	}



	/**
	 * {link ...}
	 */
	public function macroLink($content, $modifiers)
	{
		return $this->writer->formatModifiers('$control->link(' . $this->formatLink($content) .')', $modifiers, $this->parser->escape);
	}



	/**
	 * {plink ...}
	 */
	public function macroPlink($content, $modifiers)
	{
		return $this->writer->formatModifiers('$presenter->link(' . $this->formatLink($content) .')', $modifiers, $this->parser->escape);
	}



	/**
	 * {ifCurrent ...}
	 */
	public function macroIfCurrent($content)
	{
		return ($content ? 'try { $presenter->link(' . $this->formatLink($content) . '); } catch (Nette\Application\UI\InvalidLinkException $e) {}' : '')
			. '; if ($presenter->getLastCreatedRequestFlag("current")):';
	}



	/**
	 * Formats {*link ...} parameters.
	 */
	private function formatLink($content)
	{
		return $this->writer->formatWord($this->writer->fetchWord($content)) . $this->writer->formatArray($content, ', '); // destination [,] args
	}



	/**
	 * {var ...}
	 */
	public function macroVar($content, $modifiers, $extract = FALSE)
	{
		$out = '';
		$var = TRUE;
		$tokenizer = $this->writer->preprocess(new MacroTokenizer($content));
		while ($token = $tokenizer->fetchToken()) {
			if ($var && ($token['type'] === MacroTokenizer::T_SYMBOL || $token['type'] === MacroTokenizer::T_VARIABLE)) {
				if ($extract) {
					$out .= "'" . trim($token['value'], "'$") . "'";
				} else {
					$out .= '$' . trim($token['value'], "'$");
				}
			} elseif (($token['value'] === '=' || $token['value'] === '=>') && $token['depth'] === 0) {
				$out .= $extract ? '=>' : '=';
				$var = FALSE;

			} elseif ($token['value'] === ',' && $token['depth'] === 0) {
				$out .= $extract ? ',' : ';';
				$var = TRUE;
			} else {
				$out .= $this->writer->canQuote($tokenizer) ? "'$token[value]'" : $token['value'];
			}
		}
		return $out;
	}



	/**
	 * {default ...}
	 */
	public function macroDefault($content)
	{
		return 'extract(array(' . $this->macroVar($content, '', TRUE) . '), EXTR_SKIP)';
	}



	/**
	 * Just modifiers helper.
	 */
	public function macroModifiers($content, $modifiers)
	{
		return $this->writer->formatModifiers($this->writer->formatArgs($content), $modifiers, $this->parser->escape);
	}



	/**
	 * Argument formating helper.
	 */
	public function formatArray($content)
	{
		return $this->writer->formatArray($content);
	}



	/**
	 * Escaping helper.
	 */
	public function escape()
	{
		$tmp = explode('|', $this->parser->escape);
		return $tmp[0];
	}



	/********************* run-time helpers ****************d*g**/



	/**
	 * Calls block.
	 * @param  stdClass
	 * @param  string
	 * @param  array
	 * @return void
	 */
	public static function callBlock($context, $name, $params)
	{
		if (empty($context->blocks[$name])) {
			throw new Nette\InvalidStateException("Cannot include undefined block '$name'.");
		}
		$block = reset($context->blocks[$name]);
		$block($context, $params);
	}



	/**
	 * Calls parent block.
	 * @param  stdClass
	 * @param  string
	 * @param  array
	 * @return void
	 */
	public static function callBlockParent($context, $name, $params)
	{
		if (empty($context->blocks[$name]) || ($block = next($context->blocks[$name])) === FALSE) {
			throw new Nette\InvalidStateException("Cannot include undefined parent block '$name'.");
		}
		$block($context, $params);
	}



	/**
	 * Includes subtemplate.
	 * @param  mixed      included file name or template
	 * @param  array      parameters
	 * @param  Nette\Templating\ITemplate  current template
	 * @return Nette\Templating\Template
	 */
	public static function includeTemplate($destination, $params, $template)
	{
		if ($destination instanceof Nette\Templating\ITemplate) {
			$tpl = $destination;

		} elseif ($destination == NULL) { // intentionally ==
			throw new Nette\InvalidArgumentException("Template file name was not specified.");

		} else {
			$tpl = clone $template;
			if ($template instanceof Nette\Templating\IFileTemplate) {
				if (substr($destination, 0, 1) !== '/' && substr($destination, 1, 1) !== ':') {
					$destination = dirname($template->getFile()) . '/' . $destination;
				}
				$tpl->setFile($destination);
			}
		}

		$tpl->setParams($params); // interface?
		return $tpl;
	}



	/**
	 * Initializes local & global storage in template.
	 * @param  Nette\Templating\ITemplate
	 * @param  bool
	 * @param  string
	 * @return stdClass
	 */
	public static function initRuntime($template, $extends, $realFile)
	{
		$local = (object) NULL;

		// extends support
		if (isset($template->_l)) {
			$local->blocks = & $template->_l->blocks;
			$local->templates = & $template->_l->templates;
		}
		$local->templates[$realFile] = $template;
		$local->extends = is_bool($extends) ? $extends : (empty($template->_extends) ? FALSE : $template->_extends);
		unset($template->_l, $template->_extends);

		// global storage
		if (!isset($template->_g)) {
			$template->_g = (object) NULL;
		}
		$local->g = $template->_g;

		// cache support
		if (!empty($local->g->caches)) {
			end($local->g->caches)->dependencies[Nette\Caching\Cache::FILES][] = $template->getFile();
		}

		return $local;
	}



	public static function renderSnippets($control, $local, $params)
	{
		$payload = $control->getPresenter()->getPayload();
		if (isset($local->blocks)) {
			foreach ($local->blocks as $name => $function) {
				if ($name[0] !== '_' || !$control->isControlInvalid(substr($name, 1))) {
					continue;
				}
				ob_start();
				$function = reset($function);
				$function($local, $params);
				$payload->snippets[$control->getSnippetId(substr($name, 1))] = ob_get_clean();
			}
		}
		if ($control instanceof Nette\Application\UI\Control) {
			foreach ($control->getComponents(FALSE, 'Nette\Application\UI\Control') as $child) {
				if ($child->isControlInvalid()) {
					$child->render();
				}
			}
		}
	}



	/**
	 * Starts the output cache. Returns Nette\Caching\OutputHelper object if buffering was started.
	 * @param  Nette\Caching\IStorage
	 * @param  string
	 * @param  array of Nette\Caching\OutputHelper
	 * @param  array
	 * @return Nette\Caching\OutputHelper
	 */
	public static function createCache(Nette\Caching\IStorage $cacheStorage, $key, & $parents, $args = NULL)
	{
		if ($args) {
			if (array_key_exists('if', $args) && !$args['if']) {
				return $parents[] = (object) NULL;
			}
			$key = array_merge(array($key), array_intersect_key($args, range(0, count($args))));
		}
		if ($parents) {
			end($parents)->dependencies[Nette\Caching\Cache::ITEMS][] = $key;
		}

		$cache = new Nette\Caching\Cache($cacheStorage, 'Nette.Templating.Cache');
		if ($helper = $cache->start($key)) {
			$helper->dependencies = array(
				Nette\Caching\Cache::TAGS => isset($args['tags']) ? $args['tags'] : NULL,
				Nette\Caching\Cache::EXPIRATION => isset($args['expire']) ? $args['expire'] : '+ 7 days',
			);
			$parents[] = $helper;
		}
		return $helper;
	}

}
