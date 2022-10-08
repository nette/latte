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

	protected const Yy2Tblstate = 259;

	/** Number of non-leaf states */
	protected const NumNonLeafStates = 352;

	/** Map of lexer tokens to internal symbols */
	protected const TokenToSymbol = [
		0,     113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,
		113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   49,    108,   113,   109,   48,    113,   113,
		102,   103,   46,    43,    2,     44,    45,    47,    113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   22,    106,
		36,    7,     38,    21,    60,    113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,
		113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   62,    113,   107,   27,    113,   113,   101,   113,   113,
		113,   99,    113,   113,   113,   113,   113,   113,   113,   100,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,
		113,   113,   113,   104,   26,    105,   51,    113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,
		113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,
		113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,
		113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,
		113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,
		113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,
		113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   1,     3,     4,     5,
		6,     8,     9,     10,    11,    12,    13,    14,    15,    16,    17,    18,    19,    20,    23,    24,    25,    28,    29,    30,
		31,    32,    33,    34,    35,    37,    39,    40,    41,    42,    50,    52,    53,    54,    55,    56,    57,    58,    59,    61,
		63,    64,    65,    66,    67,    68,    69,    70,    71,    72,    73,    74,    75,    76,    77,    78,    79,    80,    81,    82,
		83,    84,    85,    86,    87,    88,    89,    90,    91,    110,   92,    93,    94,    95,    111,   112,   96,    97,    98,
	];

	/** Map of states to a displacement into the self::Action table. The corresponding action for this
	 *  state/symbol pair is self::Action[self::ActionBase[$state] + $symbol]. If self::ActionBase[$state] is 0, the
	 *  action is defaulted, i.e. self::ActionDefault[$state] should be used instead. */
	protected const ActionBase = [
		133,   336,   336,   336,   336,   114,   134,   336,   316,   215,   235,   336,   417,   417,   417,   417,   417,   171,   171,   171,
		286,   286,   268,   244,   375,   376,   380,   384,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,
		-30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,
		-30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,
		-30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,
		-30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   118,   176,   397,   405,   398,   407,   448,   451,   452,   456,
		455,   66,    66,    66,    66,    66,    66,    66,    66,    66,    66,    66,    66,    66,    66,    153,   127,   422,   7,     7,
		7,     7,     7,     7,     7,     7,     7,     7,     7,     7,     7,     7,     7,     7,     7,     7,     469,   469,   469,   528,
		445,   148,   423,   -55,   325,   325,   325,   225,   225,   225,   225,   225,   449,   449,   449,   449,   44,    44,    44,    44,
		59,    59,    59,    59,    59,    59,    59,    59,    93,    17,    17,    38,    175,   -41,   -41,   261,   261,   261,   239,   239,
		239,   239,   239,   185,   140,   420,   453,   255,   -51,   -51,   -51,   -51,   -51,   -51,   280,   458,   189,   457,   457,   476,
		115,   115,   115,   457,   233,   -71,   79,    -21,   103,   289,   358,   122,   281,   73,    385,   214,   223,   283,   275,   275,
		275,   275,   171,   454,   454,   171,   171,   171,   219,   219,   219,   -60,   191,   34,    102,   414,   191,   191,   191,   48,
		46,    10,    307,   274,   304,   305,   50,    90,    58,    321,   322,   307,   307,   94,    58,    58,    288,   270,   252,   145,
		75,    252,   262,   262,   138,   8,     349,   345,   353,   302,   300,   430,   197,   209,   284,   278,   299,   291,   213,   349,
		345,   353,   240,   197,   200,   200,   200,   285,   200,   200,   200,   200,   200,   200,   200,   282,   43,    287,   354,   356,
		360,   361,   277,   264,   355,   200,   248,   279,   272,   436,   197,   296,   443,   416,   315,   298,   273,   359,   271,   447,
		368,   440,   160,   357,   164,   441,   173,   438,   220,   374,   415,   442,   0,     -30,   -30,   -30,   -30,   -30,   -30,   -30,
		-30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,   -30,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     66,    66,    66,    66,    66,    66,    66,    66,    66,    66,    66,    0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     66,    66,    66,    66,    66,    66,    66,    66,    66,    66,    66,    66,    66,
		66,    66,    66,    66,    66,    66,    66,    66,    66,    66,    66,    66,    66,    66,    0,     449,   66,    66,    66,    66,
		66,    66,    66,    66,    0,     0,     0,     0,     -41,   -41,   -41,   -41,   239,   239,   239,   239,   239,   239,   239,   239,
		-41,   239,   239,   239,   239,   0,     0,     239,   239,   239,   0,     0,     0,     0,     0,     0,     0,     0,     0,     233,
		262,   262,   262,   262,   262,   262,   233,   233,   0,     0,     0,     0,     -41,   -41,   -41,   0,     0,     233,   262,   0,
		0,     0,     0,     0,     0,     0,     171,   171,   171,   233,   0,     0,     0,     0,     0,     262,   262,   0,     0,     0,
		0,     0,     0,     0,     200,   0,     0,     43,    200,   200,   200,
	];

	/** Table of actions. Indexed according to self::ActionBase comment. */
	protected const Action = [
		34,    -8190, 76,    77,    78,    79,    80,    81,    0,     197,   -8190, -8190, -8190, 35,    36,    378,   376,   556,   377,   37,
		82,    38,    180,   181,   39,    40,    41,    42,    43,    44,    45,    -177,  1,     195,   46,    547,   548,   204,   198,   530,
		378,   -214,  -177,  545,   286,   7,     245,   246,   16,    99,    287,   288,   11,    100,   291,   289,   290,   207,   291,   198,
		15,    -214,  -214,  -214,  532,   531,   553,   551,   552,   55,    56,    57,    29,    -261,  199,   -261,  200,   21,    224,   291,
		-8191, -8191, -8191, -8191, 73,    74,    75,    23,    32,    58,    59,    60,    102,   61,    62,    63,    191,   64,    65,    66,
		67,    68,    69,    70,    71,    72,    73,    74,    75,    76,    77,    78,    79,    80,    81,    513,   197,   359,   360,   358,
		-45,   1,     -71,   338,   -71,   -176,  253,   82,    175,   378,   -8190, -8190, 545,   -8191, 74,    75,    -176,  359,   360,   358,
		234,   -28,   357,   356,   174,   290,   631,   380,   -71,   436,   632,   -8190, -8190, -8190, 369,   -8190, -8190, -8190, -8190, -8190,
		-8190, 50,    357,   356,   363,   30,    106,   224,   291,   -8190, 107,   -8190, -8190, -8190, 369,   -8190, -261,  196,   47,    108,
		-261,  203,   18,    298,   363,   208,   209,   210,   299,   365,   235,   236,   364,   370,   300,   301,   198,   196,   47,    357,
		356,   203,   -215,  298,   96,    496,   24,    -21,   299,   365,   235,   236,   364,   370,   300,   301,   97,    418,   359,   360,
		358,   206,   -215,  -215,  -215,  -71,   25,    109,   -8190, -8190, -8190, -176,  33,    263,   3,     -22,   -16,   418,   359,   360,
		358,   197,   -176,  357,   356,   -15,   -8190, -215,  -8190, -8190, -8190, -212,  -8190, -8190, -8190, 369,   -8191, -8191, -8191, -8191,
		-8191, 82,    19,    357,   356,   363,   98,    -215,  -215,  -215,  105,   -212,  -212,  -212,  192,   369,   -176,  12,    196,   47,
		-212,  -258,  203,   -258,  298,   363,   376,   -176,  377,   299,   365,   235,   236,   364,   370,   300,   301,   13,    196,   47,
		172,   202,   203,   378,   298,   620,   528,   26,    530,   299,   365,   235,   236,   364,   370,   300,   301,   173,   418,   359,
		360,   358,   22,    183,   201,   407,   103,   27,    -8190, -8190, -8190, 378,   378,   532,   531,   409,   322,   408,   418,   359,
		360,   358,   633,   557,   357,   356,   -8190, -8190, -8190, -8190, -8190, 226,   -8190, -8190, -8190, -8190, 369,   -8190, -255,  -8190,
		-255,  279,   388,   51,    357,   356,   363,   343,   558,   -185,  291,   248,   249,   250,   -244,  163,   369,   14,    243,   196,
		47,    -242,  628,   203,   -258,  298,   363,   -218,  -258,  542,   299,   365,   235,   236,   364,   370,   300,   301,   -217,  196,
		47,    -216,  2,     203,   4,     298,   5,     6,     101,   8,     299,   365,   235,   236,   364,   370,   300,   301,   276,   418,
		359,   360,   358,   9,     10,    -8190, -8190, -8190, 48,    49,    17,    83,    84,    85,    86,    87,    88,    89,    90,    91,
		92,    93,    94,    95,    104,   357,   356,   28,    -8190, -8190, -8190, 52,    359,   360,   358,   53,    189,   369,   190,   242,
		630,   -255,  278,   501,   543,   -255,  -8190, 363,   -8190, -8190, -8190, 419,   -8190, -8190, -8190, 458,   460,   516,   522,   524,
		196,   47,    -211,  526,   203,   -209,  298,   575,   -28,   369,   -8190, 299,   365,   235,   236,   364,   370,   300,   301,   363,
		331,   333,   -211,  -211,  -211,  -209,  -209,  -209,  535,   101,   502,   -211,  362,   361,   -209,  -210,  373,   600,   374,   -209,
		20,    351,   -218,  366,   365,   368,   367,   364,   370,   371,   372,   -8190, -8190, -8190, 54,    -210,  -210,  -210,  31,    -209,
		-209,  -209,  261,   572,   -210,  389,   629,   512,   -209,  -8190, 588,   -8190, -8190, 627,   623,   -217,  584,   595,   211,   212,
		213,   598,   345,   291,   0,     0,     546,   244,
	];

	/** Table indexed analogously to self::Action. If self::ActionCheck[self::ActionBase[$state] + $symbol] != $symbol
	 *  then the action is defaulted, i.e. self::ActionDefault[$state] should be used instead. */
	protected const ActionCheck = [
		30,    72,    43,    44,    45,    46,    47,    48,    0,     50,    3,     4,     5,     43,    44,    70,    67,    88,    69,    49,
		61,    51,    52,    53,    54,    55,    56,    57,    58,    59,    60,    91,    62,    63,    64,    65,    66,    67,    21,    69,
		70,    62,    102,   73,    74,    2,     76,    77,    2,     104,   80,    81,    2,     104,   109,   85,    86,    87,    109,   21,
		2,     82,    83,    84,    94,    95,    96,    97,    98,    3,     4,     5,     102,   0,     26,    2,     28,    2,     108,   109,
		36,    37,    38,    39,    40,    41,    42,    21,    78,    23,    24,    25,    2,     27,    28,    29,    2,     31,    32,    33,
		34,    35,    36,    37,    38,    39,    40,    41,    42,    43,    44,    45,    46,    47,    48,    105,   50,    3,     4,     5,
		103,   62,    0,     44,    2,     91,    67,    61,    26,    70,    3,     4,     73,    40,    41,    42,    102,   3,     4,     5,
		2,     103,   28,    29,    26,    86,    67,    2,     26,    103,   71,    3,     4,     5,     40,    96,    3,     98,    43,    44,
		45,    102,   28,    29,    50,    62,    6,     108,   109,   21,    6,     23,    24,    25,    40,    27,    103,   63,    64,    6,
		107,   67,    6,     69,    50,    82,    83,    84,    74,    75,    76,    77,    78,    79,    80,    81,    21,    63,    64,    28,
		29,    67,    62,    69,    7,     103,   92,    22,    74,    75,    76,    77,    78,    79,    80,    81,    7,     103,   3,     4,
		5,     103,   82,    83,    84,    103,   92,    7,     3,     4,     5,     91,    99,    100,   101,   22,    22,    103,   3,     4,
		5,     50,    102,   28,    29,    22,    21,    62,    23,    24,    25,    62,    27,    28,    29,    40,    31,    32,    33,    34,
		35,    61,    22,    28,    29,    50,    22,    82,    83,    84,    22,    82,    83,    84,    22,    40,    91,    2,     63,    64,
		91,    0,     67,    2,     69,    50,    67,    102,   69,    74,    75,    76,    77,    78,    79,    80,    81,    22,    63,    64,
		26,    28,    67,    70,    69,    72,    67,    92,    69,    74,    75,    76,    77,    78,    79,    80,    81,    26,    103,   3,
		4,     5,     89,    90,    26,    86,    62,    92,    3,     4,     5,     70,    70,    94,    95,    96,    68,    98,    103,   3,
		4,     5,     71,    88,    28,    29,    21,    86,    23,    24,    25,    62,    27,    28,    29,    72,    40,    96,    0,     98,
		2,     75,    92,    102,   28,    29,    50,    79,    88,    91,    109,   82,    83,    84,    102,   91,    40,    102,   91,    63,
		64,    102,   105,   67,    103,   69,    50,    102,   107,   107,   74,    75,    76,    77,    78,    79,    80,    81,    102,   63,
		64,    102,   102,   67,    102,   69,    102,   102,   92,    102,   74,    75,    76,    77,    78,    79,    80,    81,    103,   103,
		3,     4,     5,     102,   102,   3,     4,     5,     92,    93,    7,     8,     9,     10,    11,    12,    13,    14,    15,    16,
		17,    18,    19,    20,    22,    28,    29,    102,   3,     4,     5,     102,   3,     4,     5,     102,   102,   40,    102,   102,
		105,   103,   103,   103,   103,   107,   21,    50,    23,    24,    25,    103,   3,     4,     5,     52,    53,    103,   103,   103,
		63,    64,    62,    103,   67,    62,    69,    103,   103,   40,    21,    74,    75,    76,    77,    78,    79,    80,    81,    50,
		103,   103,   82,    83,    84,    82,    83,    84,    103,   92,    103,   91,    63,    64,    91,    62,    67,    103,   69,    62,
		104,   106,   102,   74,    75,    76,    77,    78,    79,    80,    81,    3,     4,     5,     104,   82,    83,    84,    62,    82,
		83,    84,    104,   107,   91,    105,   105,   105,   91,    21,    107,   23,    24,    105,   107,   102,   105,   105,   82,    83,
		84,    105,   107,   109,   -1,    -1,    108,   91,
	];

	/** Map of states to their default action */
	protected const ActionDefault = [
		8191,  253,   253,   30,    253,   8191,  8191,  253,   8191,  8191,  8191,  28,    8191,  8191,  8191,  28,    8191,  8191,  8191,  8191,
		38,    28,    8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  207,   207,   207,   8191,  8191,  8191,  8191,  8191,  8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  9,     8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,
		8191,  8191,  28,    8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  254,   8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,
		8191,  1,     262,   263,   76,    70,    208,   257,   260,   72,    75,    73,    42,    43,    49,    112,   114,   147,   113,   88,
		93,    94,    95,    96,    97,    98,    99,    100,   101,   102,   103,   104,   105,   86,    87,    159,   148,   146,   145,   110,
		111,   117,   85,    8191,  131,   115,   116,   135,   136,   133,   134,   137,   8191,  8191,  8191,  8191,  138,   139,   140,   141,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  127,   62,    62,    62,    8191,  124,   125,   8191,  10,    8191,  8191,  8191,
		8191,  8191,  8191,  198,   198,   197,   143,   8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  203,   107,   109,   182,
		119,   120,   118,   89,    8191,  8191,  8191,  202,   8191,  270,   209,   209,   209,   209,   33,    33,    33,    8191,  81,    81,
		81,    81,    33,    8191,  8191,  33,    33,    33,    8191,  8191,  8191,  188,   130,   215,   8191,  8191,  121,   122,   123,   50,
		8191,  8191,  186,   8191,  175,   8191,  27,    27,    27,    8191,  228,   229,   230,   27,    27,    27,    163,   35,    64,    27,
		27,    64,    8191,  8191,  27,    8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  192,   8191,  213,   226,   2,     178,   14,
		19,    20,    8191,  256,   128,   129,   132,   211,   151,   152,   153,   154,   155,   156,   157,   8191,  249,   181,   8191,  8191,
		8191,  8191,  269,   8191,  209,   126,   8191,  189,   233,   8191,  259,   210,   8191,  8191,  8191,  52,    53,    8191,  8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  48,    8191,  8191,  8191,
	];

	/** Map of non-terminals to a displacement into the self::Goto table. The corresponding goto state for this
	 *  non-terminal/state pair is self::Goto[self::GotoBase[$nonTerminal] + $state] (unless defaulted) */
	protected const GotoBase = [
		0,     0,     -1,    0,     0,     108,   0,     184,   23,    -18,   -64,   0,     181,   -103,  0,     0,     0,     0,     169,   170,
		9,     0,     11,    0,     13,    -78,   0,     0,     -61,   -63,   -257,  104,   -11,   -32,   0,     0,     21,    252,   41,    0,
		15,    0,     0,     125,   0,     0,     0,     16,    0,     0,     0,     0,     71,    -25,   0,     0,     49,    57,    7,     65,
		-10,   50,    0,     0,     -50,   -31,   0,     -75,   110,   4,     -24,   0,     0,
	];

	/** Table of states to goto after reduction. Indexed according to self::GotoBase comment. */
	protected const Goto = [
		111,   111,   111,   111,   421,   421,   111,   521,   523,   321,   111,   602,   525,   574,   576,   577,   139,   127,   128,   124,
		124,   116,   137,   129,   129,   129,   129,   124,   110,   126,   126,   126,   121,   164,   304,   305,   252,   306,   308,   309,
		310,   311,   312,   313,   314,   444,   444,   122,   123,   112,   113,   114,   115,   117,   135,   136,   138,   156,   159,   160,
		161,   165,   166,   167,   168,   169,   170,   171,   176,   177,   178,   179,   188,   193,   194,   220,   221,   222,   256,   257,
		258,   325,   140,   141,   142,   143,   144,   145,   146,   147,   148,   149,   150,   151,   152,   153,   154,   157,   118,   119,
		129,   130,   120,   158,   131,   132,   155,   133,   134,   182,   182,   182,   182,   328,   255,   182,   274,   275,   260,   182,
		403,   410,   412,   411,   413,   185,   186,   187,   406,   406,   406,   406,   318,   319,   527,   527,   527,   406,   406,   406,
		406,   406,   318,   319,   277,   587,   587,   587,   217,   218,   230,   324,   231,   223,   232,   233,   332,   350,   599,   599,
		599,   599,   599,   599,   529,   529,   529,   529,   592,   593,   529,   529,   529,   529,   529,   529,   529,   529,   271,   272,
		589,   590,   591,   618,   383,   302,   302,   302,   302,   229,   393,   302,   384,   585,   585,   302,   229,   229,   320,   337,
		618,   619,   399,   426,   340,   417,   229,   229,   634,   396,   435,   434,   494,   619,   215,   346,   391,   0,     347,   229,
		517,   544,   544,   544,   544,   544,   544,   544,   544,   566,   566,   566,   566,   566,   566,   566,   566,   564,   564,   564,
		564,   564,   564,   564,   564,   307,   307,   307,   307,   307,   307,   307,   307,   303,   303,   303,   303,   225,   518,   303,
		432,   429,   430,   303,   348,   520,   317,   317,   317,   0,     330,   624,   625,   626,   237,   0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     386,   386,   386,   0,     0,     327,   0,     0,     386,   0,     0,     386,   386,   386,   238,   239,   240,   241,
	];

	/** Table indexed analogously to self::Goto. If self::GotoCheck[self::GotoBase[$nonTerminal] + $state] != $nonTerminal
	 *  then the goto state is defaulted, i.e. self::GotoDefault[$nonTerminal] should be used. */
	protected const GotoCheck = [
		2,     2,     2,     2,     32,    32,    2,     30,    30,    58,    2,     69,    30,    30,    30,    30,    2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     5,     5,     5,     5,     64,    68,    5,     31,    31,    31,    5,
		25,    25,    25,    25,    25,    5,     5,     5,     28,    28,    28,    28,    13,    13,    28,    28,    28,    28,    28,    28,
		28,    28,    13,    13,    13,    64,    64,    64,    33,    33,    33,    33,    33,    33,    33,    33,    43,    43,    64,    64,
		64,    64,    64,    64,    53,    53,    53,    53,    67,    67,    53,    53,    53,    53,    53,    53,    53,    53,    65,    65,
		65,    65,    65,    70,    12,    7,     7,     7,     7,     9,     18,    7,     12,    64,    64,    7,     9,     9,     19,    20,
		70,    70,    22,    10,    10,    24,    9,     9,     9,     10,    10,    10,    40,    70,    60,    10,    10,    -1,    29,    9,
		10,    38,    38,    38,    38,    38,    38,    38,    38,    56,    56,    56,    56,    56,    56,    56,    56,    57,    57,    57,
		57,    57,    57,    57,    57,    59,    59,    59,    59,    59,    59,    59,    59,    37,    37,    37,    37,    61,    47,    37,
		36,    36,    36,    37,    9,     9,     52,    52,    52,    -1,    37,    8,     8,     8,     61,    -1,    -1,    -1,    -1,    -1,
		-1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
		-1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
		-1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
		-1,    -1,    5,     5,     5,     -1,    -1,    7,     -1,    -1,    5,     -1,    -1,    5,     5,     5,     7,     7,     7,     7,
	];

	/** Map of non-terminals to the default state to goto after their reduction */
	protected const GotoDefault = [
		-8192, 285,   125,   297,   355,   184,   375,   326,   597,   583,   381,   266,   604,   283,   282,   443,   341,   280,   392,   342,
		334,   273,   398,   247,   415,   259,   335,   336,   264,   344,   539,   268,   420,   162,   267,   254,   431,   292,   293,   442,
		262,   510,   281,   329,   514,   349,   284,   519,   573,   265,   294,   269,   536,   251,   219,   295,   227,   216,   315,   205,
		214,   617,   228,   296,   571,   270,   579,   586,   316,   603,   616,   323,   339,
	];

	/** Map of rules to the non-terminal on their left-hand side, i.e. the non-terminal to use for
	 *  determining the state to goto after reduction. */
	protected const RuleToNonTerminal = [
		0,     1,     1,     1,     5,     5,     6,     6,     6,     6,     6,     6,     6,     6,     6,     6,     6,     6,     6,     6,
		6,     7,     7,     7,     8,     8,     9,     10,    10,    4,     4,     11,    11,    13,    13,    14,    14,    15,    16,    16,
		17,    17,    18,    18,    20,    20,    21,    21,    22,    22,    24,    24,    24,    24,    25,    25,    25,    25,    26,    26,
		27,    27,    23,    23,    29,    29,    30,    30,    31,    31,    32,    32,    32,    32,    19,    34,    34,    35,    35,    3,
		3,     36,    36,    36,    36,    2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     39,    42,    42,    45,    46,    46,    47,    48,    48,    48,    48,    48,    48,    52,    28,    28,    53,    53,
		53,    40,    40,    40,    50,    50,    44,    44,    56,    57,    38,    59,    59,    59,    59,    41,    41,    41,    41,    41,
		41,    41,    41,    41,    41,    41,    41,    43,    43,    55,    55,    55,    55,    62,    62,    62,    49,    49,    49,    63,
		63,    63,    63,    63,    63,    63,    33,    33,    33,    33,    33,    64,    64,    67,    66,    54,    54,    54,    54,    54,
		54,    54,    51,    51,    51,    65,    65,    65,    37,    58,    68,    68,    69,    69,    12,    12,    12,    12,    12,    12,
		12,    12,    12,    12,    60,    60,    60,    60,    61,    71,    70,    70,    70,    70,    70,    70,    70,    70,    70,    72,
		72,    72,    72,
	];

	/** Map of rules to the length of their right-hand side, which is the number of elements that have to
	 *  be popped from the stack(s) on reduction. */
	protected const RuleToLength = [
		1,     2,     2,     2,     1,     1,     1,     1,     1,     1,     1,     1,     1,     1,     1,     1,     1,     1,     1,     1,
		1,     1,     1,     1,     1,     1,     1,     0,     1,     2,     0,     1,     3,     0,     1,     0,     1,     7,     0,     2,
		1,     3,     3,     4,     2,     0,     1,     3,     4,     6,     1,     2,     1,     1,     1,     1,     1,     1,     3,     3,
		3,     3,     0,     1,     0,     2,     2,     4,     1,     3,     1,     2,     2,     3,     2,     3,     1,     4,     4,     3,
		4,     0,     3,     3,     3,     1,     3,     3,     3,     4,     1,     1,     2,     3,     3,     3,     3,     3,     3,     3,
		3,     3,     3,     3,     3,     3,     2,     2,     2,     2,     3,     3,     3,     3,     3,     3,     3,     3,     3,     3,
		3,     3,     3,     3,     3,     3,     3,     3,     2,     2,     2,     2,     2,     3,     3,     3,     3,     3,     3,     3,
		3,     3,     3,     3,     3,     5,     4,     3,     3,     4,     4,     2,     2,     2,     2,     2,     2,     2,     1,     8,
		12,    9,     3,     0,     4,     2,     1,     3,     2,     2,     4,     2,     4,     4,     6,     1,     1,     1,     1,     1,
		1,     1,     1,     3,     1,     1,     0,     1,     1,     3,     3,     4,     1,     1,     3,     1,     1,     1,     1,     1,
		1,     1,     1,     1,     3,     2,     3,     0,     1,     1,     3,     1,     1,     1,     1,     1,     1,     3,     1,     1,
		4,     1,     4,     6,     4,     4,     1,     1,     3,     3,     3,     1,     4,     1,     3,     1,     4,     3,     3,     3,
		3,     3,     1,     3,     1,     1,     3,     1,     4,     1,     3,     1,     1,     0,     1,     2,     1,     3,     4,     3,
		3,     4,     2,     2,     2,     2,     1,     2,     1,     1,     1,     4,     3,     3,     3,     3,     3,     6,     3,     1,
		1,     2,     1,
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
		"'not'",
		"'=='",
		"'!='",
		"'==='",
		"'!=='",
		"'<=>'",
		"'<'",
		"'<='",
		"'>'",
		"'>='",
		"'in'",
		"'<<'",
		"'>>'",
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
			0, 1, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 27, 28, 50, 63, 65, 85, 90, 91, 158, 175, 177, 181, 182, 184, 185, 187, 192, 197, 202, 203, 208, 209, 211, 212, 213, 214, 216, 218, 219, 221, 226, 227, 231, 235, 242, 244, 245, 247, 252, 270, 282 => fn() => $this->semValue = $this->semStack[$pos],
			2 => fn() => $this->semValue = new Node\ModifierNode($this->semStack[$pos]),
			3 => fn() => $this->semValue = new Expression\ArrayNode($this->semStack[$pos]),
			21, 22, 23, 24, 25, 55, 56, 57 => fn() => $this->semValue = new Node\IdentifierNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			26 => fn() => $this->semValue = new Expression\VariableNode(substr($this->semStack[$pos], 1), $this->startTokenStack[$pos]->position),
			29, 39, 44, 74, 82, 83, 84, 143, 144, 164, 165, 183, 210, 217, 243, 246, 278 => fn() => $this->semValue = $this->semStack[$pos - 1],
			30, 38, 45, 66, 81, 163, 186 => fn() => $this->semValue = [],
			31, 40, 46, 68, 76, 166, 251, 266 => fn() => $this->semValue = [$this->semStack[$pos]],
			32, 41, 47, 59, 61, 69, 75, 167, 250 => function () use ($pos) {
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
			62, 64, 207, 253 => fn() => $this->semValue = null,
			67 => fn() => $this->semValue = $this->semStack[$pos - 2],
			70 => fn() => $this->semValue = new Node\ArgumentNode($this->semStack[$pos], false, false, null, $this->startTokenStack[$pos]->position),
			71 => fn() => $this->semValue = new Node\ArgumentNode($this->semStack[$pos], true, false, null, $this->startTokenStack[$pos - 1]->position),
			72 => fn() => $this->semValue = new Node\ArgumentNode($this->semStack[$pos], false, true, null, $this->startTokenStack[$pos - 1]->position),
			73 => fn() => $this->semValue = new Node\ArgumentNode($this->semStack[$pos], false, false, $this->semStack[$pos - 2], $this->startTokenStack[$pos - 2]->position),
			77, 78 => fn() => $this->semValue = new Expression\FilterCallNode($this->semStack[$pos - 3], new Node\FilterNode($this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position), $this->startTokenStack[$pos - 3]->position),
			79 => fn() => $this->semValue = [new Node\FilterNode($this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position)],
			80 => function () use ($pos) {
				$this->semStack[$pos - 3][] = new Node\FilterNode($this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position);
				$this->semValue = $this->semStack[$pos - 3];
			},
			86, 87, 88 => fn() => $this->semValue = new Expression\AssignNode($this->semStack[$pos - 2], $this->semStack[$pos], false, $this->startTokenStack[$pos - 2]->position),
			89 => fn() => $this->semValue = new Expression\AssignNode($this->semStack[$pos - 3], $this->semStack[$pos], true, $this->startTokenStack[$pos - 3]->position),
			92 => fn() => $this->semValue = new Expression\CloneNode($this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			93 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '+', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			94 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '-', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			95 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '*', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			96 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '/', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			97 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '.', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			98 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '%', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			99 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '&', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			100 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '|', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			101 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '^', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			102 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '<<', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			103 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '>>', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			104 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '**', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			105 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '??', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			106 => fn() => $this->semValue = new Expression\PostOpNode($this->semStack[$pos - 1], '++', $this->startTokenStack[$pos - 1]->position),
			107 => fn() => $this->semValue = new Expression\PreOpNode($this->semStack[$pos], '++', $this->startTokenStack[$pos - 1]->position),
			108 => fn() => $this->semValue = new Expression\PostOpNode($this->semStack[$pos - 1], '--', $this->startTokenStack[$pos - 1]->position),
			109 => fn() => $this->semValue = new Expression\PreOpNode($this->semStack[$pos], '--', $this->startTokenStack[$pos - 1]->position),
			110 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '||', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			111 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '&&', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			112 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], 'or', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			113 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], 'and', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			114 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], 'xor', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			115, 116 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '&', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			117 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '^', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			118 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '.', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			119 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '+', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			120 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '-', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			121 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '*', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			122 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '/', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			123 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '%', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			124 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '<<', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			125 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '>>', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			126 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '**', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			127 => fn() => $this->semValue = new Expression\InRangeNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			128 => fn() => $this->semValue = new Expression\UnaryOpNode($this->semStack[$pos], '+', $this->startTokenStack[$pos - 1]->position),
			129 => fn() => $this->semValue = new Expression\UnaryOpNode($this->semStack[$pos], '-', $this->startTokenStack[$pos - 1]->position),
			130, 131 => fn() => $this->semValue = new Expression\NotNode($this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			132 => fn() => $this->semValue = new Expression\UnaryOpNode($this->semStack[$pos], '~', $this->startTokenStack[$pos - 1]->position),
			133 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '===', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			134 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '!==', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			135 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '==', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			136 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '!=', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			137 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '<=>', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			138 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '<', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			139 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '<=', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			140 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '>', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			141 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '>=', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			142 => fn() => $this->semValue = new Expression\InstanceofNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			145 => fn() => $this->semValue = new Expression\TernaryNode($this->semStack[$pos - 4], $this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 4]->position),
			146 => fn() => $this->semValue = new Expression\TernaryNode($this->semStack[$pos - 3], null, $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position),
			147 => fn() => $this->semValue = new Expression\TernaryNode($this->semStack[$pos - 2], $this->semStack[$pos], null, $this->startTokenStack[$pos - 2]->position),
			148 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '??', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			149 => fn() => $this->semValue = new Expression\IssetNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			150 => fn() => $this->semValue = new Expression\EmptyNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			151 => fn() => $this->semValue = new Expression\CastNode('int', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			152 => fn() => $this->semValue = new Expression\CastNode('float', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			153 => fn() => $this->semValue = new Expression\CastNode('string', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			154 => fn() => $this->semValue = new Expression\CastNode('array', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			155 => fn() => $this->semValue = new Expression\CastNode('object', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			156 => fn() => $this->semValue = new Expression\CastNode('bool', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			157 => fn() => $this->semValue = new Expression\ErrorSuppressNode($this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			159 => fn() => $this->semValue = new Expression\ClosureNode((bool) $this->semStack[$pos - 6], $this->semStack[$pos - 4], [], $this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 7]->position),
			160 => fn() => $this->semValue = new Expression\ClosureNode((bool) $this->semStack[$pos - 10], $this->semStack[$pos - 8], $this->semStack[$pos - 6], $this->semStack[$pos - 5], $this->semStack[$pos - 2], $this->startTokenStack[$pos - 11]->position),
			161 => fn() => $this->semValue = new Expression\ClosureNode((bool) $this->semStack[$pos - 7], $this->semStack[$pos - 5], $this->semStack[$pos - 3], $this->semStack[$pos - 2], null, $this->startTokenStack[$pos - 8]->position),
			162 => fn() => $this->semValue = new Expression\NewNode($this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			168 => fn() => $this->semValue = new Expression\ClosureUseNode($this->semStack[$pos], $this->semStack[$pos - 1], $this->startTokenStack[$pos - 1]->position),
			169, 171 => fn() => $this->semValue = new Expression\FunctionCallNode($this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			170, 172 => fn() => $this->semValue = new Expression\FunctionCallableNode($this->semStack[$pos - 3], $this->startTokenStack[$pos - 3]->position),
			173 => fn() => $this->semValue = new Expression\StaticCallNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position),
			174 => fn() => $this->semValue = new Expression\StaticCallableNode($this->semStack[$pos - 5], $this->semStack[$pos - 3], $this->startTokenStack[$pos - 5]->position),
			176, 178, 179 => fn() => $this->semValue = new Node\NameNode($this->semStack[$pos], Node\NameNode::KindNormal, $this->startTokenStack[$pos]->position),
			180 => fn() => $this->semValue = new Node\NameNode($this->semStack[$pos], Node\NameNode::KindFullyQualified, $this->startTokenStack[$pos]->position),
			188 => fn() => $this->semValue = new Expression\ConstantFetchNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			189 => fn() => $this->semValue = new Expression\ClassConstantFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			190 => fn() => $this->semValue = new Expression\ArrayNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 2]->position),
			191, 248 => fn() => $this->semValue = new Expression\ArrayNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			193 => fn() => $this->semValue = Scalar\StringNode::parse($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			194 => fn() => $this->semValue = Scalar\EncapsedStringNode::parse($this->semStack[$pos - 1], $this->startTokenStack[$pos - 2]->position),
			195 => fn() => $this->semValue = Scalar\IntegerNode::parse($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			196 => fn() => $this->semValue = Scalar\FloatNode::parse($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			198, 279 => fn() => $this->semValue = new Scalar\StringNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			199 => fn() => $this->semValue = new Scalar\BooleanNode(true, $this->startTokenStack[$pos]->position),
			200 => fn() => $this->semValue = new Scalar\BooleanNode(false, $this->startTokenStack[$pos]->position),
			201 => fn() => $this->semValue = new Scalar\NullNode($this->startTokenStack[$pos]->position),
			204 => fn() => $this->semValue = $this->parseDocString($this->semStack[$pos - 2], [$this->semStack[$pos - 1]], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position, $this->startTokenStack[$pos]->position),
			205 => fn() => $this->semValue = $this->parseDocString($this->semStack[$pos - 1], [], $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position, $this->startTokenStack[$pos]->position),
			206 => fn() => $this->semValue = $this->parseDocString($this->semStack[$pos - 2], $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position, $this->startTokenStack[$pos]->position),
			215 => fn() => $this->semValue = new Expression\ConstantFetchNode(new Node\NameNode($this->semStack[$pos], Node\NameNode::KindNormal, $this->startTokenStack[$pos]->position), $this->startTokenStack[$pos]->position),
			220, 236, 271 => fn() => $this->semValue = new Expression\ArrayAccessNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			222 => fn() => $this->semValue = new Expression\MethodCallNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->semStack[$pos], false, $this->startTokenStack[$pos - 3]->position),
			223 => fn() => $this->semValue = new Expression\MethodCallableNode($this->semStack[$pos - 5], $this->semStack[$pos - 3], $this->startTokenStack[$pos - 5]->position),
			224 => fn() => $this->semValue = new Expression\MethodCallNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->semStack[$pos], true, $this->startTokenStack[$pos - 3]->position),
			225 => fn() => $this->semValue = new Expression\MethodCallNode(new Expression\BinaryOpNode($this->semStack[$pos - 3], '??', new Scalar\NullNode($this->startTokenStack[$pos - 3]->position), $this->startTokenStack[$pos - 3]->position), $this->semStack[$pos - 1], $this->semStack[$pos], true, $this->startTokenStack[$pos - 3]->position),
			228, 237, 272 => fn() => $this->semValue = new Expression\PropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], false, $this->startTokenStack[$pos - 2]->position),
			229, 238, 273 => fn() => $this->semValue = new Expression\PropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], true, $this->startTokenStack[$pos - 2]->position),
			230, 239, 274 => fn() => $this->semValue = new Expression\PropertyFetchNode(new Expression\BinaryOpNode($this->semStack[$pos - 2], '??', new Scalar\NullNode($this->startTokenStack[$pos - 2]->position), $this->startTokenStack[$pos - 2]->position), $this->semStack[$pos], true, $this->startTokenStack[$pos - 2]->position),
			232 => fn() => $this->semValue = new Expression\VariableNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			233 => function () use ($pos) {
				$var = $this->semStack[$pos]->name;
				$this->semValue = \is_string($var)
					? new Node\VarLikeIdentifierNode($var, $this->startTokenStack[$pos]->position)
					: $var;
			},
			234, 240, 241 => fn() => $this->semValue = new Expression\StaticPropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			249 => function () use ($pos) {
				$this->semValue = $this->semStack[$pos];
				$end = count($this->semValue) - 1;
				if ($this->semValue[$end] === null) {
					array_pop($this->semValue);
				}
			},
			254, 256 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], null, false, false, $this->startTokenStack[$pos]->position),
			255 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], null, true, false, $this->startTokenStack[$pos - 1]->position),
			257, 259, 260 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], $this->semStack[$pos - 2], false, false, $this->startTokenStack[$pos - 2]->position),
			258, 261 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], $this->semStack[$pos - 3], true, false, $this->startTokenStack[$pos - 3]->position),
			262, 263 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], null, false, true, $this->startTokenStack[$pos - 1]->position),
			264, 265 => function () use ($pos) {
				$this->semStack[$pos - 1][] = $this->semStack[$pos];
				$this->semValue = $this->semStack[$pos - 1];
			},
			267 => fn() => $this->semValue = [$this->semStack[$pos - 1], $this->semStack[$pos]],
			268 => fn() => $this->semValue = new Scalar\EncapsedStringPartNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			269 => fn() => $this->semValue = new Expression\VariableNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			275, 276 => fn() => $this->semValue = new Expression\VariableNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 2]->position),
			277 => fn() => $this->semValue = new Expression\ArrayAccessNode($this->semStack[$pos - 4], $this->semStack[$pos - 2], $this->startTokenStack[$pos - 5]->position),
			280 => fn() => $this->semValue = TagParser::parseOffset($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			281 => fn() => $this->semValue = TagParser::parseOffset('-' . $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
		})();
	}
}
