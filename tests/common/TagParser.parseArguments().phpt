<?php

declare(strict_types=1);

use Latte\Compiler\PrintContext;
use Latte\Compiler\TagLexer;
use Latte\Compiler\TagParser;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function formatArgs(string $str)
{
	$tokens = (new TagLexer)->tokenize($str);
	$parser = new TagParser($tokens);
	$node = $parser->parseArguments();
	if (!$parser->isEnd()) {
		$parser->stream->throwUnexpectedException();
	}
	return (new PrintContext)->implode($node->toArguments());
}


test('symbols', function () {
	Assert::same('', formatArgs(''));
	Assert::same('1', formatArgs('1'));
	Assert::same("'symbol'", formatArgs('symbol'));
	Assert::same("1, 2, 'symbol1', 'symbol-2'", formatArgs('1, 2, symbol1, symbol-2'));
	Assert::same("'a', 'b', c: 'd', 'e' ? 'f' : 'g', h['i'], j('k')", formatArgs('a, b, c => d, e ? f : g, h[i], j(k)'));
	Assert::same(
		"'x' && 'y', 'x' || 'y', 'x' < 'y', 'x' <= 'y', 'x' > 'y', x: 'y', 'x' == 'y', 'x' === 'y', 'x' != 'y', 'x' !== 'y', 'x' != 'y'",
		formatArgs('x && y, x || y, x < y, x <= y, x > y, x => y, x == y, x === y, x != y, x !== y, x <> y'),
	);
	Assert::same("'x' and 'y', 'x' or 'y', 'x' xor 'y', 'x' and 'y' or 'x'", formatArgs('x and y, x or y, x xor y, x and y or x'));
});


test('strings', function () {
	Assert::same("'\"1, 2, symbol1, symbol2'", formatArgs('"\"1, 2, symbol1, symbol2"'));
	Assert::same("'\\'1, 2, symbol1, symbol2'", formatArgs("'\\'1, 2, symbol1, symbol2'"));
	Assert::same("'hello'", formatArgs('(hello)'));
	Assert::exception(
		fn() => formatArgs("'\\\\'1, 2, symbol1, symbol2'"),
		Latte\CompileException::class,
		'Unterminated string (at column 27)',
	);
});


test('key words', function () {
	Assert::same('true, false, null, 1 or 1 and 2 xor 3, clone $obj, new namespace\Class', formatArgs('TRUE, false, null, 1 or 1 and 2 xor 3, clone $obj, new Class'));
	Assert::same('func(10)', formatArgs('func (10)'));
});


test('associative arrays', function () {
	Assert::same("symbol1: 'value', symbol2: 'value'", formatArgs('symbol1 => value,symbol2=>value'));
	Assert::same("symbol1: ['symbol2' => 'value']", formatArgs('symbol1 => array (symbol2 => value)'));
});


test('short ternary operators', function () {
	Assert::same("\$first ? 'first' : null, \$var ? 'foo' : 'bar', \$var ? 'foo' : null", formatArgs('($first ? first), $var ? foo : bar, $var ? foo'));
	Assert::same("('a' ? 'b' : null) ? 'c' ? 'd' : null : null", formatArgs('(a ? b) ? (c ? d)'));
	Assert::same("fce() ? 'a' : null, fce() ? 'b' : null", formatArgs('fce() ? a, fce() ? b'));
});


test('special UTF-8', function () {
	Assert::same("Iñtërnâtiônàlizætiøn: 'Iñtërnâtiônàlizætiøn'", formatArgs('Iñtërnâtiônàlizætiøn => Iñtërnâtiônàlizætiøn'));
	Assert::same('$våŕìăbłë', formatArgs('$våŕìăbłë'));
	Assert::same("symbôl-1: 'vålue-2'", formatArgs('symbôl-1 => vålue-2'));
});


