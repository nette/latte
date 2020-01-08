<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;


/**
 * Latte compiler.
 */
class Compiler
{
	use Strict;

	/** Context-aware escaping content types */
	public const
		CONTENT_HTML = Engine::CONTENT_HTML,
		CONTENT_XHTML = Engine::CONTENT_XHTML,
		CONTENT_XML = Engine::CONTENT_XML,
		CONTENT_JS = Engine::CONTENT_JS,
		CONTENT_CSS = Engine::CONTENT_CSS,
		CONTENT_ICAL = Engine::CONTENT_ICAL,
		CONTENT_TEXT = Engine::CONTENT_TEXT;

	/** @internal Context-aware escaping HTML contexts */
	public const
		CONTEXT_HTML_TEXT = null,
		CONTEXT_HTML_TAG = 'Tag',
		CONTEXT_HTML_ATTRIBUTE = 'Attr',
		CONTEXT_HTML_ATTRIBUTE_JS = 'AttrJs',
		CONTEXT_HTML_ATTRIBUTE_CSS = 'AttrCss',
		CONTEXT_HTML_ATTRIBUTE_URL = 'AttrUrl',
		CONTEXT_HTML_ATTRIBUTE_UNQUOTED_URL = 'AttrUnquotedUrl',
		CONTEXT_HTML_COMMENT = 'Comment',
		CONTEXT_HTML_BOGUS_COMMENT = 'Bogus',
		CONTEXT_HTML_CSS = 'Css',
		CONTEXT_HTML_JS = 'Js',

		CONTEXT_XML_TEXT = self::CONTEXT_HTML_TEXT,
		CONTEXT_XML_TAG = self::CONTEXT_HTML_TAG,
		CONTEXT_XML_ATTRIBUTE = self::CONTEXT_HTML_ATTRIBUTE,
		CONTEXT_XML_COMMENT = self::CONTEXT_HTML_COMMENT,
		CONTEXT_XML_BOGUS_COMMENT = self::CONTEXT_HTML_BOGUS_COMMENT;

	/** @var Token[] */
	private $tokens;

	/** @var string pointer to current node content */
	private $output;

	/** @var int  position on source template */
	private $position;

	/** @var array of [name => IMacro[]] */
	private $macros = [];

	/** @var string[] of orig name */
	private $functions = [];

	/** @var int[] IMacro flags */
	private $flags;

	/** @var HtmlNode|null */
	private $htmlNode;

	/** @var MacroNode|null */
	private $macroNode;

	/** @var string[] */
	private $placeholders = [];

	/** @var string */
	private $contentType = self::CONTENT_HTML;

	/** @var string|null */
	private $context;

	/** @var mixed */
	private $lastAttrValue;

	/** @var int */
	private $tagOffset;

	/** @var bool */
	private $inHead;

	/** @var array of [name => [body, arguments]] */
	private $methods = [];

	/** @var array of [name => serialized value] */
	private $properties = [];


	/**
	 * Adds new macro with IMacro flags.
	 * @return static
	 */
	public function addMacro(string $name, IMacro $macro, int $flags = null)
	{
		if (!isset($this->flags[$name])) {
			$this->flags[$name] = $flags ?: IMacro::DEFAULT_FLAGS;
		} elseif ($flags && $this->flags[$name] !== $flags) {
			throw new \LogicException("Incompatible flags for macro $name.");
		}
		$this->macros[$name][] = $macro;
		return $this;
	}


	/**
	 * Registers run-time function.
	 */
	public function addFunction(string $name): string
	{
		$lname = strtolower($name);
		$this->functions[$lname] = $name;
		return '_fn' . $lname;
	}


