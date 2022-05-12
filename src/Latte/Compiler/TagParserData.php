<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\Compiler\Nodes\Php as Node;
use Latte\Compiler\Nodes\Php\Expression;
use Latte\Compiler\Nodes\Php\Scalar;


/** @internal generated trait used by TagParser */
abstract class TagParserData
{
	/** Symbol number of error recovery token */
	protected const ErrorSymbol = 1;

	/** Action number signifying default action */
	protected const DefaultAction = -8190;

	/** Rule number signifying that an unexpected token was encountered */
	protected const UnexpectedTokenRule = 8191;

	protected const Yy2Tblstate = 247;

	/** Number of non-leaf states */
	protected const NumNonLeafStates = 341;

	/** Map of lexer tokens to internal symbols */
	protected const TokenToSymbol = [
		0,     110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,
		110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   48,    105,   110,   106,   47,    110,   110,
		99,    100,   45,    42,    2,     43,    44,    46,    110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   22,    103,
		35,    7,     37,    21,    59,    110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,
		110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   61,    110,   104,   27,    110,   110,   98,    110,   110,
		110,   96,    110,   110,   110,   110,   110,   110,   110,   97,    110,   110,   110,   110,   110,   110,   110,   110,   110,   110,
		110,   110,   110,   101,   26,    102,   50,    110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,
		110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,
		110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,
		110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,
		110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,
		110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,
		110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   110,   1,     3,     4,     5,
		6,     8,     9,     10,    11,    12,    13,    14,    15,    16,    17,    18,    19,    20,    23,    24,    25,    28,    29,    30,
		31,    32,    33,    34,    36,    38,    39,    40,    41,    49,    51,    52,    53,    54,    55,    56,    57,    58,    60,    62,
		63,    64,    65,    66,    67,    68,    69,    70,    71,    72,    73,    74,    75,    76,    77,    78,    79,    80,    81,    82,
		83,    84,    85,    86,    87,    88,    107,   89,    90,    91,    92,    108,   109,   93,    94,    95,
	];

	/** Map of states to a displacement into the self::Action table. The corresponding action for this
	 *  state/symbol pair is self::Action[self::ActionBase[$state] + $symbol]. If self::ActionBase[$state] is 0, the
	 *  action is defaulted, i.e. self::ActionDefault[$state] should be used instead. */
	protected const ActionBase = [
		315,   139,   139,   139,   139,   98,    139,   139,   219,   219,   219,   219,   219,   197,   197,   197,   324,   324,   316,   302,
		-42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,
		-42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,
		-42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,
		-42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,
		-42,   -42,   155,   243,   357,   359,   358,   360,   369,   370,   371,   372,   377,   51,    51,    51,    51,    51,    51,    51,
		51,    51,    51,    51,    51,    51,    51,    162,   200,   254,   309,   309,   309,   309,   309,   309,   309,   309,   309,   309,
		309,   309,   309,   309,   309,   309,   309,   309,   206,   206,   206,   366,   353,   338,   223,   -66,   326,   326,   125,   125,
		125,   125,   125,   260,   260,   260,   260,   77,    77,    77,    77,    184,   184,   184,   184,   184,   184,   184,   184,   7,
		7,     41,    240,   273,   273,   273,   100,   100,   100,   40,    40,    40,    40,    40,    101,   -22,   109,   285,   313,   313,
		313,   313,   313,   313,   320,   190,   311,   311,   314,   91,    91,    91,    311,   232,   -41,   -13,   327,   340,   300,   45,
		244,   5,     356,   264,   266,   134,   330,   330,   330,   330,   197,   322,   322,   197,   197,   197,   -1,    -1,    -1,    192,
		331,   222,   96,    361,   331,   331,   331,   18,    34,    22,    342,   241,   342,   342,   30,    71,    39,    342,   342,   342,
		342,   102,   39,    39,    307,   335,   305,   161,   56,    305,   317,   317,   108,   4,     344,   343,   345,   341,   339,   362,
		245,   247,   329,   328,   337,   281,   248,   344,   343,   345,   298,   245,   304,   304,   304,   334,   304,   304,   304,   304,
		304,   304,   304,   323,   16,    332,   346,   347,   350,   351,   301,   299,   364,   304,   303,   352,   333,   306,   374,   245,
		336,   375,   325,   318,   319,   238,   349,   312,   376,   354,   365,   173,   348,   180,   367,   188,   363,   255,   355,   373,
		368,   0,     -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,
		-42,   0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     51,    51,    51,    51,    51,    51,    51,    51,    51,    51,    51,    0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     51,    51,    51,    51,    51,    51,    51,    51,    51,    51,    51,    51,
		51,    51,    51,    51,    51,    51,    51,    51,    51,    51,    51,    51,    51,    51,    51,    0,     260,   51,    51,    51,
		51,    51,    51,    51,    0,     0,     0,     0,     91,    91,    91,    91,    40,    40,    40,    40,    40,    40,    40,    40,
		40,    40,    40,    40,    91,    91,    91,    40,    40,    40,    0,     0,     0,     0,     0,     0,     0,     0,     0,     317,
		317,   317,   317,   317,   317,   317,   0,     0,     0,     0,     0,     0,     0,     0,     317,   317,   0,     0,     0,     0,
		0,     0,     0,     197,   197,   197,   317,   0,     0,     0,     0,     0,     317,   317,   0,     0,     0,     0,     0,     0,
		0,     304,   0,     0,     16,    304,   304,   304,
	];

