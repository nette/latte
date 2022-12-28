<?php

// Assignments

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	/* simple assign */
	$a = $b,

	/* combined assign */
	$a &= $b,
	$a |= $b,
	$a ^= $b,
	$a .= $b,
	$a /= $b,
	$a -= $b,
	$a %= $b,
	$a *= $b,
	$a += $b,
	$a <<= $b,
	$a >>= $b,
	$a **= $b,
	$a ??= $b,

	/* chained assign */
	$a = $b *= $c **= $d,

	/* by ref assign */
	$a =& $b,

	/* list() assign */
	list($a) = $b,
	list($a, , $b) = $c,
	list($a, list(, $c), $d) = $e,

	/* inc/dec */
	++$a,
	$a++,
	--$a,
	$a--,
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (23)
   |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 2:1 (offset 20)
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 2:6 (offset 25)
   |  |  |  byRef: false
   |  |  |  position: 2:1 (offset 20)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1 (offset 20)
   |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 5:1 (offset 52)
   |  |  |  operator: '&'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 5:7 (offset 58)
   |  |  |  position: 5:1 (offset 52)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1 (offset 52)
   |  2 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 6:1 (offset 62)
   |  |  |  operator: '|'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 6:7 (offset 68)
   |  |  |  position: 6:1 (offset 62)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1 (offset 62)
   |  3 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 7:1 (offset 72)
   |  |  |  operator: '^'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 7:7 (offset 78)
   |  |  |  position: 7:1 (offset 72)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 7:1 (offset 72)
   |  4 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 8:1 (offset 82)
   |  |  |  operator: '.'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 8:7 (offset 88)
   |  |  |  position: 8:1 (offset 82)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 8:1 (offset 82)
   |  5 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 9:1 (offset 92)
   |  |  |  operator: '/'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 9:7 (offset 98)
   |  |  |  position: 9:1 (offset 92)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 9:1 (offset 92)
   |  6 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 10:1 (offset 102)
   |  |  |  operator: '-'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 10:7 (offset 108)
   |  |  |  position: 10:1 (offset 102)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 10:1 (offset 102)
   |  7 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 11:1 (offset 112)
   |  |  |  operator: '%'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 11:7 (offset 118)
   |  |  |  position: 11:1 (offset 112)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 11:1 (offset 112)
   |  8 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 12:1 (offset 122)
   |  |  |  operator: '*'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 12:7 (offset 128)
   |  |  |  position: 12:1 (offset 122)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 12:1 (offset 122)
   |  9 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 13:1 (offset 132)
   |  |  |  operator: '+'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 13:7 (offset 138)
   |  |  |  position: 13:1 (offset 132)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 13:1 (offset 132)
   |  10 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 14:1 (offset 142)
   |  |  |  operator: '<<'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 14:8 (offset 149)
   |  |  |  position: 14:1 (offset 142)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 14:1 (offset 142)
   |  11 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 15:1 (offset 153)
   |  |  |  operator: '>>'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 15:8 (offset 160)
   |  |  |  position: 15:1 (offset 153)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 15:1 (offset 153)
   |  12 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 16:1 (offset 164)
   |  |  |  operator: '**'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 16:8 (offset 171)
   |  |  |  position: 16:1 (offset 164)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 16:1 (offset 164)
   |  13 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 17:1 (offset 175)
   |  |  |  operator: '??'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 17:8 (offset 182)
   |  |  |  position: 17:1 (offset 175)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 17:1 (offset 175)
   |  14 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 20:1 (offset 208)
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\AssignOpNode
   |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 20:6 (offset 213)
   |  |  |  |  operator: '*'
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\AssignOpNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'c'
   |  |  |  |  |  |  position: 20:12 (offset 219)
   |  |  |  |  |  operator: '**'
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'd'
   |  |  |  |  |  |  position: 20:19 (offset 226)
   |  |  |  |  |  position: 20:12 (offset 219)
   |  |  |  |  position: 20:6 (offset 213)
   |  |  |  byRef: false
   |  |  |  position: 20:1 (offset 208)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 20:1 (offset 208)
   |  15 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 23:1 (offset 251)
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 23:7 (offset 257)
   |  |  |  byRef: true
   |  |  |  position: 23:1 (offset 251)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 23:1 (offset 251)
   |  16 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  items: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  |  position: 26:6 (offset 287)
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  position: 26:6 (offset 287)
   |  |  |  |  position: 26:1 (offset 282)
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 26:12 (offset 293)
   |  |  |  byRef: false
   |  |  |  position: 26:1 (offset 282)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 26:1 (offset 282)
   |  17 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  items: array (3)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  |  position: 27:6 (offset 302)
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  position: 27:6 (offset 302)
   |  |  |  |  |  1 => null
   |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  |  position: 27:12 (offset 308)
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  position: 27:12 (offset 308)
   |  |  |  |  position: 27:1 (offset 297)
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'c'
   |  |  |  |  position: 27:18 (offset 314)
   |  |  |  byRef: false
   |  |  |  position: 27:1 (offset 297)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 27:1 (offset 297)
   |  18 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  items: array (3)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  |  position: 28:6 (offset 323)
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  position: 28:6 (offset 323)
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  |  |  items: array (2)
   |  |  |  |  |  |  |  |  0 => null
   |  |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  |  name: 'c'
   |  |  |  |  |  |  |  |  |  |  position: 28:17 (offset 334)
   |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  position: 28:17 (offset 334)
   |  |  |  |  |  |  |  position: 28:10 (offset 327)
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  position: 28:10 (offset 327)
   |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'd'
   |  |  |  |  |  |  |  position: 28:22 (offset 339)
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  position: 28:22 (offset 339)
   |  |  |  |  position: 28:1 (offset 318)
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'e'
   |  |  |  |  position: 28:28 (offset 345)
   |  |  |  byRef: false
   |  |  |  position: 28:1 (offset 318)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 28:1 (offset 318)
   |  19 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\PreOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 31:3 (offset 366)
   |  |  |  operator: '++'
   |  |  |  position: 31:1 (offset 364)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 31:1 (offset 364)
   |  20 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\PostOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 32:1 (offset 370)
   |  |  |  operator: '++'
   |  |  |  position: 32:1 (offset 370)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 32:1 (offset 370)
   |  21 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\PreOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 33:3 (offset 378)
   |  |  |  operator: '--'
   |  |  |  position: 33:1 (offset 376)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 33:1 (offset 376)
   |  22 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\PostOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 34:1 (offset 382)
   |  |  |  operator: '--'
   |  |  |  position: 34:1 (offset 382)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 34:1 (offset 382)
   position: 2:1 (offset 20)