	/**
	 * Compiles tokens to PHP code.
	 * @param  Token[]  $tokens
	 */
	public function compile(array $tokens, string $className): string
	{
		$this->tokens = $tokens;
		$output = '';
		$this->output = &$output;
		$this->inHead = true;
		$this->htmlNode = $this->macroNode = $this->context = null;
		$this->placeholders = $this->properties = [];
		$this->methods = ['main' => null, 'prepare' => null];

		$macroHandlers = new \SplObjectStorage;

		if ($this->macros) {
			array_map([$macroHandlers, 'attach'], array_merge(...array_values($this->macros)));
		}

		foreach ($macroHandlers as $handler) {
			$handler->initialize($this);
		}

		foreach ($tokens as $this->position => $token) {
			if ($this->inHead && !($token->type === $token::COMMENT
				|| $token->type === $token::MACRO_TAG && ($this->flags[$token->name] ?? null) & IMacro::ALLOWED_IN_HEAD
				|| $token->type === $token::TEXT && trim($token->text) === ''
			)) {
				$this->inHead = false;
			}
			$this->{"process$token->type"}($token);
		}

		while ($this->htmlNode) {
			if (!empty($this->htmlNode->macroAttrs)) {
				throw new CompileException('Missing ' . self::printEndTag($this->htmlNode));
			}
			$this->htmlNode = $this->htmlNode->parentNode;
		}

		while ($this->macroNode) {
			if (~$this->flags[$this->macroNode->name] & IMacro::AUTO_CLOSE) {
				throw new CompileException('Missing ' . self::printEndTag($this->macroNode));
			}
			$this->closeMacro($this->macroNode->name);
		}

		$prepare = $epilogs = '';
		foreach ($macroHandlers as $handler) {
			$res = $handler->finalize();
			$prepare .= empty($res[0]) ? '' : "<?php $res[0] ?>";
			$epilogs = (empty($res[1]) ? '' : "<?php $res[1] ?>") . $epilogs;
		}

		$this->addMethod('main', $this->expandTokens("extract(\$this->params);?>\n$output$epilogs<?php return get_defined_vars();"));

		if ($prepare) {
			$this->addMethod('prepare', "extract(\$this->params);?>$prepare<?php");
		}
		if ($this->contentType !== self::CONTENT_HTML) {
			$this->addProperty('contentType', $this->contentType);
		}

		$members = [];
		foreach ($this->properties as $name => $value) {
			$members[] = "\tpublic $$name = " . PhpHelpers::dump($value) . ';';
		}
		foreach (array_filter($this->methods) as $name => $method) {
			$members[] = "\n\tfunction $name($method[arguments])\n\t{\n" . ($method['body'] ? "\t\t$method[body]\n" : '') . "\t}";
		}

		return "<?php\n"
			. "use Latte\\Runtime as LR;\n\n"
			. "class $className extends Latte\\Runtime\\Template\n{\n"
			. implode("\n\n", $members)
			. "\n\n}\n";
	}


	/** @return static */
	public function setContentType(string $type)
	{
		$this->contentType = $type;
		$this->context = null;
		return $this;
	}


	public function getMacroNode(): ?MacroNode
	{
		return $this->macroNode;
	}


	public function getMacros(): array
	{
		return $this->macros;
	}


	public function getFunctions(): array
	{
		return $this->functions;
	}


	/**
	 * Returns current line number.
	 */
	public function getLine(): ?int
	{
		return isset($this->tokens[$this->position]) ? $this->tokens[$this->position]->line : null;
	}


	public function isInHead(): bool
	{
		return $this->inHead;
	}


	/**
	 * Adds custom method to template.
	 * @internal
	 */
	public function addMethod(string $name, string $body, string $arguments = ''): void
	{
		$this->methods[$name] = ['body' => trim($body), 'arguments' => $arguments];
	}


	/**
	 * Returns custom methods.
	 * @internal
	 */
	public function getMethods(): array
	{
		return $this->methods;
	}


	/**
	 * Adds custom property to template.
	 * @internal
	 */
	public function addProperty(string $name, $value): void
	{
		$this->properties[$name] = $value;
	}


	/**
	 * Returns custom properites.
	 * @internal
	 */
	public function getProperties(): array
	{
		return $this->properties;
	}


	/** @internal */
	public function expandTokens(string $s): string
	{
		return strtr($s, $this->placeholders);
	}


	private function processText(Token $token): void
	{
		if ($this->lastAttrValue === '' && $this->context && Helpers::startsWith($this->context, self::CONTEXT_HTML_ATTRIBUTE)) {
			$this->lastAttrValue = $token->text;
		}
		$this->output .= $this->escape($token->text);
	}


