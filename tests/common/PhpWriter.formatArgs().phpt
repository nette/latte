<?php

/**
 * Test: Latte\PhpWriter::formatArgs()
 */

declare(strict_types=1);

use Latte\MacroTokens;
use Latte\PhpWriter;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


function formatArgs($args)
{
	$writer = new PhpWriter(new MacroTokens($args));
	return $writer->formatArgs();
}


test('symbols', function () {
	Assert::same('', formatArgs(''));
	Assert::same('1', formatArgs('1'));
	Assert::same("'symbol'", formatArgs('symbol'));
	Assert::same("1, 2, 'symbol1', 'symbol-2'", formatArgs('1, 2, symbol1, symbol-2'));
	Assert::same("('a', 'b', 'c' => 'd', 'e' ? 'f' : 'g', h['i'], j('k'))", formatArgs('(a, b, c => d, e ? f : g, h[i], j(k))'));
	Assert::same("'x' && 'y', 'x' || 'y', 'x' < 'y', 'x' <= 'y', 'x' > 'y', 'x' => 'y', 'x' == 'y', 'x' === 'y', 'x' != 'y', 'x' !== 'y', 'x' <> 'y'", formatArgs('x && y, x || y, x < y, x <= y, x > y, x => y, x == y, x === y, x != y, x !== y, x <> y'));
	Assert::same("'x' and 'y', 'x' or 'y', 'x' xor 'y', 'x' and 'y' or 'x'", formatArgs('x and y, x or y, x xor y, x and y or x'));
	Assert::same("\$x = 'x', x = 1, 'x' . 'y'", formatArgs('$x = x, x = 1, x . y'));
});


test('strings', function () {
	Assert::same('"\"1, 2, symbol1, symbol2"', formatArgs('"\"1, 2, symbol1, symbol2"')); // unable to parse "${'"'}" yet
	Assert::same("'\\'1, 2, symbol1, symbol2'", formatArgs("'\\'1, 2, symbol1, symbol2'"));
	Assert::same("('hello')", formatArgs('(hello)'));
	Assert::exception(function () {
		formatArgs("'\\\\'1, 2, symbol1, symbol2'");
	}, Latte\CompileException::class, 'Unexpected %a% on line 1, column 27.');
});


test('key words', function () {
	Assert::same('TRUE, false, null, 1 or 1 and 2 xor 3, clone $obj, new Class', formatArgs('TRUE, false, null, 1 or 1 and 2 xor 3, clone $obj, new Class'));
	Assert::same('func (10)', formatArgs('func (10)'));
});


test('associative arrays', function () {
	Assert::same("'symbol1' => 'value','symbol2'=>'value'", formatArgs('symbol1 => value,symbol2=>value'));
	Assert::same("'symbol1' => array ('symbol2' => 'value')", formatArgs('symbol1 => array (symbol2 => value)'));
});


test('short ternary operators', function () {
	Assert::same("(\$first ? 'first' : null), \$var ? 'foo' : 'bar', \$var ? 'foo' : null", formatArgs('($first ? first), $var ? foo : bar, $var ? foo'));
	Assert::same("('a' ? 'b' : null) ? ('c' ? 'd' : null) : null", formatArgs('(a ? b) ? (c ? d)'));
	Assert::same("fce() ? 'a' : null, fce() ? 'b' : null", formatArgs('fce() ? a, fce() ? b'));
	Assert::same("fce() ?? 'a'", formatArgs('fce() ?? a')); // null coalesce is ignored
	Assert::same("'a'?", formatArgs('a?')); // value must exists
	Assert::same('$a?(1) : null', formatArgs('$a?(1)')); // with braces
	Assert::same('$a ? \Foo::BAR : null', formatArgs('$a ? \Foo::BAR'));
	Assert::same('$c ?: ($a ?: $b)', formatArgs('$c ?: ($a ?: $b)'));
	Assert::same('$c ? ($a ?: $b) : null', formatArgs('$c ? ($a ?: $b)'));
	Assert::same('$a?(1) : null', formatArgs('$a?(1)')); // with braces
});


test('special', function () {
	Assert::same('$var', formatArgs('$var'));
	Assert::same('$var => $var', formatArgs('$var => $var'));
	Assert::same("'truex' => 0word, 0true, true-true, true-1", formatArgs('truex => 0word, 0true, true-true, true-1'));
	Assert::same("'symbol' => 'PI'", formatArgs('symbol => PI'));
	Assert::same("'symbol' => NOTCONST", formatArgs('symbol => NOTCONST'));
	Assert::same("'symbol' => M_PI, NAN, INF ", formatArgs('symbol => M_PI, NAN, INF '));
	Assert::same("'symbol' => Class::CONST, ", formatArgs('symbol => Class::CONST, '));
	Assert::same("'symbol' => CLASS::CONST, ", formatArgs('symbol => CLASS::CONST, '));
	Assert::same("'symbol' => NAMESPACE\\CLASS::CONST, ", formatArgs('symbol => NAMESPACE\CLASS::CONST, '));
	Assert::same("'symbol' => Class::method(), ", formatArgs('symbol => Class::method(), '));
	Assert::same("'symbol' => Namespace\\Class::method()", formatArgs('symbol => Namespace\Class::method()'));
	Assert::same("'symbol' => Namespace \\ Class :: method ()", formatArgs('symbol => Namespace \ Class :: method ()'));
	Assert::same("'symbol' => \$this->var, ", formatArgs('symbol => $this->var, '));
	Assert::same("'symbol' => \$this->VAR, ", formatArgs('symbol => $this->VAR, '));
	Assert::same("'symbol1' =>  'value' ", formatArgs('symbol1 => /*value,* /symbol2=>*/value/**/'));
	Assert::same('(array)', formatArgs('(array)'));
	Assert::same('func()[1]', formatArgs('func()[1]'));
	Assert::same('$var = match(7) {8 => true, default => false,}', formatArgs('$var = match(7) {8 => true, default => false,}'));
	Assert::same('a b', formatArgs('a/**/b'));
});


