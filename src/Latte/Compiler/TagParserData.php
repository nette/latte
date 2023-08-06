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
	protected const NumNonLeafStates = 353;

	/** Map of lexer tokens to internal symbols */
	protected const TokenToSymbol = [
		0,     112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,
		112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   48,    107,   112,   108,   47,    112,   112,
		101,   102,   45,    43,    2,     44,    39,    46,    112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   22,    105,
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
		31,    32,    33,    34,    36,    38,    40,    41,    42,    49,    51,    52,    53,    54,    55,    56,    57,    58,    60,    62,
		63,    64,    65,    66,    67,    68,    69,    70,    71,    72,    73,    74,    75,    76,    77,    78,    79,    80,    81,    82,
		83,    84,    85,    86,    87,    88,    89,    90,    109,   91,    92,    93,    94,    110,   111,   95,    96,    97,
	];

	/** Map of states to a displacement into the self::Action table. The corresponding action for this
	 *  state/symbol pair is self::Action[self::ActionBase[$state] + $symbol]. If self::ActionBase[$state] is 0, the
	 *  action is defaulted, i.e. self::ActionDefault[$state] should be used instead. */
	protected const ActionBase = [
		261,   304,   304,   304,   304,   99,    140,   304,   263,   181,   222,   304,   384,   384,   384,   384,   384,   159,   159,   159,
		159,   248,   248,   237,   241,   372,   374,   376,   379,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,
		-43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,
		-43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,
		-43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,
		-43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   21,    216,   223,   382,   399,   398,   404,   440,   446,   435,
		452,   459,   52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    167,   177,   538,   234,
		234,   234,   234,   234,   234,   234,   234,   234,   234,   234,   234,   234,   234,   234,   234,   234,   234,   543,   543,   543,
		370,   499,   494,   390,   5,     411,   411,   462,   462,   462,   462,   462,   58,    58,    58,    58,    156,   156,   156,   156,
		45,    45,    45,    45,    45,    45,    45,    45,    236,   3,     3,     7,     219,   250,   250,   250,   139,   139,   139,   139,
		139,   267,   111,   111,   111,   192,   233,   387,   257,   -64,   -64,   -64,   -64,   -64,   -64,   268,   460,   -12,   444,   444,
		447,   168,   168,   444,   469,   76,    286,   -15,   68,    471,   443,   453,   429,   318,   380,   226,   230,   232,   38,    38,
		38,    38,    159,   451,   451,   159,   159,   159,   98,    98,    98,    -84,   196,   -63,   8,     405,   196,   196,   196,   90,
		57,    -32,   316,   244,   291,   310,   33,    117,   46,    317,   320,   316,   316,   120,   46,    46,    239,   278,   247,   197,
		113,   247,   217,   217,   123,   31,    322,   321,   324,   289,   285,   407,   176,   221,   273,   272,   284,   253,   224,   322,
		321,   324,   240,   227,   227,   227,   270,   227,   227,   227,   227,   227,   227,   227,   176,   445,   1,     274,   326,   327,
		342,   347,   315,   220,   409,   227,   243,   210,   205,   455,   275,   176,   456,   406,   225,   254,   213,   335,   251,   457,
		352,   209,   410,   194,   329,   198,   412,   215,   408,   228,   354,   428,   432,   0,     -43,   -43,   -43,   -43,   -43,   -43,
		-43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,
		-43,   -43,   0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,
		52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    0,     58,    52,    52,
		52,    52,    52,    52,    52,    0,     0,     0,     0,     111,   111,   111,   111,   139,   139,   139,   139,   139,   139,   139,
		139,   111,   139,   139,   139,   139,   139,   139,   139,   0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     469,   217,   217,   217,   217,   217,   217,   469,   469,   0,     0,     0,     0,     111,   111,   0,     0,     469,   217,
		0,     0,     0,     0,     0,     0,     0,     159,   159,   159,   469,   0,     0,     0,     0,     0,     217,   217,   0,     0,
		0,     0,     0,     0,     0,     227,   0,     0,     1,     227,   227,   227,
	];

	/** Table of actions. Indexed according to self::ActionBase comment. */
	protected const Action = [
		35,    36,    377,   7,     378,   37,    -176,  38,    180,   181,   39,    40,    41,    42,    43,    44,    45,    -176,  1,     193,
		46,    548,   549,   205,   196,   530,   379,   -175,  196,   546,   286,   0,     245,   246,   175,   11,    287,   288,   -175,  100,
		12,    289,   290,   208,   291,   33,    -214,  174,   15,    -212,  532,   531,   554,   552,   553,   55,    56,    57,    30,    16,
		13,    360,   361,   359,   224,   291,   -214,  -214,  -214,  -212,  -212,  -212,  513,   24,    379,   58,    59,    60,    -212,  61,
		62,    63,    64,    65,    66,    67,    68,    69,    70,    71,    72,    73,    74,    75,    76,    77,    78,    79,    80,    81,
		370,   195,   360,   361,   359,   -45,   1,     364,   99,    -28,   496,   253,   82,    291,   379,   22,    197,   546,   198,   102,
		363,   362,   191,   207,   374,   234,   375,   358,   357,   31,    290,   367,   366,   369,   368,   365,   371,   372,   373,   14,
		-8190, 370,   -8190, 360,   361,   359,   50,    -8190, 364,   209,   210,   211,   224,   291,   77,    78,    79,    80,    81,    437,
		195,   194,   47,    557,   377,   201,   378,   298,   358,   357,   -8190, 82,    299,   366,   235,   236,   365,   371,   300,   301,
		-8190, -8190, 370,   96,    360,   361,   359,   358,   357,   364,   25,    -8191, -8191, -8191, -8191, 73,    74,    75,    76,    381,
		106,   419,   194,   47,    107,   528,   201,   530,   298,   358,   357,   -8190, -8190, 299,   366,   235,   236,   365,   371,   300,
		301,   108,   18,    370,   408,   360,   361,   359,   97,    20,    364,   26,    532,   531,   410,   109,   409,   -8190, -8190, -8190,
		196,   200,   419,   194,   47,    195,   -22,   201,   -16,   298,   358,   357,   -15,   -215,  299,   366,   235,   236,   365,   371,
		300,   301,   19,    98,    370,   105,   360,   361,   359,   192,   172,   364,   27,    -215,  -215,  -215,  74,    75,    76,    173,
		199,   103,   -175,  419,   194,   47,    379,   82,    201,   -21,   298,   358,   357,   -175,  -211,  299,   366,   235,   236,   365,
		371,   300,   301,   -8190, 322,   370,   -244,  360,   361,   359,   -243,  -242,  364,   28,    -211,  -211,  -211,  344,   -261,  379,
		-261,  634,   279,   -211,  419,   194,   47,    276,   -215,  201,   338,   298,   358,   357,   -218,  -8190, 299,   366,   235,   236,
		365,   371,   300,   301,   558,   -8190, 370,   -8190, -215,  -215,  -215,  51,    632,   364,   101,   559,   633,   -175,  291,   34,
		263,   3,     -184,  164,   243,   419,   194,   47,    -175,  389,   201,   -218,  298,   -8190, -8190, -8190, -217,  299,   366,   235,
		236,   365,   371,   300,   301,   -216,  2,     360,   361,   359,   4,     -8190, 5,     -8190, -8190, 48,    49,    17,    83,    84,
		85,    86,    87,    88,    89,    90,    91,    92,    93,    94,    95,    6,     358,   357,   -8190, -8190, -8190, 8,     9,     629,
		-261,  10,    29,    52,    -261,  53,    370,   189,   190,   -259,  242,   -259,  -8190, 364,   -8190, -8190, -8190, 278,   -8190, -8190,
		-8190, 459,   461,   -257,  501,   -257,  194,   47,    -210,  544,   201,   -209,  298,   -71,   420,   -71,   516,   299,   366,   235,
		236,   365,   371,   300,   301,   -8190, -8190, -8190, -210,  -210,  -210,  -209,  -209,  -209,  522,   101,   524,   -210,  526,   -71,
		-209,  576,   -28,   -8190, 330,   -8190, -8190, -8190, -217,  -8190, -8190, -8190, -8191, -8191, -8191, -8191, -8191, -8190, -8190, -8190,
		333,   535,   -8190, -8190, -8190, -209,  502,   601,   32,    21,    54,    261,   0,     631,   390,   -8190, 630,   -8190, -8190, -8190,
		-8190, -8190, -8190, -8190, -8190, -209,  -209,  -209,  212,   213,   214,   -259,  226,   352,   -209,  -259,  512,   244,   379,   341,
		621,   -8190, -8190, -8190, 628,   -257,  -8190, -8190, -8190, -257,  585,   543,   248,   249,   250,   -71,   599,   23,    183,   291,
		104,   573,   589,   624,   -8190, 346,   0,     547,
	];

	/** Table indexed analogously to self::Action. If self::ActionCheck[self::ActionBase[$state] + $symbol] != $symbol
	 *  then the action is defaulted, i.e. self::ActionDefault[$state] should be used instead. */
	protected const ActionCheck = [
		43,    44,    66,    2,     68,    48,    90,    50,    51,    52,    53,    54,    55,    56,    57,    58,    59,    101,   61,    62,
		63,    64,    65,    66,    21,    68,    69,    90,    21,    72,    73,    0,     75,    76,    26,    2,     79,    80,    101,   103,
		2,     84,    85,    86,    108,   77,    61,    26,    2,     61,    93,    94,    95,    96,    97,    3,     4,     5,     101,   2,
		22,    3,     4,     5,     107,   108,   81,    82,    83,    81,    82,    83,    104,   21,    69,    23,    24,    25,    90,    27,
		28,    29,    30,    31,    32,    33,    34,    35,    36,    37,    38,    39,    40,    41,    42,    43,    44,    45,    46,    47,
		42,    49,    3,     4,     5,     102,   61,    49,    103,   102,   102,   66,    60,    108,   69,    2,     26,    72,    28,    2,
		62,    63,    2,     102,   66,    2,     68,    28,    29,    61,    85,    73,    74,    75,    76,    77,    78,    79,    80,    101,
		95,    42,    97,    3,     4,     5,     101,   71,    49,    81,    82,    83,    107,   108,   43,    44,    45,    46,    47,    102,
		49,    62,    63,    87,    66,    66,    68,    68,    28,    29,    3,     60,    73,    74,    75,    76,    77,    78,    79,    80,
		3,     4,     42,    7,     3,     4,     5,     28,    29,    49,    91,    35,    36,    37,    38,    39,    40,    41,    42,    2,
		6,     102,   62,    63,    6,     66,    66,    68,    68,    28,    29,    43,    44,    73,    74,    75,    76,    77,    78,    79,
		80,    6,     6,     42,    85,    3,     4,     5,     7,     6,     49,    91,    93,    94,    95,    7,     97,    3,     4,     5,
		21,    28,    102,   62,    63,    49,    22,    66,    22,    68,    28,    29,    22,    61,    73,    74,    75,    76,    77,    78,
		79,    80,    22,    22,    42,    22,    3,     4,     5,     22,    26,    49,    91,    81,    82,    83,    40,    41,    42,    26,
		26,    61,    90,    102,   62,    63,    69,    60,    66,    22,    68,    28,    29,    101,   61,    73,    74,    75,    76,    77,
		78,    79,    80,    71,    67,    42,    101,   3,     4,     5,     101,   101,   49,    91,    81,    82,    83,    78,    0,     69,
		2,     70,    74,    90,    102,   62,    63,    102,   61,    66,    44,    68,    28,    29,    101,   85,    73,    74,    75,    76,
		77,    78,    79,    80,    87,    95,    42,    97,    81,    82,    83,    101,   66,    49,    91,    87,    70,    90,    108,   98,
		99,    100,   90,    90,    90,    102,   62,    63,    101,   91,    66,    101,   68,    3,     4,     5,     101,   73,    74,    75,
		76,    77,    78,    79,    80,    101,   101,   3,     4,     5,     101,   21,    101,   23,    24,    91,    92,    7,     8,     9,
		10,    11,    12,    13,    14,    15,    16,    17,    18,    19,    20,    101,   28,    29,    3,     4,     5,     101,   101,   104,
		102,   101,   101,   101,   106,   101,   42,    101,   101,   0,     101,   2,     21,    49,    23,    24,    25,    102,   27,    28,
		29,    51,    52,    0,     102,   2,     62,    63,    61,    102,   66,    61,    68,    0,     102,   2,     102,   73,    74,    75,
		76,    77,    78,    79,    80,    3,     4,     5,     81,    82,    83,    81,    82,    83,    102,   91,    102,   90,    102,   26,
		90,    102,   102,   21,    102,   23,    24,    25,    101,   27,    28,    29,    30,    31,    32,    33,    34,    3,     4,     5,
		102,   102,   3,     4,     5,     61,    102,   102,   61,    103,   103,   103,   -1,    104,   104,   21,    104,   23,    24,    25,
		21,    27,    23,    24,    25,    81,    82,    83,    81,    82,    83,    102,   61,    105,   90,    106,   104,   90,    69,    104,
		71,    3,     4,     5,     104,   102,   3,     4,     5,     106,   104,   106,   81,    82,    83,    102,   104,   88,    89,    108,
		22,    106,   106,   106,   21,    106,   -1,    107,
	];

	/** Map of states to their default action */
	protected const ActionDefault = [
		8191,  255,   255,   30,    255,   8191,  8191,  255,   8191,  8191,  8191,  28,    8191,  8191,  8191,  28,    8191,  8191,  8191,  8191,
		8191,  38,    28,    8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  207,   207,   207,   8191,  8191,  8191,  8191,  8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  9,     8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,
		8191,  8191,  28,    8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  256,   256,   8191,  8191,  8191,  8191,  8191,  8191,  8191,
		8191,  8191,  1,     262,   263,   76,    70,    208,   258,   260,   72,    75,    73,    42,    43,    49,    112,   114,   146,   113,
		88,    93,    94,    95,    96,    97,    98,    99,    100,   101,   102,   103,   104,   105,   86,    87,    158,   147,   145,   144,
		110,   111,   117,   85,    8191,  115,   116,   134,   135,   132,   133,   136,   8191,  8191,  8191,  8191,  137,   138,   139,   140,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  118,   62,    62,    62,    8191,  8191,  10,    8191,  8191,  8191,  8191,  8191,
		8191,  198,   124,   125,   127,   198,   197,   142,   8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  203,   107,   109,
		181,   119,   120,   89,    8191,  8191,  8191,  202,   8191,  270,   209,   209,   209,   209,   33,    33,    33,    8191,  81,    81,
		81,    81,    33,    8191,  8191,  33,    33,    33,    8191,  8191,  8191,  187,   130,   215,   8191,  8191,  121,   122,   123,   50,
		8191,  8191,  185,   8191,  174,   8191,  27,    27,    27,    8191,  228,   229,   230,   27,    27,    27,    162,   35,    64,    27,
		27,    64,    8191,  8191,  27,    8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  192,   8191,  213,   226,   2,     177,   14,
		19,    20,    8191,  128,   129,   131,   211,   150,   151,   152,   153,   154,   155,   156,   253,   8191,  249,   180,   8191,  8191,
		8191,  8191,  269,   8191,  209,   126,   8191,  188,   233,   8191,  210,   254,   8191,  8191,  8191,  52,    53,    8191,  8191,  8191,
		8191,  189,   8191,  8191,  8191,  8191,  8191,  8191,  8191,  48,    8191,  8191,  8191,
	];

	/** Map of non-terminals to a displacement into the self::Goto table. The corresponding goto state for this
	 *  non-terminal/state pair is self::Goto[self::GotoBase[$nonTerminal] + $state] (unless defaulted) */
	protected const GotoBase = [
		0,     0,     -1,    0,     0,     108,   0,     163,   16,    -40,   -85,   0,     251,   13,    0,     0,     0,     0,     147,   158,
		1,     0,     5,     0,     6,     -76,   0,     0,     -60,   -26,   -257,  104,   -11,   20,    0,     0,     12,    243,   28,    0,
		46,    0,     0,     235,   0,     0,     0,     19,    0,     0,     0,     0,     -22,   -44,   0,     0,     36,    44,    7,     52,
		22,    -67,   0,     0,     -51,   -33,   0,     27,    110,   4,     32,    0,     0,
	];

	/** Table of states to goto after reduction. Indexed according to self::GotoBase comment. */
	protected const Goto = [
		112,   112,   111,   112,   422,   422,   112,   521,   523,   321,   111,   603,   525,   575,   577,   578,   140,   128,   129,   128,
		125,   125,   117,   138,   130,   130,   130,   130,   125,   110,   127,   127,   127,   122,   303,   304,   252,   305,   307,   308,
		309,   310,   311,   312,   313,   445,   445,   123,   124,   113,   114,   115,   116,   118,   136,   137,   139,   157,   160,   161,
		162,   165,   166,   167,   168,   169,   170,   171,   176,   177,   178,   179,   188,   202,   203,   204,   221,   222,   256,   257,
		258,   325,   141,   142,   143,   144,   145,   146,   147,   148,   149,   150,   151,   152,   153,   154,   155,   158,   119,   120,
		130,   131,   121,   159,   132,   133,   156,   134,   135,   182,   182,   182,   182,   328,   255,   182,   274,   275,   260,   182,
		404,   411,   413,   412,   414,   185,   186,   187,   186,   407,   407,   407,   407,   527,   527,   527,   407,   407,   407,   407,
		407,   225,   588,   588,   588,   529,   529,   529,   529,   529,   529,   529,   529,   529,   529,   529,   529,   237,   600,   600,
		600,   600,   600,   600,   302,   302,   302,   302,   229,   394,   302,   317,   317,   317,   302,   229,   229,   271,   272,   590,
		591,   592,   427,   340,   229,   229,   635,   320,   397,   436,   435,   337,   586,   586,   347,   392,   400,   229,   418,   517,
		218,   219,   230,   324,   231,   223,   232,   233,   545,   545,   545,   545,   545,   545,   545,   545,   567,   567,   567,   567,
		567,   567,   567,   567,   565,   565,   565,   565,   565,   565,   565,   565,   306,   306,   306,   306,   306,   306,   306,   306,
		619,   494,   349,   520,   314,   314,   216,   314,   318,   319,   314,   433,   430,   431,   384,   348,   619,   620,   318,   319,
		277,   518,   385,   331,   625,   626,   627,   332,   351,   620,   593,   594,   0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     327,   0,     0,     0,     0,     0,     0,     0,     238,   239,   240,   241,   0,
		0,     0,     387,   387,   387,   0,     0,     0,     0,     0,     387,   0,     0,     387,   387,   387,
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
		25,    25,    25,    25,    25,    5,     5,     5,     5,     28,    28,    28,    28,    28,    28,    28,    28,    28,    28,    28,
		28,    61,    64,    64,    64,    53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    61,    64,    64,
		64,    64,    64,    64,    7,     7,     7,     7,     9,     18,    7,     52,    52,    52,    7,     9,     9,     65,    65,    65,
		65,    65,    10,    10,    9,     9,     9,     19,    10,    10,    10,    20,    64,    64,    10,    10,    22,    9,     24,    10,
		33,    33,    33,    33,    33,    33,    33,    33,    38,    38,    38,    38,    38,    38,    38,    38,    56,    56,    56,    56,
		56,    56,    56,    56,    57,    57,    57,    57,    57,    57,    57,    57,    59,    59,    59,    59,    59,    59,    59,    59,
		70,    40,    9,     9,     37,    37,    60,    37,    13,    13,    37,    36,    36,    36,    12,    29,    70,    70,    13,    13,
		13,    47,    12,    37,    8,     8,     8,     43,    43,    70,    67,    67,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
		-1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
		-1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
		-1,    -1,    -1,    -1,    -1,    -1,    -1,    7,     -1,    -1,    -1,    -1,    -1,    -1,    -1,    7,     7,     7,     7,     -1,
		-1,    -1,    5,     5,     5,     -1,    -1,    -1,    -1,    -1,    5,     -1,    -1,    5,     5,     5,
	];

	/** Map of non-terminals to the default state to goto after their reduction */
	protected const GotoDefault = [
		-8192, 285,   126,   297,   356,   184,   376,   326,   598,   584,   382,   266,   605,   283,   282,   444,   342,   280,   393,   343,
		334,   273,   399,   247,   416,   259,   335,   336,   264,   345,   539,   268,   421,   163,   267,   254,   432,   292,   293,   443,
		262,   510,   281,   329,   514,   350,   284,   519,   574,   265,   294,   269,   536,   251,   220,   295,   227,   217,   315,   206,
		215,   618,   228,   296,   572,   270,   580,   587,   316,   604,   617,   323,   339,
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
		2,     39,    42,    42,    45,    46,    46,    47,    48,    48,    48,    48,    48,    48,    52,    28,    28,    53,    53,    53,
		40,    40,    40,    50,    50,    44,    44,    56,    57,    57,    38,    59,    59,    59,    59,    41,    41,    41,    41,    41,
		41,    41,    41,    41,    41,    41,    41,    43,    43,    55,    55,    55,    55,    62,    62,    62,    49,    49,    49,    63,
		63,    63,    63,    63,    63,    63,    33,    33,    33,    33,    33,    64,    64,    67,    66,    54,    54,    54,    54,    54,
		54,    54,    51,    51,    51,    65,    65,    65,    37,    58,    68,    68,    69,    69,    69,    69,    12,    12,    12,    12,
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
		3,     3,     3,     3,     3,     3,     3,     3,     2,     2,     2,     2,     3,     3,     3,     3,     3,     3,     3,     3,
		3,     3,     3,     3,     5,     4,     3,     3,     4,     4,     2,     2,     2,     2,     2,     2,     2,     1,     8,     12,
		9,     3,     0,     4,     2,     1,     3,     2,     2,     4,     2,     4,     4,     6,     1,     1,     1,     1,     1,     1,
		1,     1,     3,     1,     1,     0,     1,     1,     3,     5,     3,     4,     1,     1,     3,     1,     1,     1,     1,     1,
		1,     1,     1,     1,     3,     2,     3,     0,     1,     1,     3,     1,     1,     1,     1,     1,     1,     3,     1,     1,
		4,     1,     4,     6,     4,     4,     1,     1,     3,     3,     3,     1,     4,     1,     3,     1,     4,     3,     3,     3,
		3,     3,     1,     3,     1,     1,     3,     1,     4,     1,     3,     1,     1,     1,     3,     0,     1,     2,     3,     4,
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
		"'=='",
		"'!='",
		"'==='",
		"'!=='",
		"'<=>'",
		"'<'",
		"'<='",
		"'>'",
		"'>='",
		"'.'",
		"'<<'",
		"'>>'",
		"'in'",
		"'+'",
		"'-'",
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
			0, 1, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 27, 28, 50, 63, 65, 85, 90, 91, 157, 174, 176, 180, 181, 183, 184, 186, 192, 197, 202, 203, 208, 209, 211, 212, 213, 214, 216, 218, 219, 221, 226, 227, 231, 235, 242, 244, 245, 247, 252, 270, 282 => fn() => $this->semValue = $this->semStack[$pos],
			2 => fn() => $this->semValue = new Node\ModifierNode($this->semStack[$pos], position: $this->startTokenStack[$pos]->position),
			3 => fn() => $this->semValue = new Expression\ArrayNode($this->semStack[$pos], position: $this->startTokenStack[$pos]->position),
			21, 22, 23, 24, 25, 55, 56, 57 => fn() => $this->semValue = new Node\IdentifierNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			26 => fn() => $this->semValue = new Expression\VariableNode(substr($this->semStack[$pos], 1), $this->startTokenStack[$pos]->position),
			29, 39, 44, 74, 82, 83, 84, 142, 143, 163, 164, 182, 210, 217, 243, 246, 278 => fn() => $this->semValue = $this->semStack[$pos - 1],
			30, 38, 45, 66, 81, 162, 185 => fn() => $this->semValue = [],
			31, 40, 46, 68, 76, 165, 251, 266 => fn() => $this->semValue = [$this->semStack[$pos]],
			32, 41, 47, 59, 61, 69, 75, 166, 250 => function () use ($pos) {
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
			62, 64, 207, 255 => fn() => $this->semValue = null,
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
			127 => fn() => $this->semValue = new Expression\InNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			128 => fn() => $this->semValue = new Expression\UnaryOpNode($this->semStack[$pos], '+', $this->startTokenStack[$pos - 1]->position),
			129 => fn() => $this->semValue = new Expression\UnaryOpNode($this->semStack[$pos], '-', $this->startTokenStack[$pos - 1]->position),
			130 => fn() => $this->semValue = new Expression\NotNode($this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			131 => fn() => $this->semValue = new Expression\UnaryOpNode($this->semStack[$pos], '~', $this->startTokenStack[$pos - 1]->position),
			132 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '===', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			133 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '!==', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			134 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '==', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			135 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '!=', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			136 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '<=>', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			137 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '<', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			138 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '<=', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			139 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '>', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			140 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '>=', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			141 => fn() => $this->semValue = new Expression\InstanceofNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			144 => fn() => $this->semValue = new Expression\TernaryNode($this->semStack[$pos - 4], $this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 4]->position),
			145 => fn() => $this->semValue = new Expression\TernaryNode($this->semStack[$pos - 3], null, $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position),
			146 => fn() => $this->semValue = new Expression\TernaryNode($this->semStack[$pos - 2], $this->semStack[$pos], null, $this->startTokenStack[$pos - 2]->position),
			147 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '??', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			148 => fn() => $this->semValue = new Expression\IssetNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			149 => fn() => $this->semValue = new Expression\EmptyNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			150 => fn() => $this->semValue = new Expression\CastNode('int', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			151 => fn() => $this->semValue = new Expression\CastNode('float', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			152 => fn() => $this->semValue = new Expression\CastNode('string', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			153 => fn() => $this->semValue = new Expression\CastNode('array', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			154 => fn() => $this->semValue = new Expression\CastNode('object', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			155 => fn() => $this->semValue = new Expression\CastNode('bool', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			156 => fn() => $this->semValue = new Expression\ErrorSuppressNode($this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			158 => fn() => $this->semValue = new Expression\ClosureNode((bool) $this->semStack[$pos - 6], $this->semStack[$pos - 4], [], $this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 7]->position),
			159 => fn() => $this->semValue = new Expression\ClosureNode((bool) $this->semStack[$pos - 10], $this->semStack[$pos - 8], $this->semStack[$pos - 6], $this->semStack[$pos - 5], $this->semStack[$pos - 2], $this->startTokenStack[$pos - 11]->position),
			160 => fn() => $this->semValue = new Expression\ClosureNode((bool) $this->semStack[$pos - 7], $this->semStack[$pos - 5], $this->semStack[$pos - 3], $this->semStack[$pos - 2], null, $this->startTokenStack[$pos - 8]->position),
			161 => fn() => $this->semValue = new Expression\NewNode($this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			167 => fn() => $this->semValue = new Node\ClosureUseNode($this->semStack[$pos], $this->semStack[$pos - 1], $this->startTokenStack[$pos - 1]->position),
			168, 170 => fn() => $this->semValue = $this->checkFunctionName(new Expression\FunctionCallNode($this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position)),
			169, 171 => fn() => $this->semValue = $this->checkFunctionName(new Expression\FunctionCallableNode($this->semStack[$pos - 3], $this->startTokenStack[$pos - 3]->position)),
			172 => fn() => $this->semValue = new Expression\StaticCallNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position),
			173 => fn() => $this->semValue = new Expression\StaticCallableNode($this->semStack[$pos - 5], $this->semStack[$pos - 3], $this->startTokenStack[$pos - 5]->position),
			175, 177, 178 => fn() => $this->semValue = new Node\NameNode($this->semStack[$pos], Node\NameNode::KindNormal, $this->startTokenStack[$pos]->position),
			179 => fn() => $this->semValue = new Node\NameNode($this->semStack[$pos], Node\NameNode::KindFullyQualified, $this->startTokenStack[$pos]->position),
			187 => fn() => $this->semValue = new Expression\ConstantFetchNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			188 => fn() => $this->semValue = new Expression\ClassConstantFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			189 => fn() => $this->semValue = new Expression\ClassConstantFetchNode($this->semStack[$pos - 4], $this->semStack[$pos - 1], $this->startTokenStack[$pos - 4]->position),
			190 => fn() => $this->semValue = new Expression\ArrayNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 2]->position),
			191, 248 => fn() => $this->semValue = new Expression\ArrayNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			193 => fn() => $this->semValue = Scalar\StringNode::parse($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			194 => fn() => $this->semValue = Scalar\InterpolatedStringNode::parse($this->semStack[$pos - 1], $this->startTokenStack[$pos - 2]->position),
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
			253, 256 => fn() => $this->semValue = new Node\ArrayItemNode($this->semStack[$pos], null, false, false, $this->startTokenStack[$pos]->position),
			254, 258, 260 => fn() => $this->semValue = new Node\ArrayItemNode($this->semStack[$pos], $this->semStack[$pos - 2], false, false, $this->startTokenStack[$pos - 2]->position),
			257 => fn() => $this->semValue = new Node\ArrayItemNode($this->semStack[$pos], null, true, false, $this->startTokenStack[$pos - 1]->position),
			259, 261 => fn() => $this->semValue = new Node\ArrayItemNode($this->semStack[$pos], $this->semStack[$pos - 3], true, false, $this->startTokenStack[$pos - 3]->position),
			262, 263 => fn() => $this->semValue = new Node\ArrayItemNode($this->semStack[$pos], null, false, true, $this->startTokenStack[$pos - 1]->position),
			264, 265 => function () use ($pos) {
				$this->semStack[$pos - 1][] = $this->semStack[$pos];
				$this->semValue = $this->semStack[$pos - 1];
			},
			267 => fn() => $this->semValue = [$this->semStack[$pos - 1], $this->semStack[$pos]],
			268 => fn() => $this->semValue = new Node\InterpolatedStringPartNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			269 => fn() => $this->semValue = new Expression\VariableNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			275, 276 => fn() => $this->semValue = new Expression\VariableNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 2]->position),
			277 => fn() => $this->semValue = new Expression\ArrayAccessNode($this->semStack[$pos - 4], $this->semStack[$pos - 2], $this->startTokenStack[$pos - 5]->position),
			280 => fn() => $this->semValue = TagParser::parseOffset($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			281 => fn() => $this->semValue = TagParser::parseOffset('-' . $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
		})();
	}
}
