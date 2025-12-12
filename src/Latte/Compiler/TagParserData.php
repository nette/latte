<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler;

use Latte\Compiler\Nodes\Php as Node;
use Latte\Compiler\Nodes\Php\{Expression, Scalar};
use function count, is_string;


/** @internal generated part of TagParser */
trait TagParserData
{
	/** Symbol number of error recovery token */
	protected const ErrorSymbol = 1;

	/** Action number signifying default action */
	protected const DefaultAction = -8190;

	/** Rule number signifying that an unexpected token was encountered */
	protected const UnexpectedTokenRule = 8191;

	protected const Yy2Tblstate = 268;

	/** Number of non-leaf states */
	protected const NumNonLeafStates = 368;

	/** Map of lexer tokens to internal symbols */
	protected const TokenToSymbol = [
		0, 116, 116, 116, 116, 116, 116, 116, 116, 116,
		116, 116, 116, 116, 116, 116, 116, 116, 116, 116,
		116, 116, 116, 116, 116, 116, 116, 116, 116, 116,
		116, 116, 116, 51, 111, 116, 112, 50, 116, 116,
		105, 106, 48, 46, 2, 47, 42, 49, 116, 116,
		116, 116, 116, 116, 116, 116, 116, 116, 22, 109,
		37, 7, 39, 21, 62, 116, 116, 116, 116, 116,
		116, 116, 116, 116, 116, 116, 116, 116, 116, 116,
		116, 116, 116, 116, 116, 116, 116, 116, 116, 116,
		116, 64, 116, 110, 29, 116, 116, 103, 116, 116,
		116, 101, 104, 116, 116, 116, 116, 116, 116, 102,
		116, 116, 116, 116, 116, 116, 116, 116, 116, 116,
		116, 116, 116, 107, 26, 108, 53, 116, 116, 116,
		116, 116, 116, 116, 116, 116, 116, 116, 116, 116,
		116, 116, 116, 116, 116, 116, 116, 116, 116, 116,
		116, 116, 116, 116, 116, 116, 116, 116, 116, 116,
		116, 116, 116, 116, 116, 116, 116, 116, 116, 116,
		116, 116, 116, 116, 116, 116, 116, 116, 116, 116,
		116, 116, 116, 116, 116, 116, 116, 116, 116, 116,
		116, 116, 116, 116, 116, 116, 116, 116, 116, 116,
		116, 116, 116, 116, 116, 116, 116, 116, 116, 116,
		116, 116, 116, 116, 116, 116, 116, 116, 116, 116,
		116, 116, 116, 116, 116, 116, 116, 116, 116, 116,
		116, 116, 116, 116, 116, 116, 116, 116, 116, 116,
		116, 116, 116, 116, 116, 116, 116, 116, 116, 116,
		116, 116, 116, 116, 116, 116, 1, 3, 4, 5,
		6, 8, 9, 10, 11, 12, 13, 14, 15, 16,
		17, 18, 19, 20, 23, 24, 25, 27, 28, 30,
		31, 32, 33, 34, 35, 36, 38, 40, 41, 43,
		44, 45, 52, 54, 55, 56, 57, 58, 59, 60,
		61, 63, 65, 66, 67, 68, 69, 70, 71, 72,
		73, 74, 75, 76, 77, 78, 79, 80, 81, 82,
		83, 84, 85, 86, 87, 88, 89, 90, 91, 92,
		93, 113, 94, 95, 96, 97, 114, 115, 98, 99,
		100,
	];

	/** Map of states to a displacement into the self::Action table. The corresponding action for this
	 *  state/symbol pair is self::Action[self::ActionBase[$state] + $symbol]. If self::ActionBase[$state] is 0, the
	 *  action is defaulted, i.e. self::ActionDefault[$state] should be used instead. */
	protected const ActionBase = [
		84, 170, 148, 240, 284, 284, 354, 354, 284, 284,
		262, 284, 284, 393, 393, 393, -30, -30, -30, -30,
		317, 317, 151, -11, 425, 232, 65, 232, 65, 232,
		232, 232, 232, 232, 232, 232, 232, 232, 232, 232,
		232, 232, 232, 232, 232, 232, 232, 232, 232, 232,
		232, 232, 232, 232, 232, 232, 232, 232, 232, 232,
		232, 232, 232, 232, 232, 232, 232, 232, 232, 232,
		232, 232, 232, 232, 232, 232, 232, 232, 232, 232,
		232, 232, 232, 232, 232, 232, 232, 232, 232, 232,
		232, 232, 232, 232, 232, 232, 232, 232, 232, 232,
		232, 232, 232, 232, 232, 232, 232, 232, 232, 16,
		56, 293, 295, 492, 494, 493, 495, 504, 505, 503,
		506, 511, 68, 68, 68, 68, 68, 68, 68, 68,
		68, 68, 68, 68, 68, 68, 68, 160, 146, 574,
		321, 321, 321, 321, 321, 321, 321, 321, 321, 321,
		321, 321, 321, 321, 321, 321, 321, 539, 539, 539,
		143, 474, 366, 254, 125, 173, 173, 379, 379, 379,
		379, 379, 161, 436, 436, -35, -35, -35, -35, 96,
		96, 378, 378, 378, 378, 378, 378, 378, 378, 378,
		538, 541, 184, 184, 202, 59, 225, 225, 225, 225,
		140, 140, 140, 140, 140, 140, 140, 210, 475, 475,
		475, 288, 444, 447, 462, 377, -57, -57, -57, -57,
		-57, -57, 390, 512, -8, 472, 472, 477, 414, 414,
		128, 472, 412, -27, 17, 343, 487, 490, 386, 270,
		13, 489, 313, 329, 375, 52, 52, 491, 391, 421,
		513, 513, 228, 421, 421, 421, 228, 121, 121, 121,
		176, 193, 206, 364, 496, 364, 364, 364, 98, -47,
		429, 429, 156, 440, 440, 63, 235, 119, 429, 429,
		429, 429, 214, 258, 214, 214, 376, 374, 373, 289,
		218, 373, 372, 372, 269, 129, 448, 445, 454, 434,
		430, 387, 297, 303, 370, 361, 415, 310, 448, 445,
		454, 332, 22, 401, 404, 359, 359, 359, 359, 359,
		359, 359, 359, 359, 359, 297, 507, 152, 389, 459,
		461, 40, 355, 482, 483, 498, 360, 499, 359, 395,
		380, 508, 405, 51, 297, 363, 509, 484, 358, 385,
		371, 481, 353, 510, 485, 486, 388, 500, 166, 463,
		189, 501, 287, 497, 305, 488, 460, 502, 0, -30,
		-30, -30, -30, -30, -30, -30, -30, -30, -30, -30,
		-30, -30, -30, -30, 0, 0, 0, 0, -30, -30,
		-30, -30, -30, -30, -30, -30, -30, -30, -30, -30,
		-30, -30, -30, -30, -30, -30, -30, -30, -30, -30,
		-30, -30, -30, -30, -30, -30, -30, -30, -30, -30,
		-30, -30, -30, -30, -30, -30, -30, -30, -30, -30,
		-30, -30, -30, -30, -30, -30, -30, -30, -30, -30,
		-30, -30, -30, -30, -30, -30, -30, -30, -30, -30,
		-30, -30, -30, -30, -30, -30, -30, -30, -30, -30,
		-30, -30, -30, -30, -30, -30, -30, -30, -30, -30,
		-30, -30, -30, -30, -30, -30, -30, 68, 68, 68,
		68, 68, 68, 68, 68, 68, 68, 68, 68, 68,
		0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
		0, 0, 0, 0, 0, 68, 68, 68, 68, 68,
		68, 68, 68, 68, 68, 68, 68, 68, 68, 68,
		68, 68, 68, 68, 68, 68, 68, 68, 68, 68,
		68, 68, 0, 68, 68, 68, 68, 68, 68, 68,
		436, 0, 0, 475, 475, 475, 475, -9, -9, -9,
		-9, -9, -9, -9, -9, -9, -9, -9, 475, 475,
		-9, -9, -9, -9, -9, -9, -9, -9, -9, -9,
		-9, -9, -9, -9, -9, 0, 0, 0, 0, 0,
		0, 0, 0, 412, 372, 372, 372, 372, 372, 372,
		412, 412, 0, 0, 0, 0, 475, 475, 0, 0,
		0, 412, 372, 0, 0, 0, 0, 0, 0, 421,
		421, 421, 412, 440, 440, 228, 0, 0, 372, 372,
		0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 359, 152, 359, 359, 359,
	];

