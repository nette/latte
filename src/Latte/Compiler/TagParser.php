<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte;
use Latte\Compiler\Nodes\Php as Node;
use Latte\Compiler\Nodes\Php\Expression;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\NameNode;
use Latte\Compiler\Nodes\Php\Scalar;


/**
 * Parser for PHP-like expression language used in tags.
 * Based on works by Nikita Popov, Moriyoshi Koizumi and Masato Bito.
 */
final class TagParser extends TagParserData
{
	use Latte\Strict;

	private const
		SchemaExpression = 'e',
		SchemaArguments = 'a',
		SchemaFilters = 'm';

	private const SymbolNone = -1;

	public TokenStream /*readonly*/ $stream;
	public string $text;
	private int /*readonly*/ $offsetDelta;


	public function __construct(array $tokens)
	{
		$this->offsetDelta = $tokens[0]->position->offset ?? 0;
		$tokens = $this->filterTokens($tokens);
		$this->stream = new TokenStream(new \ArrayIterator($tokens));
	}


	public function parseExpression(): ExpressionNode
	{
		return $this->parse(self::SchemaExpression, recovery: true);
	}


	public function parseArguments(): Expression\ArrayNode
	{
		return $this->parse(self::SchemaArguments, recovery: true);
	}


	public function parseModifier(): Node\ModifierNode
	{
		return $this->isEnd()
			? new Node\ModifierNode([])
			: $this->parse(self::SchemaFilters);
	}


	public function isEnd(): bool
	{
		return $this->stream->peek()->isEnd();
	}


	public function parseUnquotedStringOrExpression(bool $colon = true): ExpressionNode
	{
		$position = $this->stream->peek()->position;
		$lexer = new TagLexer;
		$tokens = $lexer->tokenizeUnquotedString($this->text, $position, $colon, $this->offsetDelta);

		if (!$tokens) {
			return $this->parseExpression();
		}

		$parser = new self($tokens);
		$end = $position->offset + strlen($parser->text) - 2; // 2 quotes
		do {
			$this->stream->consume();
		} while ($this->stream->peek()->position->offset < $end);

		return $parser->parseExpression();
	}


	public function tryConsumeModifier(string ...$modifiers): ?Token
	{
		$token = $this->stream->peek();
		return $token->is(...$modifiers) // is followed by whitespace
			&& $this->stream->peek(1)->position->offset > $token->position->offset + strlen($token->text)
			? $this->stream->consume()
			: null;
	}


	public function parseType(): ?string
	{
		$kind = [
			Token::Php_Identifier, Token::Php_Constant, Token::Php_Ellipsis, Token::Php_Array, Token::Php_Integer,
			Token::Php_NameFullyQualified, Token::Php_NameQualified, Token::Php_Null, Token::Php_False,
			'(', ')', '<', '>', '[', ']', '|', '&', '{', '}', ':', ',', '=', '?',
		];
		$res = null;
		while ($token = $this->stream->tryConsume(...$kind)) {
			$res .= $token->text;
		}

		return $res;
	}