	/** Table of actions. Indexed according to self::ActionBase comment. */
	protected const Action = [
		26,    27,    327,   367,   0,     -255,  28,    -255,  29,    171,   172,   30,    31,    32,    33,    34,    35,    36,    6,     1,
		186,   37,    534,   535,   195,   614,   517,   367,   189,   615,   532,   274,   7,     233,   234,   91,    12,    275,   276,   -210,
		279,   11,    277,   278,   190,   -73,   191,   -73,   -209,  519,   518,   540,   538,   539,   46,    47,    48,    21,    17,    -210,
		-210,  -210,  189,   213,   279,   365,   -174,  366,   -209,  -209,  -209,  -73,   19,    93,    49,    50,    51,    -174,  52,    53,
		54,    55,    56,    57,    58,    59,    60,    61,    62,    63,    64,    65,    66,    67,    68,    69,    70,    71,    72,    24,
		188,   348,   349,   347,   181,   -255,  515,   -45,   517,   -255,  222,   73,    -8191, -8191, -8191, -8191, 64,    65,    66,    67,
		68,    69,    166,   -21,   503,   396,   346,   345,   -8190, -8190, -8190, 519,   518,   398,   427,   397,   70,    71,    72,    358,
		188,   -28,   348,   349,   347,   -73,   -8190, 352,   -8190, -8190, -8190, 73,    -8190, -8190, -8190, -8191, -8191, -8191, -8191, -8191,
		187,   38,    -210,  369,   194,   -8190, 286,   346,   345,   367,   -206,  287,   354,   223,   224,   353,   359,   288,   289,   98,
		358,   165,   -210,  -210,  -210,  -8190, 99,    90,    352,   -174,  -206,  -206,  -206,  -8190, 100,   -8190, 486,   -206,  407,   42,
		-174,  187,   38,    -8190, -8190, 194,   279,   286,   -213,  -8190, -8190, -8190, 287,   354,   223,   224,   353,   359,   288,   289,
		18,    174,   348,   349,   347,   346,   345,   -8190, 39,    40,    13,    74,    75,    76,    77,    78,    79,    80,    81,    82,
		83,    84,    85,    86,    -252,  1,     -252,  346,   345,   14,    241,   -207,  87,    367,   88,    197,   532,   -8190, -8190, -8190,
		358,   189,   101,   348,   349,   347,   193,   163,   352,   278,   -22,   -207,  -207,  -207,  449,   451,   95,    -8190, -207,  -8190,
		-175,  187,   38,    41,    -204,  194,   -16,   286,   -15,   213,   279,   -175,  287,   354,   223,   224,   353,   359,   288,   289,
		-249,  358,   -249,  603,   -204,  -204,  -204,  164,   97,    352,   -174,  -204,  -8190, -8190, -8190, 67,    68,    69,    18,    174,
		15,    -174,  351,   350,   89,    96,    362,   182,   363,   -8190, -8190, -8190, 8,     355,   354,   357,   356,   353,   359,   360,
		361,   -8190, -8190, -8190, -252,  192,   -205,  -8190, -252,  -8190, -8190, -8190, 9,     -8190, -8190, -8190, -8190, -8190, -8190, -8190,
		94,    -8190, -8190, -8190, 73,    -8190, -205,  -205,  -205,  -8190, -8190, -8190, -204,  -205,  -8190, 23,    -8190, -8190, -8190, 365,
		188,   366,   616,   310,   -212,  332,   367,   -8190, 22,    -8190, -8190, 603,   -204,  -204,  -204,  201,   202,   203,   267,   -204,
		-249,  214,   232,   611,   -249,  -238,  18,    174,   198,   199,   200,   25,    251,   3,     92,    0,     -183,  155,   264,   279,
		231,   236,   237,   238,   377,   533,   16,    529,   279,   10,    0,     0,     -236,  -213,  0,     -212,  -211,  0,     2,     0,
		4,     5,     20,    43,    44,    179,   180,   230,   0,     266,   491,   530,   409,   0,     408,   506,   -28,   320,   322,   522,
		492,   583,   0,     45,    249,   0,     613,   378,   0,     612,   502,   610,   567,   578,   581,   0,     340,   0,     556,   571,
		606,   334,
	];