	private function processMacroTag(Token $token): void
	{
		if ($this->context === self::CONTEXT_HTML_TAG || $this->context && Helpers::startsWith($this->context, self::CONTEXT_HTML_ATTRIBUTE)) {
			$this->lastAttrValue = true;
		}

		$isRightmost = !isset($this->tokens[$this->position + 1])
			|| substr($this->tokens[$this->position + 1]->text, 0, 1) === "\n";

		if ($token->closing) {
			$this->closeMacro($token->name, $token->value, $token->modifiers, $isRightmost);
		} else {
			if (!$token->empty && ($this->flags[$token->name] ?? null) & IMacro::AUTO_EMPTY) {
				$pos = $this->position;
				while (($t = $this->tokens[++$pos] ?? null)
					&& ($t->type !== Token::MACRO_TAG || $t->name !== $token->name)
					&& ($t->type !== Token::HTML_ATTRIBUTE_BEGIN || $t->name !== Parser::N_PREFIX . $token->name));
				$token->empty = $t ? !$t->closing : true;
			}
			$node = $this->openMacro($token->name, $token->value, $token->modifiers, $isRightmost);
			if ($token->empty) {
				if ($node->empty) {
					throw new CompileException("Unexpected /} in tag {$token->text}");
				}
				$this->closeMacro($token->name, null, null, $isRightmost);
			}
		}
	}


	private function processHtmlTagBegin(Token $token): void
	{
		if ($token->closing) {
			while ($this->htmlNode) {
				if (strcasecmp($this->htmlNode->name, $token->name) === 0) {
					break;
				}
				if ($this->htmlNode->macroAttrs) {
					throw new CompileException("Unexpected </$token->name>, expecting " . self::printEndTag($this->htmlNode));
				}
				$this->htmlNode = $this->htmlNode->parentNode;
			}
			if (!$this->htmlNode) {
				$this->htmlNode = new HtmlNode($token->name);
			}
			$this->htmlNode->closing = true;
			$this->htmlNode->endLine = $this->getLine();
			$this->context = self::CONTEXT_HTML_TEXT;

		} elseif ($token->text === '<!--') {
			$this->context = self::CONTEXT_HTML_COMMENT;

		} elseif ($token->text === '<?' || $token->text === '<!') {
			$this->context = self::CONTEXT_HTML_BOGUS_COMMENT;

		} else {
			$this->htmlNode = new HtmlNode($token->name, $this->htmlNode);
			$this->htmlNode->startLine = $this->getLine();
			$this->context = self::CONTEXT_HTML_TAG;
		}
		$this->tagOffset = strlen($this->output);
		$this->output .= $this->escape($token->text);
	}


	private function processHtmlTagEnd(Token $token): void
	{
		if (in_array($this->context, [self::CONTEXT_HTML_COMMENT, self::CONTEXT_HTML_BOGUS_COMMENT], true)) {
			$this->output .= $token->text;
			$this->context = self::CONTEXT_HTML_TEXT;
			return;
		}

		$htmlNode = $this->htmlNode;
		$end = '';

		if (!$htmlNode->closing) {
			$htmlNode->empty = strpos($token->text, '/') !== false;
			if (in_array($this->contentType, [self::CONTENT_HTML, self::CONTENT_XHTML], true)) {
				$emptyElement = isset(Helpers::$emptyElements[strtolower($htmlNode->name)]);
				$htmlNode->empty = $htmlNode->empty || $emptyElement;
				if ($htmlNode->empty) { // auto-correct
					$space = substr(strstr($token->text, '>'), 1);
					if ($emptyElement) {
						$token->text = ($this->contentType === self::CONTENT_XHTML ? ' />' : '>') . $space;
					} else {
						$token->text = '>';
						$end = "</$htmlNode->name>" . $space;
					}
				}
			}
		}

		if ($htmlNode->macroAttrs) {
			$html = substr($this->output, $this->tagOffset) . $token->text;
			$this->output = substr($this->output, 0, $this->tagOffset);
			$this->writeAttrsMacro($html);
		} else {
			$this->output .= $token->text . $end;
		}

		if ($htmlNode->empty) {
			$htmlNode->closing = true;
			if ($htmlNode->macroAttrs) {
				$this->writeAttrsMacro($end);
			}
		}

		$this->context = self::CONTEXT_HTML_TEXT;

		if ($htmlNode->closing) {
			$this->htmlNode = $this->htmlNode->parentNode;

		} elseif (
			(($lower = strtolower($htmlNode->name)) === 'script' || $lower === 'style')
			&& (!isset($htmlNode->attrs['type']) || preg_match('#(java|j|ecma|live)script|json|css#i', $htmlNode->attrs['type']))
		) {
			$this->context = $lower === 'script' ? self::CONTEXT_HTML_JS : self::CONTEXT_HTML_CSS;
		}
	}


