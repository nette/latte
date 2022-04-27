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
		0,     111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,
		111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   48,    106,   111,   107,   47,    111,   111,
		100,   101,   45,    42,    2,     43,    44,    46,    111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   22,    104,
		35,    7,     37,    21,    59,    111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,
		111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   61,    111,   105,   27,    111,   111,   99,    111,   111,
		111,   97,    111,   111,   111,   111,   111,   111,   111,   98,    111,   111,   111,   111,   111,   111,   111,   111,   111,   111,
		111,   111,   111,   102,   26,    103,   50,    111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,
		111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,
		111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,
		111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,
		111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,
		111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,
		111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   111,   1,     3,     4,     5,
		6,     8,     9,     10,    11,    12,    13,    14,    15,    16,    17,    18,    19,    20,    23,    24,    25,    28,    29,    30,
		31,    32,    33,    34,    36,    38,    39,    40,    41,    49,    51,    52,    53,    54,    55,    56,    57,    58,    60,    62,
		63,    64,    65,    66,    67,    68,    69,    70,    71,    72,    73,    74,    75,    76,    77,    78,    79,    80,    81,    82,
		83,    84,    85,    86,    87,    88,    108,   89,    90,    91,    92,    93,    109,   110,   94,    95,    96,
	];

	/** Map of states to a displacement into the self::Action table. The corresponding action for this
	 *  state/symbol pair is self::Action[self::ActionBase[$state] + $symbol]. If self::ActionBase[$state] is 0, the
	 *  action is defaulted, i.e. self::ActionDefault[$state] should be used instead. */
	protected const ActionBase = [
		161,   140,   140,   140,   140,   99,    140,   140,   220,   220,   220,   220,   220,   234,   234,   234,   294,   294,   278,   292,
		-42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,
		-42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,
		-42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,
		-42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,
		-42,   -42,   144,   203,   352,   354,   353,   355,   364,   365,   366,   367,   372,   52,    52,    52,    52,    52,    52,    52,
		52,    52,    52,    52,    52,    52,    52,    160,   277,   424,   106,   106,   106,   106,   106,   106,   106,   106,   106,   106,
		106,   106,   106,   106,   106,   106,   106,   106,   384,   384,   384,   102,   357,   247,   224,   -66,   346,   346,   126,   126,
		126,   126,   126,   261,   261,   261,   261,   78,    78,    78,    78,    259,   259,   259,   259,   259,   259,   259,   259,   79,
		79,    121,   233,   39,    39,    39,    -22,   -22,   -22,   -22,   -22,   152,   152,   152,   110,   302,   311,   315,   119,   119,
		119,   119,   119,   119,   141,   -21,   196,   196,   325,   137,   137,   137,   196,   -39,   -41,   265,   339,   343,   313,   316,
		290,   253,   351,   269,   286,   217,   37,    37,    37,    37,    234,   373,   373,   234,   234,   234,   -61,   -61,   -61,   111,
		238,   201,   40,    356,   238,   238,   238,   179,   33,    61,    333,   296,   333,   333,   26,    76,    43,    333,   333,   333,
		333,   134,   43,    43,    299,   312,   297,   164,   66,    297,   283,   283,   150,   4,     335,   334,   336,   332,   331,   345,
		239,   249,   276,   268,   330,   304,   251,   335,   334,   336,   289,   239,   273,   273,   273,   319,   273,   273,   273,   273,
		273,   273,   273,   368,   16,    291,   337,   338,   344,   347,   359,   282,   360,   273,   295,   348,   310,   309,   369,   239,
		323,   370,   314,   341,   306,   293,   342,   287,   371,   349,   361,   184,   340,   195,   362,   198,   358,   260,   350,   308,
		363,   0,     -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,
		-42,   0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,
		52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    0,     261,   52,    52,    52,
		52,    52,    52,    52,    0,     0,     0,     0,     137,   137,   137,   137,   -22,   -22,   -22,   -22,   -22,   -22,   -22,   -22,
		-22,   -22,   -22,   -22,   -22,   -22,   -22,   0,     0,     0,     0,     0,     137,   137,   137,   0,     0,     0,     0,     283,
		283,   283,   283,   283,   283,   283,   0,     0,     0,     0,     0,     0,     0,     0,     283,   283,   0,     0,     0,     0,
		0,     0,     0,     234,   234,   234,   283,   0,     0,     0,     0,     0,     283,   283,   0,     0,     0,     0,     0,     0,
		0,     273,   0,     0,     16,    273,   273,   273,
	];

	/** Table of actions. Indexed according to self::ActionBase comment. */
	protected const Action = [
		26,    27,    327,   367,   0,     365,   28,    366,   29,    171,   172,   30,    31,    32,    33,    34,    35,    36,    6,     1,
		183,   37,    535,   536,   195,   615,   517,   367,   7,     616,   533,   274,   604,   233,   234,   12,    91,    275,   276,   8,
		-208,  279,   277,   278,   515,   11,    517,   18,    174,   519,   518,   520,   541,   539,   540,   46,    47,    48,    21,    9,
		-208,  -208,  -208,  396,   213,   279,   166,   -208,  17,    519,   518,   520,   398,   19,    397,   49,    50,    51,    93,    52,
		53,    54,    55,    56,    57,    58,    59,    60,    61,    62,    63,    64,    65,    66,    67,    68,    69,    70,    71,    72,
		186,   185,   348,   349,   347,   -8190, -8190, -8190, 367,   -8190, -8190, -8190, 73,    -8191, -8191, -8191, -8191, 64,    65,    66,
		67,    68,    69,    -8190, -8190, -8190, -8190, 346,   345,   -8190, -8190, -8190, -21,   -8190, 427,   -8190, 181,   10,    24,    42,
		358,   486,   186,   348,   349,   347,   279,   -8190, 352,   -8190, -8190, -8190, 222,   -8190, -8190, -8190, -8191, -8191, -8191, -8191,
		-8191, 184,   38,    -8190, 503,   194,   369,   286,   346,   345,   165,   -211,  287,   354,   223,   224,   353,   359,   288,   289,
		-45,   358,   70,    71,    72,    365,   185,   366,   90,    352,   98,    -211,  -211,  -211,  67,    68,    69,    73,    -174,  -175,
		407,   99,    184,   38,    100,   187,   194,   188,   286,   14,    -174,  -175,  604,   287,   354,   223,   224,   353,   359,   288,
		289,   92,    -28,   348,   349,   347,   279,   18,    174,   39,    40,    13,    74,    75,    76,    77,    78,    79,    80,    81,
		82,    83,    84,    85,    86,    197,   87,    534,   346,   345,   -8190, -8190, -8190, -256,  186,   -256,  88,    -205,  25,    251,
		3,     358,   346,   345,   348,   349,   347,   101,   -8190, 352,   -8190, -8190, -8190, -22,   -8190, 449,   451,   -205,  -205,  -205,
		-8190, -8190, 184,   38,    -205,  -205,  194,   185,   286,   -174,  -253,  -16,   -253,  287,   354,   223,   224,   353,   359,   288,
		289,   -174,  358,   18,    174,   -205,  -205,  -205,  -15,   97,    352,   15,    -205,  -250,  89,    -250,  -73,   96,    -73,   182,
		1,     190,   163,   351,   350,   241,   -210,  362,   367,   363,   164,   533,   189,   73,    355,   354,   357,   356,   353,   359,
		360,   361,   -73,   94,    278,   310,   -210,  -210,  -210,  -8190, -8190, -8190, 367,   -8190, -256,  -8190, -184,  617,   -256,  41,
		-8190, -8190, -8190, -211,  155,   213,   279,   -8190, 267,   -8190, -8190, -8190, -207,  -8190, -8190, -8190, -206,  332,   -8190, 231,
		-8190, -8190, -8190, -211,  -211,  -211,  23,    -8190, -8190, -8190, -174,  -253,  -207,  -207,  -207,  -253,  -206,  -206,  -206,  -207,
		22,    377,   -174,  -206,  214,   -8190, 201,   202,   203,   -239,  -237,  -214,  340,   232,   -250,  -213,  16,    -73,   -250,  -214,
		198,   199,   200,   -213,  236,   237,   238,   -8190, -8190, -8190, -212,  2,     4,     5,     20,    43,    44,    179,   180,   0,
		230,   0,     264,   266,   0,     491,   95,    45,    531,   409,   408,   506,   -28,   320,   322,   523,   492,   584,   0,     0,
		249,   0,     612,   614,   378,   613,   502,   611,   568,   579,   582,   0,     0,     530,   557,   572,   607,   334,   0,     0,
		279,
	];

	/** Table indexed analogously to self::Action. If self::ActionCheck[self::ActionBase[$state] + $symbol] != $symbol
	 *  then the action is defaulted, i.e. self::ActionDefault[$state] should be used instead. */
	protected const ActionCheck = [
		42,    43,    43,    69,    0,     66,    48,    68,    50,    51,    52,    53,    54,    55,    56,    57,    58,    59,    2,     61,
		62,    63,    64,    65,    66,    66,    68,    69,    2,     70,    72,    73,    71,    75,    76,    2,     102,   79,    80,    2,
		61,    107,   84,    85,    66,    2,     68,    86,    87,    91,    92,    93,    94,    95,    96,    3,     4,     5,     100,   22,
		81,    82,    83,    85,    106,   107,   26,    88,    2,     91,    92,    93,    94,    21,    96,    23,    24,    25,    2,     27,
		28,    29,    30,    31,    32,    33,    34,    35,    36,    37,    38,    39,    40,    41,    42,    43,    44,    45,    46,    47,
		21,    49,    3,     4,     5,     3,     4,     5,     69,    3,     4,     5,     60,    35,    36,    37,    38,    39,    40,    41,
		42,    43,    44,    21,    85,    23,    24,    28,    29,    3,     4,     5,     22,    94,    101,   96,    2,     100,   77,    100,
		41,    101,   21,    3,     4,     5,     107,   21,    49,    23,    24,    25,    2,     27,    28,    29,    30,    31,    32,    33,
		34,    62,    63,    3,     103,   66,    2,     68,    28,    29,    26,    61,    73,    74,    75,    76,    77,    78,    79,    80,
		101,   41,    45,    46,    47,    66,    49,    68,    89,    49,    6,     81,    82,    83,    42,    43,    44,    60,    88,    88,
		101,   6,     62,    63,    6,     26,    66,    28,    68,    6,     100,   100,   71,    73,    74,    75,    76,    77,    78,    79,
		80,    102,   101,   3,     4,     5,     107,   86,    87,    89,    90,    7,     8,     9,     10,    11,    12,    13,    14,    15,
		16,    17,    18,    19,    20,    101,   7,     106,   28,    29,    3,     4,     5,     0,     21,    2,     7,     61,    97,    98,
		99,    41,    28,    29,    3,     4,     5,     7,     21,    49,    23,    24,    25,    22,    27,    51,    52,    81,    82,    83,
		3,     4,     62,    63,    88,    61,    66,    49,    68,    88,    0,     22,    2,     73,    74,    75,    76,    77,    78,    79,
		80,    100,   41,    86,    87,    81,    82,    83,    22,    89,    49,    22,    88,    0,     22,    2,     0,     22,    2,     22,
		61,    28,    26,    62,    63,    66,    61,    66,    69,    68,    26,    72,    26,    60,    73,    74,    75,    76,    77,    78,
		79,    80,    26,    61,    85,    67,    81,    82,    83,    3,     4,     5,     69,    94,    101,   96,    88,    70,    105,   100,
		3,     4,     5,     61,    88,    106,   107,   21,    74,    23,    24,    25,    61,    27,    28,    29,    61,    78,    21,    88,
		23,    24,    25,    81,    82,    83,    61,    3,     4,     5,     88,    101,   81,    82,    83,    105,   81,    82,    83,    88,
		61,    89,    100,   88,    61,    21,    81,    82,    83,    100,   100,   100,   104,   88,    101,   100,   102,   101,   105,   100,
		81,    82,    83,    100,   81,    82,    83,    3,     4,     5,     100,   100,   100,   100,   100,   100,   100,   100,   100,   -1,
		100,   -1,    101,   101,   -1,    101,   22,    102,   101,   101,   101,   101,   101,   101,   101,   101,   101,   101,   -1,    -1,
		102,   -1,    103,   103,   103,   103,   103,   103,   103,   103,   103,   -1,    -1,    105,   105,   105,   105,   105,   -1,    -1,
		107,
	];

	/** Map of states to their default action */
	protected const ActionDefault = [
		8191,  248,   248,   30,    248,   8191,  248,   28,    8191,  8191,  8191,  28,    8191,  8191,  8191,  8191,  38,    28,    8191,  8191,
		8191,  8191,  203,   203,   203,   8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  9,     8191,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  69,    8191,  8191,  28,    8191,  8191,  8191,  8191,  8191,  8191,
		8191,  8191,  8191,  249,   8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  1,     257,   258,   78,    72,    204,   252,
		255,   74,    77,    75,    42,    43,    49,    114,   116,   148,   115,   90,    95,    96,    97,    98,    99,    100,   101,   102,
		103,   104,   105,   106,   107,   88,    89,    160,   149,   147,   146,   112,   113,   119,   87,    8191,  117,   118,   136,   137,
		134,   135,   138,   8191,  8191,  8191,  8191,  139,   140,   141,   142,   8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  62,
		62,    62,    8191,  8191,  10,    8191,  8191,  8191,  8191,  8191,  8191,  126,   127,   129,   197,   197,   196,   144,   8191,  8191,
		8191,  8191,  8191,  8191,  8191,  202,   109,   111,   181,   121,   122,   120,   91,    8191,  8191,  201,   8191,  265,   205,   205,
		205,   205,   33,    33,    33,    8191,  83,    83,    83,    83,    33,    8191,  8191,  33,    33,    33,    8191,  8191,  8191,  187,
		132,   211,   8191,  8191,  123,   124,   125,   50,    8191,  8191,  185,   8191,  173,   8191,  27,    27,    27,    8191,  223,   224,
		225,   27,    27,    27,    164,   35,    64,    27,    27,    64,    8191,  8191,  27,    8191,  8191,  8191,  8191,  8191,  8191,  8191,
		8191,  191,   8191,  209,   221,   2,     176,   14,    19,    20,    8191,  251,   130,   131,   133,   207,   152,   153,   154,   155,
		156,   157,   158,   8191,  244,   180,   8191,  8191,  8191,  8191,  264,   8191,  205,   128,   8191,  8191,  188,   228,   8191,  254,
		206,   8191,  8191,  8191,  52,    53,    8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  48,    8191,  8191,
		8191,
	];

	/** Map of non-terminals to a displacement into the self::Goto table. The corresponding goto state for this
	 *  non-terminal/state pair is self::Goto[self::GotoBase[$nonTerminal] + $state] (unless defaulted) */
	protected const GotoBase = [
		0,     0,     -5,    0,     0,     96,    0,     138,   20,    -38,   -94,   0,     252,   -70,   0,     0,     0,     0,     223,   240,
		81,    0,     82,    0,     80,    59,    0,     0,     -66,   -2,    -16,   129,   0,     -10,   8,     0,     0,     2,     221,   19,
		0,     79,    0,     0,     121,   0,     0,     0,     36,    0,     0,     0,     0,     -13,   -54,   0,     0,     27,    35,    175,
		43,    0,     0,     0,     -43,   51,    0,     -80,   230,   233,   13,    52,    0,     0,
	];

	/** Table of states to goto after reduction. Indexed according to self::GotoBase comment. */
	protected const Goto = [
		117,   412,   412,   117,   117,   117,   117,   117,   131,   119,   120,   116,   116,   108,   129,   116,   102,   118,   118,   118,
		113,   292,   293,   240,   294,   296,   297,   298,   299,   300,   301,   302,   435,   435,   114,   115,   104,   105,   106,   107,
		109,   127,   128,   130,   148,   151,   152,   153,   156,   157,   158,   159,   160,   161,   162,   167,   168,   169,   170,   191,
		192,   193,   209,   210,   211,   244,   245,   246,   313,   132,   133,   134,   135,   136,   137,   138,   139,   140,   141,   142,
		143,   144,   145,   146,   149,   121,   110,   111,   122,   112,   150,   123,   121,   124,   147,   125,   126,   173,   173,   173,
		173,   175,   173,   173,   175,   175,   175,   175,   175,   176,   177,   178,   317,   395,   395,   395,   395,   514,   514,   514,
		395,   395,   395,   395,   395,   516,   516,   516,   516,   516,   516,   516,   516,   516,   516,   516,   516,   262,   263,   248,
		571,   571,   571,   314,   321,   339,   314,   314,   314,   314,   314,   576,   577,   306,   307,   583,   583,   583,   583,   583,
		583,   417,   329,   306,   307,   265,   217,   385,   426,   425,   305,   305,   305,   335,   380,   217,   618,   309,   507,   206,
		207,   218,   312,   219,   212,   220,   221,   217,   569,   569,   532,   532,   532,   532,   532,   532,   532,   532,   551,   551,
		551,   551,   551,   551,   551,   551,   549,   549,   549,   549,   549,   549,   549,   549,   295,   295,   295,   295,   295,   295,
		295,   295,   291,   291,   291,   291,   602,   291,   291,   423,   420,   421,   337,   510,   243,   319,   511,   512,   603,   586,
		382,   513,   559,   560,   561,   392,   399,   401,   400,   402,   259,   260,   573,   574,   575,   372,   608,   609,   610,   373,
		308,   326,   406,   388,   484,   225,   508,   336,   0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     316,   0,     0,     0,     0,     0,     0,
		0,     226,   227,   228,   229,
	];

	/** Table indexed analogously to self::Goto. If self::GotoCheck[self::GotoBase[$nonTerminal] + $state] != $nonTerminal
	 *  then the goto state is defaulted, i.e. self::GotoDefault[$nonTerminal] should be used. */
	protected const GotoCheck = [
		2,     33,    33,    2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     5,     5,     5,
		5,     5,     5,     5,     5,     5,     5,     5,     5,     5,     5,     5,     64,    28,    28,    28,    28,    28,    28,    28,
		28,    28,    28,    28,    28,    54,    54,    54,    54,    54,    54,    54,    54,    54,    54,    54,    54,    31,    31,    31,
		64,    64,    64,    7,     44,    44,    7,     7,     7,     7,     7,     67,    67,    13,    13,    64,    64,    64,    64,    64,
		64,    10,    10,    13,    13,    13,    9,     10,    10,    10,    53,    53,    53,    10,    10,    9,     9,     59,    10,    34,
		34,    34,    34,    34,    34,    34,    34,    9,     64,    64,    39,    39,    39,    39,    39,    39,    39,    39,    57,    57,
		57,    57,    57,    57,    57,    57,    58,    58,    58,    58,    58,    58,    58,    58,    60,    60,    60,    60,    60,    60,
		60,    60,    38,    38,    38,    38,    70,    38,    38,    37,    37,    37,    9,     9,     68,    38,    30,    30,    70,    69,
		18,    30,    30,    30,    30,    25,    25,    25,    25,    25,    65,    65,    65,    65,    65,    12,    8,     8,     8,     12,
		19,    20,    24,    22,    41,    71,    48,    29,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
		-1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    7,     -1,    -1,    -1,    -1,    -1,    -1,
		-1,    7,     7,     7,     7,
	];

	/** Map of non-terminals to the default state to goto after their reduction */
	protected const GotoDefault = [
		-8192, 273,   103,   285,   344,   375,   364,   290,   581,   567,   370,   254,   588,   271,   270,   434,   330,   268,   381,   331,
		323,   261,   387,   235,   404,   247,   324,   325,   252,   333,   527,   256,   315,   411,   154,   255,   242,   422,   280,   281,
		433,   250,   500,   269,   318,   504,   338,   272,   509,   558,   253,   282,   257,   524,   239,   208,   283,   215,   205,   303,
		196,   204,   216,   284,   556,   258,   563,   570,   304,   587,   600,   601,   311,   328,
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
		2,     2,     2,     40,    43,    43,    46,    47,    47,    48,    49,    49,    49,    53,    28,    28,    54,    54,    54,    54,
		41,    41,    41,    51,    51,    45,    45,    57,    58,    39,    60,    60,    60,    60,    42,    42,    42,    42,    42,    42,
		42,    42,    42,    44,    44,    56,    56,    56,    56,    62,    62,    62,    50,    50,    50,    63,    63,    63,    63,    63,
		63,    34,    34,    34,    34,    34,    64,    64,    67,    66,    55,    55,    55,    55,    55,    55,    55,    52,    52,    52,
		65,    65,    65,    38,    59,    68,    68,    69,    69,    12,    12,    12,    12,    12,    12,    12,    12,    12,    12,    61,
		61,    61,    61,    71,    72,    70,    70,    70,    70,    70,    70,    70,    70,    70,    73,    73,    73,    73,
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
		1,     1,     3,     1,     1,     0,     1,     1,     3,     3,     4,     1,     1,     3,     1,     1,     1,     1,     1,     1,
		1,     1,     1,     0,     1,     1,     3,     1,     1,     1,     1,     1,     1,     3,     1,     1,     4,     1,     4,     4,
		4,     1,     1,     3,     3,     3,     1,     4,     1,     3,     1,     4,     3,     3,     3,     3,     3,     1,     3,     1,
		1,     3,     1,     4,     1,     3,     1,     1,     0,     1,     2,     1,     3,     4,     3,     3,     4,     2,     2,     2,
		2,     1,     2,     1,     1,     1,     4,     3,     3,     3,     3,     3,     6,     3,     1,     1,     2,     1,
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
		'namespace-relative name',
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
			0, 1, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 27, 28, 50, 63, 65, 87, 92, 93, 159, 173, 175, 180, 181, 183, 184, 186, 191, 196, 201, 202, 204, 205, 207, 208, 209, 210, 212, 214, 215, 217, 221, 222, 226, 230, 237, 239, 240, 242, 247, 265, 277 => fn() => $this->semValue = $this->semStack[$pos],
			2 => fn() => $this->semValue = new Node\ModifierNode($this->semStack[$pos]),
			3 => fn() => $this->semValue = new Expression\ArrayNode($this->semStack[$pos]),
			21, 22, 23, 24, 25, 55, 56, 57 => fn() => $this->semValue = new Node\IdentifierNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			26 => fn() => $this->semValue = new Expression\VariableNode(substr($this->semStack[$pos], 1), $this->startTokenStack[$pos]->position),
			29, 39, 44, 76, 84, 85, 86, 144, 145, 165, 166, 182, 206, 213, 238, 241, 273 => fn() => $this->semValue = $this->semStack[$pos - 1],
			30, 38, 45, 66, 83, 164, 185 => fn() => $this->semValue = [],
			31, 40, 46, 70, 78, 167, 246, 261 => fn() => $this->semValue = [$this->semStack[$pos]],
			32, 41, 47, 59, 61, 71, 77, 168, 245 => function () use ($pos) {
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
			54 => fn() => $this->semValue = $this->handleBuiltinTypes($this->semStack[$pos]),
			58, 60 => fn() => $this->semValue = [$this->semStack[$pos - 2], $this->semStack[$pos]],
			62, 64, 203, 248 => fn() => $this->semValue = null,
			67 => fn() => $this->semValue = $this->semStack[$pos - 2],
			68 => fn() => $this->semValue = [$this->semStack[$pos - 1]],
			69 => fn() => $this->semValue = new Node\VariadicPlaceholderNode($this->startTokenStack[$pos]->position),
			72 => fn() => $this->semValue = new Node\ArgumentNode($this->semStack[$pos], false, false, $this->startTokenStack[$pos]->position),
			73 => fn() => $this->semValue = new Node\ArgumentNode($this->semStack[$pos], true, false, $this->startTokenStack[$pos - 1]->position),
			74 => fn() => $this->semValue = new Node\ArgumentNode($this->semStack[$pos], false, true, $this->startTokenStack[$pos - 1]->position),
			75 => fn() => $this->semValue = new Node\ArgumentNode($this->semStack[$pos], false, false, $this->startTokenStack[$pos - 2]->position, $this->semStack[$pos - 2]),
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
			174, 176, 177 => fn() => $this->semValue = new Node\NameNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			178 => fn() => $this->semValue = new Node\FullyQualifiedNameNode(substr($this->semStack[$pos], 1), $this->startTokenStack[$pos]->position),
			179 => fn() => $this->semValue = new Node\RelativeNameNode(substr($this->semStack[$pos], 10), $this->startTokenStack[$pos]->position),
			187 => fn() => $this->semValue = new Expression\ConstantFetchNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			188 => fn() => $this->semValue = new Expression\ClassConstantFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			189, 190 => fn() => $this->semValue = new Expression\ArrayNode($this->semStack[$pos - 1]),
			192 => fn() => $this->semValue = Scalar\StringNode::parse($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			193 => fn() => $this->semValue = Scalar\EncapsedStringNode::parse($this->semStack[$pos - 1], $this->startTokenStack[$pos - 2]->position),
			194 => fn() => $this->semValue = Scalar\IntegerNode::parse($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			195 => fn() => $this->semValue = Scalar\FloatNode::parse($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			197, 274 => fn() => $this->semValue = new Scalar\StringNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			198 => fn() => $this->semValue = new Scalar\BooleanNode(true, $this->startTokenStack[$pos]->position),
			199 => fn() => $this->semValue = new Scalar\BooleanNode(false, $this->startTokenStack[$pos]->position),
			200 => fn() => $this->semValue = new Scalar\NullNode($this->startTokenStack[$pos]->position),
			211 => fn() => $this->semValue = new Expression\ConstantFetchNode(new Node\NameNode($this->semStack[$pos], $this->startTokenStack[$pos]->position), $this->startTokenStack[$pos]->position),
			216, 231, 266 => fn() => $this->semValue = new Expression\ArrayAccessNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			218 => fn() => $this->semValue = new Expression\MethodCallNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position),
			219 => fn() => $this->semValue = new Expression\NullsafeMethodCallNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position),
			220 => fn() => $this->semValue = new Expression\UndefinedsafeMethodCallNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position),
			223, 232, 267 => fn() => $this->semValue = new Expression\PropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			224, 233, 268 => fn() => $this->semValue = new Expression\NullsafePropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			225, 234, 269 => fn() => $this->semValue = new Expression\UndefinedsafePropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			227 => fn() => $this->semValue = new Expression\VariableNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			228 => function () use ($pos) {
				$var = $this->semStack[$pos]->name;
				$this->semValue = \is_string($var)
					? new Node\VarLikeIdentifierNode($var, $this->startTokenStack[$pos]->position)
					: $var;
			},
			229, 235, 236 => fn() => $this->semValue = new Expression\StaticPropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			243 => fn() => $this->semValue = new Expression\ListNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			244 => function () use ($pos) {
				$this->semValue = $this->semStack[$pos];
				$end = count($this->semValue) - 1;
				if ($this->semValue[$end] === null) {
					array_pop($this->semValue);
				}
			},
			249, 251 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], null, false, $this->startTokenStack[$pos]->position),
			250 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], null, true, $this->startTokenStack[$pos - 1]->position),
			252, 254, 255 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], $this->semStack[$pos - 2], false, $this->startTokenStack[$pos - 2]->position),
			253, 256 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], $this->semStack[$pos - 3], true, $this->startTokenStack[$pos - 3]->position),
			257, 258 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], null, false, $this->startTokenStack[$pos - 1]->position, true, $this->startTokenStack[$pos - 1]->position),
			259, 260 => function () use ($pos) {
				$this->semStack[$pos - 1][] = $this->semStack[$pos];
				$this->semValue = $this->semStack[$pos - 1];
			},
			262 => fn() => $this->semValue = [$this->semStack[$pos - 1], $this->semStack[$pos]],
			263 => fn() => $this->semValue = new Scalar\EncapsedStringPartNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			264 => fn() => $this->semValue = new Expression\VariableNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			270, 271 => fn() => $this->semValue = new Expression\VariableNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 2]->position),
			272 => fn() => $this->semValue = new Expression\ArrayAccessNode($this->semStack[$pos - 4], $this->semStack[$pos - 2], $this->startTokenStack[$pos - 5]->position),
			275 => fn() => $this->semValue = $this->parseOffset($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			276 => fn() => $this->semValue = $this->parseOffset('-' . $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
		})();
	}
}