	/** Table of actions. Indexed according to self::ActionBase comment. */
	protected const Action = [
		374, 373, -8191, -8191, -8191, -8191, 70, 71, 72, 73,
		74, 97, 393, -293, 394, -293, 33, 34, 3, -8190,
		-8190, 35, -50, 36, 181, 182, 37, 38, 39, 40,
		41, 42, 43, 31, 4, 196, 26, 594, 595, 211,
		-48, 576, 395, 173, 174, 592, 296, -8190, 253, 254,
		99, -47, 297, 298, 13, 301, -243, 299, 300, 215,
		574, 559, 576, 603, 352, 6, 578, 577, 600, 598,
		599, 51, 52, 53, 14, 27, -243, -243, -243, 430,
		200, 232, 301, 173, 174, -243, 679, 578, 577, 23,
		680, 54, 55, 56, 57, -8190, -8190, 58, 59, 60,
		61, 62, 63, 64, 65, 66, 67, 68, 69, 70,
		71, 72, 73, 74, 75, 76, 77, 78, 79, -293,
		199, 7, 214, -293, 204, 205, 374, 373, -88, 0,
		-88, 80, 16, 81, 82, 83, 84, 85, 86, 87,
		88, 89, 90, 91, 92, 93, -8190, -8190, -8190, -8190,
		-8190, 376, 377, 375, 11, -88, -88, 432, 12, 431,
		4, 198, 214, -8190, -8190, 261, -8190, -8190, 395, 466,
		2, 592, 105, 376, 377, 375, -8190, -8190, -8190, 503,
		505, -8190, -8190, 299, 300, 32, 252, 8, 179, -240,
		393, 466, 394, 386, -8190, 106, -8190, -8190, -8190, -8190,
		380, 46, -8190, -8190, -8190, 200, -47, 232, 301, -240,
		-240, -240, 180, 197, 28, 386, 15, 207, -240, 307,
		21, 335, 380, 200, 308, 382, 242, 243, 381, 387,
		309, 310, -22, 395, -88, 197, 28, 101, 432, 207,
		431, 307, 96, 376, 377, 375, 308, 382, 242, 243,
		381, 387, 309, 310, 447, 173, 174, -8190, -8190, -8190,
		194, 466, -8190, -8190, 96, 376, 377, 375, 98, -207,
		-291, 241, -291, 301, -247, -8190, 459, -8190, -8190, -8190,
		-8190, -207, 432, -8190, 431, 386, -206, 376, 377, 375,
		-52, 397, 380, 107, -247, -247, -247, 395, -206, 17,
		432, 19, 431, -206, 94, 197, 28, 386, -29, 207,
		95, 307, 108, -8190, 380, -206, 308, 382, 242, 243,
		381, 387, 309, 310, -8190, -8190, -8190, 197, 28, 386,
		47, 207, -23, 307, 96, -17, 380, 301, 308, 382,
		242, 243, 381, 387, 309, 310, 449, -8190, -8190, 197,
		28, -16, -247, 207, 18, 307, 100, 376, 377, 375,
		308, 382, 242, 243, 381, 387, 309, 310, 443, -8190,
		-8190, -8190, -247, -247, -247, 466, -291, 24, 44, 45,
		-291, -206, -8190, -8190, -8190, 104, -289, -8190, -289, -8190,
		-8190, -8190, -8190, -206, 289, 195, 376, 377, 375, 386,
		-8190, 206, -8190, -8190, -8190, -8190, 380, -246, -8190, -8190,
		-8190, -8191, -8191, -8191, -8191, -8191, 199, 201, 202, 197,
		28, 203, 80, 207, 102, 307, 681, -246, -246, -246,
		308, 382, 242, 243, 381, 387, 309, 310, 386, 376,
		377, 375, 4, 198, 395, 380, 467, 261, 96, -8190,
		395, 374, 373, 592, -215, -8190, -8190, 359, 197, 28,
		-8190, -8190, 207, 172, 307, 20, 300, 604, 405, 308,
		382, 242, 243, 381, 387, 309, 310, -8190, -8190, -8190,
		605, 386, 250, 46, 395, -276, 668, 100, 380, 232,
		301, 286, -289, -275, 50, -8190, -289, -8190, -8190, -8190,
		-274, 379, 378, 22, 185, 390, -251, 391, -244, -250,
		-249, -242, 383, 382, 385, 384, 381, 387, 388, 389,
		-248, 75, 76, 77, 78, 79, -241, 199, -244, -244,
		-244, -242, -242, -242, 1, 5, -240, -244, 80, 9,
		-242, 30, -8190, -8190, -8190, 10, -241, -241, -241, -251,
		25, 29, -250, 48, 234, -241, -240, -240, -240, 49,
		-8190, 219, 220, 221, 192, -240, 193, -249, 249, 367,
		251, 216, 217, 218, 257, 258, 259, -8190, -8190, -8190,
		71, 72, 73, 74, 72, 73, 74, 288, 547, 590,
		448, 460, 444, 0, 565, -29, 103, 542, 342, 348,
		581, 548, 648, 0, 269, 0, 676, 678, 406, 677,
		558, 356, 675, 632, 646, 0, 0, 589, 621, 636,
		671, 361, 0, 593, 0, 301,
	];

	/** Table indexed analogously to self::Action. If self::ActionCheck[self::ActionBase[$state] + $symbol] != $symbol
	 *  then the action is defaulted, i.e. self::ActionDefault[$state] should be used instead. */
	protected const ActionCheck = [
		30, 31, 37, 38, 39, 40, 41, 42, 43, 44,
		45, 22, 69, 0, 71, 2, 46, 47, 2, 30,
		31, 51, 0, 53, 54, 55, 56, 57, 58, 59,
		60, 61, 62, 80, 64, 65, 66, 67, 68, 69,
		0, 71, 72, 27, 28, 75, 76, 74, 78, 79,
		107, 0, 82, 83, 2, 112, 64, 87, 88, 89,
		69, 108, 71, 90, 47, 2, 96, 97, 98, 99,
		100, 3, 4, 5, 22, 105, 84, 85, 86, 88,
		21, 111, 112, 27, 28, 93, 69, 96, 97, 21,
		73, 23, 24, 25, 26, 30, 31, 29, 30, 31,
		32, 33, 34, 35, 36, 37, 38, 39, 40, 41,
		42, 43, 44, 45, 46, 47, 48, 49, 50, 106,
		52, 2, 106, 110, 26, 27, 30, 31, 0, 0,
		2, 63, 7, 8, 9, 10, 11, 12, 13, 14,
		15, 16, 17, 18, 19, 20, 3, 4, 5, 3,
		4, 3, 4, 5, 2, 27, 28, 98, 2, 100,
		64, 65, 106, 3, 21, 69, 23, 24, 72, 21,
		105, 75, 6, 3, 4, 5, 3, 4, 5, 54,
		55, 30, 31, 87, 88, 101, 102, 103, 104, 64,
		69, 21, 71, 45, 21, 6, 23, 24, 25, 26,
		52, 105, 29, 30, 31, 21, 0, 111, 112, 84,
		85, 86, 6, 65, 66, 45, 2, 69, 93, 71,
		2, 70, 52, 21, 76, 77, 78, 79, 80, 81,
		82, 83, 22, 72, 106, 65, 66, 2, 98, 69,
		100, 71, 94, 3, 4, 5, 76, 77, 78, 79,
		80, 81, 82, 83, 106, 27, 28, 3, 4, 5,
		2, 21, 30, 31, 94, 3, 4, 5, 107, 93,
		0, 2, 2, 112, 64, 21, 106, 23, 24, 25,
		26, 105, 98, 29, 100, 45, 93, 3, 4, 5,
		106, 2, 52, 6, 84, 85, 86, 72, 105, 6,
		98, 6, 100, 93, 7, 65, 66, 45, 106, 69,
		7, 71, 7, 88, 52, 105, 76, 77, 78, 79,
		80, 81, 82, 83, 3, 4, 5, 65, 66, 45,
		105, 69, 22, 71, 94, 22, 52, 112, 76, 77,
		78, 79, 80, 81, 82, 83, 106, 30, 31, 65,
		66, 22, 64, 69, 22, 71, 94, 3, 4, 5,
		76, 77, 78, 79, 80, 81, 82, 83, 106, 3,
		4, 5, 84, 85, 86, 21, 106, 22, 94, 95,
		110, 93, 3, 4, 5, 22, 0, 21, 2, 23,
		24, 25, 26, 105, 77, 22, 3, 4, 5, 45,
		21, 30, 23, 24, 25, 26, 52, 64, 29, 30,
		31, 32, 33, 34, 35, 36, 52, 26, 27, 65,
		66, 30, 63, 69, 64, 71, 73, 84, 85, 86,
		76, 77, 78, 79, 80, 81, 82, 83, 45, 3,
		4, 5, 64, 65, 72, 52, 21, 69, 94, 74,
		72, 30, 31, 75, 93, 30, 31, 81, 65, 66,
		46, 47, 69, 93, 71, 107, 88, 90, 94, 76,
		77, 78, 79, 80, 81, 82, 83, 3, 4, 5,
		90, 45, 93, 105, 72, 105, 74, 94, 52, 111,
		112, 106, 106, 105, 107, 21, 110, 23, 24, 25,
		105, 65, 66, 91, 92, 69, 105, 71, 64, 105,
		105, 64, 76, 77, 78, 79, 80, 81, 82, 83,
		105, 46, 47, 48, 49, 50, 64, 52, 84, 85,
		86, 84, 85, 86, 105, 105, 64, 93, 63, 105,
		93, 64, 3, 4, 5, 105, 84, 85, 86, 105,
		105, 64, 105, 105, 64, 93, 84, 85, 86, 105,
		21, 84, 85, 86, 105, 93, 105, 105, 105, 109,
		93, 84, 85, 86, 84, 85, 86, 3, 4, 5,
		42, 43, 44, 45, 43, 44, 45, 106, 106, 106,
		106, 106, 106, -1, 106, 106, 22, 106, 106, 106,
		106, 106, 106, -1, 107, -1, 108, 108, 108, 108,
		108, 108, 108, 108, 108, -1, -1, 110, 110, 110,
		110, 110, -1, 111, -1, 112,
	];