	private function processHtmlAttributeBegin(Token $token): void
	{
		if (Helpers::startsWith($token->name, Parser::N_PREFIX)) {
			$name = substr($token->name, strlen(Parser::N_PREFIX));
			if (isset($this->htmlNode->macroAttrs[$name])) {
				throw new CompileException("Found multiple attributes $token->name.");

			} elseif ($this->macroNode && $this->macroNode->htmlNode === $this->htmlNode) {
				throw new CompileException("n:attributes must not appear inside macro; found $token->name inside {{$this->macroNode->name}}.");
			}
			$this->htmlNode->macroAttrs[$name] = $token->value;
			return;
		}

		$this->lastAttrValue = &$this->htmlNode->attrs[$token->name];
		$this->output .= $this->escape($token->text);

		$lower = strtolower($token->name);
		if (in_array($token->value, ['"', "'"], true)) {
			$this->lastAttrValue = '';
			$this->context = self::CONTEXT_HTML_ATTRIBUTE;
			if (in_array($this->contentType, [self::CONTENT_HTML, self::CONTENT_XHTML], true)) {
				if (Helpers::startsWith($lower, 'on')) {
					$this->context = self::CONTEXT_HTML_ATTRIBUTE_JS;
				} elseif ($lower === 'style') {
					$this->context = self::CONTEXT_HTML_ATTRIBUTE_CSS;
				}
			}
		} else {
			$this->lastAttrValue = $token->value;
			$this->context = self::CONTEXT_HTML_TAG;
		}

		if (
			in_array($this->contentType, [self::CONTENT_HTML, self::CONTENT_XHTML], true)
			&& (in_array($lower, ['href', 'src', 'action', 'formaction'], true)
				|| ($lower === 'data' && strtolower($this->htmlNode->name) === 'object'))
		) {
			$this->context = $this->context === self::CONTEXT_HTML_TAG ? self::CONTEXT_HTML_ATTRIBUTE_UNQUOTED_URL : self::CONTEXT_HTML_ATTRIBUTE_URL;
		}
	}


	private function processHtmlAttributeEnd(Token $token): void
	{
		$this->context = self::CONTEXT_HTML_TAG;
		$this->output .= $token->text;
	}


	private function processComment(Token $token): void
	{
		$leftOfs = ($tmp = strrpos($this->output, "\n")) === false ? 0 : $tmp + 1;
		$isLeftmost = trim(substr($this->output, $leftOfs)) === '';
		$isRightmost = substr($token->text, -1) === "\n";
		if ($isLeftmost && $isRightmost) {
			$this->output = substr($this->output, 0, $leftOfs);
		} else {
			$this->output .= substr($token->text, strlen(rtrim($token->text, "\n")));
		}
	}


	private function escape(string $s): string
	{
		return substr(str_replace('<?', '<<?php ?>?', $s . '?'), 0, -1);
	}


	/********************* macros ****************d*g**/


	/**
	 * Generates code for {macro ...} to the output.
	 * @internal
	 */
	public function openMacro(string $name, string $args = null, string $modifiers = null, bool $isRightmost = false, string $nPrefix = null): MacroNode
	{
		$node = $this->expandMacro($name, $args, $modifiers, $nPrefix);
		if ($node->empty) {
			$this->writeCode((string) $node->openingCode, $node->replaced, $isRightmost);
			if ($node->prefix && $node->prefix !== MacroNode::PREFIX_TAG) {
				$this->htmlNode->attrCode .= $node->attrCode;
			}
		} else {
			$this->macroNode = $node;
			$node->saved = [&$this->output, $isRightmost];
			$this->output = &$node->content;
			$this->output = '';
		}
		return $node;
	}


	/**
	 * Generates code for {/macro ...} to the output.
	 * @internal
	 */
	public function closeMacro(string $name, string $args = null, string $modifiers = null, bool $isRightmost = false, string $nPrefix = null): MacroNode
	{
		$node = $this->macroNode;

		if (
			!$node
			|| ($node->name !== $name && $name !== '')
			|| $modifiers
			|| ($args && $node->args && !Helpers::startsWith("$node->args ", "$args "))
			|| $nPrefix !== $node->prefix
		) {
			$name = $nPrefix
				? "</{$this->htmlNode->name}> for " . Parser::N_PREFIX . implode(' and ' . Parser::N_PREFIX, array_keys($this->htmlNode->macroAttrs))
				: '{/' . $name . ($args ? ' ' . $args : '') . $modifiers . '}';
			throw new CompileException("Unexpected $name" . ($node ? ', expecting ' . self::printEndTag($node->prefix ? $this->htmlNode : $node) : ''));
		}

		$this->macroNode = $node->parentNode;
		if (!$node->args) {
			$node->setArgs($args);
		}

		if ($node->prefix === MacroNode::PREFIX_NONE) {
			$parts = explode($node->htmlNode->innerMarker, $node->content);
			if (count($parts) === 3) { // markers may be destroyed by inner macro
				$node->innerContent = $parts[1];
			}
		}

		$node->closing = true;
		$node->endLine = $node->prefix ? $node->htmlNode->endLine : $this->getLine();
		$node->macro->nodeClosed($node);

		if (isset($parts[1]) && $node->innerContent !== $parts[1]) {
			$node->content = implode($node->htmlNode->innerMarker, [$parts[0], $node->innerContent, $parts[2]]);
		}

		if ($node->prefix && $node->prefix !== MacroNode::PREFIX_TAG) {
			$this->htmlNode->attrCode .= $node->attrCode;
		}
		$this->output = &$node->saved[0];
		$this->writeCode((string) $node->openingCode, $node->replaced, $node->saved[1]);
		$this->output .= $node->content;
		$this->writeCode((string) $node->closingCode, $node->replaced, $isRightmost);
		return $node;
	}