	/** Table indexed analogously to self::Action. If self::ActionCheck[self::ActionBase[$state] + $symbol] != $symbol
	 *  then the action is defaulted, i.e. self::ActionDefault[$state] should be used instead. */
	protected const ActionCheck = [
		42,    43,    43,    69,    0,     0,     48,    2,     50,    51,    52,    53,    54,    55,    56,    57,    58,    59,    2,     61,
		62,    63,    64,    65,    66,    66,    68,    69,    21,    70,    72,    73,    2,     75,    76,    101,   2,     79,    80,    61,
		106,   2,     84,    85,    26,    0,     28,    2,     61,    91,    92,    93,    94,    95,    3,     4,     5,     99,    2,     81,
		82,    83,    21,    105,   106,   66,    88,    68,    81,    82,    83,    26,    21,    2,     23,    24,    25,    99,    27,    28,
		29,    30,    31,    32,    33,    34,    35,    36,    37,    38,    39,    40,    41,    42,    43,    44,    45,    46,    47,    77,
		49,    3,     4,     5,     2,     100,   66,    100,   68,    104,   2,     60,    35,    36,    37,    38,    39,    40,    41,    42,
		43,    44,    26,    22,    102,   85,    28,    29,    3,     4,     5,     91,    92,    93,    100,   95,    45,    46,    47,    41,
		49,    100,   3,     4,     5,     100,   21,    49,    23,    24,    25,    60,    27,    28,    29,    30,    31,    32,    33,    34,
		62,    63,    61,    2,     66,    3,     68,    28,    29,    69,    61,    73,    74,    75,    76,    77,    78,    79,    80,    6,
		41,    26,    81,    82,    83,    85,    6,     89,    49,    88,    81,    82,    83,    93,    6,     95,    100,   88,    100,   99,
		99,    62,    63,    3,     4,     66,    106,   68,    99,    3,     4,     5,     73,    74,    75,    76,    77,    78,    79,    80,
		86,    87,    3,     4,     5,     28,    29,    21,    89,    90,    7,     8,     9,     10,    11,    12,    13,    14,    15,    16,
		17,    18,    19,    20,    0,     61,    2,     28,    29,    6,     66,    61,    7,     69,    7,     100,   72,    3,     4,     5,
		41,    21,    7,     3,     4,     5,     28,    26,    49,    85,    22,    81,    82,    83,    51,    52,    22,    93,    88,    95,
		88,    62,    63,    99,    61,    66,    22,    68,    22,    105,   106,   99,    73,    74,    75,    76,    77,    78,    79,    80,
		0,     41,    2,     71,    81,    82,    83,    26,    89,    49,    88,    88,    3,     4,     5,     42,    43,    44,    86,    87,
		22,    99,    62,    63,    22,    22,    66,    22,    68,    3,     4,     5,     2,     73,    74,    75,    76,    77,    78,    79,
		80,    3,     4,     5,     100,   26,    61,    21,    104,   23,    24,    25,    22,    27,    28,    29,    3,     4,     5,     21,
		61,    23,    24,    25,    60,    27,    81,    82,    83,    3,     4,     5,     61,    88,    21,    61,    23,    24,    25,    66,
		49,    68,    70,    67,    99,    78,    69,    21,    61,    23,    24,    71,    81,    82,    83,    81,    82,    83,    74,    88,
		100,   61,    88,    102,   104,   99,    86,    87,    81,    82,    83,    96,    97,    98,    101,   -1,    88,    88,    100,   106,
		88,    81,    82,    83,    89,    105,   101,   104,   106,   99,    -1,    -1,    99,    99,    -1,    99,    99,    -1,    99,    -1,
		99,    99,    99,    99,    99,    99,    99,    99,    -1,    100,   100,   100,   100,   -1,    100,   100,   100,   100,   100,   100,
		100,   100,   -1,    101,   101,   -1,    102,   102,   -1,    102,   102,   102,   102,   102,   102,   -1,    103,   -1,    104,   104,
		104,   104,
	];

	/** Map of states to their default action */
	protected const ActionDefault = [
		8191,  247,   247,   30,    247,   8191,  247,   28,    8191,  8191,  8191,  28,    8191,  8191,  8191,  8191,  38,    28,    8191,  8191,
		8191,  8191,  202,   202,   202,   8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  9,     8191,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  69,    8191,  8191,  28,    8191,  8191,  8191,  8191,  8191,  8191,
		8191,  8191,  8191,  248,   8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  1,     256,   257,   78,    72,    203,   251,
		254,   74,    77,    75,    42,    43,    49,    114,   116,   148,   115,   90,    95,    96,    97,    98,    99,    100,   101,   102,
		103,   104,   105,   106,   107,   88,    89,    160,   149,   147,   146,   112,   113,   119,   87,    8191,  117,   118,   136,   137,
		134,   135,   138,   8191,  8191,  8191,  8191,  139,   140,   141,   142,   8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  62,
		62,    62,    8191,  126,   127,   129,   8191,  10,    8191,  8191,  8191,  8191,  8191,  8191,  196,   196,   195,   144,   8191,  8191,
		8191,  8191,  8191,  8191,  8191,  201,   109,   111,   180,   121,   122,   120,   91,    8191,  8191,  200,   8191,  264,   204,   204,
		204,   204,   33,    33,    33,    8191,  83,    83,    83,    83,    33,    8191,  8191,  33,    33,    33,    8191,  8191,  8191,  186,
		132,   210,   8191,  8191,  123,   124,   125,   50,    8191,  8191,  184,   8191,  173,   8191,  27,    27,    27,    8191,  222,   223,
		224,   27,    27,    27,    164,   35,    64,    27,    27,    64,    8191,  8191,  27,    8191,  8191,  8191,  8191,  8191,  8191,  8191,
		8191,  190,   8191,  208,   220,   2,     176,   14,    19,    20,    8191,  250,   130,   131,   133,   206,   152,   153,   154,   155,
		156,   157,   158,   8191,  243,   179,   8191,  8191,  8191,  8191,  263,   8191,  204,   128,   8191,  8191,  187,   227,   8191,  253,
		205,   8191,  8191,  8191,  52,    53,    8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  48,    8191,  8191,
		8191,
	];

