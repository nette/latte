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

	protected const Yy2Tblstate = 250;

	/** Number of non-leaf states */
	protected const NumNonLeafStates = 344;

	/** Map of lexer tokens to internal symbols */
	protected const TokenToSymbol = [
		0,     112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,
		112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   48,    107,   112,   108,   47,    112,   112,
		101,   102,   45,    42,    2,     43,    44,    46,    112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   22,    105,
		35,    7,     37,    21,    59,    112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,
		112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   61,    112,   106,   27,    112,   112,   100,   112,   112,
		112,   98,    112,   112,   112,   112,   112,   112,   112,   99,    112,   112,   112,   112,   112,   112,   112,   112,   112,   112,
		112,   112,   112,   103,   26,    104,   50,    112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,
		112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,
		112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,
		112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,
		112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,
		112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,
		112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   1,     3,     4,     5,
		6,     8,     9,     10,    11,    12,    13,    14,    15,    16,    17,    18,    19,    20,    23,    24,    25,    28,    29,    30,
		31,    32,    33,    34,    36,    38,    39,    40,    41,    49,    51,    52,    53,    54,    55,    56,    57,    58,    60,    62,
		63,    64,    65,    66,    67,    68,    69,    70,    71,    72,    73,    74,    75,    76,    77,    78,    79,    80,    81,    82,
		83,    84,    85,    86,    87,    88,    89,    90,    109,   91,    92,    93,    94,    110,   111,   95,    96,    97,
	];

	/** Map of states to a displacement into the self::Action table. The corresponding action for this
	 *  state/symbol pair is self::Action[self::ActionBase[$state] + $symbol]. If self::ActionBase[$state] is 0, the
	 *  action is defaulted, i.e. self::ActionDefault[$state] should be used instead. */
	protected const ActionBase = [
		317,   141,   141,   141,   141,   100,   141,   141,   221,   221,   221,   221,   221,   -25,   -25,   -25,   326,   326,   287,   269,
		-42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,
		-42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,
		-42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,
		-42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,
		-42,   -42,   86,    195,   354,   357,   356,   358,   367,   368,   369,   370,   376,   53,    53,    53,    53,    53,    53,    53,
		53,    53,    53,    53,    53,    53,    53,    46,    168,   252,   193,   193,   193,   193,   193,   193,   193,   193,   193,   193,
		193,   193,   193,   193,   193,   193,   193,   193,   406,   406,   406,   207,   355,   348,   227,   -67,   248,   248,   127,   127,
		127,   127,   127,   262,   262,   262,   262,   79,    79,    79,    79,    260,   260,   260,   260,   260,   260,   260,   260,   8,
		8,     242,   237,   276,   276,   276,   98,    98,    98,    40,    40,    40,    40,    40,    104,   232,   301,   304,   327,   298,
		298,   298,   298,   298,   298,   331,   377,   -21,   -11,   -11,   266,   93,    93,    93,    -11,   324,   121,   157,   -36,   199,
		316,   302,   305,   290,   5,     353,   246,   263,   303,   26,    26,    26,    26,    -25,   378,   378,   -25,   -25,   -25,   322,
		322,   322,   158,   314,   233,   41,    359,   314,   314,   314,   47,    62,    32,    340,   228,   340,   340,   33,    66,    37,
		340,   340,   340,   340,   99,    37,    37,    325,   333,   294,   145,   61,    294,   312,   312,   122,   18,    342,   341,   343,
		339,   338,   360,   206,   215,   330,   329,   337,   238,   239,   342,   341,   343,   264,   206,   310,   310,   310,   335,   310,
		310,   310,   310,   310,   310,   310,   372,   30,    332,   344,   345,   347,   349,   362,   289,   363,   310,   284,   350,   334,
		328,   373,   206,   336,   374,   318,   321,   320,   315,   323,   319,   375,   351,   364,   175,   346,   178,   365,   183,   361,
		222,   352,   371,   366,   0,     -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,
		-42,   -42,   -42,   -42,   0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     53,    53,    53,    53,    53,    53,    53,    53,    53,
		53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    0,     262,
		53,    53,    53,    53,    53,    53,    53,    0,     0,     0,     0,     93,    93,    93,    93,    40,    40,    40,    40,    40,
		40,    40,    40,    40,    40,    40,    40,    93,    93,    93,    40,    40,    40,    0,     0,     0,     0,     0,     0,     0,
		0,     0,     324,   312,   312,   312,   312,   312,   312,   324,   324,   0,     0,     0,     0,     0,     0,     0,     0,     0,
		324,   312,   0,     0,     0,     0,     0,     0,     0,     -25,   -25,   -25,   324,   0,     0,     0,     0,     0,     312,   312,
		0,     0,     0,     0,     0,     0,     0,     310,   0,     0,     30,    310,   310,   310,
	];

	/** Table of actions. Indexed according to self::ActionBase comment. */
	protected const Action = [
		26,    27,    370,   349,   348,   -258,  28,    -258,  29,    171,   172,   30,    31,    32,    33,    34,    35,    36,    0,     1,
		186,   37,    537,   538,   195,   -212,  520,   370,   8,     189,   535,   277,   6,     236,   237,   7,     91,    278,   279,   11,
		-210,  282,   280,   281,   198,   -212,  -212,  -212,  9,     -8190, -207,  522,   521,   543,   541,   542,   46,    47,    48,    21,
		-210,  -210,  -210,  17,    12,    215,   282,   166,   93,    -210,  -207,  -207,  -207,  190,   19,    191,   49,    50,    51,    -207,
		52,    53,    54,    55,    56,    57,    58,    59,    60,    61,    62,    63,    64,    65,    66,    67,    68,    69,    70,    71,
		72,    181,   188,   351,   352,   350,   518,   -258,  520,   24,    -45,   -258,  165,   73,    -8191, -8191, -8191, -8191, 64,    65,
		66,    67,    68,    69,    225,   399,   -21,   10,    349,   348,   -8190, -8190, -8190, 522,   521,   401,   506,   400,   70,    71,
		72,    361,   188,   489,   351,   352,   350,   372,   -8190, 355,   -8190, -8190, -8190, 73,    -8190, -8190, -8190, -8191, -8191, -8191,
		-8191, -8191, 187,   38,    430,   -213,  194,   370,   289,   349,   348,   -8190, -8190, 290,   357,   226,   227,   356,   362,   291,
		292,   98,    361,   -8190, 99,    -213,  -213,  -213,  197,   100,   355,   90,    -8190, -8190, -174,  -8190, -8190, -8190, -8190, 42,
		330,   14,    410,   187,   38,    -174,  282,   194,   546,   289,   -8190, -8190, -8190, 87,    290,   357,   226,   227,   356,   362,
		291,   292,   88,    620,   351,   352,   350,   621,   -8190, 101,   -8190, -8190, 39,    40,    13,    74,    75,    76,    77,    78,
		79,    80,    81,    82,    83,    84,    85,    86,    -175,  349,   348,   -8190, -8190, -8190, 163,   -8190, -8190, -8190, 189,   -175,
		22,    -22,   361,   189,   164,   351,   352,   350,   -16,   -8190, 355,   -8190, -8190, -8190, 95,    -8190, -8190, -8190, 452,   454,
		199,   200,   201,   187,   38,    -15,   15,    194,   -207,  289,   -255,  89,    -255,  -213,  290,   357,   226,   227,   356,   362,
		291,   292,   -252,  361,   -252,  -73,   96,    -73,   -207,  -207,  -207,  355,   97,    -213,  -213,  -213,  182,   -207,  67,    68,
		69,    1,     -174,  -174,  354,   353,   244,   23,    365,   370,   366,   -73,   535,   -174,  -174,  358,   357,   360,   359,   356,
		362,   363,   364,   193,   -28,   281,   192,   202,   203,   204,   94,    -8190, -8190, -8190, 313,   -8190, 235,   -8190, -8190, -8190,
		-8190, 41,    -209,  188,   368,   -208,  369,   215,   282,   -8190, 73,    -8190, -8190, -8190, -8190, -8190, -8190, 217,   -8190, -8190,
		-8190, 370,   -209,  -209,  -209,  -208,  -208,  -208,  368,   622,   369,   -209,  -255,  370,   -208,  609,   -255,  239,   240,   241,
		270,   92,    -216,  335,   -252,  -215,  282,   -73,   -252,  -8190, -8190, -8190, 18,    174,   547,   25,    254,   3,     548,   -183,
		155,   16,    234,   267,   380,   269,   0,     -8190, 0,     -241,  0,     0,     0,     0,     0,     -239,  -216,  -215,  -214,  2,
		4,     5,     20,    43,    44,    179,   180,   233,   0,     494,   0,     533,   412,   411,   509,   -28,   323,   0,     325,   525,
		495,   589,   0,     45,    252,   0,     617,   619,   381,   618,   505,   616,   573,   584,   587,   0,     343,   0,     532,   562,
		577,   612,   337,   0,     536,   0,     282,
	];

	/** Table indexed analogously to self::Action. If self::ActionCheck[self::ActionBase[$state] + $symbol] != $symbol
	 *  then the action is defaulted, i.e. self::ActionDefault[$state] should be used instead. */
	protected const ActionCheck = [
		42,    43,    69,    28,    29,    0,     48,    2,     50,    51,    52,    53,    54,    55,    56,    57,    58,    59,    0,     61,
		62,    63,    64,    65,    66,    61,    68,    69,    2,     21,    72,    73,    2,     75,    76,    2,     103,   79,    80,    2,
		61,    108,   84,    85,    86,    81,    82,    83,    22,    3,     61,    93,    94,    95,    96,    97,    3,     4,     5,     101,
		81,    82,    83,    2,     2,     107,   108,   26,    2,     90,    81,    82,    83,    26,    21,    28,    23,    24,    25,    90,
		27,    28,    29,    30,    31,    32,    33,    34,    35,    36,    37,    38,    39,    40,    41,    42,    43,    44,    45,    46,
		47,    2,     49,    3,     4,     5,     66,    102,   68,    77,    102,   106,   26,    60,    35,    36,    37,    38,    39,    40,
		41,    42,    43,    44,    2,     85,    22,    101,   28,    29,    3,     4,     5,     93,    94,    95,    104,   97,    45,    46,
		47,    41,    49,    102,   3,     4,     5,     2,     21,    49,    23,    24,    25,    60,    27,    28,    29,    30,    31,    32,
		33,    34,    62,    63,    102,   61,    66,    69,    68,    28,    29,    3,     4,     73,    74,    75,    76,    77,    78,    79,
		80,    6,     41,    85,    6,     81,    82,    83,    102,   6,     49,    91,    71,    95,    90,    97,    3,     4,     5,     101,
		43,    6,     102,   62,    63,    101,   108,   66,    87,    68,    3,     4,     5,     7,     73,    74,    75,    76,    77,    78,
		79,    80,    7,     66,    3,     4,     5,     70,    21,    7,     23,    24,    91,    92,    7,     8,     9,     10,    11,    12,
		13,    14,    15,    16,    17,    18,    19,    20,    90,    28,    29,    3,     4,     5,     26,    3,     4,     5,     21,    101,
		61,    22,    41,    21,    26,    3,     4,     5,     22,    21,    49,    23,    24,    25,    22,    27,    28,    29,    51,    52,
		81,    82,    83,    62,    63,    22,    22,    66,    61,    68,    0,     22,    2,     61,    73,    74,    75,    76,    77,    78,
		79,    80,    0,     41,    2,     0,     22,    2,     81,    82,    83,    49,    91,    81,    82,    83,    22,    90,    42,    43,
		44,    61,    90,    90,    62,    63,    66,    61,    66,    69,    68,    26,    72,    101,   101,   73,    74,    75,    76,    77,
		78,    79,    80,    28,    102,   85,    26,    81,    82,    83,    61,    3,     4,     5,     67,    95,    90,    97,    3,     4,
		5,     101,   61,    49,    66,    61,    68,    107,   108,   21,    60,    23,    24,    25,    71,    27,    21,    61,    23,    24,
		25,    69,    81,    82,    83,    81,    82,    83,    66,    70,    68,    90,    102,   69,    90,    71,    106,   81,    82,    83,
		74,    103,   101,   78,    102,   101,   108,   102,   106,   3,     4,     5,     88,    89,    87,    98,    99,    100,   87,    90,
		90,    103,   90,    102,   91,    102,   -1,    21,    -1,    101,   -1,    -1,    -1,    -1,    -1,    101,   101,   101,   101,   101,
		101,   101,   101,   101,   101,   101,   101,   101,   -1,    102,   -1,    102,   102,   102,   102,   102,   102,   -1,    102,   102,
		102,   102,   -1,    103,   103,   -1,    104,   104,   104,   104,   104,   104,   104,   104,   104,   -1,    105,   -1,    106,   106,
		106,   106,   106,   -1,    107,   -1,    108,
	];

	/** Map of states to their default action */
	protected const ActionDefault = [
		8191,  250,   250,   30,    250,   8191,  250,   28,    8191,  8191,  8191,  28,    8191,  8191,  8191,  8191,  38,    28,    8191,  8191,
		8191,  8191,  205,   205,   205,   8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  9,     8191,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  69,    8191,  8191,  28,    8191,  8191,  8191,  8191,  8191,  8191,
		8191,  8191,  8191,  251,   8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  1,     259,   260,   78,    72,    206,   254,
		257,   74,    77,    75,    42,    43,    49,    114,   116,   148,   115,   90,    95,    96,    97,    98,    99,    100,   101,   102,
		103,   104,   105,   106,   107,   88,    89,    160,   149,   147,   146,   112,   113,   119,   87,    8191,  117,   118,   136,   137,
		134,   135,   138,   8191,  8191,  8191,  8191,  139,   140,   141,   142,   8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  62,
		62,    62,    8191,  126,   127,   129,   8191,  10,    8191,  8191,  8191,  8191,  8191,  8191,  196,   196,   195,   144,   8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  201,   109,   111,   180,   121,   122,   120,   91,    8191,  8191,  8191,  200,   8191,
		267,   207,   207,   207,   207,   33,    33,    33,    8191,  83,    83,    83,    83,    33,    8191,  8191,  33,    33,    33,    8191,
		8191,  8191,  186,   132,   213,   8191,  8191,  123,   124,   125,   50,    8191,  8191,  184,   8191,  173,   8191,  27,    27,    27,
		8191,  225,   226,   227,   27,    27,    27,    164,   35,    64,    27,    27,    64,    8191,  8191,  27,    8191,  8191,  8191,  8191,
		8191,  8191,  8191,  8191,  190,   8191,  211,   223,   2,     176,   14,    19,    20,    8191,  253,   130,   131,   133,   209,   152,
		153,   154,   155,   156,   157,   158,   8191,  246,   179,   8191,  8191,  8191,  8191,  266,   8191,  207,   128,   8191,  8191,  187,
		230,   8191,  256,   208,   8191,  8191,  8191,  52,    53,    8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,
		48,    8191,  8191,  8191,
	];

	/** Map of non-terminals to a displacement into the self::Goto table. The corresponding goto state for this
	 *  non-terminal/state pair is self::Goto[self::GotoBase[$nonTerminal] + $state] (unless defaulted) */
	protected const GotoBase = [
		0,     0,     -5,    0,     0,     96,    0,     141,   32,    -35,   -92,   0,     162,   -50,   0,     0,     0,     0,     167,   169,
		10,    0,     56,    0,     62,    69,    0,     0,     -66,   -2,    -6,    109,   0,     -10,   23,    0,     0,     4,     239,   31,
		0,     59,    0,     0,     124,   0,     0,     0,     19,    0,     0,     0,     0,     -54,   -51,   0,     0,     39,    47,    160,
		55,    33,    -30,   0,     0,     -43,   63,    0,     -80,   171,   158,   53,    0,     0,
	];

	/** Table of states to goto after reduction. Indexed according to self::GotoBase comment. */
	protected const Goto = [
		117,   415,   415,   117,   117,   117,   117,   117,   131,   119,   120,   116,   116,   108,   129,   116,   102,   118,   118,   118,
		113,   295,   296,   243,   297,   299,   300,   301,   302,   303,   304,   305,   438,   438,   114,   115,   104,   105,   106,   107,
		109,   127,   128,   130,   148,   151,   152,   153,   156,   157,   158,   159,   160,   161,   162,   167,   168,   169,   170,   183,
		184,   185,   211,   212,   213,   247,   248,   249,   316,   132,   133,   134,   135,   136,   137,   138,   139,   140,   141,   142,
		143,   144,   145,   146,   149,   121,   110,   111,   122,   112,   150,   123,   121,   124,   147,   125,   126,   173,   173,   173,
		173,   175,   173,   173,   175,   175,   175,   175,   175,   176,   177,   178,   320,   398,   398,   398,   398,   265,   266,   251,
		517,   517,   517,   398,   398,   398,   398,   398,   519,   519,   519,   519,   308,   308,   308,   519,   519,   519,   519,   519,
		519,   519,   519,   576,   576,   576,   317,   324,   342,   317,   317,   317,   317,   317,   581,   582,   588,   588,   588,   588,
		588,   588,   312,   220,   591,   375,   420,   332,   216,   376,   220,   220,   388,   429,   428,   246,   309,   310,   338,   383,
		220,   220,   623,   510,   385,   228,   309,   310,   268,   311,   329,   574,   574,   220,   208,   209,   221,   315,   222,   214,
		223,   224,   534,   534,   534,   534,   534,   534,   534,   534,   556,   556,   556,   556,   556,   556,   556,   556,   554,   554,
		554,   554,   554,   554,   554,   554,   298,   298,   298,   298,   298,   298,   298,   298,   426,   423,   424,   391,   340,   513,
		294,   294,   294,   294,   409,   294,   294,   487,   206,   514,   515,   607,   511,   322,   516,   564,   565,   566,   395,   402,
		404,   403,   405,   262,   263,   578,   579,   580,   607,   608,   339,   613,   614,   615,   0,     0,     0,     0,     0,     0,
		0,     608,   0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     319,   0,     0,     0,
		0,     0,     0,     0,     229,   230,   231,   232,
	];

	/** Table indexed analogously to self::Goto. If self::GotoCheck[self::GotoBase[$nonTerminal] + $state] != $nonTerminal
	 *  then the goto state is defaulted, i.e. self::GotoDefault[$nonTerminal] should be used. */
	protected const GotoCheck = [
		2,     33,    33,    2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     5,     5,     5,
		5,     5,     5,     5,     5,     5,     5,     5,     5,     5,     5,     5,     65,    28,    28,    28,    28,    31,    31,    31,
		28,    28,    28,    28,    28,    28,    28,    28,    54,    54,    54,    54,    53,    53,    53,    54,    54,    54,    54,    54,
		54,    54,    54,    65,    65,    65,    7,     44,    44,    7,     7,     7,     7,     7,     68,    68,    65,    65,    65,    65,
		65,    65,    59,    9,     70,    12,    10,    10,    62,    12,    9,     9,     10,    10,    10,    69,    13,    13,    10,    10,
		9,     9,     9,     10,    18,    62,    13,    13,    13,    19,    20,    65,    65,    9,     34,    34,    34,    34,    34,    34,
		34,    34,    39,    39,    39,    39,    39,    39,    39,    39,    57,    57,    57,    57,    57,    57,    57,    57,    58,    58,
		58,    58,    58,    58,    58,    58,    60,    60,    60,    60,    60,    60,    60,    60,    37,    37,    37,    22,    9,     9,
		38,    38,    38,    38,    24,    38,    38,    41,    61,    30,    30,    71,    48,    38,    30,    30,    30,    30,    25,    25,
		25,    25,    25,    66,    66,    66,    66,    66,    71,    71,    29,    8,     8,     8,     -1,    -1,    -1,    -1,    -1,    -1,
		-1,    71,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    7,     -1,    -1,    -1,
		-1,    -1,    -1,    -1,    7,     7,     7,     7,
	];

	/** Map of non-terminals to the default state to goto after their reduction */
	protected const GotoDefault = [
		-8192, 276,   103,   288,   347,   378,   367,   293,   586,   572,   373,   257,   593,   274,   273,   437,   333,   271,   384,   334,
		326,   264,   390,   238,   407,   250,   327,   328,   255,   336,   529,   259,   318,   414,   154,   258,   245,   425,   283,   284,
		436,   253,   503,   272,   321,   507,   341,   275,   512,   563,   256,   285,   260,   526,   242,   210,   286,   218,   207,   306,
		196,   205,   606,   219,   287,   561,   261,   568,   575,   307,   592,   605,   314,   331,
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
		42,    42,    42,    42,    42,    44,    44,    56,    56,    56,    56,    63,    63,    63,    50,    50,    50,    64,    64,    64,
		64,    64,    64,    34,    34,    34,    34,    34,    65,    65,    68,    67,    55,    55,    55,    55,    55,    55,    55,    52,
		52,    52,    66,    66,    66,    38,    59,    69,    69,    70,    70,    12,    12,    12,    12,    12,    12,    12,    12,    12,
		12,    61,    61,    61,    61,    62,    72,    71,    71,    71,    71,    71,    71,    71,    71,    71,    73,    73,    73,    73,
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
		1,     1,     3,     2,     3,     0,     1,     1,     3,     1,     1,     1,     1,     1,     1,     3,     1,     1,     4,     1,
		4,     4,     4,     1,     1,     3,     3,     3,     1,     4,     1,     3,     1,     4,     3,     3,     3,     3,     3,     1,
		3,     1,     1,     3,     1,     4,     1,     3,     1,     1,     0,     1,     2,     1,     3,     4,     3,     3,     4,     2,
		2,     2,     2,     1,     2,     1,     1,     1,     4,     3,     3,     3,     3,     3,     6,     3,     1,     1,     2,     1,
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
		"'heredoc start'",
		"'heredoc end'",
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
			0, 1, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 27, 28, 50, 63, 65, 87, 92, 93, 159, 173, 175, 179, 180, 182, 183, 185, 190, 195, 200, 201, 206, 207, 209, 210, 211, 212, 214, 216, 217, 219, 223, 224, 228, 232, 239, 241, 242, 244, 249, 267, 279 => fn() => $this->semValue = $this->semStack[$pos],
			2 => fn() => $this->semValue = new Node\ModifierNode($this->semStack[$pos]),
			3 => fn() => $this->semValue = new Expression\ArrayNode($this->semStack[$pos]),
			21, 22, 23, 24, 25, 55, 56, 57 => fn() => $this->semValue = new Node\IdentifierNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			26 => fn() => $this->semValue = new Expression\VariableNode(substr($this->semStack[$pos], 1), $this->startTokenStack[$pos]->position),
			29, 39, 44, 76, 84, 85, 86, 144, 145, 165, 166, 181, 208, 215, 240, 243, 275 => fn() => $this->semValue = $this->semStack[$pos - 1],
			30, 38, 45, 66, 83, 164, 184 => fn() => $this->semValue = [],
			31, 40, 46, 70, 78, 167, 248, 263 => fn() => $this->semValue = [$this->semStack[$pos]],
			32, 41, 47, 59, 61, 71, 77, 168, 247 => function () use ($pos) {
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
			62, 64, 205, 250 => fn() => $this->semValue = null,
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
			189, 245 => fn() => $this->semValue = new Expression\ArrayNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			191 => fn() => $this->semValue = Scalar\StringNode::parse($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			192 => fn() => $this->semValue = Scalar\EncapsedStringNode::parse($this->semStack[$pos - 1], $this->startTokenStack[$pos - 2]->position),
			193 => fn() => $this->semValue = Scalar\IntegerNode::parse($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			194 => fn() => $this->semValue = Scalar\FloatNode::parse($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			196, 276 => fn() => $this->semValue = new Scalar\StringNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			197 => fn() => $this->semValue = new Scalar\BooleanNode(true, $this->startTokenStack[$pos]->position),
			198 => fn() => $this->semValue = new Scalar\BooleanNode(false, $this->startTokenStack[$pos]->position),
			199 => fn() => $this->semValue = new Scalar\NullNode($this->startTokenStack[$pos]->position),
			202 => fn() => $this->semValue = $this->parseDocString($this->semStack[$pos - 2], [$this->semStack[$pos - 1]], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position, $this->startTokenStack[$pos]->position),
			203 => fn() => $this->semValue = $this->parseDocString($this->semStack[$pos - 1], [], $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position, $this->startTokenStack[$pos]->position),
			204 => fn() => $this->semValue = $this->parseDocString($this->semStack[$pos - 2], $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position, $this->startTokenStack[$pos]->position),
			213 => fn() => $this->semValue = new Expression\ConstantFetchNode(new Node\NameNode($this->semStack[$pos], Node\NameNode::KindNormal, $this->startTokenStack[$pos]->position), $this->startTokenStack[$pos]->position),
			218, 233, 268 => fn() => $this->semValue = new Expression\ArrayAccessNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			220 => fn() => $this->semValue = new Expression\MethodCallNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position),
			221 => fn() => $this->semValue = new Expression\NullsafeMethodCallNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position),
			222 => fn() => $this->semValue = new Expression\UndefinedsafeMethodCallNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position),
			225, 234, 269 => fn() => $this->semValue = new Expression\PropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			226, 235, 270 => fn() => $this->semValue = new Expression\NullsafePropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			227, 236, 271 => fn() => $this->semValue = new Expression\UndefinedsafePropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			229 => fn() => $this->semValue = new Expression\VariableNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			230 => function () use ($pos) {
				$var = $this->semStack[$pos]->name;
				$this->semValue = \is_string($var)
					? new Node\VarLikeIdentifierNode($var, $this->startTokenStack[$pos]->position)
					: $var;
			},
			231, 237, 238 => fn() => $this->semValue = new Expression\StaticPropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			246 => function () use ($pos) {
				$this->semValue = $this->semStack[$pos];
				$end = count($this->semValue) - 1;
				if ($this->semValue[$end] === null) {
					array_pop($this->semValue);
				}
			},
			251, 253 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], null, false, false, $this->startTokenStack[$pos]->position),
			252 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], null, true, false, $this->startTokenStack[$pos - 1]->position),
			254, 256, 257 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], $this->semStack[$pos - 2], false, false, $this->startTokenStack[$pos - 2]->position),
			255, 258 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], $this->semStack[$pos - 3], true, false, $this->startTokenStack[$pos - 3]->position),
			259, 260 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], null, false, true, $this->startTokenStack[$pos - 1]->position),
			261, 262 => function () use ($pos) {
				$this->semStack[$pos - 1][] = $this->semStack[$pos];
				$this->semValue = $this->semStack[$pos - 1];
			},
			264 => fn() => $this->semValue = [$this->semStack[$pos - 1], $this->semStack[$pos]],
			265 => fn() => $this->semValue = new Scalar\EncapsedStringPartNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			266 => fn() => $this->semValue = new Expression\VariableNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			272, 273 => fn() => $this->semValue = new Expression\VariableNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 2]->position),
			274 => fn() => $this->semValue = new Expression\ArrayAccessNode($this->semStack[$pos - 4], $this->semStack[$pos - 2], $this->startTokenStack[$pos - 5]->position),
			277 => fn() => $this->semValue = TagParser::parseOffset($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			278 => fn() => $this->semValue = TagParser::parseOffset('-' . $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
		})();
	}
}