	/** @throws Latte\CompileException */
	private function parse(string $schema, bool $recovery = false): mixed
	{
		$symbol = self::SymbolNone; // We start off with no lookahead-token
		$this->startTokenStack = []; // Keep stack of start token
		$token = null;
		$state = 0; // Start off in the initial state and keep a stack of previous states
		$stateStack = [$state];
		$this->semStack = []; // Semantic value stack (contains values of tokens and semantic action results)
		$stackPos = 0; // Current position in the stack(s)

		do {
			if (self::ActionBase[$state] === 0) {
				$rule = self::ActionDefault[$state];
			} else {
				if ($symbol === self::SymbolNone) {
					$recovery = $recovery
						? [$this->stream->getIndex(), $state, $stateStack, $stackPos, $this->semValue, $this->semStack, $this->startTokenStack]
						: null;


					if ($token) {
						$prevToken = $token;
						$token = $this->stream->consume();
					} else {
						$token = new Token(ord($schema), $schema);
					}

					recovery:
					$symbol = self::TokenToSymbol[$token->type];
				}

				$idx = self::ActionBase[$state] + $symbol;
				if ((($idx >= 0 && $idx < count(self::Action) && self::ActionCheck[$idx] === $symbol)
					|| ($state < self::Yy2Tblstate
						&& ($idx = self::ActionBase[$state + self::NumNonLeafStates] + $symbol) >= 0
						&& $idx < count(self::Action) && self::ActionCheck[$idx] === $symbol))
					&& ($action = self::Action[$idx]) !== self::DefaultAction
				) {
					/*
					>= numNonLeafStates: shift and reduce
					> 0: shift
					= 0: accept
					< 0: reduce
					= -YYUNEXPECTED: error
					*/
					if ($action > 0) { // shift
						++$stackPos;
						$stateStack[$stackPos] = $state = $action;
						$this->semStack[$stackPos] = $token->text;
						$this->startTokenStack[$stackPos] = $token;
						$symbol = self::SymbolNone;
						if ($action < self::NumNonLeafStates) {
							continue;
						}

						$rule = $action - self::NumNonLeafStates; // shift-and-reduce
					} else {
						$rule = -$action;
					}
				} else {
					$rule = self::ActionDefault[$state];
				}
			}

			do {
				if ($rule === 0) { // accept
					return $this->semValue;

				} elseif ($rule !== self::UnexpectedTokenRule) { // reduce
					$this->reduce($rule, $stackPos);

					// Goto - shift nonterminal
					$ruleLength = self::RuleToLength[$rule];
					$stackPos -= $ruleLength;
					$nonTerminal = self::RuleToNonTerminal[$rule];
					$idx = self::GotoBase[$nonTerminal] + $stateStack[$stackPos];
					if ($idx >= 0 && $idx < count(self::Goto) && self::GotoCheck[$idx] === $nonTerminal) {
						$state = self::Goto[$idx];
					} else {
						$state = self::GotoDefault[$nonTerminal];
					}

					++$stackPos;
					$stateStack[$stackPos] = $state;
					$this->semStack[$stackPos] = $this->semValue;
					if ($ruleLength === 0) {
						$this->startTokenStack[$stackPos] = $token;
					}

				} else { // error
					if ($prevToken->is('echo', 'print', 'return', 'yield', 'throw', 'if', 'foreach', 'unset')) {
						throw new Latte\CompileException("Keyword '$prevToken->text' is forbidden in Latte", $prevToken->position);
					}

					if ($recovery && $this->isExpectedEof($state)) {
						[, $state, $stateStack, $stackPos, $this->semValue, $this->semStack, $this->startTokenStack] = $recovery;
						$this->stream->seek($recovery[0]);
						$token = new Token(Token::End, '');
						goto recovery;
					}

					throw new Latte\CompileException('Unexpected ' . ($token->text ? "'$token->text'" : 'end'), $token->position);
				}

				if ($state < self::NumNonLeafStates) {
					break;
				}

				$rule = $state - self::NumNonLeafStates; // shift-and-reduce
			} while (true);
		} while (true);
	}


	/**
	 * Can EOF be the next token?
	 */
	private function isExpectedEof(int $state): bool
	{
		foreach (self::SymbolToName as $symbol => $name) {
			$idx = self::ActionBase[$state] + $symbol;
			if (($idx >= 0 && $idx < count(self::Action) && self::ActionCheck[$idx] === $symbol
					|| $state < self::Yy2Tblstate
					&& ($idx = self::ActionBase[$state + self::NumNonLeafStates] + $symbol) >= 0
					&& $idx < count(self::Action) && self::ActionCheck[$idx] === $symbol)
				&& self::Action[$idx] !== self::UnexpectedTokenRule
				&& self::Action[$idx] !== self::DefaultAction
				&& $symbol === 0
			) {
				return true;
			}
		}

		return false;
	}


	protected static function handleBuiltinTypes(NameNode $name): NameNode|Node\IdentifierNode
	{
		$builtinTypes = [
			'bool' => true, 'int' => true, 'float' => true, 'string' => true, 'iterable' => true, 'void' => true,
			'object' => true, 'null' => true, 'false' => true, 'mixed' => true, 'never' => true,
		];

		$lowerName = strtolower($name->toCodeString());
		return isset($builtinTypes[$lowerName])
			? new Node\IdentifierNode($lowerName, $name->position)
			: $name;
	}


	protected static function parseOffset(string $str, Position $position): Scalar\StringNode|Scalar\IntegerNode
	{
		if (!preg_match('/^(?:0|-?[1-9][0-9]*)$/', $str)) {
			return new Scalar\StringNode($str, $position);
		}

		$num = +$str;
		if (!is_int($num)) {
			return new Scalar\StringNode($str, $position);
		}

		return new Scalar\IntegerNode($num, Scalar\IntegerNode::KindDecimal, $position);
	}


	/** @param  Token[]  $tokens */
	private function filterTokens(array $tokens): array
	{
		$this->text = '';
		$res = [];
		foreach ($tokens as $token) {
			$this->text .= $token->text;
			if (!$token->is(Token::Php_Whitespace, Token::Php_Comment)) {
				$res[] = $token;
			}
		}

		return $res;
	}
}