test('inline modifiers', function () {
	Assert::same('($this->filters->mod)(0)', formatArgs('(0|mod)'));
	Assert::same('($this->filters->mod3)(($this->filters->mod2)(($this->filters->mod1)(0)))', formatArgs('(0|mod1|mod2|mod3)'));
	Assert::same('($this->filters->mod3)(($this->filters->mod2)(($this->filters->mod1)(0)))', formatArgs('((0|mod1)|mod2|mod3)'));
	Assert::same('($this->filters->mod)(0, 1, 2, $var[\'pocet\'])', formatArgs('(0|mod,1,2,$var["pocet"])'));
	Assert::same('($this->filters->mod)(0, $var, 0, -0.0, \'s"\\\'tr\', \'s"\\\'tr\')', formatArgs('(0|mod, $var, 0, -0.0, "s\"\'tr", \'s"\\\'tr\')'));
	Assert::same('($this->filters->mod)(0, [1])', formatArgs('(0|mod: array(1))'));
	Assert::same('($this->filters->mod)($a ? $b : null)', formatArgs('($a ? $b|mod)'));

	Assert::same("foo: (\$this->filters->mod)(\$val, 'param', 'param2)')", formatArgs('foo => ($val|mod:param, "param2)")'));
	Assert::same('foo: ($this->filters->mod2)(($this->filters->mod)($val))', formatArgs('foo => ($val|mod|mod2)'));
	Assert::same("foo: (\$this->filters->mod)(\$val, 'param', (\$this->filters->mod2)(1))", formatArgs('foo => ($val|mod:param,(1|mod2))'));
	Assert::same("foo: (\$this->filters->mod)(\$val, 'param', (\$this->filters->mod2)(1, round((\$this->filters->foo)(2))))", formatArgs('foo => ($val|mod:param,(1|mod2:round((2|foo))))'));
	Assert::same('foo: foo($val)', formatArgs('foo => foo($val)'));
	Assert::same('($this->filters->escape)(0)', formatArgs('(0|escape)'));
	Assert::same('($this->filters->checkurl)(0)', formatArgs('(0|checkurl)'));
});


test('in operator', function () {
	Assert::same("in_array(\$a, ['a', 'b'], true), 1", formatArgs('$a in [a, b], 1'));
	Assert::same('$a, in_array($b->func(), [1, 2], true)', formatArgs('$a, $b->func() in [1, 2]'));
	Assert::same('$a, in_array($b[1], [1, 2], true)', formatArgs('$a, $b[1] in [1, 2]'));
	Assert::same('in_array($b, [1, [2], 3], true)', formatArgs('$b in [1, [2], 3]'));
});


test('optionalChainingPass', function () {
	Assert::same('$var->prop->elem[1]->call(2)->item', formatArgs('$var->prop->elem[1]->call(2)->item'));
	Assert::same(
		'$var?->prop?->elem[1]?->call(2)?->item',
		formatArgs('$var?->prop?->elem[1]?->call(2)?->item'),
	);
	Assert::same(
		'((((($var ?? null)?->prop ?? null)?->elem)[1] ?? null)?->call(2) ?? null)?->item',
		formatArgs('$var??->prop??->elem[1]??->call(2)??->item'),
	);
});


test('named arguments', function () {
	Assert::same('func(a: 1, b: 2)', formatArgs('func(a: 1, b: 2)'));
	Assert::same("func(a: 1, 'a' ? 'b' : 2)", formatArgs('func(a: 1, (a ?b: 2))')); // ternary
	Assert::same("a: 1, 'a' ? 'b' : 2", formatArgs('a: 1, a ? b: 2'));
	Assert::same('foo: ($this->filters->mod)($val, param: ($this->filters->mod2)(1))', formatArgs('foo => ($val|mod:param:(1|mod2))'));
});


test('short array syntax', function () {
	Assert::same("['a' => 1, 'a' ? 'b' : 2]", formatArgs('[a: 1, a ? b: 2]'));
});