	private function writeCode(string $code, ?bool $isReplaced, ?bool $isRightmost): void
	{
		if ($isRightmost) {
			$leftOfs = ($tmp = strrpos($this->output, "\n")) === false ? 0 : $tmp + 1;
			$isLeftmost = trim(substr($this->output, $leftOfs)) === '';
			if ($isReplaced === null) {
				$isReplaced = preg_match('#<\?php.*\secho\s#As', $code);
			}
			if ($isLeftmost && !$isReplaced) {
				$this->output = substr($this->output, 0, $leftOfs); // alone macro without output -> remove indentation
				if (substr($code, -2) !== '?>') {
					$code .= '<?php ?>'; // consume new line
				}
			} elseif (substr($code, -2) === '?>') {
				$code .= "\n"; // double newline to avoid newline eating by PHP
			}
		}
		$this->output .= $code;
	}


	/**
	 * Generates code for macro <tag n:attr> to the output.
	 * @internal
	 */
	public function writeAttrsMacro(string $html): void
	{
		//     none-2 none-1 tag-1 tag-2       <el attr-1 attr-2>   /tag-2 /tag-1 [none-2] [none-1] inner-2 inner-1
		// /inner-1 /inner-2 [none-1] [none-2] tag-1 tag-2  </el>   /tag-2 /tag-1 /none-1 /none-2
		$attrs = $this->htmlNode->macroAttrs;
		$left = $right = [];

		foreach ($this->macros as $name => $foo) {
			$attrName = MacroNode::PREFIX_INNER . "-$name";
			if (isset($attrs[$attrName])) {
				if ($this->htmlNode->closing) {
					$left[] = function () use ($name) {
						$this->closeMacro($name, '', null, false, MacroNode::PREFIX_INNER);
					};
				} else {
					array_unshift($right, function () use ($name, $attrs, $attrName) {
						if ($this->openMacro($name, $attrs[$attrName], null, false, MacroNode::PREFIX_INNER)->empty) {
							throw new CompileException("Unable to use empty macro as n:$attrName.");
						}
					});
				}
				unset($attrs[$attrName]);
			}
		}

		$innerMarker = '';
		if ($this->htmlNode->closing) {
			$left[] = function () {
				$this->output .= $this->htmlNode->innerMarker;
			};
		} else {
			array_unshift($right, function () use (&$innerMarker) {
				$this->output .= $innerMarker;
			});
		}


		foreach (array_reverse($this->macros) as $name => $foo) {
			$attrName = MacroNode::PREFIX_TAG . "-$name";
			if (isset($attrs[$attrName])) {
				$left[] = function () use ($name, $attrs, $attrName) {
					if ($this->openMacro($name, $attrs[$attrName], null, false, MacroNode::PREFIX_TAG)->empty) {
						throw new CompileException("Unable to use empty macro as n:$attrName.");
					}
				};
				array_unshift($right, function () use ($name) {
					$this->closeMacro($name, '', null, false, MacroNode::PREFIX_TAG);
				});
				unset($attrs[$attrName]);
			}
		}

		foreach ($this->macros as $name => $foo) {
			if (isset($attrs[$name])) {
				if ($this->htmlNode->closing) {
					$right[] = function () use ($name) {
						$this->closeMacro($name, '', null, false, MacroNode::PREFIX_NONE);
					};
				} else {
					array_unshift($left, function () use ($name, $attrs, &$innerMarker) {
						$node = $this->openMacro($name, $attrs[$name], null, false, MacroNode::PREFIX_NONE);
						if ($node->empty) {
							unset($this->htmlNode->macroAttrs[$name]); // don't call closeMacro
						} elseif (!$innerMarker) {
							$this->htmlNode->innerMarker = $innerMarker = '<n:q' . count($this->placeholders) . 'q>';
							$this->placeholders[$innerMarker] = '';
						}
					});
				}
				unset($attrs[$name]);
			}
		}

		if ($attrs) {
			throw new CompileException(
				'Unknown attribute ' . Parser::N_PREFIX
				. implode(' and ' . Parser::N_PREFIX, array_keys($attrs))
				. (($t = Helpers::getSuggestion(array_keys($this->macros), key($attrs))) ? ', did you mean ' . Parser::N_PREFIX . $t . '?' : '')
			);
		}

		if (!$this->htmlNode->closing) {
			$this->htmlNode->attrCode = &$this->placeholders[$uniq = ' n:q' . count($this->placeholders) . 'q'];
			$html = substr_replace($html, $uniq, strrpos($html, '/>') ?: strrpos($html, '>'), 0);
		}

		foreach ($left as $func) {
			$func();
		}

		$this->output .= $html;

		foreach ($right as $func) {
			$func();
		}

		if ($right && substr($this->output, -2) === '?>') {
			$this->output .= "\n";
		}
	}