	/** Map of states to their default action */
	protected const ActionDefault = [
		8191, 8191, 8191, 8191, 287, 287, 29, 29, 31, 287,
		8191, 287, 29, 8191, 8191, 29, 8191, 8191, 8191, 8191,
		39, 29, 8191, 8191, 8191, 8191, 8191, 8191, 10, 238,
		238, 238, 8191, 8191, 8191, 8191, 8191, 8191, 8191, 8191,
		8191, 8191, 8191, 8191, 8191, 8191, 8191, 8191, 8191, 8191,
		8191, 8191, 8191, 8191, 8191, 8191, 8191, 8191, 8191, 8191,
		8191, 8191, 8191, 8191, 8191, 8191, 8191, 8191, 8191, 8191,
		8191, 8191, 8191, 8191, 8191, 8191, 8191, 8191, 8191, 8191,
		8191, 8191, 8191, 8191, 8191, 8191, 8191, 8191, 8191, 8191,
		8191, 8191, 8191, 8191, 8191, 8191, 97, 8191, 8191, 8191,
		8191, 29, 8191, 8191, 8191, 8191, 8191, 8191, 8191, 8191,
		8191, 288, 288, 8191, 8191, 8191, 8191, 8191, 8191, 8191,
		8191, 8191, 1, 294, 295, 102, 86, 239, 290, 292,
		89, 90, 101, 43, 189, 44, 56, 141, 143, 177,
		142, 116, 122, 123, 124, 125, 126, 127, 128, 129,
		130, 131, 132, 133, 134, 114, 115, 178, 176, 175,
		139, 140, 144, 147, 113, 145, 146, 164, 165, 162,
		163, 166, 8191, 8191, 8191, 167, 168, 169, 170, 8191,
		8191, 8191, 8191, 8191, 8191, 8191, 8191, 8191, 8191, 8191,
		171, 148, 71, 71, 71, 8191, 8191, 11, 8191, 8191,
		8191, 8191, 8191, 8191, 8191, 8191, 8191, 229, 154, 155,
		157, 229, 194, 228, 173, 8191, 8191, 8191, 8191, 8191,
		8191, 8191, 8191, 8191, 234, 136, 138, 212, 149, 150,
		240, 117, 8191, 8191, 8191, 233, 8191, 302, 240, 240,
		240, 34, 34, 34, 8191, 109, 109, 8191, 57, 34,
		8191, 8191, 8191, 34, 34, 34, 2, 8191, 8191, 8191,
		218, 247, 240, 160, 8191, 151, 152, 153, 59, 8191,
		205, 8191, 28, 193, 8191, 28, 28, 28, 8191, 260,
		261, 262, 28, 28, 28, 28, 196, 36, 73, 28,
		28, 73, 8191, 8191, 28, 8191, 8191, 8191, 8191, 8191,
		8191, 8191, 8191, 223, 8191, 245, 258, 208, 15, 20,
		21, 8191, 223, 244, 242, 158, 159, 161, 181, 182,
		183, 184, 185, 186, 187, 285, 8191, 281, 211, 8191,
		8191, 240, 8191, 8191, 8191, 301, 8191, 240, 156, 219,
		265, 8191, 241, 240, 286, 8191, 8191, 8191, 8191, 8191,
		60, 8191, 8191, 8191, 8191, 8191, 220, 8191, 8191, 8191,
		8191, 8191, 8191, 8191, 55, 8191, 8191, 8191,
	];

	/** Map of non-terminals to a displacement into the self::Goto table. The corresponding goto state for this
	 *  non-terminal/state pair is self::Goto[self::GotoBase[$nonTerminal] + $state] (unless defaulted) */
	protected const GotoBase = [
		0, 0, -2, 0, 0, 0, 103, 0, 153, -141,
		55, -9, 0, -7, 58, 0, 0, 0, 0, 250,
		247, 104, 2, 286, 24, 93, 0, 100, 0, 108,
		-31, 0, 0, -69, 27, -47, 96, 215, 0, 0,
		5, 11, 111, 36, 0, 0, 0, 46, 60, 0,
		-37, 0, 0, 127, 35, 0, 0, 0, 59, 0,
		0, 0, 0, 132, -54, 0, 0, 0, 54, 65,
		-1, 76, 72, 64, 0, 0, -19, -25, 0, -86,
		167, 258, 77, 0, 0,
	];

