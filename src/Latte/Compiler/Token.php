<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use function in_array;


final class Token
{
	public const
		End = 0,
		Text = 10000,
		Whitespace = 10002,
		Newline = 10003,
		Indentation = 10004,
		Slash = 10005,
		Equals = 10006,
		Quote = 10007; // single or double quote

	public const
		Latte_TagOpen = 10010,
		Latte_TagClose = 10011,
		Latte_Name = 10012,
		Latte_CommentOpen = 10014,
		Latte_CommentClose = 10015;

	public const
		Html_TagOpen = 10020,
		Html_TagClose = 10021,
		Html_CommentOpen = 10022,
		Html_CommentClose = 10023,
		Html_BogusOpen = 10024,
		Html_Name = 10025;

	public const
		Php_LogicalOr = 257,
		Php_LogicalXor = 258,
		Php_LogicalAnd = 259,
		Php_DoubleArrow = 260,
		Php_PlusEqual = 261,
		Php_MinusEqual = 262,
		Php_MulEqual = 263,
		Php_DivEqual = 264,
		Php_ConcatEqual = 265,
		Php_ModEqual = 266,
		Php_AndEqual = 267,
		Php_OrEqual = 268,
		Php_XorEqual = 269,
		Php_SlEqual = 270,
		Php_SrEqual = 271,
		Php_PowEqual = 272,
		Php_CoalesceEqual = 273,
		Php_Coalesce = 274,
		Php_BooleanOr = 275,
		Php_BooleanAnd = 276,
		Php_FilterPipe = 277,
		Php_NullsafePipe = 278,
		Php_AmpersandNotFollowed = 279,
		Php_AmpersandFollowed = 280,
		Php_IsEqual = 281,
		Php_IsNotEqual = 282,
		Php_IsIdentical = 283,
		Php_IsNotIdentical = 284,
		Php_Spaceship = 285,
		Php_IsSmallerOrEqual = 286,
		Php_IsGreaterOrEqual = 287,
		Php_Pipe = 288,
		Php_Sl = 289,
		Php_Sr = 290,
		Php_In = 291,
		Php_Instanceof = 292,
		Php_Inc = 293,
		Php_Dec = 294,
		Php_IntCast = 295,
		Php_FloatCast = 296,
		Php_StringCast = 297,
		Php_ArrayCast = 298,
		Php_ObjectCast = 299,
		Php_BoolCast = 300,
		Php_Pow = 301,
		Php_New = 302,
		Php_Clone = 303,
		Php_Integer = 304,
		Php_Float = 305,
		Php_Identifier = 306,
		Php_StringVarname = 307,
		Php_Constant = 308,
		Php_Variable = 309,
		Php_NumString = 310,
		Php_EncapsedAndWhitespace = 311,
		Php_ConstantEncapsedString = 312,
		Php_Match = 313,
		Php_Default = 314,
		Php_Function = 315,
		Php_Fn = 316,
		Php_Return = 317,
		Php_Use = 318,
		Php_Isset = 319,
		Php_Empty = 320,
		Php_ObjectOperator = 321,
		Php_NullsafeObjectOperator = 322,
		Php_UndefinedsafeObjectOperator = 323,
		Php_List = 324,
		Php_Array = 325,
		Php_StartHeredoc = 326,
		Php_EndHeredoc = 327,
		Php_DollarOpenCurlyBraces = 328,
		Php_CurlyOpen = 329,
		Php_PaamayimNekudotayim = 330,
		Php_NsSeparator = 331,
		Php_Ellipsis = 332,
		Php_ExpandCast = 333,
		Php_NameFullyQualified = 334,
		Php_NameQualified = 335,
		Php_Whitespace = 336,
		Php_Comment = 337,
		Php_Null = 338,
		Php_True = 339,
		Php_False = 340;

