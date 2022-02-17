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

	/** @var string[] @internal */
	public $placeholders = [];

	/** @var string|null */
	public $paramsExtraction;

	/** @var Token[] */
	private $tokens;

	/** @var string pointer to current node content */
	private $output;

	/** @var int  position on source template */
	private $position;

	/** @var array<string, Macro[]> */
	private $macros = [];

	/** @var string[] of orig name */
	private $functions = [];

	/** @var int[] Macro flags */
	private $flags;

	/** @var HtmlNode|null */
	private $htmlNode;

	/** @var MacroNode|null */
	private $macroNode;

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

	/** @var array<string, ?array{body: string, arguments: string, returns: string, comment: ?string}> */
	private $methods = [];

	/** @var array<string, mixed> */
	private $properties = [];

	/** @var array<string, mixed> */
	private $constants = [];

	/** @var Policy|null */
	private $policy;


	/**
	 * Adds new macro with Macro flags.
	 * @return static
	 */
	public function addMacro(string $name, Macro $macro, ?int $flags = null)
	{
		if (!preg_match('#^[a-z_=]\w*(?:[.:-]\w+)*$#iD', $name)) {
			throw new \LogicException("Invalid tag name '$name'.");

		} elseif (!isset($this->flags[$name])) {
			$this->flags[$name] = $flags ?: Macro::DEFAULT_FLAGS;

		} elseif ($flags && $this->flags[$name] !== $flags) {
			throw new \LogicException("Incompatible flags for tag '$name'.");
		}

		$this->macros[$name][] = $macro;
		return $this;
	}


	/**
	 * Registers run-time functions.
	 * @param  string[]  $names
	 * @return static
	 */
	public function setFunctions(array $names)
	{
		$this->functions = array_combine(array_map('strtolower', $names), $names);
		return $this;
	}


	/**
	 * Compiles tokens to PHP file
	 * @param  Token[]  $tokens
	 */
	public function compile(array $tokens, string $className, ?string $comment = null, bool $strictMode = false): string
	{
		$code = "<?php\n\n"
			. ($strictMode ? "declare(strict_types=1);\n\n" : '')
			. "use Latte\\Runtime as LR;\n\n"
			. ($comment === null ? '' : '/** ' . str_replace('*/', '* /', $comment) . " */\n")
			. "final class $className extends Latte\\Runtime\\Template\n{\n"
			. $this->buildClassBody($tokens)
			. "\n\n}\n";

		$code = PhpHelpers::inlineHtmlToEcho($code);
		$code = PhpHelpers::reformatCode($code);
		return $code;
	}


	/**
	 * @param  Token[]  $tokens
	 */
	private function buildClassBody(array $tokens): string
	{
		$this->tokens = $tokens;
		$output = '';
		$this->output = &$output;
		$this->inHead = true;
		$this->htmlNode = $this->macroNode = $this->context = $this->paramsExtraction = null;
		$this->placeholders = $this->properties = $this->constants = [];
		$this->methods = ['main' => null, 'prepare' => null];

		$macroHandlers = new \SplObjectStorage;

		if ($this->macros) {
			array_map([$macroHandlers, 'attach'], array_merge(...array_values($this->macros)));
		}

		foreach ($macroHandlers as $handler) {
			$handler->initialize($this);
		}

		foreach ($tokens as $this->position => $token) {
			if ($this->inHead && !(
				$token->type === $token::COMMENT
				|| $token->type === $token::MACRO_TAG && ($this->flags[$token->name] ?? null) & Macro::ALLOWED_IN_HEAD
				|| $token->type === $token::TEXT && trim($token->text) === ''
			)) {
				$this->inHead = false;
			}

			$this->{"process$token->type"}($token);
		}

		while ($this->htmlNode) {
			$this->closeHtmlTag('end');
		}

		while ($this->macroNode) {
			if ($this->macroNode->parentNode) {
				throw new CompileException('Missing {/' . $this->macroNode->name . '}');
			}

			if (~$this->flags[$this->macroNode->name] & Macro::AUTO_CLOSE) {
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

		$extractParams = $this->paramsExtraction ?? 'extract($this->params);';
		$this->addMethod('main', $this->expandTokens($extractParams . "?>\n$output$epilogs<?php return get_defined_vars();"), '', 'array');

		if ($prepare) {
			$this->addMethod('prepare', $extractParams . "?>$prepare<?php", '', 'void');
		}

		if ($this->contentType !== self::CONTENT_HTML) {
			$this->addConstant('CONTENT_TYPE', $this->contentType);
		}

		$members = [];
		foreach ($this->constants as $name => $value) {
			$members[] = "\tprotected const $name = " . PhpHelpers::dump($value, true) . ';';
		}

		foreach ($this->properties as $name => $value) {
			$members[] = "\tpublic $$name = " . PhpHelpers::dump($value, true) . ';';
		}

		foreach (array_filter($this->methods) as $name => $method) {
			$members[] = ($method['comment'] === null ? '' : "\n\t/** " . str_replace('*/', '* /', $method['comment']) . ' */')
				. "\n\tpublic function $name($method[arguments])"
				. ($method['returns'] ? ': ' . $method['returns'] : '')
				. "\n\t{\n"
				. ($method['body'] ? "\t\t$method[body]\n" : '') . "\t}";
		}

		return implode("\n\n", $members);
	}


	/** @return static */
	public function setPolicy(?Policy $policy)
	{
		$this->policy = $policy;
		return $this;
	}


	public function getPolicy(): ?Policy
	{
		return $this->policy;
	}


	/** @return static */
	public function setContentType(string $type)
	{
		$this->contentType = $type;
		$this->context = null;
		return $this;
	}


	public function getContentType(): string
	{
		return $this->contentType;
	}


	public function getMacroNode(): ?MacroNode
	{
		return $this->macroNode;
	}


	/**
	 * @return Macro[][]
	 */
	public function getMacros(): array
	{
		return $this->macros;
	}


	/**
	 * @return string[]
	 */
	public function getFunctions(): array
	{
		return $this->functions;
	}


	/**
	 * Returns current line number.
	 */
	public function getLine(): ?int
	{
		return isset($this->tokens[$this->position])
			? $this->tokens[$this->position]->line
			: null;
	}


	public function isInHead(): bool
	{
		return $this->inHead;
	}


	/**
	 * Adds custom method to template.
	 * @internal
	 */
	public function addMethod(
		string $name,
		string $body,
		string $arguments = '',
		string $returns = '',
		?string $comment = null
	): void {
		$body = trim($body);
		$this->methods[$name] = compact('body', 'arguments', 'returns', 'comment');
	}


	/**
	 * Returns custom methods.
	 * @return array<string, ?array{body: string, arguments: string, returns: string, comment: ?string}>
	 * @internal
	 */
	public function getMethods(): array
	{
		return $this->methods;
	}


	/**
	 * Adds custom property to template.
	 * @param  mixed  $value
	 * @internal
	 */
	public function addProperty(string $name, $value): void
	{
		$this->properties[$name] = $value;
	}


	/**
	 * Returns custom properites.
	 * @return array<string, mixed>
	 * @internal
	 */
	public function getProperties(): array
	{
		return $this->properties;
	}


	/**
	 * Adds custom constant to template.
	 * @param  mixed  $value
	 * @internal
	 */
	public function addConstant(string $name, $value): void
	{
		$this->constants[$name] = $value;
	}


	/** @internal */
	public function expandTokens(string $s): string
	{
		return strtr($s, $this->placeholders);
	}


	private function processText(Token $token): void
	{
		if (
			$this->lastAttrValue === ''
			&& $this->context
			&& Helpers::startsWith($this->context, self::CONTEXT_HTML_ATTRIBUTE)
		) {
			$this->lastAttrValue = $token->text;
		}

		$this->output .= $this->escape($token->text);
	}


	private function processMacroTag(Token $token): void
	{
		if (
			$this->context === self::CONTEXT_HTML_TAG
			|| $this->context
			&& Helpers::startsWith($this->context, self::CONTEXT_HTML_ATTRIBUTE)
		) {
			$this->lastAttrValue = true;
		}

		$isRightmost = !isset($this->tokens[$this->position + 1])
			|| substr($this->tokens[$this->position + 1]->text, 0, 1) === "\n";

		if ($token->closing) {
			$this->closeMacro($token->name, $token->value, $token->modifiers, $isRightmost);
		} else {
			$node = $this->openMacro($token->name, $token->value, $token->modifiers, $isRightmost);
			if ($token->empty) {
				if ($node->empty) {
					throw new CompileException("Unexpected /} in tag {$token->text}");
				}

				$this->closeMacro($token->name, '', '', $isRightmost);
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

				$this->closeHtmlTag("</$token->name>");
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
			if ($this->contentType === self::CONTENT_HTML) {
				$emptyElement = isset(Helpers::$emptyElements[strtolower($htmlNode->name)]);
				$htmlNode->empty = $htmlNode->empty || $emptyElement;
				if ($htmlNode->empty && !$emptyElement) { // auto-correct
					$space = substr(strstr($token->text, '>'), 1);
					$token->text = '>';
					$end = "</$htmlNode->name>" . $space;
				}
			}
		}

		if ($htmlNode->macroAttrs) {
			$html = substr($this->output, $this->tagOffset) . $token->text;
			$this->output = substr($this->output, 0, $this->tagOffset);
			$this->writeAttrsMacro($html, $emptyElement ?? null);
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
			&& (!isset($htmlNode->attrs['type']) || preg_match('#(java|j|ecma|live)script|module|json|css|plain#i', $htmlNode->attrs['type']))
		) {
			$this->context = $lower === 'script'
				? self::CONTEXT_HTML_JS
				: self::CONTEXT_HTML_CSS;
		}
	}


	private function processHtmlAttributeBegin(Token $token): void
	{
		if (Helpers::startsWith($token->name, Parser::N_PREFIX)) {
			$name = substr($token->name, strlen(Parser::N_PREFIX));
			if (isset($this->htmlNode->macroAttrs[$name])) {
				throw new CompileException("Found multiple attributes {$token->name}.");

			} elseif ($this->macroNode && $this->macroNode->htmlNode === $this->htmlNode) {
				throw new CompileException("n:attribute must not appear inside tags; found {$token->name} inside {{$this->macroNode->name}}.");
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
			if ($this->contentType === self::CONTENT_HTML) {
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
			$this->contentType === self::CONTENT_HTML
			&& (in_array($lower, ['href', 'src', 'action', 'formaction'], true)
				|| ($lower === 'data' && strtolower($this->htmlNode->name) === 'object'))
		) {
			$this->context = $this->context === self::CONTEXT_HTML_TAG
				? self::CONTEXT_HTML_ATTRIBUTE_UNQUOTED_URL
				: self::CONTEXT_HTML_ATTRIBUTE_URL;
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
	public function openMacro(
		string $name,
		string $args = '',
		string $modifiers = '',
		bool $isRightmost = false,
		?string $nPrefix = null
	): MacroNode {
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
	public function closeMacro(
		string $name,
		string $args = '',
		string $modifiers = '',
		bool $isRightmost = false,
		?string $nPrefix = null
	): MacroNode {
		$node = $this->macroNode;

		if (
			!$node
			|| ($node->name !== $name && $name !== '')
			|| $modifiers
			|| ($args !== '' && $node->args !== '' && !Helpers::startsWith($node->args . ' ', $args . ' '))
			|| $nPrefix !== $node->prefix
		) {
			$name = $nPrefix
				? "</{$this->htmlNode->name}> for " . Parser::N_PREFIX . implode(' and ' . Parser::N_PREFIX, array_keys($this->htmlNode->macroAttrs))
				: '{/' . $name . ($args ? ' ' . $args : '') . $modifiers . '}';
			throw new CompileException("Unexpected $name" . ($node ? ', expecting ' . self::printEndTag($node->prefix ? $this->htmlNode : $node) : ''));
		}

		$this->macroNode = $node->parentNode;
		if ($node->args === '') {
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
		$this->writeCode((string) $node->closingCode, $node->replaced, $isRightmost, true);
		return $node;
	}


	private function writeCode(string $code, ?bool $isReplaced, ?bool $isRightmost, bool $isClosing = false): void
	{
		if ($isRightmost) {
			$leftOfs = ($tmp = strrpos($this->output, "\n")) === false ? 0 : $tmp + 1;
			$isLeftmost = trim(substr($this->output, $leftOfs)) === '';
			if ($isReplaced === null) {
				$isReplaced = preg_match('#<\?php.*\secho\s#As', $code);
			}

			if ($isLeftmost && !$isReplaced) {
				$this->output = substr($this->output, 0, $leftOfs); // alone macro without output -> remove indentation
				if (!$isClosing && substr($code, -2) !== '?>') {
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
	public function writeAttrsMacro(string $html, ?bool $empty = null): void
	{
		//     none-2 none-1 tag-1 tag-2       <el attr-1 attr-2>   /tag-2 /tag-1 [none-2] [none-1] inner-2 inner-1
		// /inner-1 /inner-2 [none-1] [none-2] tag-1 tag-2  </el>   /tag-2 /tag-1 /none-1 /none-2
		$attrs = $this->htmlNode->macroAttrs;
		$left = $right = [];

		foreach ($this->macros as $name => $foo) {
			$attrName = MacroNode::PREFIX_INNER . "-$name";
			if (!isset($attrs[$attrName])) {
				continue;
			}
			if ($empty) {
				trigger_error("Unexpected n:$attrName on void element <{$this->htmlNode->name}> (on line {$this->getLine()}", E_USER_WARNING);
			}

			if ($this->htmlNode->closing) {
				$left[] = function () use ($name) {
					$this->closeMacro($name, '', '', false, MacroNode::PREFIX_INNER);
				};
			} else {
				array_unshift($right, function () use ($name, $attrs, $attrName) {
					if ($this->openMacro($name, $attrs[$attrName], '', false, MacroNode::PREFIX_INNER)->empty) {
						throw new CompileException("Unexpected prefix in n:$attrName.");
					}
				});
			}

			unset($attrs[$attrName]);
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
			if (!isset($attrs[$attrName])) {
				continue;
			}
			if ($empty) {
				trigger_error("Unexpected n:$attrName on void element <{$this->htmlNode->name}> (on line {$this->getLine()}", E_USER_WARNING);
			}

			$left[] = function () use ($name, $attrs, $attrName) {
				if ($this->openMacro($name, $attrs[$attrName], '', false, MacroNode::PREFIX_TAG)->empty) {
					throw new CompileException("Unexpected prefix in n:$attrName.");
				}
			};
			array_unshift($right, function () use ($name) {
				$this->closeMacro($name, '', '', false, MacroNode::PREFIX_TAG);
			});
			unset($attrs[$attrName]);
		}

		foreach ($this->macros as $name => $foo) {
			if (isset($attrs[$name])) {
				if ($this->htmlNode->closing) {
					$right[] = function () use ($name) {
						$this->closeMacro($name, '', '', false, MacroNode::PREFIX_NONE);
					};
				} else {
					array_unshift($left, function () use ($name, $attrs, &$innerMarker) {
						$node = $this->openMacro($name, $attrs[$name], '', false, MacroNode::PREFIX_NONE);
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
	public function expandMacro(string $name, string $args, string $modifiers = '', ?string $nPrefix = null): MacroNode
	{
		if (empty($this->macros[$name])) {
			$hint = (($t = Helpers::getSuggestion(array_keys($this->macros), $name)) ? ", did you mean {{$t}}?" : '')
				. (in_array($this->context, [self::CONTEXT_HTML_JS, self::CONTEXT_HTML_CSS], true) ? ' (in JavaScript or CSS, try to put a space after bracket or use n:syntax=off)' : '');
			throw new CompileException("Unknown tag {{$name}}$hint");

		} elseif ($this->policy && !$this->policy->isMacroAllowed($name)) {
			throw new SecurityViolationException('Tag ' . ($nPrefix ? "n:$name" : "{{$name}}") . ' is not allowed.');
		}

		$modifiers = (string) $modifiers;

		if (strpbrk($name, '=~%^&_')) {
			if (in_array($this->context, [self::CONTEXT_HTML_ATTRIBUTE_URL, self::CONTEXT_HTML_ATTRIBUTE_UNQUOTED_URL], true)) {
				if (!Helpers::removeFilter($modifiers, 'nocheck')) {
					if (!preg_match('#\|datastream(?=\s|\||$)#Di', $modifiers)) {
						$modifiers .= '|checkUrl';
					}
				} elseif ($this->policy && !$this->policy->isFilterAllowed('nocheck')) {
					throw new SecurityViolationException('Filter |nocheck is not allowed.');
				}
			}

			if (!Helpers::removeFilter($modifiers, 'noescape')) {
				$modifiers .= '|escape';
			} elseif ($this->policy && !$this->policy->isFilterAllowed('noescape')) {
				throw new SecurityViolationException('Filter |noescape is not allowed.');
			}

			if (
				$this->context === self::CONTEXT_HTML_JS
				&& $name === '='
				&& preg_match('#["\']$#D', $this->tokens[$this->position - 1]->text)
			) {
				throw new CompileException("Do not place {$this->tokens[$this->position]->text} inside quotes in JavaScript.");
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
			: 'tag {' . $name . ($args ? " $args" : '') . '}'
		));
	}


	/**
	 * @param  HtmlNode|MacroNode  $node
	 */
	private static function printEndTag($node): string
	{
		return $node instanceof HtmlNode
			? "</{$node->name}> for " . Parser::N_PREFIX . implode(' and ' . Parser::N_PREFIX, array_keys($node->macroAttrs))
			: "{/{$node->name}}";
	}


	private function closeHtmlTag($token): void
	{
		if ($this->htmlNode->macroAttrs) {
			throw new CompileException("Unexpected $token, expecting " . self::printEndTag($this->htmlNode));
		} elseif ($this->contentType === self::CONTENT_HTML
			&& in_array(strtolower($this->htmlNode->name), ['script', 'style'], true)
		) {
			trigger_error("Unexpected $token, expecting </{$this->htmlNode->name}> (on line {$this->getLine()})", E_USER_DEPRECATED);
		}

		$this->htmlNode = $this->htmlNode->parentNode;
	}
}