	/** Table of states to goto after reduction. Indexed according to self::GotoBase comment. */
	protected const Goto = [
		109, 400, 112, 112, 334, 401, 111, 112, 450, 112,
		111, 453, 462, 451, 141, 128, 129, 128, 125, 125,
		117, 139, 131, 125, 489, 110, 489, 127, 127, 127,
		122, 315, 316, 263, 317, 318, 319, 320, 321, 322,
		323, 324, 123, 124, 113, 114, 115, 116, 118, 137,
		138, 140, 157, 160, 161, 162, 163, 165, 166, 167,
		168, 169, 170, 171, 175, 176, 177, 178, 190, 191,
		208, 209, 210, 228, 229, 265, 266, 267, 338, 142,
		143, 144, 145, 146, 147, 148, 149, 150, 151, 152,
		153, 154, 155, 156, 130, 158, 119, 120, 130, 132,
		121, 159, 131, 133, 134, 135, 136, 183, 183, 284,
		285, 183, 183, 452, 183, 183, 672, 673, 674, 187,
		188, 189, 188, 429, 429, 429, 429, 573, 573, 573,
		573, 429, 429, 429, 429, 429, 429, 429, 575, 575,
		575, 575, 575, 575, 575, 575, 575, 575, 575, 575,
		575, 575, 575, 340, 332, 332, 332, 346, 366, 332,
		332, 274, 540, 345, 640, 641, 345, 345, 345, 426,
		433, 435, 437, 434, 436, 438, 264, 635, 635, 635,
		635, 262, 343, 225, 226, 238, 331, 337, 230, 231,
		239, 240, 280, 281, 637, 638, 639, 647, 647, 647,
		647, 647, 647, 312, 312, 591, 591, 591, 591, 591,
		591, 591, 591, 591, 313, 313, 313, 313, 313, 313,
		313, 313, 313, 313, 313, 445, 560, 560, 445, 445,
		446, 633, 633, 614, 614, 614, 614, 614, 614, 614,
		614, 614, 614, 614, 611, 611, 611, 611, 611, 611,
		611, 611, 611, 611, 611, 314, 314, 314, 314, 314,
		314, 314, 314, 314, 314, 314, 347, 468, 354, 650,
		237, 410, 333, 355, 419, 479, 478, 237, 237, 233,
		362, 408, 184, 184, 414, 566, 351, 237, 237, 682,
		325, 325, 666, 472, 422, 325, 244, 325, 473, 237,
		329, 330, 474, 442, 223, 344, 476, 571, 567, 666,
		667, 329, 330, 287, 572, 623, 624, 625, 363, 0,
		0, 667, 0, 0, 0, 339, 245, 246, 328, 328,
		328, 328, 0, 0, 0, 0, 0, 0, 0, 0,
		0, 0, 0, 0, 403, 403, 403, 364, 569, 0,
		0, 0, 403, 0, 0, 0, 403, 403, 403, 0,
		0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
		0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
		0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
		0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
		0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
		0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
		0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
		0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
		0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
		0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
		0, 0, 0, 0, 0, 417, 417,
	];

	/** Table indexed analogously to self::Goto. If self::GotoCheck[self::GotoBase[$nonTerminal] + $state] != $nonTerminal
	 *  then the goto state is defaulted, i.e. self::GotoDefault[$nonTerminal] should be used. */
	protected const GotoCheck = [
		2, 13, 2, 2, 70, 13, 2, 2, 40, 2,
		2, 40, 40, 41, 2, 2, 2, 2, 2, 2,
		2, 2, 2, 2, 2, 2, 2, 2, 2, 2,
		2, 2, 2, 2, 2, 2, 2, 2, 2, 2,
		2, 2, 2, 2, 2, 2, 2, 2, 2, 2,
		2, 2, 2, 2, 2, 2, 2, 2, 2, 2,
		2, 2, 2, 2, 2, 2, 2, 2, 2, 2,
		2, 2, 2, 2, 2, 2, 2, 2, 2, 2,
		2, 2, 2, 2, 2, 2, 2, 2, 2, 2,
		2, 2, 2, 2, 2, 2, 2, 2, 2, 2,
		2, 2, 2, 2, 2, 2, 2, 6, 6, 36,
		36, 6, 6, 42, 6, 6, 9, 9, 9, 6,
		6, 6, 6, 33, 33, 33, 33, 33, 33, 33,
		33, 33, 33, 33, 33, 33, 33, 33, 64, 64,
		64, 64, 64, 64, 64, 64, 64, 64, 64, 64,
		64, 64, 64, 76, 8, 8, 8, 53, 53, 8,
		8, 50, 50, 8, 79, 79, 8, 8, 8, 30,
		30, 30, 30, 30, 30, 30, 80, 76, 76, 76,
		76, 22, 22, 22, 22, 22, 22, 22, 22, 22,
		22, 22, 77, 77, 77, 77, 77, 76, 76, 76,
		76, 76, 76, 24, 24, 24, 24, 24, 24, 24,
		24, 24, 24, 24, 54, 54, 54, 54, 54, 54,
		54, 54, 54, 54, 54, 37, 35, 35, 37, 37,
		37, 76, 76, 68, 68, 68, 68, 68, 68, 68,
		68, 68, 68, 68, 69, 69, 69, 69, 69, 69,
		69, 69, 69, 69, 69, 71, 71, 71, 71, 71,
		71, 71, 71, 71, 71, 71, 11, 11, 11, 81,
		10, 19, 20, 11, 11, 11, 11, 10, 10, 73,
		11, 11, 6, 6, 21, 11, 25, 10, 10, 10,
		23, 23, 82, 47, 27, 23, 73, 23, 47, 10,
		14, 14, 47, 29, 72, 23, 48, 43, 58, 82,
		82, 14, 14, 14, 43, 43, 43, 43, 34, -1,
		-1, 82, -1, -1, -1, 8, 8, 8, 63, 63,
		63, 63, -1, -1, -1, -1, -1, -1, -1, -1,
		-1, -1, -1, -1, 6, 6, 6, 10, 10, -1,
		-1, -1, 6, -1, -1, -1, 6, 6, 6, -1,
		-1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
		-1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
		-1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
		-1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
		-1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
		-1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
		-1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
		-1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
		-1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
		-1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
		-1, -1, -1, -1, -1, 23, 23,
	];

	/** Map of non-terminals to the default state to goto after their reduction */
	protected const GotoDefault = [
		-8192, 295, 126, 256, 371, 372, 186, 392, 311, 645,
		631, 398, 272, 652, 293, 292, 487, 357, 290, 409,
		358, 413, 164, 302, 303, 349, 283, 421, 255, 440,
		248, 268, 350, 270, 360, 480, 282, 463, 488, 275,
		461, 455, 464, 570, 277, 276, 247, 471, 475, 486,
		273, 556, 291, 341, 212, 563, 365, 294, 568, 622,
		271, 304, 278, 582, 260, 227, 305, -8192, 235, 224,
		326, 213, 222, 665, 236, 306, 620, 279, 627, 634,
		327, 651, 664, 336, 353,
	];

	/** Map of rules to the non-terminal on their left-hand side, i.e. the non-terminal to use for
	 *  determining the state to goto after reduction. */
	protected const RuleToNonTerminal = [
		0, 1, 1, 1, 1, 6, 6, 7, 7, 7,
		7, 7, 7, 7, 7, 7, 7, 7, 7, 7,
		7, 7, 8, 8, 8, 9, 9, 10, 11, 11,
		4, 4, 12, 12, 14, 14, 15, 15, 16, 17,
		17, 18, 18, 19, 19, 5, 5, 21, 21, 21,
		21, 25, 25, 26, 26, 27, 27, 29, 29, 29,
		29, 30, 30, 30, 30, 31, 31, 31, 31, 32,
		32, 28, 28, 34, 34, 35, 35, 36, 36, 38,
		38, 38, 39, 39, 39, 39, 37, 37, 41, 41,
		41, 43, 43, 44, 44, 40, 40, 42, 42, 42,
		20, 45, 45, 46, 46, 3, 3, 47, 47, 48,
		48, 48, 48, 2, 2, 2, 2, 2, 2, 2,
		2, 2, 2, 2, 2, 2, 2, 2, 2, 2,
		2, 2, 2, 2, 2, 2, 2, 2, 2, 2,
		2, 2, 2, 2, 2, 2, 2, 2, 2, 2,
		2, 2, 2, 2, 2, 2, 2, 2, 2, 2,
		2, 2, 2, 2, 2, 2, 2, 2, 2, 2,
		2, 2, 2, 2, 2, 2, 2, 2, 2, 2,
		2, 2, 2, 2, 2, 2, 2, 2, 2, 2,
		2, 2, 54, 55, 49, 49, 52, 52, 56, 57,
		57, 58, 59, 59, 59, 63, 33, 33, 64, 64,
		64, 50, 50, 50, 61, 61, 67, 67, 68, 69,
		69, 24, 71, 71, 71, 71, 51, 51, 51, 51,
		51, 51, 51, 51, 51, 51, 51, 51, 53, 53,
		66, 66, 66, 66, 66, 74, 74, 74, 60, 60,
		60, 60, 75, 75, 75, 75, 75, 75, 22, 22,
		22, 22, 22, 76, 76, 79, 78, 65, 65, 65,
		65, 65, 65, 65, 62, 62, 62, 77, 77, 77,
		23, 70, 80, 80, 81, 81, 81, 81, 13, 13,
		13, 13, 13, 13, 13, 13, 72, 72, 72, 72,
		73, 83, 82, 82, 82, 82, 82, 82, 82, 82, 82, 84, 84, 84, 84,
	];