	public const Names = [
		self::End => '[EOF]',
		self::Text => 'text',
		self::Whitespace => 'whitespace',
		self::Newline => 'newline',
		self::Indentation => 'indentation',
		self::Slash => "'/'",
		self::Equals => "'='",
		self::Quote => 'quote',

		self::Latte_TagOpen => 'Latte tag',
		self::Latte_TagClose => 'end of Latte tag',
		self::Latte_Name => 'tag name',
		self::Latte_CommentOpen => 'Latte comment',
		self::Latte_CommentClose => 'end of Latte comment',

		self::Html_TagOpen => 'HTML tag',
		self::Html_TagClose => 'end of HTML tag',
		self::Html_CommentOpen => 'HTML comment',
		self::Html_CommentClose => 'end of HTML comment',
		self::Html_BogusOpen => 'HTML bogus tag',
		self::Html_Name => 'HTML name',

		self::Php_LogicalOr => "'or'",
		self::Php_LogicalXor => "'xor'",
		self::Php_LogicalAnd => "'and'",
		self::Php_DoubleArrow => "'=>'",
		self::Php_PlusEqual => "'+='",
		self::Php_MinusEqual => "'-='",
		self::Php_MulEqual => "'*='",
		self::Php_DivEqual => "'/='",
		self::Php_ConcatEqual => "'.='",
		self::Php_ModEqual => "'%='",
		self::Php_AndEqual => "'&='",
		self::Php_OrEqual => "'|='",
		self::Php_XorEqual => "'^='",
		self::Php_SlEqual => "'<<='",
		self::Php_SrEqual => "'>>='",
		self::Php_PowEqual => "'**='",
		self::Php_CoalesceEqual => "'??='",
		self::Php_Coalesce => "'??'",
		self::Php_BooleanOr => "'||'",
		self::Php_BooleanAnd => "'&&'",
		self::Php_FilterPipe => "'|'",
		self::Php_NullsafePipe => "'?|",
		self::Php_AmpersandNotFollowed => "'&'",
		self::Php_AmpersandFollowed => "'&'",
		self::Php_IsEqual => "'=='",
		self::Php_IsNotEqual => "'!='",
		self::Php_IsIdentical => "'==='",
		self::Php_IsNotIdentical => "'!=='",
		self::Php_Spaceship => "'<=>'",
		self::Php_IsSmallerOrEqual => "'<='",
		self::Php_IsGreaterOrEqual => "'>='",
		self::Php_Pipe => "'|>'",
		self::Php_Sl => "'<<'",
		self::Php_Sr => "'>>'",
		self::Php_In => "'in'",
		self::Php_Instanceof => "'instanceof'",
		self::Php_Inc => "'++'",
		self::Php_Dec => "'--'",
		self::Php_IntCast => "'(int)'",
		self::Php_FloatCast => "'(float)'",
		self::Php_StringCast => "'(string)'",
		self::Php_ArrayCast => "'(array)'",
		self::Php_ObjectCast => "'(object)'",
		self::Php_BoolCast => "'(bool)'",
		self::Php_Pow => "'**'",
		self::Php_New => "'new'",
		self::Php_Clone => "'clone'",
		self::Php_Integer => 'integer',
		self::Php_Float => 'floating-point number',
		self::Php_Identifier => 'identifier',
		self::Php_StringVarname => 'variable name',
		self::Php_Constant => 'constant',
		self::Php_Variable => 'variable',
		self::Php_NumString => 'number',
		self::Php_EncapsedAndWhitespace => 'string content',
		self::Php_ConstantEncapsedString => 'quoted string',
		self::Php_Match => "'match'",
		self::Php_Default => "'default'",
		self::Php_Function => "'function'",
		self::Php_Fn => "'fn'",
		self::Php_Return => "'return'",
		self::Php_Use => "'use'",
		self::Php_Isset => "'isset'",
		self::Php_Empty => "'empty'",
		self::Php_ObjectOperator => "'->'",
		self::Php_NullsafeObjectOperator => "'?->'",
		self::Php_UndefinedsafeObjectOperator => "'??->'",
		self::Php_List => "'list'",
		self::Php_Array => "'array'",
		self::Php_StartHeredoc => 'heredoc start',
		self::Php_EndHeredoc => 'heredoc end',
		self::Php_DollarOpenCurlyBraces => "'\${'",
		self::Php_CurlyOpen => "'{\$'",
		self::Php_PaamayimNekudotayim => "'::'",
		self::Php_NsSeparator => "'\\'",
		self::Php_Ellipsis => "'...'",
		self::Php_ExpandCast => "'(expand)'",
		self::Php_NameFullyQualified => 'fully qualified name',
		self::Php_NameQualified => 'namespaced name',
		self::Php_Whitespace => 'whitespace',
		self::Php_Comment => 'comment',
		self::Php_Null => "'null'",
		self::Php_True => "'true'",
		self::Php_False => "'false'",
	];


	public function __construct(
		public readonly int $type,
		public readonly string $text,
		public readonly ?Position $position = null,
	) {
	}


	public function is(int|string ...$kind): bool
	{
		return in_array($this->type, $kind, true)
			|| in_array($this->text, $kind, true);
	}


	public function isEnd(): bool
	{
		return $this->type === self::End;
	}


	public function isPhpKind(): bool
	{
		return $this->type > 0 && $this->type < 10000;
	}
}