	/**
	 * Expands macro and returns node & code.
	 * @internal
	 */
	public function expandMacro(string $name, string $args, string $modifiers = null, string $nPrefix = null): MacroNode
	{
		if (empty($this->macros[$name])) {
			$hint = (($t = Helpers::getSuggestion(array_keys($this->macros), $name)) ? ", did you mean {{$t}}?" : '')
				. (in_array($this->context, [self::CONTEXT_HTML_JS, self::CONTEXT_HTML_CSS], true) ? ' (in JavaScript or CSS, try to put a space after bracket or use n:syntax=off)' : '');
			throw new CompileException("Unknown macro {{$name}}$hint");
		}

		$modifiers = (string) $modifiers;

		if (strpbrk($name, '=~%^&_')) {
			if (in_array($this->context, [self::CONTEXT_HTML_ATTRIBUTE_URL, self::CONTEXT_HTML_ATTRIBUTE_UNQUOTED_URL], true)) {
				if (!Helpers::removeFilter($modifiers, 'nocheck') && !preg_match('#\|datastream(?=\s|\||$)#Di', $modifiers)) {
					$modifiers .= '|checkurl';
				}
			}

			if (!Helpers::removeFilter($modifiers, 'noescape')) {
				$modifiers .= '|escape';
				if ($this->context === self::CONTEXT_HTML_JS && $name === '=' && preg_match('#["\'] *$#D', $this->tokens[$this->position - 1]->text)) {
					throw new CompileException("Do not place {$this->tokens[$this->position]->text} inside quotes.");
				}
			}
		}

		if ($nPrefix === MacroNode::PREFIX_INNER && !strcasecmp($this->htmlNode->name, 'script')) {
			$context = [$this->contentType, self::CONTEXT_HTML_JS];
		} elseif ($nPrefix === MacroNode::PREFIX_INNER && !strcasecmp($this->htmlNode->name, 'style')) {
			$context = [$this->contentType, self::CONTEXT_HTML_CSS];
		} elseif ($nPrefix) {
			$context = [$this->contentType, self::CONTEXT_HTML_TEXT];
		} else {
			$context = [$this->contentType, $this->context];
		}

		foreach (array_reverse($this->macros[$name]) as $macro) {
			$node = new MacroNode($macro, $name, $args, $modifiers, $this->macroNode, $this->htmlNode, $nPrefix);
			$node->context = $context;
			$node->startLine = $nPrefix ? $this->htmlNode->startLine : $this->getLine();
			if ($macro->nodeOpened($node) !== false) {
				return $node;
			}
		}

		throw new CompileException('Unknown ' . ($nPrefix
			? 'attribute ' . Parser::N_PREFIX . ($nPrefix === MacroNode::PREFIX_NONE ? '' : "$nPrefix-") . $name
			: 'macro {' . $name . ($args ? " $args" : '') . '}'
		));
	}


	private static function printEndTag($node): string
	{
		if ($node instanceof HtmlNode) {
			return  "</{$node->name}> for " . Parser::N_PREFIX
				. implode(' and ' . Parser::N_PREFIX, array_keys($node->macroAttrs));
		} else {
			return "{/$node->name}";
		}
	}
}