	/** Map of rules to the length of their right-hand side, which is the number of elements that have to
	 *  be popped from the stack(s) on reduction. */
	protected const RuleToLength = [
		1, 2, 2, 2, 2, 1, 1, 1, 1, 1,
		1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
		1, 1, 1, 1, 1, 1, 1, 1, 0, 1,
		2, 0, 1, 3, 0, 1, 0, 1, 7, 0,
		2, 1, 3, 3, 4, 1, 3, 1, 2, 1,
		1, 2, 0, 1, 3, 4, 6, 1, 2, 1,
		1, 1, 1, 1, 1, 3, 3, 3, 3, 3,
		3, 0, 1, 0, 2, 2, 4, 1, 3, 2,
		4, 4, 3, 1, 1, 3, 1, 1, 2, 2,
		3, 2, 4, 1, 3, 1, 1, 1, 1, 3,
		2, 3, 1, 2, 2, 1, 2, 3, 3, 0,
		3, 3, 1, 1, 3, 3, 3, 4, 1, 1,
		2, 2, 3, 3, 3, 3, 3, 3, 3, 3,
		3, 3, 3, 3, 3, 2, 2, 2, 2, 3,
		3, 3, 3, 3, 3, 3, 3, 3, 3, 3,
		3, 3, 3, 3, 3, 3, 3, 3, 2, 2,
		2, 2, 3, 3, 3, 3, 3, 3, 3, 3,
		3, 3, 3, 3, 3, 5, 4, 3, 3, 4,
		4, 2, 2, 2, 2, 2, 2, 2, 1, 8,
		12, 9, 3, 2, 1, 1, 0, 4, 2, 1,
		3, 2, 2, 2, 4, 1, 1, 1, 1, 1,
		1, 1, 1, 3, 1, 1, 0, 1, 1, 3,
		5, 3, 4, 1, 1, 3, 1, 1, 1, 1,
		1, 1, 1, 1, 1, 3, 2, 3, 0, 1,
		1, 3, 1, 1, 1, 1, 1, 1, 1, 3,
		1, 1, 1, 4, 1, 4, 4, 4, 1, 1,
		3, 3, 3, 1, 4, 1, 3, 1, 4, 3,
		3, 3, 3, 3, 1, 3, 1, 1, 3, 1,
		4, 1, 3, 1, 1, 1, 3, 0, 1, 2,
		3, 4, 3, 4, 2, 2, 2, 2, 1, 2,
		1, 1, 1, 4, 3, 3, 3, 3, 3, 6, 3, 1, 1, 2, 1,
	];

	/** Temporary value containing the result of last semantic action (reduction) */
	protected mixed $semValue = null;

	/** Semantic value stack (contains values of tokens and semantic action results) */
	protected array $semStack;

	/** @var array<int, ?Token> Start attribute stack */
	protected array $startTokenStack;

	/** @var array<int, ?Token> End attribute stack */
	protected array $endTokenStack;