test('special UTF-8', function () {
	Assert::same("'Iñtërnâtiônàlizætiøn' => 'Iñtërnâtiônàlizætiøn'", formatArgs('Iñtërnâtiônàlizætiøn => Iñtërnâtiônàlizætiøn'));
	Assert::same('$våŕìăbłë', formatArgs('$våŕìăbłë'));
	Assert::same("'M_PIÁNO'", formatArgs('M_PIÁNO'));
	Assert::same("'symbôl-1' => 'vålue-2'", formatArgs('symbôl-1 => vålue-2'));
});


test('inline modifiers', function () {
	Assert::same('($this->filters->mod)(@)', formatArgs('(@|mod)'));
	Assert::same('($this->filters->mod3)(($this->filters->mod2)(($this->filters->mod1)(@)))', formatArgs('(@|mod1|mod2|mod3)'));
	Assert::same('($this->filters->mod3)(($this->filters->mod2)(($this->filters->mod1)(@)))', formatArgs('((@|mod1)|mod2|mod3)'));
	Assert::same('($this->filters->mod)(@, 1, 2, $var["pocet"])', formatArgs('(@|mod,1,2,$var["pocet"])'));
	Assert::same('($this->filters->mod)(@, $var, 0, -0.0, "s\"\'tr", \'s"\\\'tr\')', formatArgs('(@|mod, $var, 0, -0.0, "s\"\'tr", \'s"\\\'tr\')'));
	Assert::same('($this->filters->mod)(@, array(1))', formatArgs('(@|mod: array(1))'));
	Assert::same('($this->filters->mod)($a ? $b : null)', formatArgs('($a ? $b|mod)'));

	Assert::same("'foo' => (\$this->filters->mod)(\$val, 'param', \"param2)\")", formatArgs('foo => ($val|mod:param,"param2)")'));
	Assert::same("'foo' => (\$this->filters->mod2)((\$this->filters->mod)(\$val))", formatArgs('foo => ($val|mod|mod2)'));
	Assert::same("'foo' => (\$this->filters->mod)(\$val, 'param', (\$this->filters->mod2)(1))", @formatArgs('foo => ($val|mod:param:(1|mod2))')); // deprecated :
	Assert::same("'foo' => (\$this->filters->mod)(\$val, 'param', (\$this->filters->mod2)(1, round((\$this->filters->foo)(2))))", @formatArgs('foo => ($val|mod:param:(1|mod2:round((2|foo))))')); // deprecated :
	Assert::same("'foo' => foo(\$val)", formatArgs('foo => foo($val)'));
	Assert::same("'foo' => foo((\$this->filters->bar)(\$val))", formatArgs('foo => foo($val|bar)'));
	Assert::same('foo(($this->filters->bar)($val),($this->filters->lorem)( $val))', formatArgs('foo($val|bar, $val|lorem)'));
	Assert::same("'foo' => array((\$this->filters->bar)(\$val),)", formatArgs('foo => array($val|bar,)'));
	Assert::same('[($this->filters->bar)($val),($this->filters->lorem)( $val)]', formatArgs('[$val|bar, $val|lorem]'));
	Assert::exception(function () {
		formatArgs('($val|mod:param:"param2"');
	}, Latte\CompileException::class, 'Missing )');

	Assert::same('($this->filters->escape)(@)', formatArgs('(@|escape)'));
	Assert::same('LR\Filters::safeUrl(@)', formatArgs('(@|checkUrl)'));
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
		PHP_VERSION_ID >= 80000
			? '$var?->prop?->elem[1]?->call(2)?->item'
			: '(($ʟ_tmp = $var) === null ? null : (($ʟ_tmp = $ʟ_tmp->prop) === null ? null : (($ʟ_tmp = $ʟ_tmp->elem[1]) === null ? null : (($ʟ_tmp = $ʟ_tmp->call(2)) === null ? null : $ʟ_tmp->item))))',
		formatArgs('$var?->prop?->elem[1]?->call(2)?->item')
	);
	Assert::same(
		'(($ʟ_tmp = $var ?? null) === null ? null : (($ʟ_tmp = $ʟ_tmp->prop ?? null) === null ? null : (($ʟ_tmp = $ʟ_tmp->elem[1] ?? null) === null ? null : (($ʟ_tmp = $ʟ_tmp->call(2) ?? null) === null ? null : $ʟ_tmp->item))))',
		formatArgs('$var??->prop??->elem[1]??->call(2)??->item')
	);
});


test('named arguments', function () {
	Assert::same('func(a: 1, b: 2)', formatArgs('func(a: 1, b: 2)'));
	Assert::same("func(a: 1, ('a' ?'b': 2))", formatArgs('func(a: 1, (a ?b: 2))')); // ternary
	Assert::same("a: 1, 'a' ? 'b': 2", formatArgs('a: 1, a ? b: 2'));
});


test('short array syntax', function () {
	Assert::same("['a' => 1, 'a' ? 'b': 2]", formatArgs('[a: 1, a ? b: 2]'));
});