	/** Map of non-terminals to a displacement into the self::Goto table. The corresponding goto state for this
	 *  non-terminal/state pair is self::Goto[self::GotoBase[$nonTerminal] + $state] (unless defaulted) */
	protected const GotoBase = [
		0,     0,     -5,    0,     0,     96,    0,     141,   -7,    -38,   -94,   0,     258,   15,    0,     0,     0,     0,     209,   220,
		65,    0,     66,    0,     80,    62,    0,     0,     -66,   -2,    -16,   109,   0,     -10,   8,     0,     0,     -64,   221,   19,
		0,     75,    0,     0,     124,   0,     0,     0,     36,    0,     0,     0,     0,     -54,   -51,   0,     0,     27,    35,    152,
		43,    0,     0,     0,     -43,   57,    0,     -61,   168,   171,   21,    51,    0,     0,
	];

	/** Table of states to goto after reduction. Indexed according to self::GotoBase comment. */
	protected const Goto = [
		117,   412,   412,   117,   117,   117,   117,   117,   131,   119,   120,   116,   116,   108,   129,   116,   102,   118,   118,   118,
		113,   292,   293,   240,   294,   296,   297,   298,   299,   300,   301,   302,   435,   435,   114,   115,   104,   105,   106,   107,
		109,   127,   128,   130,   148,   151,   152,   153,   156,   157,   158,   159,   160,   161,   162,   167,   168,   169,   170,   183,
		184,   185,   209,   210,   211,   244,   245,   246,   313,   132,   133,   134,   135,   136,   137,   138,   139,   140,   141,   142,
		143,   144,   145,   146,   149,   121,   110,   111,   122,   112,   150,   123,   121,   124,   147,   125,   126,   173,   173,   173,
		173,   175,   173,   173,   175,   175,   175,   175,   175,   176,   177,   178,   317,   395,   395,   395,   395,   262,   263,   248,
		514,   514,   514,   395,   395,   395,   395,   395,   516,   516,   516,   516,   305,   305,   305,   516,   516,   516,   516,   516,
		516,   516,   516,   570,   570,   570,   314,   321,   339,   314,   314,   314,   314,   314,   309,   582,   582,   582,   582,   582,
		582,   417,   329,   423,   420,   421,   217,   385,   426,   425,   575,   576,   243,   335,   380,   217,   617,   585,   507,   206,
		207,   218,   312,   219,   212,   220,   221,   217,   568,   568,   531,   531,   531,   531,   531,   531,   531,   531,   550,   550,
		550,   550,   550,   550,   550,   550,   548,   548,   548,   548,   548,   548,   548,   548,   295,   295,   295,   295,   295,   295,
		295,   295,   291,   291,   291,   291,   382,   291,   291,   607,   608,   609,   337,   510,   601,   319,   511,   512,   306,   307,
		308,   513,   558,   559,   560,   326,   602,   388,   306,   307,   265,   392,   399,   401,   400,   402,   259,   260,   572,   573,
		574,   372,   406,   484,   225,   373,   508,   336,   0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     316,   0,     0,     0,
		0,     0,     0,     0,     226,   227,   228,   229,
	];

	/** Table indexed analogously to self::Goto. If self::GotoCheck[self::GotoBase[$nonTerminal] + $state] != $nonTerminal
	 *  then the goto state is defaulted, i.e. self::GotoDefault[$nonTerminal] should be used. */
	protected const GotoCheck = [
		2,     33,    33,    2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     5,     5,     5,
		5,     5,     5,     5,     5,     5,     5,     5,     5,     5,     5,     5,     64,    28,    28,    28,    28,    31,    31,    31,
		28,    28,    28,    28,    28,    28,    28,    28,    54,    54,    54,    54,    53,    53,    53,    54,    54,    54,    54,    54,
		54,    54,    54,    64,    64,    64,    7,     44,    44,    7,     7,     7,     7,     7,     59,    64,    64,    64,    64,    64,
		64,    10,    10,    37,    37,    37,    9,     10,    10,    10,    67,    67,    68,    10,    10,    9,     9,     69,    10,    34,
		34,    34,    34,    34,    34,    34,    34,    9,     64,    64,    39,    39,    39,    39,    39,    39,    39,    39,    57,    57,
		57,    57,    57,    57,    57,    57,    58,    58,    58,    58,    58,    58,    58,    58,    60,    60,    60,    60,    60,    60,
		60,    60,    38,    38,    38,    38,    18,    38,    38,    8,     8,     8,     9,     9,     70,    38,    30,    30,    13,    13,
		19,    30,    30,    30,    30,    20,    70,    22,    13,    13,    13,    25,    25,    25,    25,    25,    65,    65,    65,    65,
		65,    12,    24,    41,    71,    12,    48,    29,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
		-1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    7,     -1,    -1,    -1,
		-1,    -1,    -1,    -1,    7,     7,     7,     7,
	];