	protected function reduce(int $rule, int $pos): void
	{
		switch ($rule) {
			case 0:
			case 1:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 19:
			case 20:
			case 21:
			case 28:
			case 29:
			case 57:
			case 72:
			case 74:
			case 87:
			case 95:
			case 96:
			case 112:
			case 113:
			case 118:
			case 119:
			case 188:
			case 194:
			case 195:
			case 205:
			case 207:
			case 211:
			case 212:
			case 214:
			case 215:
			case 217:
			case 228:
			case 233:
			case 234:
			case 239:
			case 240:
			case 242:
			case 243:
			case 244:
			case 245:
			case 246:
			case 248:
			case 250:
			case 251:
			case 252:
			case 254:
			case 258:
			case 259:
			case 263:
			case 267:
			case 274:
			case 276:
			case 277:
			case 279:
			case 284:
			case 302:
			case 314:
				$this->semValue = $this->semStack[$pos];
				break;
			case 2:
				$this->semValue = new Node\ModifierNode($this->semStack[$pos], position: $this->startPos($pos), end: $this->endPos($pos));
				break;
			case 3:
				$this->semValue = new Expression\ArrayNode(
					$this->semStack[$pos],
					position: $this->startPos($pos),
					end: $this->endPos($pos),
				);
				break;
			case 22:
			case 23:
			case 24:
			case 25:
			case 26:
			case 62:
			case 63:
			case 64:
				$this->semValue = new Node\IdentifierNode($this->semStack[$pos], $this->startPos($pos), $this->endPos($pos));
				break;
			case 27:
				$this->semValue = new Expression\VariableNode(substr($this->semStack[$pos], 1), $this->startPos($pos), $this->endPos($pos));
				break;
			case 30:
			case 40:
			case 51:
			case 100:
			case 110:
			case 111:
			case 173:
			case 174:
			case 197:
			case 198:
			case 213:
			case 241:
			case 249:
			case 275:
			case 278:
			case 310:
				$this->semValue = $this->semStack[$pos - 1];
				break;
			case 31:
			case 39:
			case 52:
			case 75:
			case 79:
			case 91:
			case 109:
			case 196:
			case 216:
				$this->semValue = [];
				break;
			case 32:
			case 41:
			case 53:
			case 77:
			case 83:
			case 84:
			case 93:
			case 102:
			case 105:
			case 199:
			case 283:
			case 298:
				$this->semValue = [$this->semStack[$pos]];
				break;
			case 33:
			case 42:
			case 54:
			case 66:
			case 68:
			case 70:
			case 78:
			case 85:
			case 94:
			case 101:
			case 200:
			case 282:
				$this->semStack[$pos - 2][] = $this->semStack[$pos];
				$this->semValue = $this->semStack[$pos - 2];
				break;
			case 34:
			case 36:
				$this->semValue = false;
				break;
			case 35:
			case 37:
				$this->semValue = true;
				break;
			case 38:
				$this->semValue = new Expression\MatchNode(
					$this->semStack[$pos - 4],
					$this->semStack[$pos - 1],
					$this->startPos($pos - 6),
					$this->endPos($pos),
				);
				break;
			case 43:
				$this->semValue = new Node\MatchArmNode(
					$this->semStack[$pos - 2],
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 44:
				$this->semValue = new Node\MatchArmNode(null, $this->semStack[$pos], $this->startPos($pos - 3), $this->endPos($pos));
				break;
			case 45:
				$this->semValue = [null, ...$this->semStack[$pos]];
				break;
			case 46:
				$this->semValue = [$this->semStack[$pos - 2], ...$this->semStack[$pos]];
				break;
			case 47:
			case 49:
				$this->semValue = [$this->semStack[$pos], false];
				break;
			case 48:
				$this->semValue = [$this->semStack[$pos], true];
				break;
			case 50:
				$this->semValue = [$this->convertArrayToList($this->semStack[$pos]), false];
				break;
			case 55:
				$this->semValue = new Node\ParameterNode(
					$this->semStack[$pos],
					null,
					$this->semStack[$pos - 3],
					$this->semStack[$pos - 2],
					$this->semStack[$pos - 1],
					$this->startPos($pos - 3),
					$this->endPos($pos),
				);
				break;
			case 56:
				$this->semValue = new Node\ParameterNode(
					$this->semStack[$pos - 2],
					$this->semStack[$pos],
					$this->semStack[$pos - 5],
					$this->semStack[$pos - 4],
					$this->semStack[$pos - 3],
					$this->startPos($pos - 5),
					$this->endPos($pos),
				);
				break;
			case 58:
				$this->semValue = new Node\NullableTypeNode($this->semStack[$pos], $this->startPos($pos - 1), $this->endPos($pos));
				break;
			case 59:
				$this->semValue = new Node\UnionTypeNode($this->semStack[$pos], $this->startPos($pos), $this->endPos($pos));
				break;
			case 60:
				$this->semValue = new Node\IntersectionTypeNode($this->semStack[$pos], $this->startPos($pos), $this->endPos($pos));
				break;
			case 61:
				$this->semValue = TagParser::handleBuiltinTypes($this->semStack[$pos]);
				break;
			case 65:
			case 67:
			case 69:
				$this->semValue = [$this->semStack[$pos - 2], $this->semStack[$pos]];
				break;
			case 71:
			case 73:
			case 238:
				$this->semValue = null;
				break;
			case 76:
				$this->semValue = $this->semStack[$pos - 2];
				break;
			case 80:
			case 92:
				$this->semValue = $this->checkPlaceholders($this->semStack[$pos - 2]);
				break;
			case 81:
				$this->semValue = [new Node\ArgumentNode(
					$this->semStack[$pos - 2],
					false,
					false,
					null,
					$this->startPos($pos - 3),
					$this->endPos($pos),
				)];
				break;
			case 82:
				$this->semValue = [
					new Node\ArgumentNode($this->semStack[$pos - 2], false, false, null, $this->startPos($pos - 2), $this->endPos($pos)),
					$this->semStack[$pos],
				];
				break;
			case 86:
				$this->semValue = new Node\ArgumentNode(
					$this->semStack[$pos],
					false,
					false,
					null,
					$this->startPos($pos),
					$this->endPos($pos),
				);
				break;
			case 88:
				$this->semValue = new Node\ArgumentNode(
					$this->semStack[$pos],
					true,
					false,
					null,
					$this->startPos($pos - 1),
					$this->endPos($pos),
				);
				break;
			case 89:
				$this->semValue = new Node\ArgumentNode(
					$this->semStack[$pos],
					false,
					true,
					null,
					$this->startPos($pos - 1),
					$this->endPos($pos),
				);
				break;
			case 90:
				$this->semValue = new Node\ArgumentNode(
					$this->semStack[$pos],
					false,
					false,
					$this->semStack[$pos - 2],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 97:
				$this->semValue = new Node\VariadicPlaceholderNode($this->startPos($pos), $this->endPos($pos));
				break;
			case 98:
				$this->semValue = new Node\ArgumentPlaceholderNode(null, $this->startPos($pos), $this->endPos($pos));
				break;
			case 99:
				$this->semValue = new Node\ArgumentPlaceholderNode(
					$this->semStack[$pos - 2],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 103:
			case 104:
				$this->semValue = new Expression\FilterCallNode(
					$this->semStack[$pos - 1],
					$this->semStack[$pos],
					$this->startPos($pos - 1),
					$this->endPos($pos),
				);
				break;
			case 106:
			case 296:
			case 297:
				$this->semStack[$pos - 1][] = $this->semStack[$pos];
				$this->semValue = $this->semStack[$pos - 1];
				break;
			case 107:
				$this->semValue = new Node\FilterNode(
					$this->semStack[$pos - 1],
					$this->semStack[$pos],
					false,
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 108:
				$this->semValue = new Node\FilterNode(
					$this->semStack[$pos - 1],
					$this->semStack[$pos],
					true,
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 114:
			case 116:
				$this->semValue = new Expression\AssignNode(
					$this->semStack[$pos - 2],
					$this->semStack[$pos],
					false,
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 115:
				$this->semValue = new Expression\AssignNode(
					$this->convertArrayToList($this->semStack[$pos - 2]),
					$this->semStack[$pos],
					false,
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 117:
				$this->semValue = new Expression\AssignNode(
					$this->semStack[$pos - 3],
					$this->semStack[$pos],
					true,
					$this->startPos($pos - 3),
					$this->endPos($pos),
				);
				break;
			case 120:
				$this->semValue = new Expression\FunctionCallNode(
					new Node\NameNode($this->semStack[$pos - 1], Node\NameNode::KindNormal, $this->startPos($pos - 1), $this->endPos($pos)),
					$this->semStack[$pos],
					$this->startPos($pos - 1),
					$this->endPos($pos),
				);
				break;
			case 121:
				$this->semValue = new Expression\CloneNode($this->semStack[$pos], $this->startPos($pos - 1), $this->endPos($pos));
				break;
			case 122:
				$this->semValue = new Expression\AssignOpNode(
					$this->semStack[$pos - 2],
					'+',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 123:
				$this->semValue = new Expression\AssignOpNode(
					$this->semStack[$pos - 2],
					'-',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 124:
				$this->semValue = new Expression\AssignOpNode(
					$this->semStack[$pos - 2],
					'*',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 125:
				$this->semValue = new Expression\AssignOpNode(
					$this->semStack[$pos - 2],
					'/',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 126:
				$this->semValue = new Expression\AssignOpNode(
					$this->semStack[$pos - 2],
					'.',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 127:
				$this->semValue = new Expression\AssignOpNode(
					$this->semStack[$pos - 2],
					'%',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 128:
				$this->semValue = new Expression\AssignOpNode(
					$this->semStack[$pos - 2],
					'&',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 129:
				$this->semValue = new Expression\AssignOpNode(
					$this->semStack[$pos - 2],
					'|',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 130:
				$this->semValue = new Expression\AssignOpNode(
					$this->semStack[$pos - 2],
					'^',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 131:
				$this->semValue = new Expression\AssignOpNode(
					$this->semStack[$pos - 2],
					'<<',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 132:
				$this->semValue = new Expression\AssignOpNode(
					$this->semStack[$pos - 2],
					'>>',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 133:
				$this->semValue = new Expression\AssignOpNode(
					$this->semStack[$pos - 2],
					'**',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 134:
				$this->semValue = new Expression\AssignOpNode(
					$this->semStack[$pos - 2],
					'??',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 135:
				$this->semValue = new Expression\PostOpNode(
					$this->semStack[$pos - 1],
					'++',
					$this->startPos($pos - 1),
					$this->endPos($pos),
				);
				break;
			case 136:
				$this->semValue = new Expression\PreOpNode($this->semStack[$pos], '++', $this->startPos($pos - 1), $this->endPos($pos));
				break;
			case 137:
				$this->semValue = new Expression\PostOpNode(
					$this->semStack[$pos - 1],
					'--',
					$this->startPos($pos - 1),
					$this->endPos($pos),
				);
				break;
			case 138:
				$this->semValue = new Expression\PreOpNode($this->semStack[$pos], '--', $this->startPos($pos - 1), $this->endPos($pos));
				break;
			case 139:
				$this->semValue = new Expression\BinaryOpNode(
					$this->semStack[$pos - 2],
					'||',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 140:
				$this->semValue = new Expression\BinaryOpNode(
					$this->semStack[$pos - 2],
					'&&',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 141:
				$this->semValue = new Expression\BinaryOpNode(
					$this->semStack[$pos - 2],
					'or',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 142:
				$this->semValue = new Expression\BinaryOpNode(
					$this->semStack[$pos - 2],
					'and',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 143:
				$this->semValue = new Expression\BinaryOpNode(
					$this->semStack[$pos - 2],
					'xor',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 144:
				$this->semValue = new Expression\BinaryOpNode(
					$this->semStack[$pos - 2],
					'|',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 145:
			case 146:
				$this->semValue = new Expression\BinaryOpNode(
					$this->semStack[$pos - 2],
					'&',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 147:
				$this->semValue = new Expression\BinaryOpNode(
					$this->semStack[$pos - 2],
					'^',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 148:
				$this->semValue = new Expression\BinaryOpNode(
					$this->semStack[$pos - 2],
					'.',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 149:
				$this->semValue = new Expression\BinaryOpNode(
					$this->semStack[$pos - 2],
					'+',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 150:
				$this->semValue = new Expression\BinaryOpNode(
					$this->semStack[$pos - 2],
					'-',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 151:
				$this->semValue = new Expression\BinaryOpNode(
					$this->semStack[$pos - 2],
					'*',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 152:
				$this->semValue = new Expression\BinaryOpNode(
					$this->semStack[$pos - 2],
					'/',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 153:
				$this->semValue = new Expression\BinaryOpNode(
					$this->semStack[$pos - 2],
					'%',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 154:
				$this->semValue = new Expression\BinaryOpNode(
					$this->semStack[$pos - 2],
					'<<',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 155:
				$this->semValue = new Expression\BinaryOpNode(
					$this->semStack[$pos - 2],
					'>>',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 156:
				$this->semValue = new Expression\BinaryOpNode(
					$this->semStack[$pos - 2],
					'**',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 157:
				$this->semValue = new Expression\InNode(
					$this->semStack[$pos - 2],
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 158:
				$this->semValue = new Expression\UnaryOpNode($this->semStack[$pos], '+', $this->startPos($pos - 1), $this->endPos($pos));
				break;
			case 159:
				$this->semValue = new Expression\UnaryOpNode($this->semStack[$pos], '-', $this->startPos($pos - 1), $this->endPos($pos));
				break;
			case 160:
				$this->semValue = new Expression\UnaryOpNode($this->semStack[$pos], '!', $this->startPos($pos - 1), $this->endPos($pos));
				break;
			case 161:
				$this->semValue = new Expression\UnaryOpNode($this->semStack[$pos], '~', $this->startPos($pos - 1), $this->endPos($pos));
				break;
			case 162:
				$this->semValue = new Expression\BinaryOpNode(
					$this->semStack[$pos - 2],
					'===',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 163:
				$this->semValue = new Expression\BinaryOpNode(
					$this->semStack[$pos - 2],
					'!==',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 164:
				$this->semValue = new Expression\BinaryOpNode(
					$this->semStack[$pos - 2],
					'==',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 165:
				$this->semValue = new Expression\BinaryOpNode(
					$this->semStack[$pos - 2],
					'!=',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 166:
				$this->semValue = new Expression\BinaryOpNode(
					$this->semStack[$pos - 2],
					'<=>',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 167:
				$this->semValue = new Expression\BinaryOpNode(
					$this->semStack[$pos - 2],
					'<',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 168:
				$this->semValue = new Expression\BinaryOpNode(
					$this->semStack[$pos - 2],
					'<=',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 169:
				$this->semValue = new Expression\BinaryOpNode(
					$this->semStack[$pos - 2],
					'>',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 170:
				$this->semValue = new Expression\BinaryOpNode(
					$this->semStack[$pos - 2],
					'>=',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 171:
				$this->semValue = new Expression\BinaryOpNode(
					$this->semStack[$pos - 2],
					'|>',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 172:
				$this->semValue = new Expression\InstanceofNode(
					$this->semStack[$pos - 2],
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 175:
				$this->semValue = new Expression\TernaryNode(
					$this->semStack[$pos - 4],
					$this->semStack[$pos - 2],
					$this->semStack[$pos],
					$this->startPos($pos - 4),
					$this->endPos($pos),
				);
				break;
			case 176:
				$this->semValue = new Expression\TernaryNode(
					$this->semStack[$pos - 3],
					null,
					$this->semStack[$pos],
					$this->startPos($pos - 3),
					$this->endPos($pos),
				);
				break;
			case 177:
				$this->semValue = new Expression\TernaryNode(
					$this->semStack[$pos - 2],
					$this->semStack[$pos],
					null,
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 178:
				$this->semValue = new Expression\BinaryOpNode(
					$this->semStack[$pos - 2],
					'??',
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 179:
				$this->semValue = new Expression\IssetNode($this->semStack[$pos - 1], $this->startPos($pos - 3), $this->endPos($pos));
				break;
			case 180:
				$this->semValue = new Expression\EmptyNode($this->semStack[$pos - 1], $this->startPos($pos - 3), $this->endPos($pos));
				break;
			case 181:
				$this->semValue = new Expression\CastNode('int', $this->semStack[$pos], $this->startPos($pos - 1), $this->endPos($pos));
				break;
			case 182:
				$this->semValue = new Expression\CastNode('float', $this->semStack[$pos], $this->startPos($pos - 1), $this->endPos($pos));
				break;
			case 183:
				$this->semValue = new Expression\CastNode('string', $this->semStack[$pos], $this->startPos($pos - 1), $this->endPos($pos));
				break;
			case 184:
				$this->semValue = new Expression\CastNode('array', $this->semStack[$pos], $this->startPos($pos - 1), $this->endPos($pos));
				break;
			case 185:
				$this->semValue = new Expression\CastNode('object', $this->semStack[$pos], $this->startPos($pos - 1), $this->endPos($pos));
				break;
			case 186:
				$this->semValue = new Expression\CastNode('bool', $this->semStack[$pos], $this->startPos($pos - 1), $this->endPos($pos));
				break;
			case 187:
				$this->semValue = new Expression\UnaryOpNode($this->semStack[$pos], '@', $this->startPos($pos - 1), $this->endPos($pos));
				break;
			case 189:
				$this->semValue = new Expression\ClosureNode(
					(bool) $this->semStack[$pos - 6],
					$this->semStack[$pos - 4],
					[],
					$this->semStack[$pos - 2],
					$this->semStack[$pos],
					$this->startPos($pos - 7),
					$this->endPos($pos),
				);
				break;
			case 190:
				$this->semValue = new Expression\ClosureNode(
					(bool) $this->semStack[$pos - 10],
					$this->semStack[$pos - 8],
					$this->semStack[$pos - 6],
					$this->semStack[$pos - 5],
					$this->semStack[$pos - 2],
					$this->startPos($pos - 11),
					$this->endPos($pos),
				);
				break;
			case 191:
				$this->semValue = new Expression\ClosureNode(
					(bool) $this->semStack[$pos - 7],
					$this->semStack[$pos - 5],
					$this->semStack[$pos - 3],
					$this->semStack[$pos - 2],
					null,
					$this->startPos($pos - 8),
					$this->endPos($pos),
				);
				break;
			case 192:
				$this->semValue = new Expression\NewNode(
					$this->semStack[$pos - 1],
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 193:
				$this->semValue = new Expression\NewNode($this->semStack[$pos], [], $this->startPos($pos - 1), $this->endPos($pos));
				break;
			case 201:
				$this->semValue = new Node\ClosureUseNode(
					$this->semStack[$pos],
					$this->semStack[$pos - 1],
					$this->startPos($pos - 1),
					$this->endPos($pos),
				);
				break;
			case 202:
			case 203:
				$this->semValue = $this->checkFunctionName(new Expression\FunctionCallNode(
					$this->semStack[$pos - 1],
					$this->semStack[$pos],
					$this->startPos($pos - 1),
					$this->endPos($pos),
				));
				break;
			case 204:
				$this->semValue = new Expression\StaticMethodCallNode(
					$this->semStack[$pos - 3],
					$this->semStack[$pos - 1],
					$this->semStack[$pos],
					$this->startPos($pos - 3),
					$this->endPos($pos),
				);
				break;
			case 206:
			case 208:
			case 209:
				$this->semValue = new Node\NameNode(
					$this->semStack[$pos],
					Node\NameNode::KindNormal,
					$this->startPos($pos),
					$this->endPos($pos),
				);
				break;
			case 210:
				$this->semValue = new Node\NameNode(
					$this->semStack[$pos],
					Node\NameNode::KindFullyQualified,
					$this->startPos($pos),
					$this->endPos($pos),
				);
				break;
			case 218:
				$this->semValue = new Expression\ConstantFetchNode(
					$this->warnUnqualifiedConstant($this->semStack[$pos]),
					$this->startPos($pos),
					$this->endPos($pos),
				);
				break;
			case 219:
				$this->semValue = new Expression\ClassConstantFetchNode(
					$this->semStack[$pos - 2],
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 220:
				$this->semValue = new Expression\ClassConstantFetchNode(
					$this->semStack[$pos - 4],
					$this->semStack[$pos - 1],
					$this->startPos($pos - 4),
					$this->endPos($pos),
				);
				break;
			case 221:
				$this->semValue = new Expression\ArrayNode($this->semStack[$pos - 1], $this->startPos($pos - 2), $this->endPos($pos));
				break;
			case 222:
				$this->semValue = new Expression\ArrayNode($this->semStack[$pos - 1], $this->startPos($pos - 3), $this->endPos($pos));
				break;
			case 223:
				$this->semValue = $this->semStack[$pos];
				$this->shortArrays->offsetSet($this->semValue);
				break;
			case 224:
				$this->semValue = Scalar\StringNode::parse($this->semStack[$pos], $this->startPos($pos), $this->endPos($pos));
				break;
			case 225:
				$this->semValue = Scalar\InterpolatedStringNode::parse(
					$this->semStack[$pos - 1],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 226:
				$this->semValue = Scalar\IntegerNode::parse($this->semStack[$pos], $this->startPos($pos), $this->endPos($pos));
				break;
			case 227:
				$this->semValue = Scalar\FloatNode::parse($this->semStack[$pos], $this->startPos($pos), $this->endPos($pos));
				break;
			case 229:
			case 311:
				$this->semValue = new Scalar\StringNode($this->semStack[$pos], $this->startPos($pos), $this->endPos($pos));
				break;
			case 230:
				$this->semValue = new Scalar\BooleanNode(true, $this->startPos($pos), $this->endPos($pos));
				break;
			case 231:
				$this->semValue = new Scalar\BooleanNode(false, $this->startPos($pos), $this->endPos($pos));
				break;
			case 232:
				$this->semValue = new Scalar\NullNode($this->startPos($pos), $this->endPos($pos));
				break;
			case 235:
				$this->semValue = $this->parseDocString(
					$this->semStack[$pos - 2],
					[$this->semStack[$pos - 1]],
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
					$this->startTokenStack[$pos]->position,
				);
				break;
			case 236:
				$this->semValue = $this->parseDocString(
					$this->semStack[$pos - 1],
					[],
					$this->semStack[$pos],
					$this->startPos($pos - 1),
					$this->endPos($pos),
					$this->startTokenStack[$pos]->position,
				);
				break;
			case 237:
				$this->semValue = $this->parseDocString(
					$this->semStack[$pos - 2],
					$this->semStack[$pos - 1],
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
					$this->startTokenStack[$pos]->position,
				);
				break;
			case 247:
				$this->semValue = new Expression\ConstantFetchNode(
					new Node\NameNode($this->semStack[$pos], Node\NameNode::KindNormal, $this->startPos($pos), $this->endPos($pos)),
					$this->startPos($pos),
					$this->endPos($pos),
				);
				break;
			case 253:
			case 268:
			case 303:
				$this->semValue = new Expression\ArrayAccessNode(
					$this->semStack[$pos - 3],
					$this->semStack[$pos - 1],
					$this->startPos($pos - 3),
					$this->endPos($pos),
				);
				break;
			case 255:
				$this->semValue = new Expression\MethodCallNode(
					$this->semStack[$pos - 3],
					$this->semStack[$pos - 1],
					$this->semStack[$pos],
					false,
					$this->startPos($pos - 3),
					$this->endPos($pos),
				);
				break;
			case 256:
				$this->semValue = new Expression\MethodCallNode(
					$this->semStack[$pos - 3],
					$this->semStack[$pos - 1],
					$this->semStack[$pos],
					true,
					$this->startPos($pos - 3),
					$this->endPos($pos),
				);
				break;
			case 257:
				$this->semValue = new Expression\MethodCallNode(
					new Expression\BinaryOpNode(
						$this->semStack[$pos - 3],
						'??',
						new Scalar\NullNode($this->startPos($pos - 3), $this->endPos($pos)),
						$this->startPos($pos - 3),
						$this->endPos($pos),
					),
					$this->semStack[$pos - 1],
					$this->semStack[$pos],
					true,
					$this->startPos($pos - 3),
					$this->endPos($pos),
				);
				trigger_error('Latte: undefined-nullsafe operator ??-> is deprecated, used ' . $this->startPos($pos - 3));
				break;
			case 260:
			case 269:
			case 304:
				$this->semValue = new Expression\PropertyFetchNode(
					$this->semStack[$pos - 2],
					$this->semStack[$pos],
					false,
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 261:
			case 270:
			case 305:
				$this->semValue = new Expression\PropertyFetchNode(
					$this->semStack[$pos - 2],
					$this->semStack[$pos],
					true,
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 262:
			case 271:
			case 306:
				$this->semValue = new Expression\PropertyFetchNode(
					new Expression\BinaryOpNode(
						$this->semStack[$pos - 2],
						'??',
						new Scalar\NullNode($this->startPos($pos - 2), $this->endPos($pos)),
						$this->startPos($pos - 2),
						$this->endPos($pos),
					),
					$this->semStack[$pos],
					true,
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				trigger_error('Latte: undefined-nullsafe operator ??-> is deprecated, used ' . $this->startPos($pos - 2));
				break;
			case 264:
				$this->semValue = new Expression\VariableNode($this->semStack[$pos - 1], $this->startPos($pos - 3), $this->endPos($pos));
				break;
			case 265:
				$var = $this->semStack[$pos]->name;
				$this->semValue = is_string($var) ? new Node\VarLikeIdentifierNode($var, $this->startPos($pos), $this->endPos($pos)) : $var;
				break;
			case 266:
			case 272:
			case 273:
				$this->semValue = new Expression\StaticPropertyFetchNode(
					$this->semStack[$pos - 2],
					$this->semStack[$pos],
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 280:
				$this->semValue = $this->convertArrayToList(new Expression\ArrayNode(
					$this->semStack[$pos - 1],
					$this->startPos($pos - 3),
					$this->endPos($pos),
				));
				break;
			case 281:
				$this->semValue = $this->semStack[$pos];
				$end = count($this->semValue) - 1;
				if ($this->semValue[$end]->value instanceof Expression\TemporaryNode && !$this->semValue[$end]->value->value) {
					array_pop($this->semValue);
				}
				break;
			case 285:
				$this->semValue = new Node\ArrayItemNode(
					new Expression\TemporaryNode($this->semStack[$pos], $this->startPos($pos), $this->endPos($pos)),
					null,
					false,
					false,
					$this->startPos($pos),
					$this->endPos($pos),
				);
				break;
			case 286:
				$this->semValue = new Node\ArrayItemNode(
					new Expression\TemporaryNode($this->semStack[$pos], $this->startPos($pos - 2), $this->endPos($pos)),
					$this->semStack[$pos - 2],
					false,
					false,
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 287:
				$this->semValue = new Node\ArrayItemNode(
					new Expression\TemporaryNode(null),
					null,
					false,
					false,
					$this->startPos($pos),
					$this->endPos($pos),
				);
				break;
			case 288:
				$this->semValue = new Node\ArrayItemNode(
					$this->semStack[$pos],
					null,
					false,
					false,
					$this->startPos($pos),
					$this->endPos($pos),
				);
				break;
			case 289:
				$this->semValue = new Node\ArrayItemNode(
					$this->semStack[$pos],
					null,
					true,
					false,
					$this->startPos($pos - 1),
					$this->endPos($pos),
				);
				break;
			case 290:
			case 292:
				$this->semValue = new Node\ArrayItemNode(
					$this->semStack[$pos],
					$this->semStack[$pos - 2],
					false,
					false,
					$this->startPos($pos - 2),
					$this->endPos($pos),
				);
				break;
			case 291:
			case 293:
				$this->semValue = new Node\ArrayItemNode(
					$this->semStack[$pos],
					$this->semStack[$pos - 3],
					true,
					false,
					$this->startPos($pos - 3),
					$this->endPos($pos),
				);
				break;
			case 294:
			case 295:
				$this->semValue = new Node\ArrayItemNode(
					$this->semStack[$pos],
					null,
					false,
					true,
					$this->startPos($pos - 1),
					$this->endPos($pos),
				);
				break;
			case 299:
				$this->semValue = [$this->semStack[$pos - 1], $this->semStack[$pos]];
				break;
			case 300:
				$this->semValue = new Node\InterpolatedStringPartNode($this->semStack[$pos], $this->startPos($pos), $this->endPos($pos));
				break;
			case 301:
				$this->semValue = new Expression\VariableNode($this->semStack[$pos], $this->startPos($pos), $this->endPos($pos));
				break;
			case 307:
			case 308:
				$this->semValue = new Expression\VariableNode($this->semStack[$pos - 1], $this->startPos($pos - 2), $this->endPos($pos));
				break;
			case 309:
				$this->semValue = new Expression\ArrayAccessNode(
					$this->semStack[$pos - 4],
					$this->semStack[$pos - 2],
					$this->startPos($pos - 5),
					$this->endPos($pos),
				);
				break;
			case 312:
				$this->semValue = TagParser::parseOffset($this->semStack[$pos], $this->startPos($pos), $this->endPos($pos));
				break;
			case 313:
				$this->semValue = TagParser::parseOffset('-' . $this->semStack[$pos], $this->startPos($pos - 1), $this->endPos($pos));
				break;
		}
	}
}
