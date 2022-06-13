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

	protected const Yy2Tblstate = 257;

	/** Number of non-leaf states */
	protected const NumNonLeafStates = 350;

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
		97,    305,   305,   305,   305,   100,   141,   305,   264,   182,   223,   305,   385,   385,   385,   385,   385,   155,   155,   155,
		232,   232,   214,   225,   353,   354,   355,   375,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,
		-42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,
		-42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,
		-42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,
		-42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   38,    224,   378,   381,   380,   382,   422,   429,   430,   433,   441,
		53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    140,   64,    557,   107,   107,   107,
		107,   107,   107,   107,   107,   107,   107,   107,   107,   107,   107,   107,   107,   107,   107,   274,   274,   274,   371,   504,
		492,   391,   -67,   412,   412,   127,   127,   127,   127,   127,   426,   426,   426,   426,   79,    79,    79,    79,    250,   250,
		250,   250,   250,   250,   250,   250,   87,    87,    103,   208,   -39,   -39,   -39,   451,   451,   451,   41,    41,    41,    41,
		41,    111,   152,   193,   449,   203,   446,   446,   446,   446,   446,   446,   230,   444,   -21,   -11,   -11,   388,   303,   303,
		303,   -11,   397,   94,    321,   -36,   461,   474,   318,   419,   263,   222,   377,   216,   217,   236,   26,    26,    26,    26,
		155,   445,   445,   155,   155,   155,   132,   132,   132,   -83,   221,   52,    23,    389,   221,   221,   221,   47,    37,    60,
		285,   220,   271,   276,   33,    104,   61,    292,   311,   285,   285,   145,   61,    61,    234,   240,   244,   169,   99,    244,
		235,   235,   165,   29,    322,   317,   324,   269,   261,   387,   181,   192,   233,   231,   260,   245,   215,   322,   317,   324,
		218,   181,   228,   228,   228,   258,   228,   228,   228,   228,   228,   228,   228,   432,   30,    239,   326,   327,   342,   344,
		211,   226,   394,   228,   227,   255,   252,   434,   181,   259,   435,   384,   320,   254,   213,   336,   212,   439,   348,   407,
		200,   331,   202,   414,   219,   390,   229,   352,   418,   420,   0,     -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,
		-42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   -42,   0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     53,
		53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    53,
		53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    0,     426,   53,    53,    53,    53,    53,    53,    53,
		0,     0,     0,     0,     303,   303,   303,   303,   41,    41,    41,    41,    41,    41,    41,    41,    41,    41,    41,    41,
		303,   303,   303,   41,    41,    41,    0,     0,     0,     0,     0,     0,     0,     0,     0,     397,   235,   235,   235,   235,
		235,   235,   397,   397,   0,     0,     0,     0,     0,     0,     0,     0,     0,     397,   235,   0,     0,     0,     0,     0,
		0,     0,     155,   155,   155,   397,   0,     0,     0,     0,     0,     235,   235,   0,     0,     0,     0,     0,     0,     0,
		228,   0,     0,     30,    228,   228,   228,
	];

	/** Table of actions. Indexed according to self::ActionBase comment. */
	protected const Action = [
		34,    35,    376,   75,    76,    77,    36,    -176,  37,    178,   179,   38,    39,    40,    41,    42,    43,    44,    -176,  1,
		193,   45,    544,   545,   202,   -213,  527,   376,   12,    0,     542,   284,   7,     243,   244,   11,    98,    285,   286,   16,
		-211,  289,   287,   288,   205,   -213,  -213,  -213,  13,    173,   -208,  529,   528,   550,   548,   549,   54,    55,    56,    29,
		-211,  -211,  -211,  15,    172,   222,   289,   -8190, -8190, -211,  -208,  -208,  -208,  197,   23,    198,   57,    58,    59,    -208,
		60,    61,    62,    63,    64,    65,    66,    67,    68,    69,    70,    71,    72,    73,    74,    75,    76,    77,    78,    79,
		80,    21,    195,   357,   358,   356,   101,   525,   196,   527,   -8190, -8190, -8190, 81,    -8191, -8191, -8191, -8191, 72,    73,
		74,    75,    76,    77,    196,   493,   405,   14,    355,   354,   -8190, -8190, -8190, -21,   529,   528,   407,   32,    406,   434,
		204,   367,   -175,  -8190, 357,   358,   356,   188,   -8190, 361,   -8190, -8190, -8190, -175,  -8190, -8190, -8190, -8191, -8191, -8191,
		-8191, -8191, 194,   46,    510,   -8190, 201,   232,   296,   355,   354,   378,   -214,  297,   363,   233,   234,   362,   368,   298,
		299,   553,   367,   355,   354,   357,   358,   356,   95,    -45,   361,   24,    -214,  -214,  -214,  33,    261,   3,     374,   96,
		375,   -175,  416,   194,   46,    -28,   105,   201,   106,   296,   355,   354,   -175,  -214,  297,   363,   233,   234,   362,   368,
		298,   299,   -260,  367,   -260,  107,   357,   358,   356,   196,   18,    361,   25,    -214,  -214,  -214,  108,   -22,   -16,   -15,
		19,    200,   -175,  416,   194,   46,    170,   97,    201,   104,   296,   355,   354,   -175,  -210,  297,   363,   233,   234,   362,
		368,   298,   299,   -257,  367,   -257,  189,   357,   358,   356,   195,   171,   361,   26,    -210,  -210,  -210,  -8190, -8190, -8190,
		199,   320,   630,   -210,  416,   194,   46,    102,   81,    201,   554,   296,   355,   354,   -217,  -8190, 297,   363,   233,   234,
		362,   368,   298,   299,   376,   367,   277,   -8190, 357,   358,   356,   1,     341,   361,   27,    625,   251,   555,   -254,  376,
		-254,  -184,  542,   162,   -260,  416,   194,   46,    -260,  241,   201,   386,   296,   355,   354,   288,   0,     297,   363,   233,
		234,   362,   368,   298,   299,   -8190, 367,   -8190, 78,    79,    80,    49,    195,   -243,  361,   100,   -241,  222,   289,   -217,
		-216,  -215,  2,     81,    336,   -257,  416,   194,   46,    -257,  4,     201,   5,     296,   -8190, -8190, -8190, 6,     297,   363,
		233,   234,   362,   368,   298,   299,   8,     628,   357,   358,   356,   629,   -8190, 9,     -8190, -8190, 47,    48,    17,    82,
		83,    84,    85,    86,    87,    88,    89,    90,    91,    92,    93,    94,    10,    355,   354,   -8190, -8190, -8190, 28,    -71,
		-254,  -71,   274,   51,    -254,  52,    367,   186,   187,   357,   358,   356,   240,   -8190, 361,   -8190, -8190, -8190, 276,   -8190,
		-8190, -8190, 456,   458,   498,   -71,   540,   194,   46,    31,    417,   201,   -208,  296,   513,   519,   521,   523,   297,   363,
		233,   234,   362,   368,   298,   299,   376,   367,   617,   209,   210,   211,   -208,  -208,  -208,  361,   100,   572,   242,   -28,
		329,   -208,  331,   532,   499,   22,    181,   20,    360,   359,   53,    597,   371,   259,   372,   -8190, -8190, -8190, 627,   364,
		363,   366,   365,   362,   368,   369,   370,   -8190, -8190, -8190, -209,  387,   374,   -8190, 375,   -8190, -8190, -8190, 626,   -8190,
		376,   -71,   30,    349,   509,   -8190, 624,   -8190, -8190, -8190, -209,  -209,  -209,  581,   592,   224,   -8190, 595,   539,   -209,
		569,   585,   206,   207,   208,   620,   -8190, 343,   -8190, 99,    -216,  543,   50,    289,   289,   246,   247,   248,   0,     289,
		-8190, -8190, -8190, 0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     103,
	];

	/** Table indexed analogously to self::Action. If self::ActionCheck[self::ActionBase[$state] + $symbol] != $symbol
	 *  then the action is defaulted, i.e. self::ActionDefault[$state] should be used instead. */
	protected const ActionCheck = [
		42,    43,    69,    42,    43,    44,    48,    90,    50,    51,    52,    53,    54,    55,    56,    57,    58,    59,    101,   61,
		62,    63,    64,    65,    66,    61,    68,    69,    2,     0,     72,    73,    2,     75,    76,    2,     103,   79,    80,    2,
		61,    108,   84,    85,    86,    81,    82,    83,    22,    26,    61,    93,    94,    95,    96,    97,    3,     4,     5,     101,
		81,    82,    83,    2,     26,    107,   108,   3,     4,     90,    81,    82,    83,    26,    21,    28,    23,    24,    25,    90,
		27,    28,    29,    30,    31,    32,    33,    34,    35,    36,    37,    38,    39,    40,    41,    42,    43,    44,    45,    46,
		47,    2,     49,    3,     4,     5,     2,     66,    21,    68,    3,     4,     5,     60,    35,    36,    37,    38,    39,    40,
		41,    42,    43,    44,    21,    102,   85,    101,   28,    29,    3,     4,     5,     22,    93,    94,    95,    77,    97,    102,
		102,   41,    90,    3,     3,     4,     5,     2,     21,    49,    23,    24,    25,    101,   27,    28,    29,    30,    31,    32,
		33,    34,    62,    63,    104,   71,    66,    2,     68,    28,    29,    2,     61,    73,    74,    75,    76,    77,    78,    79,
		80,    87,    41,    28,    29,    3,     4,     5,     7,     102,   49,    91,    81,    82,    83,    98,    99,    100,   66,    7,
		68,    90,    102,   62,    63,    102,   6,     66,    6,     68,    28,    29,    101,   61,    73,    74,    75,    76,    77,    78,
		79,    80,    0,     41,    2,     6,     3,     4,     5,     21,    6,     49,    91,    81,    82,    83,    7,     22,    22,    22,
		22,    28,    90,    102,   62,    63,    26,    22,    66,    22,    68,    28,    29,    101,   61,    73,    74,    75,    76,    77,
		78,    79,    80,    0,     41,    2,     22,    3,     4,     5,     49,    26,    49,    91,    81,    82,    83,    3,     4,     5,
		26,    67,    70,    90,    102,   62,    63,    61,    60,    66,    87,    68,    28,    29,    101,   21,    73,    74,    75,    76,
		77,    78,    79,    80,    69,    41,    74,    71,    3,     4,     5,     61,    78,    49,    91,    104,   66,    87,    0,     69,
		2,     90,    72,    90,    102,   102,   62,    63,    106,   90,    66,    91,    68,    28,    29,    85,    -1,    73,    74,    75,
		76,    77,    78,    79,    80,    95,    41,    97,    45,    46,    47,    101,   49,    101,   49,    91,    101,   107,   108,   101,
		101,   101,   101,   60,    43,    102,   102,   62,    63,    106,   101,   66,    101,   68,    3,     4,     5,     101,   73,    74,
		75,    76,    77,    78,    79,    80,    101,   66,    3,     4,     5,     70,    21,    101,   23,    24,    91,    92,    7,     8,
		9,     10,    11,    12,    13,    14,    15,    16,    17,    18,    19,    20,    101,   28,    29,    3,     4,     5,     101,   0,
		102,   2,     102,   101,   106,   101,   41,    101,   101,   3,     4,     5,     101,   21,    49,    23,    24,    25,    102,   27,
		28,    29,    51,    52,    102,   26,    102,   62,    63,    61,    102,   66,    61,    68,    102,   102,   102,   102,   73,    74,
		75,    76,    77,    78,    79,    80,    69,    41,    71,    81,    82,    83,    81,    82,    83,    49,    91,    102,   90,    102,
		102,   90,    102,   102,   102,   88,    89,    103,   62,    63,    103,   102,   66,    103,   68,    3,     4,     5,     104,   73,
		74,    75,    76,    77,    78,    79,    80,    3,     4,     5,     61,    104,   66,    21,    68,    23,    24,    25,    104,   27,
		69,    102,   61,    105,   104,   21,    104,   23,    24,    25,    81,    82,    83,    104,   104,   61,    85,    104,   106,   90,
		106,   106,   81,    82,    83,    106,   95,    106,   97,    103,   101,   107,   101,   108,   108,   81,    82,    83,    -1,    108,
		3,     4,     5,     -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    22,
	];

	/** Map of states to their default action */
	protected const ActionDefault = [
		8191,  252,   252,   30,    252,   8191,  8191,  252,   8191,  8191,  8191,  28,    8191,  8191,  8191,  28,    8191,  8191,  8191,  8191,
		38,    28,    8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  206,   206,   206,   8191,  8191,  8191,  8191,  8191,  8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  9,     8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,
		8191,  28,    8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  253,   8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,
		1,     261,   262,   76,    70,    207,   256,   259,   72,    75,    73,    42,    43,    49,    112,   114,   146,   113,   88,    93,
		94,    95,    96,    97,    98,    99,    100,   101,   102,   103,   104,   105,   86,    87,    158,   147,   145,   144,   110,   111,
		117,   85,    8191,  115,   116,   134,   135,   132,   133,   136,   8191,  8191,  8191,  8191,  137,   138,   139,   140,   8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  62,    62,    62,    8191,  124,   125,   127,   8191,  10,    8191,  8191,  8191,  8191,  8191,
		8191,  197,   197,   196,   142,   8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  202,   107,   109,   181,   119,   120,
		118,   89,    8191,  8191,  8191,  201,   8191,  269,   208,   208,   208,   208,   33,    33,    33,    8191,  81,    81,    81,    81,
		33,    8191,  8191,  33,    33,    33,    8191,  8191,  8191,  187,   130,   214,   8191,  8191,  121,   122,   123,   50,    8191,  8191,
		185,   8191,  174,   8191,  27,    27,    27,    8191,  227,   228,   229,   27,    27,    27,    162,   35,    64,    27,    27,    64,
		8191,  8191,  27,    8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  191,   8191,  212,   225,   2,     177,   14,    19,    20,
		8191,  255,   128,   129,   131,   210,   150,   151,   152,   153,   154,   155,   156,   8191,  248,   180,   8191,  8191,  8191,  8191,
		268,   8191,  208,   126,   8191,  188,   232,   8191,  258,   209,   8191,  8191,  8191,  52,    53,    8191,  8191,  8191,  8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  48,    8191,  8191,  8191,
	];

	/** Map of non-terminals to a displacement into the self::Goto table. The corresponding goto state for this
	 *  non-terminal/state pair is self::Goto[self::GotoBase[$nonTerminal] + $state] (unless defaulted) */
	protected const GotoBase = [
		0,     0,     -1,    0,     0,     107,   0,     161,   -2,    -39,   -96,   0,     260,   -55,   0,     0,     0,     0,     114,   110,
		-16,   0,     -8,    0,     64,    52,    0,     0,     -66,   -9,    -255,  103,   -11,   19,    0,     0,     -44,   236,   27,    0,
		73,    0,     0,     233,   0,     0,     0,     29,    0,     0,     0,     0,     67,    -47,   0,     0,     35,    43,    7,     51,
		-35,   -86,   0,     0,     -50,   48,    0,     25,    109,   4,     -68,   0,     0,
	];

	/** Table of states to goto after reduction. Indexed according to self::GotoBase comment. */
	protected const Goto = [
		110,   110,   110,   110,   419,   419,   110,   518,   520,   319,   110,   599,   522,   571,   573,   574,   138,   126,   127,   123,
		123,   115,   136,   128,   128,   128,   128,   123,   109,   125,   125,   125,   120,   302,   303,   250,   304,   306,   307,   308,
		309,   310,   311,   312,   442,   442,   121,   122,   111,   112,   113,   114,   116,   134,   135,   137,   155,   158,   159,   160,
		163,   164,   165,   166,   167,   168,   169,   174,   175,   176,   177,   190,   191,   192,   218,   219,   220,   254,   255,   256,
		323,   139,   140,   141,   142,   143,   144,   145,   146,   147,   148,   149,   150,   151,   152,   153,   156,   117,   118,   128,
		129,   119,   157,   130,   131,   154,   132,   133,   180,   180,   180,   180,   326,   253,   180,   272,   273,   258,   180,   223,
		404,   404,   404,   404,   183,   184,   185,   524,   524,   524,   404,   404,   404,   404,   404,   391,   235,   615,   318,   526,
		526,   526,   526,   584,   584,   584,   526,   526,   526,   526,   526,   526,   526,   526,   615,   616,   596,   596,   596,   596,
		596,   596,   300,   300,   300,   300,   227,   616,   300,   424,   338,   335,   300,   227,   227,   394,   433,   432,   316,   317,
		397,   344,   389,   227,   227,   631,   514,   213,   316,   317,   275,   582,   582,   430,   427,   428,   227,   215,   216,   228,
		322,   229,   221,   230,   231,   541,   541,   541,   541,   541,   541,   541,   541,   563,   563,   563,   563,   563,   563,   563,
		563,   561,   561,   561,   561,   561,   561,   561,   561,   305,   305,   305,   305,   305,   305,   305,   305,   301,   301,   301,
		301,   346,   517,   301,   621,   622,   623,   301,   401,   408,   410,   409,   411,   415,   328,   269,   270,   586,   587,   588,
		315,   315,   315,   381,   330,   348,   589,   590,   491,   515,   345,   382,   0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     325,   0,     0,     0,     0,     0,     0,     0,     236,   237,   238,   239,   0,     0,     0,     0,     384,
		384,   384,   0,     0,     0,     0,     0,     384,   0,     0,     384,   384,   384,
	];

	/** Table indexed analogously to self::Goto. If self::GotoCheck[self::GotoBase[$nonTerminal] + $state] != $nonTerminal
	 *  then the goto state is defaulted, i.e. self::GotoDefault[$nonTerminal] should be used. */
	protected const GotoCheck = [
		2,     2,     2,     2,     32,    32,    2,     30,    30,    58,    2,     69,    30,    30,    30,    30,    2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     5,     5,     5,     5,     64,    68,    5,     31,    31,    31,    5,     61,
		28,    28,    28,    28,    5,     5,     5,     28,    28,    28,    28,    28,    28,    28,    28,    18,    61,    70,    19,    53,
		53,    53,    53,    64,    64,    64,    53,    53,    53,    53,    53,    53,    53,    53,    70,    70,    64,    64,    64,    64,
		64,    64,    7,     7,     7,     7,     9,     70,    7,     10,    10,    20,    7,     9,     9,     10,    10,    10,    13,    13,
		22,    10,    10,    9,     9,     9,     10,    60,    13,    13,    13,    64,    64,    36,    36,    36,    9,     33,    33,    33,
		33,    33,    33,    33,    33,    38,    38,    38,    38,    38,    38,    38,    38,    56,    56,    56,    56,    56,    56,    56,
		56,    57,    57,    57,    57,    57,    57,    57,    57,    59,    59,    59,    59,    59,    59,    59,    59,    37,    37,    37,
		37,    9,     9,     37,    8,     8,     8,     37,    25,    25,    25,    25,    25,    24,    37,    65,    65,    65,    65,    65,
		52,    52,    52,    12,    43,    43,    67,    67,    40,    47,    29,    12,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
		-1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
		-1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
		-1,    -1,    -1,    7,     -1,    -1,    -1,    -1,    -1,    -1,    -1,    7,     7,     7,     7,     -1,    -1,    -1,    -1,    5,
		5,     5,     -1,    -1,    -1,    -1,    -1,    5,     -1,    -1,    5,     5,     5,
	];

	/** Map of non-terminals to the default state to goto after their reduction */
	protected const GotoDefault = [
		-8192, 283,   124,   295,   353,   182,   373,   324,   594,   580,   379,   264,   601,   281,   280,   441,   339,   278,   390,   340,
		332,   271,   396,   245,   413,   257,   333,   334,   262,   342,   536,   266,   418,   161,   265,   252,   429,   290,   291,   440,
		260,   507,   279,   327,   511,   347,   282,   516,   570,   263,   292,   267,   533,   249,   217,   293,   225,   214,   313,   203,
		212,   614,   226,   294,   568,   268,   576,   583,   314,   600,   613,   321,   337,
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
		40,    40,    40,    50,    50,    44,    44,    56,    57,    38,    59,    59,    59,    59,    41,    41,    41,    41,    41,    41,
		41,    41,    41,    41,    41,    41,    43,    43,    55,    55,    55,    55,    62,    62,    62,    49,    49,    49,    63,    63,
		63,    63,    63,    63,    63,    33,    33,    33,    33,    33,    64,    64,    67,    66,    54,    54,    54,    54,    54,    54,
		54,    51,    51,    51,    65,    65,    65,    37,    58,    68,    68,    69,    69,    12,    12,    12,    12,    12,    12,    12,
		12,    12,    12,    60,    60,    60,    60,    61,    71,    70,    70,    70,    70,    70,    70,    70,    70,    70,    72,    72,
		72,    72,
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
		1,     1,     3,     1,     1,     0,     1,     1,     3,     3,     4,     1,     1,     3,     1,     1,     1,     1,     1,     1,
		1,     1,     1,     3,     2,     3,     0,     1,     1,     3,     1,     1,     1,     1,     1,     1,     3,     1,     1,     4,
		1,     4,     6,     4,     4,     1,     1,     3,     3,     3,     1,     4,     1,     3,     1,     4,     3,     3,     3,     3,
		3,     1,     3,     1,     1,     3,     1,     4,     1,     3,     1,     1,     0,     1,     2,     1,     3,     4,     3,     3,
		4,     2,     2,     2,     2,     1,     2,     1,     1,     1,     4,     3,     3,     3,     3,     3,     6,     3,     1,     1,
		2,     1,
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
			0, 1, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 27, 28, 50, 63, 65, 85, 90, 91, 157, 174, 176, 180, 181, 183, 184, 186, 191, 196, 201, 202, 207, 208, 210, 211, 212, 213, 215, 217, 218, 220, 225, 226, 230, 234, 241, 243, 244, 246, 251, 269, 281 => fn() => $this->semValue = $this->semStack[$pos],
			2 => fn() => $this->semValue = new Node\ModifierNode($this->semStack[$pos]),
			3 => fn() => $this->semValue = new Expression\ArrayNode($this->semStack[$pos]),
			21, 22, 23, 24, 25, 55, 56, 57 => fn() => $this->semValue = new Node\IdentifierNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			26 => fn() => $this->semValue = new Expression\VariableNode(substr($this->semStack[$pos], 1), $this->startTokenStack[$pos]->position),
			29, 39, 44, 74, 82, 83, 84, 142, 143, 163, 164, 182, 209, 216, 242, 245, 277 => fn() => $this->semValue = $this->semStack[$pos - 1],
			30, 38, 45, 66, 81, 162, 185 => fn() => $this->semValue = [],
			31, 40, 46, 68, 76, 165, 250, 265 => fn() => $this->semValue = [$this->semStack[$pos]],
			32, 41, 47, 59, 61, 69, 75, 166, 249 => function () use ($pos) {
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
			62, 64, 206, 252 => fn() => $this->semValue = null,
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
			167 => fn() => $this->semValue = new Expression\ClosureUseNode($this->semStack[$pos], $this->semStack[$pos - 1], $this->startTokenStack[$pos - 1]->position),
			168, 170 => fn() => $this->semValue = new Expression\FunctionCallNode($this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			169, 171 => fn() => $this->semValue = new Expression\FunctionCallableNode($this->semStack[$pos - 3], $this->startTokenStack[$pos - 3]->position),
			172 => fn() => $this->semValue = new Expression\StaticCallNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position),
			173 => fn() => $this->semValue = new Expression\StaticCallableNode($this->semStack[$pos - 5], $this->semStack[$pos - 3], $this->startTokenStack[$pos - 5]->position),
			175, 177, 178 => fn() => $this->semValue = new Node\NameNode($this->semStack[$pos], Node\NameNode::KindNormal, $this->startTokenStack[$pos]->position),
			179 => fn() => $this->semValue = new Node\NameNode($this->semStack[$pos], Node\NameNode::KindFullyQualified, $this->startTokenStack[$pos]->position),
			187 => fn() => $this->semValue = new Expression\ConstantFetchNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			188 => fn() => $this->semValue = new Expression\ClassConstantFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			189 => fn() => $this->semValue = new Expression\ArrayNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 2]->position),
			190, 247 => fn() => $this->semValue = new Expression\ArrayNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			192 => fn() => $this->semValue = Scalar\StringNode::parse($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			193 => fn() => $this->semValue = Scalar\EncapsedStringNode::parse($this->semStack[$pos - 1], $this->startTokenStack[$pos - 2]->position),
			194 => fn() => $this->semValue = Scalar\IntegerNode::parse($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			195 => fn() => $this->semValue = Scalar\FloatNode::parse($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			197, 278 => fn() => $this->semValue = new Scalar\StringNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			198 => fn() => $this->semValue = new Scalar\BooleanNode(true, $this->startTokenStack[$pos]->position),
			199 => fn() => $this->semValue = new Scalar\BooleanNode(false, $this->startTokenStack[$pos]->position),
			200 => fn() => $this->semValue = new Scalar\NullNode($this->startTokenStack[$pos]->position),
			203 => fn() => $this->semValue = $this->parseDocString($this->semStack[$pos - 2], [$this->semStack[$pos - 1]], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position, $this->startTokenStack[$pos]->position),
			204 => fn() => $this->semValue = $this->parseDocString($this->semStack[$pos - 1], [], $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position, $this->startTokenStack[$pos]->position),
			205 => fn() => $this->semValue = $this->parseDocString($this->semStack[$pos - 2], $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position, $this->startTokenStack[$pos]->position),
			214 => fn() => $this->semValue = new Expression\ConstantFetchNode(new Node\NameNode($this->semStack[$pos], Node\NameNode::KindNormal, $this->startTokenStack[$pos]->position), $this->startTokenStack[$pos]->position),
			219, 235, 270 => fn() => $this->semValue = new Expression\ArrayAccessNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			221 => fn() => $this->semValue = new Expression\MethodCallNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->semStack[$pos], false, $this->startTokenStack[$pos - 3]->position),
			222 => fn() => $this->semValue = new Expression\MethodCallableNode($this->semStack[$pos - 5], $this->semStack[$pos - 3], $this->startTokenStack[$pos - 5]->position),
			223 => fn() => $this->semValue = new Expression\MethodCallNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->semStack[$pos], true, $this->startTokenStack[$pos - 3]->position),
			224 => fn() => $this->semValue = new Expression\UndefinedsafeMethodCallNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position),
			227, 236, 271 => fn() => $this->semValue = new Expression\PropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], false, $this->startTokenStack[$pos - 2]->position),
			228, 237, 272 => fn() => $this->semValue = new Expression\PropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], true, $this->startTokenStack[$pos - 2]->position),
			229, 238, 273 => fn() => $this->semValue = new Expression\UndefinedsafePropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			231 => fn() => $this->semValue = new Expression\VariableNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			232 => function () use ($pos) {
				$var = $this->semStack[$pos]->name;
				$this->semValue = \is_string($var)
					? new Node\VarLikeIdentifierNode($var, $this->startTokenStack[$pos]->position)
					: $var;
			},
			233, 239, 240 => fn() => $this->semValue = new Expression\StaticPropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			248 => function () use ($pos) {
				$this->semValue = $this->semStack[$pos];
				$end = count($this->semValue) - 1;
				if ($this->semValue[$end] === null) {
					array_pop($this->semValue);
				}
			},
			253, 255 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], null, false, false, $this->startTokenStack[$pos]->position),
			254 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], null, true, false, $this->startTokenStack[$pos - 1]->position),
			256, 258, 259 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], $this->semStack[$pos - 2], false, false, $this->startTokenStack[$pos - 2]->position),
			257, 260 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], $this->semStack[$pos - 3], true, false, $this->startTokenStack[$pos - 3]->position),
			261, 262 => fn() => $this->semValue = new Expression\ArrayItemNode($this->semStack[$pos], null, false, true, $this->startTokenStack[$pos - 1]->position),
			263, 264 => function () use ($pos) {
				$this->semStack[$pos - 1][] = $this->semStack[$pos];
				$this->semValue = $this->semStack[$pos - 1];
			},
			266 => fn() => $this->semValue = [$this->semStack[$pos - 1], $this->semStack[$pos]],
			267 => fn() => $this->semValue = new Scalar\EncapsedStringPartNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			268 => fn() => $this->semValue = new Expression\VariableNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			274, 275 => fn() => $this->semValue = new Expression\VariableNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 2]->position),
			276 => fn() => $this->semValue = new Expression\ArrayAccessNode($this->semStack[$pos - 4], $this->semStack[$pos - 2], $this->startTokenStack[$pos - 5]->position),
			279 => fn() => $this->semValue = TagParser::parseOffset($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			280 => fn() => $this->semValue = TagParser::parseOffset('-' . $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
		})();
	}
}