	/** Map of non-terminals to the default state to goto after their reduction */
	protected const GotoDefault = [
		-8192, 273,   103,   285,   344,   375,   364,   290,   580,   566,   370,   254,   587,   271,   270,   434,   330,   268,   381,   331,
		323,   261,   387,   235,   404,   247,   324,   325,   252,   333,   526,   256,   315,   411,   154,   255,   242,   422,   280,   281,
		433,   250,   500,   269,   318,   504,   338,   272,   509,   557,   253,   282,   257,   523,   239,   208,   283,   215,   205,   303,
		196,   204,   216,   284,   555,   258,   562,   569,   304,   586,   599,   600,   311,   328,
	];

	/** Map of rules to the non-terminal on their left-hand side, i.e. the non-terminal to use for
	 *  determining the state to goto after reduction. */
	protected const RuleToNonTerminal = [
		0,     1,     1,     1,     5,     5,     6,     6,     6,     6,     6,     6,     6,     6,     6,     6,     6,     6,     6,     6,
		6,     7,     7,     7,     8,     8,     9,     10,    10,    4,     4,     11,    11,    13,    13,    14,    14,    15,    16,    16,
		17,    17,    18,    18,    20,    20,    21,    21,    22,    22,    24,    24,    24,    24,    25,    25,    25,    25,    26,    26,
		27,    27,    23,    23,    29,    29,    30,    30,    30,    32,    31,    31,    33,    33,    33,    33,    19,    35,    35,    36,
		36,    3,     3,     37,    37,    37,    37,    2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     40,    43,    43,    46,    47,    47,    48,    49,    49,    49,    53,    28,    28,    54,    54,    54,    41,
		41,    41,    51,    51,    45,    45,    57,    58,    39,    60,    60,    60,    60,    42,    42,    42,    42,    42,    42,    42,
		42,    42,    44,    44,    56,    56,    56,    56,    62,    62,    62,    50,    50,    50,    63,    63,    63,    63,    63,    63,
		34,    34,    34,    34,    34,    64,    64,    67,    66,    55,    55,    55,    55,    55,    55,    55,    52,    52,    52,    65,
		65,    65,    38,    59,    68,    68,    69,    69,    12,    12,    12,    12,    12,    12,    12,    12,    12,    12,    61,    61,
		61,    61,    71,    72,    70,    70,    70,    70,    70,    70,    70,    70,    70,    73,    73,    73,    73,
	];

	/** Map of rules to the length of their right-hand side, which is the number of elements that have to
	 *  be popped from the stack(s) on reduction. */
	protected const RuleToLength = [
		1,     2,     2,     2,     1,     1,     1,     1,     1,     1,     1,     1,     1,     1,     1,     1,     1,     1,     1,     1,
		1,     1,     1,     1,     1,     1,     1,     0,     1,     2,     0,     1,     3,     0,     1,     0,     1,     7,     0,     2,
		1,     3,     3,     4,     2,     0,     1,     3,     4,     6,     1,     2,     1,     1,     1,     1,     1,     1,     3,     3,
		3,     3,     0,     1,     0,     2,     2,     4,     3,     1,     1,     3,     1,     2,     2,     3,     2,     3,     1,     4,
		4,     3,     4,     0,     3,     3,     3,     1,     3,     3,     3,     4,     1,     1,     2,     3,     3,     3,     3,     3,
		3,     3,     3,     3,     3,     3,     3,     3,     2,     2,     2,     2,     3,     3,     3,     3,     3,     3,     3,     3,
		3,     3,     3,     3,     3,     3,     3,     3,     3,     3,     2,     2,     2,     2,     3,     3,     3,     3,     3,     3,
		3,     3,     3,     3,     3,     3,     5,     4,     3,     3,     4,     4,     2,     2,     2,     2,     2,     2,     2,     1,
		8,     12,    9,     3,     0,     4,     2,     1,     3,     2,     2,     2,     4,     1,     1,     1,     1,     1,     1,     1,
		1,     3,     1,     1,     0,     1,     1,     3,     3,     4,     1,     1,     3,     1,     1,     1,     1,     1,     1,     1,
		1,     1,     0,     1,     1,     3,     1,     1,     1,     1,     1,     1,     3,     1,     1,     4,     1,     4,     4,     4,
		1,     1,     3,     3,     3,     1,     4,     1,     3,     1,     4,     3,     3,     3,     3,     3,     1,     3,     1,     1,
		3,     1,     4,     1,     3,     1,     1,     0,     1,     2,     1,     3,     4,     3,     3,     4,     2,     2,     2,     2,
		1,     2,     1,     1,     1,     4,     3,     3,     3,     3,     3,     6,     3,     1,     1,     2,     1,
	];

