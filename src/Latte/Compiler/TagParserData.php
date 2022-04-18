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
		0,     113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,
		113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   48,    108,   113,   109,   47,    113,   113,
		102,   103,   45,    42,    2,     43,    44,    46,    113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   22,    106,
		35,    7,     37,    21,    59,    113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,
		113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   61,    113,   107,   27,    113,   113,   101,   113,   113,
		113,   99,    113,   113,   113,   113,   113,   113,   113,   100,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,
		113,   113,   113,   104,   26,    105,   50,    113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,
		113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,
		113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,
		113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,
		113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,
		113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,
		113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   113,   1,     3,     4,     5,
		6,     8,     9,     10,    11,    12,    13,    14,    15,    16,    17,    18,    19,    20,    23,    24,    25,    28,    29,    30,
		31,    32,    33,    34,    36,    38,    39,    40,    41,    49,    51,    52,    53,    54,    55,    56,    57,    58,    60,    62,
		63,    64,    65,    66,    67,    68,    69,    70,    71,    72,    73,    74,    75,    76,    77,    78,    79,    80,    81,    82,
		83,    84,    85,    86,    87,    88,    89,    90,    110,   91,    92,    93,    94,    95,    111,   112,   96,    97,    98,
	];

	/** Map of states to a displacement into the self::Action table. The corresponding action for this
	 *  state/symbol pair is self::Action[self::ActionBase[$state] + $symbol]. If self::ActionBase[$state] is 0, the
	 *  action is defaulted, i.e. self::ActionDefault[$state] should be used instead. */
	protected const ActionBase = [
		167,   144,   144,   144,   144,   101,   144,   144,   224,   224,   224,   224,   224,   103,   103,   103,   303,   303,   295,   324,
		-42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,
		-42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,
		-42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,
		-42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,
		-42,   -42,   -1,    219,   354,   356,   355,   357,   365,   366,   367,   368,   374,   54,    54,    54,    54,    54,    54,    54,
		54,    54,    54,    54,    54,    54,    54,    120,   109,   211,   68,    68,    68,    68,    68,    68,    68,    68,    68,    68,
		68,    68,    68,    68,    68,    68,    68,    68,    140,   140,   140,   389,   346,   251,   230,   -40,   112,   112,   302,   302,
		302,   302,   302,   121,   121,   121,   121,   116,   116,   116,   116,   276,   276,   276,   276,   276,   276,   276,   276,   18,
		18,    25,    205,   306,   306,   306,   166,   166,   166,   166,   166,   76,    76,    76,    257,   299,   305,   315,   313,   -64,
		-64,   -64,   -64,   -64,   -64,   327,   375,   -20,   227,   227,   338,   62,    62,    62,    227,   142,   209,   139,   -33,   202,
		282,   322,   353,   319,   186,   352,   255,   272,   312,   169,   169,   169,   169,   103,   376,   376,   103,   103,   103,   248,
		248,   248,   44,    292,   100,   -23,   358,   292,   292,   292,   48,    63,    -70,   337,   244,   337,   337,   30,    66,    34,
		337,   337,   337,   337,   108,   34,    34,    325,   333,   332,   136,   45,    332,   304,   304,   125,   5,     340,   339,   341,
		336,   335,   323,   201,   250,   321,   316,   334,   318,   236,   340,   339,   341,   273,   201,   296,   296,   296,   328,   296,
		296,   296,   296,   296,   296,   296,   370,   16,    326,   342,   343,   347,   348,   360,   297,   361,   296,   330,   349,   307,
		288,   371,   201,   329,   372,   314,   320,   331,   300,   345,   298,   373,   350,   362,   159,   344,   162,   363,   197,   359,
		262,   351,   369,   364,   0,     -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,
		-42,   -42,   -42,   -42,   0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     54,    54,    54,    54,    54,    54,    54,    54,    54,    54,    54,    0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     54,    54,    54,    54,    54,    54,    54,    54,    54,
		54,    54,    54,    54,    54,    54,    54,    54,    54,    54,    54,    54,    54,    54,    54,    54,    54,    54,    0,     121,
		54,    54,    54,    54,    54,    54,    54,    0,     0,     0,     0,     62,    62,    62,    62,    166,   166,   166,   166,   166,
		166,   166,   166,   166,   166,   166,   166,   166,   166,   166,   0,     0,     0,     0,     0,     62,    62,    62,    0,     0,
		0,     0,     142,   304,   304,   304,   304,   304,   304,   142,   142,   0,     0,     0,     0,     0,     0,     0,     0,     0,
		142,   304,   0,     0,     0,     0,     0,     0,     0,     103,   103,   103,   142,   0,     0,     0,     0,     0,     304,   304,
		0,     0,     0,     0,     0,     0,     0,     296,   0,     0,     16,    296,   296,   296,
	];

	/** Table of actions. Indexed according to self::ActionBase comment. */
	protected const Action = [
		26,    27,    368,   166,   369,   0,     28,    24,    29,    171,   172,   30,    31,    32,    33,    34,    35,    36,    6,     1,
		183,   37,    538,   539,   195,   165,   520,   370,   -213,  370,   536,   277,   7,     236,   237,   506,   11,    278,   279,   186,
		92,    -211,  280,   281,   198,   282,   186,   17,    -213,  -213,  -213,  522,   521,   523,   544,   542,   543,   46,    47,    48,
		21,    -211,  -211,  -211,  91,    12,    215,   282,   93,    282,   -211,  -8190, -8190, -8190, 187,   19,    188,   49,    50,    51,
		489,   52,    53,    54,    55,    56,    57,    58,    59,    60,    61,    62,    63,    64,    65,    66,    67,    68,    69,    70,
		71,    72,    197,   185,   351,   352,   350,   70,    71,    72,    181,   185,   -8190, -8190, 73,    -8190, -8190, -8190, 67,    68,
		69,    -45,   73,    -8190, 351,   352,   350,   225,   -28,   349,   348,   349,   348,   -8190, -175,  -8190, -8190, -8190, 372,   -8190,
		-8190, -8190, 361,   -8190, -8190, -8190, -175,  351,   352,   350,   355,   -8191, -8191, -8191, -8191, 64,    65,    66,    67,    68,
		69,    -8190, 361,   184,   38,    98,    430,   194,   99,    289,   355,   8,     349,   348,   290,   357,   226,   227,   356,   362,
		291,   292,   330,   354,   353,   361,   -259,  365,   -259,  366,   -174,  9,     90,    355,   358,   357,   360,   359,   356,   362,
		363,   364,   -174,  100,   410,   621,   184,   38,    87,    622,   194,   370,   289,   610,   -8190, -8190, -8190, 290,   357,   226,
		227,   356,   362,   291,   292,   14,    186,   351,   352,   350,   18,    174,   518,   95,    520,   39,    40,    13,    74,    75,
		76,    77,    78,    79,    80,    81,    82,    83,    84,    85,    86,    399,   349,   348,   -8190, -8190, -8190, 88,    -22,   522,
		521,   523,   401,   22,    400,   361,   25,    254,   3,     101,   163,   10,    -8190, 355,   -8190, -8190, -8190, -16,   -8190, -21,
		-8190, 452,   454,   199,   200,   201,   184,   38,    -208,  -259,  194,   -208,  289,   -259,  -15,   15,    547,   290,   357,   226,
		227,   356,   362,   291,   292,   -8190, -8190, -8190, -208,  -208,  -208,  -208,  -208,  -208,  368,   97,    369,   -208,  -214,  -256,
		-208,  -256,  -253,  -8190, -253,  -8190, -8190, -8190, 190,   -8190, -8190, -8190, -8191, -8191, -8191, -8191, -8191, 1,     -214,  -214,
		-214,  185,   244,   217,   164,   370,   89,    -174,  536,   -8190, -8190, -8190, 96,    -73,   182,   -73,   73,    189,   94,    -174,
		-214,  281,   313,   239,   240,   241,   -210,  -8190, 623,   -8190, -8190, -8190, -8190, 370,   -8190, 370,   -209,  270,   41,    -73,
		-214,  -214,  -214,  -8190, 215,   282,   -210,  -210,  -210,  -174,  -242,  -8190, -8190, -8190, -8190, -210,  -209,  -209,  -209,  23,
		548,   -174,  -8190, 335,   -8190, -209,  -184,  -217,  42,    -240,  -8190, 155,   -8190, -8190, 549,   282,   234,   -216,  16,    202,
		203,   204,   -256,  267,   380,   -253,  -256,  45,    235,   -253,  -217,  -216,  0,     0,     0,     0,     -215,  2,     4,     5,
		0,     20,    43,    44,    179,   180,   233,   0,     269,   0,     494,   534,   412,   411,   509,   -28,   -73,   323,   325,   526,
		495,   590,   0,     252,   0,     618,   620,   381,   619,   505,   617,   574,   585,   588,   0,     343,   0,     533,   563,   578,
		613,   337,   0,     537,   0,     282,
	];

	/** Table indexed analogously to self::Action. If self::ActionCheck[self::ActionBase[$state] + $symbol] != $symbol
	 *  then the action is defaulted, i.e. self::ActionDefault[$state] should be used instead. */
	protected const ActionCheck = [
		42,    43,    66,    26,    68,    0,     48,    77,    50,    51,    52,    53,    54,    55,    56,    57,    58,    59,    2,     61,
		62,    63,    64,    65,    66,    26,    68,    69,    61,    69,    72,    73,    2,     75,    76,    105,   2,     79,    80,    21,
		104,   61,    84,    85,    86,    109,   21,    2,     81,    82,    83,    93,    94,    95,    96,    97,    98,    3,     4,     5,
		102,   81,    82,    83,    104,   2,     108,   109,   2,     109,   90,    3,     4,     5,     26,    21,    28,    23,    24,    25,
		103,   27,    28,    29,    30,    31,    32,    33,    34,    35,    36,    37,    38,    39,    40,    41,    42,    43,    44,    45,
		46,    47,    103,   49,    3,     4,     5,     45,    46,    47,    2,     49,    3,     4,     60,    3,     4,     5,     42,    43,
		44,    103,   60,    3,     3,     4,     5,     2,     103,   28,    29,    28,    29,    21,    90,    23,    24,    25,    2,     27,
		28,    29,    41,    3,     4,     5,     102,   3,     4,     5,     49,    35,    36,    37,    38,    39,    40,    41,    42,    43,
		44,    21,    41,    62,    63,    6,     103,   66,    6,     68,    49,    2,     28,    29,    73,    74,    75,    76,    77,    78,
		79,    80,    43,    62,    63,    41,    0,     66,    2,     68,    90,    22,    91,    49,    73,    74,    75,    76,    77,    78,
		79,    80,    102,   6,     103,   66,    62,    63,    7,     70,    66,    69,    68,    71,    3,     4,     5,     73,    74,    75,
		76,    77,    78,    79,    80,    6,     21,    3,     4,     5,     88,    89,    66,    22,    68,    91,    92,    7,     8,     9,
		10,    11,    12,    13,    14,    15,    16,    17,    18,    19,    20,    85,    28,    29,    3,     4,     5,     7,     22,    93,
		94,    95,    96,    61,    98,    41,    99,    100,   101,   7,     26,    102,   21,    49,    23,    24,    25,    22,    27,    22,
		71,    51,    52,    81,    82,    83,    62,    63,    61,    103,   66,    61,    68,    107,   22,    22,    87,    73,    74,    75,
		76,    77,    78,    79,    80,    3,     4,     5,     81,    82,    83,    81,    82,    83,    66,    91,    68,    90,    61,    0,
		90,    2,     0,     21,    2,     23,    24,    25,    28,    27,    28,    29,    30,    31,    32,    33,    34,    61,    81,    82,
		83,    49,    66,    61,    26,    69,    22,    90,    72,    3,     4,     5,     22,    0,     22,    2,     60,    26,    61,    102,
		61,    85,    67,    81,    82,    83,    61,    21,    70,    23,    24,    25,    96,    69,    98,    69,    61,    74,    102,   26,
		81,    82,    83,    71,    108,   109,   81,    82,    83,    90,    102,   85,    3,     4,     5,     90,    81,    82,    83,    61,
		87,    102,   96,    78,    98,    90,    90,    102,   102,   102,   21,    90,    23,    24,    87,    109,   90,    102,   104,   81,
		82,    83,    103,   103,   91,    103,   107,   104,   90,    107,   102,   102,   -1,    -1,    -1,    -1,    102,   102,   102,   102,
		-1,    102,   102,   102,   102,   102,   102,   -1,    103,   -1,    103,   103,   103,   103,   103,   103,   103,   103,   103,   103,
		103,   103,   -1,    104,   -1,    105,   105,   105,   105,   105,   105,   105,   105,   105,   -1,    106,   -1,    107,   107,   107,
		107,   107,   -1,    108,   -1,    109,
	];

	/** Map of states to their default action */
	protected const ActionDefault = [
		8191,  251,   251,   30,    251,   8191,  251,   28,    8191,  8191,  8191,  28,    8191,  8191,  8191,  8191,  38,    28,    8191,  8191,
		8191,  8191,  206,   206,   206,   8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  9,     8191,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  69,    8191,  8191,  28,    8191,  8191,  8191,  8191,  8191,  8191,
		8191,  8191,  8191,  252,   8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  1,     260,   261,   78,    72,    207,   255,
		258,   74,    77,    75,    42,    43,    49,    114,   116,   148,   115,   90,    95,    96,    97,    98,    99,    100,   101,   102,
		103,   104,   105,   106,   107,   88,    89,    160,   149,   147,   146,   112,   113,   119,   87,    8191,  117,   118,   136,   137,
		134,   135,   138,   8191,  8191,  8191,  8191,  139,   140,   141,   142,   8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  62,
		62,    62,    8191,  8191,  10,    8191,  8191,  8191,  8191,  8191,  8191,  126,   127,   129,   197,   197,   196,   144,   8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  202,   109,   111,   181,   121,   122,   120,   91,    8191,  8191,  8191,  201,   8191,
		268,   208,   208,   208,   208,   33,    33,    33,    8191,  83,    83,    83,    83,    33,    8191,  8191,  33,    33,    33,    8191,
		8191,  8191,  187,   132,   214,   8191,  8191,  123,   124,   125,   50,    8191,  8191,  185,   8191,  173,   8191,  27,    27,    27,
		8191,  226,   227,   228,   27,    27,    27,    164,   35,    64,    27,    27,    64,    8191,  8191,  27,    8191,  8191,  8191,  8191,
		8191,  8191,  8191,  8191,  191,   8191,  212,   224,   2,     176,   14,    19,    20,    8191,  254,   130,   131,   133,   210,   152,
		153,   154,   155,   156,   157,   158,   8191,  247,   180,   8191,  8191,  8191,  8191,  267,   8191,  208,   128,   8191,  8191,  188,
		231,   8191,  257,   209,   8191,  8191,  8191,  52,    53,    8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,
		48,    8191,  8191,  8191,
	];

	/** Map of non-terminals to a displacement into the self::Goto table. The corresponding goto state for this
	 *  non-terminal/state pair is self::Goto[self::GotoBase[$nonTerminal] + $state] (unless defaulted) */
	protected const GotoBase = [
		0,     0,     -5,    0,     0,     96,    0,     138,   25,    -53,   -93,   0,     231,   -57,   0,     0,     0,     0,     159,   217,
		88,    0,     89,    0,     87,    62,    0,     0,     -66,   2,     -16,   209,   0,     -10,   12,    0,     0,     31,    229,   22,
		0,     86,    0,     0,     115,   0,     0,     0,     40,    0,     0,     0,     0,     75,    -54,   0,     0,     30,    38,    142,
		51,    57,    -47,   0,     0,     -43,   53,    0,     7,     170,   261,   -61,   0,     0,
	];

	/** Table of states to goto after reduction. Indexed according to self::GotoBase comment. */
	protected const Goto = [
		117,   415,   415,   117,   117,   117,   117,   117,   131,   119,   120,   116,   116,   108,   129,   116,   102,   118,   118,   118,
		113,   295,   296,   243,   297,   299,   300,   301,   302,   303,   304,   305,   438,   438,   114,   115,   104,   105,   106,   107,
		109,   127,   128,   130,   148,   151,   152,   153,   156,   157,   158,   159,   160,   161,   162,   167,   168,   169,   170,   191,
		192,   193,   211,   212,   213,   247,   248,   249,   316,   132,   133,   134,   135,   136,   137,   138,   139,   140,   141,   142,
		143,   144,   145,   146,   149,   121,   110,   111,   122,   112,   150,   123,   121,   124,   147,   125,   126,   173,   173,   173,
		173,   175,   173,   173,   175,   175,   175,   175,   175,   176,   177,   178,   320,   398,   398,   398,   398,   517,   517,   517,
		398,   398,   398,   398,   398,   519,   519,   519,   519,   519,   519,   519,   519,   519,   519,   519,   519,   608,   324,   342,
		577,   577,   577,   317,   312,   220,   317,   317,   317,   317,   317,   216,   220,   220,   608,   609,   589,   589,   589,   589,
		589,   589,   220,   220,   624,   420,   332,   609,   228,   309,   310,   388,   429,   428,   246,   220,   385,   338,   383,   309,
		310,   268,   510,   208,   209,   221,   315,   222,   214,   223,   224,   575,   575,   535,   535,   535,   535,   535,   535,   535,
		535,   557,   557,   557,   557,   557,   557,   557,   557,   555,   555,   555,   555,   555,   555,   555,   555,   265,   266,   251,
		340,   513,   298,   298,   298,   298,   298,   298,   298,   298,   294,   294,   294,   294,   375,   294,   294,   311,   376,   514,
		515,   582,   583,   322,   516,   565,   566,   567,   395,   402,   404,   403,   405,   262,   263,   579,   580,   581,   308,   308,
		308,   426,   423,   424,   614,   615,   616,   592,   329,   409,   391,   487,   206,   511,   339,   0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     319,   0,     0,     0,     0,     0,     0,
		0,     229,   230,   231,   232,
	];

	/** Table indexed analogously to self::Goto. If self::GotoCheck[self::GotoBase[$nonTerminal] + $state] != $nonTerminal
	 *  then the goto state is defaulted, i.e. self::GotoDefault[$nonTerminal] should be used. */
	protected const GotoCheck = [
		2,     33,    33,    2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     5,     5,     5,
		5,     5,     5,     5,     5,     5,     5,     5,     5,     5,     5,     5,     65,    28,    28,    28,    28,    28,    28,    28,
		28,    28,    28,    28,    28,    54,    54,    54,    54,    54,    54,    54,    54,    54,    54,    54,    54,    71,    44,    44,
		65,    65,    65,    7,     59,    9,     7,     7,     7,     7,     7,     62,    9,     9,     71,    71,    65,    65,    65,    65,
		65,    65,    9,     9,     9,     10,    10,    71,    62,    13,    13,    10,    10,    10,    69,    9,     18,    10,    10,    13,
		13,    13,    10,    34,    34,    34,    34,    34,    34,    34,    34,    65,    65,    39,    39,    39,    39,    39,    39,    39,
		39,    57,    57,    57,    57,    57,    57,    57,    57,    58,    58,    58,    58,    58,    58,    58,    58,    31,    31,    31,
		9,     9,     60,    60,    60,    60,    60,    60,    60,    60,    38,    38,    38,    38,    12,    38,    38,    19,    12,    30,
		30,    68,    68,    38,    30,    30,    30,    30,    25,    25,    25,    25,    25,    66,    66,    66,    66,    66,    53,    53,
		53,    37,    37,    37,    8,     8,     8,     70,    20,    24,    22,    41,    61,    48,    29,    -1,    -1,    -1,    -1,    -1,
		-1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    7,     -1,    -1,    -1,    -1,    -1,    -1,
		-1,    7,     7,     7,     7,
	];

	/** Map of non-terminals to the default state to goto after their reduction */
	protected const GotoDefault = [
		-8192, 276,   103,   288,   347,   378,   367,   293,   587,   573,   373,   257,   594,   274,   273,   437,   333,   271,   384,   334,
		326,   264,   390,   238,   407,   250,   327,   328,   255,   336,   530,   259,   318,   414,   154,   258,   245,   425,   283,   284,
		436,   253,   503,   272,   321,   507,   341,   275,   512,   564,   256,   285,   260,   527,   242,   210,   286,   218,   207,   306,
		196,   205,   607,   219,   287,   562,   261,   569,   576,   307,   593,   606,   314,   331,
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
		42,    42,    42,    42,    42,    42,    44,    44,    56,    56,    56,    56,    63,    63,    63,    50,    50,    50,    64,    64,
		64,    64,    64,    64,    34,    34,    34,    34,    34,    65,    65,    68,    67,    55,    55,    55,    55,    55,    55,    55,
		52,    52,    52,    66,    66,    66,    38,    59,    69,    69,    70,    70,    12,    12,    12,    12,    12,    12,    12,    12,
		12,    12,    61,    61,    61,    61,    62,    72,    71,    71,    71,    71,    71,    71,    71,    71,    71,    73,    73,    73,
		73,
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
		1,     1,     1,     3,     2,     3,     0,     1,     1,     3,     1,     1,     1,     1,     1,     1,     3,     1,     1,     4,
		1,     4,     4,     4,     1,     1,     3,     3,     3,     1,     4,     1,     3,     1,     4,     3,     3,     3,     3,     3,
		1,     3,     1,     1,     3,     1,     4,     1,     3,     1,     1,     0,     1,     2,     1,     3,     4,     3,     3,     4,
		2,     2,     2,     2,     1,     2,     1,     1,     1,     4,     3,     3,     3,     3,     3,     6,     3,     1,     1,     2,
		1,
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
		'heredoc start',
		'heredoc end',
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
			0, 1, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 27, 28, 50, 63, 65, 87, 92, 93, 159, 173, 175, 180, 181, 183, 184, 186, 191, 196, 201, 202, 207, 208, 210, 211, 212, 213, 215, 217, 218, 220, 224, 225, 229, 233, 240, 242, 243, 245, 250, 268, 280 => fn() => $this->semValue = $this->semStack[$pos],
			2 => fn() => $this->semValue = new Node\ModifierNode($this->semStack[$pos]),
			3 => fn() => $this->semValue = new Expression\ArrayNode($this->semStack[$pos]),
			21, 22, 23, 24, 25, 55, 56, 57 => fn() => $this->semValue = new Node\IdentifierNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			26 => fn() => $this->semValue = new Expression\VariableNode(substr($this->semStack[$pos], 1), $this->startTokenStack[$pos]->position),
			29, 39, 44, 76, 84, 85, 86, 144, 145, 165, 166, 182, 209, 216, 241, 244, 276 => fn() => $this->semValue = $this->semStack[$pos - 1],
			30, 38, 45, 66, 83, 164, 185 => fn() => $this->semValue = [],
			31, 40, 46, 70, 78, 167, 249, 264 => fn() => $this->semValue = [$this->semStack[$pos]],
			32, 41, 47, 59, 61, 71, 77, 168, 248 => function () use ($pos) {
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
			62, 64, 206, 251 => fn() => $this->semValue = null,
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
			197, 277 => fn() => $this->semValue = new Scalar\StringNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			198 => fn() => $this->semValue = new Scalar\BooleanNode(true, $this->startTokenStack[$pos]->position),
			199 => fn() => $this->semValue = new Scalar\BooleanNode(false, $this->startTokenStack[$pos]->position),
			200 => fn() => $this->semValue = new Scalar\NullNode($this->startTokenStack[$pos]->position),
			203 => fn() => $this->semValue = $this->parseDocString($this->semStack[$pos - 2], [$this->semStack[$pos - 1]], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position, $this->startTokenStack[$pos]->position),
			204 => fn() => $this->semValue = $this->parseDocString($this->semStack[$pos - 1], [], $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position, $this->startTokenStack[$pos]->position),
			205 => fn() => $this->semValue = $this->parseDocString($this->semStack[$pos - 2], $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position, $this->startTokenStack[$pos]->position),
			214 => fn() => $this->semValue = new Expression\ConstantFetchNode(new Node\NameNode($this->semStack[$pos], $this->startTokenStack[$pos]->position), $this->startTokenStack[$pos]->position),
			219, 234, 269 => fn() => $this->semValue = new Expression\ArrayAccessNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			221 => fn() => $this->semValue = new Expression\MethodCallNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position),
			222 => fn() => $this->semValue = new Expression\NullsafeMethodCallNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position),
			223 => fn() => $this->semValue = new Expression\UndefinedsafeMethodCallNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position),
			226, 235, 270 => fn() => $this->semValue = new Expression\PropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			227, 236, 271 => fn() => $this->semValue = new Expression\NullsafePropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			228, 237, 272 => fn() => $this->semValue = new Expression\UndefinedsafePropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			230 => fn() => $this->semValue = new Expression\VariableNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			231 => function () use ($pos) {
				$var = $this->semStack[$pos]->name;
				$this->semValue = \is_string($var)
					? new Node\VarLikeIdentifierNode($var, $this->startTokenStack[$pos]->position)
					: $var;
			},
			232, 238, 239 => fn() => $this->semValue = new Expression\StaticPropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			246 => fn() => $this->semValue = new Expression\ListNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			247 => function () use ($pos) {
				$this->semValue = $this->semStack[$pos];
				$end = count($this->semValue) - 1;
				if ($this->semValue[$end] === null) {
					array_pop($this->semValue);
				}
			},
			252, 254 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], null, false, $this->startTokenStack[$pos]->position),
			253 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], null, true, $this->startTokenStack[$pos - 1]->position),
			255, 257, 258 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], $this->semStack[$pos - 2], false, $this->startTokenStack[$pos - 2]->position),
			256, 259 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], $this->semStack[$pos - 3], true, $this->startTokenStack[$pos - 3]->position),
			260, 261 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], null, false, $this->startTokenStack[$pos - 1]->position, true, $this->startTokenStack[$pos - 1]->position),
			262, 263 => function () use ($pos) {
				$this->semStack[$pos - 1][] = $this->semStack[$pos];
				$this->semValue = $this->semStack[$pos - 1];
			},
			265 => fn() => $this->semValue = [$this->semStack[$pos - 1], $this->semStack[$pos]],
			266 => fn() => $this->semValue = new Scalar\EncapsedStringPartNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			267 => fn() => $this->semValue = new Expression\VariableNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			273, 274 => fn() => $this->semValue = new Expression\VariableNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 2]->position),
			275 => fn() => $this->semValue = new Expression\ArrayAccessNode($this->semStack[$pos - 4], $this->semStack[$pos - 2], $this->startTokenStack[$pos - 5]->position),
			278 => fn() => $this->semValue = $this->parseOffset($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			279 => fn() => $this->semValue = $this->parseOffset('-' . $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
		})();
	}
}