	/** Map of symbols to their names */
	protected const SymbolToName = [
		'end',
		'error',
		"','",
		"'or'",
		"'xor'",
		"'and'",
		"'=>'",
		"'='",
		"'+='",
		"'-='",
		"'*='",
		"'/='",
		"'.='",
		"'%='",
		"'&='",
		"'|='",
		"'^='",
		"'<<='",
		"'>>='",
		"'**='",
		"'??='",
		"'?'",
		"':'",
		"'??'",
		"'||'",
		"'&&'",
		"'|'",
		"'^'",
		"'&'",
		"'&'",
		"'=='",
		"'!='",
		"'==='",
		"'!=='",
		"'<=>'",
		"'<'",
		"'<='",
		"'>'",
		"'>='",
		"'<<'",
		"'>>'",
		"'in'",
		"'+'",
		"'-'",
		"'.'",
		"'*'",
		"'/'",
		"'%'",
		"'!'",
		"'instanceof'",
		"'~'",
		"'++'",
		"'--'",
		"'(int)'",
		"'(float'",
		"'(string)'",
		"'(array)'",
		"'(object)'",
		"'(bool)'",
		"'@'",
		"'**'",
		"'['",
		"'new'",
		"'clone'",
		'integer',
		'floating-point number',
		'identifier',
		'variable name',
		'constant',
		'variable',
		'number',
		'string content',
		'quoted string',
		"'match'",
		"'default'",
		"'function'",
		"'fn'",
		"'return'",
		"'use'",
		"'isset'",
		"'empty'",
		"'->'",
		"'?->'",
		"'??->'",
		"'list'",
		"'array'",
		"'\${'",
		"'{\$'",
		"'::'",
		"'...'",
		"'(expand)'",
		'fully qualified name',
		'namespaced name',
		"'null'",
		"'true'",
		"'false'",
		"'e'",
		"'m'",
		"'a'",
		"'('",
		"')'",
		"'{'",
		"'}'",
		"';'",
		"']'",
		"'\"'",
		"'$'",
		"'\\\\'",
		'whitespace',
		'comment',
	];

	/** Temporary value containing the result of last semantic action (reduction) */
	protected mixed $semValue = null;

	/** Semantic value stack (contains values of tokens and semantic action results) */
	protected array $semStack;

	/** @var Token[] Start attribute stack */
	protected array $startTokenStack;


	protected function reduce(int $rule, int $pos): void
	{
		(match ($rule) {
			0, 1, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 27, 28, 50, 63, 65, 87, 92, 93, 159, 173, 175, 179, 180, 182, 183, 185, 190, 195, 200, 201, 203, 204, 206, 207, 208, 209, 211, 213, 214, 216, 220, 221, 225, 229, 236, 238, 239, 241, 246, 264, 276 => fn() => $this->semValue = $this->semStack[$pos],
			2 => fn() => $this->semValue = new Node\ModifierNode($this->semStack[$pos]),
			3 => fn() => $this->semValue = new Expression\ArrayNode($this->semStack[$pos]),
			21, 22, 23, 24, 25, 55, 56, 57 => fn() => $this->semValue = new Node\IdentifierNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			26 => fn() => $this->semValue = new Expression\VariableNode(substr($this->semStack[$pos], 1), $this->startTokenStack[$pos]->position),
			29, 39, 44, 76, 84, 85, 86, 144, 145, 165, 166, 181, 205, 212, 237, 240, 272 => fn() => $this->semValue = $this->semStack[$pos - 1],
			30, 38, 45, 66, 83, 164, 184 => fn() => $this->semValue = [],
			31, 40, 46, 70, 78, 167, 245, 260 => fn() => $this->semValue = [$this->semStack[$pos]],
			32, 41, 47, 59, 61, 71, 77, 168, 244 => function () use ($pos) {
				$this->semStack[$pos - 2][] = $this->semStack[$pos];
				$this->semValue = $this->semStack[$pos - 2];
			},
			33, 35 => fn() => $this->semValue = false,
			34, 36 => fn() => $this->semValue = true,
			37 => fn() => $this->semValue = new Expression\MatchNode($this->semStack[$pos - 4], $this->semStack[$pos - 1], $this->startTokenStack[$pos - 6]->position),
			42 => fn() => $this->semValue = new Node\MatchArmNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			43 => fn() => $this->semValue = new Node\MatchArmNode(null, $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position),
			48 => fn() => $this->semValue = new Node\ParameterNode($this->semStack[$pos], null, $this->semStack[$pos - 3], $this->semStack[$pos - 2], $this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			49 => fn() => $this->semValue = new Node\ParameterNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->semStack[$pos - 5], $this->semStack[$pos - 4], $this->semStack[$pos - 3], $this->startTokenStack[$pos - 5]->position),
			51 => fn() => $this->semValue = new Node\NullableTypeNode($this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			52 => fn() => $this->semValue = new Node\UnionTypeNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			53 => fn() => $this->semValue = new Node\IntersectionTypeNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			54 => fn() => $this->semValue = TagParser::handleBuiltinTypes($this->semStack[$pos]),
			58, 60 => fn() => $this->semValue = [$this->semStack[$pos - 2], $this->semStack[$pos]],
			62, 64, 202, 247 => fn() => $this->semValue = null,
			67 => fn() => $this->semValue = $this->semStack[$pos - 2],
			68 => fn() => $this->semValue = [$this->semStack[$pos - 1]],
			69 => fn() => $this->semValue = new Node\VariadicPlaceholderNode($this->startTokenStack[$pos]->position),
			72 => fn() => $this->semValue = new Node\ArgumentNode($this->semStack[$pos], false, false, null, $this->startTokenStack[$pos]->position),
			73 => fn() => $this->semValue = new Node\ArgumentNode($this->semStack[$pos], true, false, null, $this->startTokenStack[$pos - 1]->position),
			74 => fn() => $this->semValue = new Node\ArgumentNode($this->semStack[$pos], false, true, null, $this->startTokenStack[$pos - 1]->position),
			75 => fn() => $this->semValue = new Node\ArgumentNode($this->semStack[$pos], false, false, $this->semStack[$pos - 2], $this->startTokenStack[$pos - 2]->position),
			79, 80 => fn() => $this->semValue = new Expression\FilterCallNode($this->semStack[$pos - 3], new Node\FilterNode($this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position), $this->startTokenStack[$pos - 3]->position),
			81 => fn() => $this->semValue = [new Node\FilterNode($this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position)],
			82 => function () use ($pos) {
				$this->semStack[$pos - 3][] = new Node\FilterNode($this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position);
				$this->semValue = $this->semStack[$pos - 3];
			},
			88, 89, 90 => fn() => $this->semValue = new Expression\AssignNode($this->semStack[$pos - 2], $this->semStack[$pos], false, $this->startTokenStack[$pos - 2]->position),
			91 => fn() => $this->semValue = new Expression\AssignNode($this->semStack[$pos - 3], $this->semStack[$pos], true, $this->startTokenStack[$pos - 3]->position),
			94 => fn() => $this->semValue = new Expression\CloneNode($this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			95 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '+', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			96 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '-', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			97 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '*', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			98 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '/', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			99 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '.', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			100 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '%', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			101 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '&', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			102 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '|', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			103 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '^', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			104 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '<<', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			105 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '>>', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			106 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '**', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			107 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '??', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			108 => fn() => $this->semValue = new Expression\PostOpNode($this->semStack[$pos - 1], '++', $this->startTokenStack[$pos - 1]->position),
			109 => fn() => $this->semValue = new Expression\PreOpNode($this->semStack[$pos], '++', $this->startTokenStack[$pos - 1]->position),
			110 => fn() => $this->semValue = new Expression\PostOpNode($this->semStack[$pos - 1], '--', $this->startTokenStack[$pos - 1]->position),
			111 => fn() => $this->semValue = new Expression\PreOpNode($this->semStack[$pos], '--', $this->startTokenStack[$pos - 1]->position),
			112 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '||', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			113 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '&&', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			114 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], 'or', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			115 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], 'and', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			116 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], 'xor', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			117, 118 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '&', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			119 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '^', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			120 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '.', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			121 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '+', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			122 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '-', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			123 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '*', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			124 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '/', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			125 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '%', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			126 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '<<', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			127 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '>>', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			128 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '**', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			129 => fn() => $this->semValue = new Expression\InRangeNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			130 => fn() => $this->semValue = new Expression\UnaryOpNode($this->semStack[$pos], '+', $this->startTokenStack[$pos - 1]->position),
			131 => fn() => $this->semValue = new Expression\UnaryOpNode($this->semStack[$pos], '-', $this->startTokenStack[$pos - 1]->position),
			132 => fn() => $this->semValue = new Expression\NotNode($this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			133 => fn() => $this->semValue = new Expression\UnaryOpNode($this->semStack[$pos], '~', $this->startTokenStack[$pos - 1]->position),
			134 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '===', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			135 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '!==', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			136 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '==', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			137 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '!=', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			138 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '<=>', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			139 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '<', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			140 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '<=', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			141 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '>', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			142 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '>=', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			143 => fn() => $this->semValue = new Expression\InstanceofNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			146 => fn() => $this->semValue = new Expression\TernaryNode($this->semStack[$pos - 4], $this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 4]->position),
			147 => fn() => $this->semValue = new Expression\TernaryNode($this->semStack[$pos - 3], null, $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position),
			148 => fn() => $this->semValue = new Expression\TernaryNode($this->semStack[$pos - 2], $this->semStack[$pos], null, $this->startTokenStack[$pos - 2]->position),
			149 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '??', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			150 => fn() => $this->semValue = new Expression\IssetNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			151 => fn() => $this->semValue = new Expression\EmptyNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			152 => fn() => $this->semValue = new Expression\CastNode('int', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			153 => fn() => $this->semValue = new Expression\CastNode('float', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			154 => fn() => $this->semValue = new Expression\CastNode('string', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			155 => fn() => $this->semValue = new Expression\CastNode('array', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			156 => fn() => $this->semValue = new Expression\CastNode('object', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			157 => fn() => $this->semValue = new Expression\CastNode('bool', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			158 => fn() => $this->semValue = new Expression\ErrorSuppressNode($this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			160 => fn() => $this->semValue = new Expression\ClosureNode((bool) $this->semStack[$pos - 6], $this->semStack[$pos - 4], [], $this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 7]->position),
			161 => fn() => $this->semValue = new Expression\ClosureNode((bool) $this->semStack[$pos - 10], $this->semStack[$pos - 8], $this->semStack[$pos - 6], $this->semStack[$pos - 5], $this->semStack[$pos - 2], $this->startTokenStack[$pos - 11]->position),
			162 => fn() => $this->semValue = new Expression\ClosureNode((bool) $this->semStack[$pos - 7], $this->semStack[$pos - 5], $this->semStack[$pos - 3], $this->semStack[$pos - 2], null, $this->startTokenStack[$pos - 8]->position),
			163 => fn() => $this->semValue = new Expression\NewNode($this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			169 => fn() => $this->semValue = new Expression\ClosureUseNode($this->semStack[$pos], $this->semStack[$pos - 1], $this->startTokenStack[$pos - 1]->position),
			170, 171 => fn() => $this->semValue = new Expression\FunctionCallNode($this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			172 => fn() => $this->semValue = new Expression\StaticCallNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position),
			174, 176, 177 => fn() => $this->semValue = new Node\NameNode($this->semStack[$pos], Node\NameNode::KindNormal, $this->startTokenStack[$pos]->position),
			178 => fn() => $this->semValue = new Node\NameNode($this->semStack[$pos], Node\NameNode::KindFullyQualified, $this->startTokenStack[$pos]->position),
			186 => fn() => $this->semValue = new Expression\ConstantFetchNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			187 => fn() => $this->semValue = new Expression\ClassConstantFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			188 => fn() => $this->semValue = new Expression\ArrayNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 2]->position),
			189, 242 => fn() => $this->semValue = new Expression\ArrayNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			191 => fn() => $this->semValue = Scalar\StringNode::parse($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			192 => fn() => $this->semValue = Scalar\EncapsedStringNode::parse($this->semStack[$pos - 1], $this->startTokenStack[$pos - 2]->position),
			193 => fn() => $this->semValue = Scalar\IntegerNode::parse($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			194 => fn() => $this->semValue = Scalar\FloatNode::parse($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			196, 273 => fn() => $this->semValue = new Scalar\StringNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			197 => fn() => $this->semValue = new Scalar\BooleanNode(true, $this->startTokenStack[$pos]->position),
			198 => fn() => $this->semValue = new Scalar\BooleanNode(false, $this->startTokenStack[$pos]->position),
			199 => fn() => $this->semValue = new Scalar\NullNode($this->startTokenStack[$pos]->position),
			210 => fn() => $this->semValue = new Expression\ConstantFetchNode(new Node\NameNode($this->semStack[$pos], Node\NameNode::KindNormal, $this->startTokenStack[$pos]->position), $this->startTokenStack[$pos]->position),
			215, 230, 265 => fn() => $this->semValue = new Expression\ArrayAccessNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			217 => fn() => $this->semValue = new Expression\MethodCallNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position),
			218 => fn() => $this->semValue = new Expression\NullsafeMethodCallNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position),
			219 => fn() => $this->semValue = new Expression\UndefinedsafeMethodCallNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position),
			222, 231, 266 => fn() => $this->semValue = new Expression\PropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			223, 232, 267 => fn() => $this->semValue = new Expression\NullsafePropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			224, 233, 268 => fn() => $this->semValue = new Expression\UndefinedsafePropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			226 => fn() => $this->semValue = new Expression\VariableNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			227 => function () use ($pos) {
				$var = $this->semStack[$pos]->name;
				$this->semValue = \is_string($var)
					? new Node\VarLikeIdentifierNode($var, $this->startTokenStack[$pos]->position)
					: $var;
			},
			228, 234, 235 => fn() => $this->semValue = new Expression\StaticPropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			243 => function () use ($pos) {
				$this->semValue = $this->semStack[$pos];
				$end = count($this->semValue) - 1;
				if ($this->semValue[$end] === null) {
					array_pop($this->semValue);
				}
			},
			248, 250 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], null, false, false, $this->startTokenStack[$pos]->position),
			249 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], null, true, false, $this->startTokenStack[$pos - 1]->position),
			251, 253, 254 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], $this->semStack[$pos - 2], false, false, $this->startTokenStack[$pos - 2]->position),
			252, 255 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], $this->semStack[$pos - 3], true, false, $this->startTokenStack[$pos - 3]->position),
			256, 257 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], null, false, true, $this->startTokenStack[$pos - 1]->position),
			258, 259 => function () use ($pos) {
				$this->semStack[$pos - 1][] = $this->semStack[$pos];
				$this->semValue = $this->semStack[$pos - 1];
			},
			261 => fn() => $this->semValue = [$this->semStack[$pos - 1], $this->semStack[$pos]],
			262 => fn() => $this->semValue = new Scalar\EncapsedStringPartNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			263 => fn() => $this->semValue = new Expression\VariableNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			269, 270 => fn() => $this->semValue = new Expression\VariableNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 2]->position),
			271 => fn() => $this->semValue = new Expression\ArrayAccessNode($this->semStack[$pos - 4], $this->semStack[$pos - 2], $this->startTokenStack[$pos - 5]->position),
			274 => fn() => $this->semValue = TagParser::parseOffset($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			275 => fn() => $this->semValue = TagParser::parseOffset('-' . $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
		})();
	}
}
