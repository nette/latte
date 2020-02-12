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


test(function () { // symbols
	Assert::same('', formatArgs(''));
	Assert::same('1', formatArgs('1'));
	Assert::same("'symbol'", formatArgs('symbol'));
	Assert::same("1, 2, 'symbol1', 'symbol-2'", formatArgs('1, 2, symbol1, symbol-2'));
	Assert::same("('a', 'b', 'c' => 'd', 'e' ? 'f' : 'g', h['i'], j('k'))", formatArgs('(a, b, c => d, e ? f : g, h[i], j(k))'));
	Assert::same("'x' && 'y', 'x' || 'y', 'x' < 'y', 'x' <= 'y', 'x' > 'y', 'x' => 'y', 'x' == 'y', 'x' === 'y', 'x' != 'y', 'x' !== 'y', 'x' <> 'y'", formatArgs('x && y, x || y, x < y, x <= y, x > y, x => y, x == y, x === y, x != y, x !== y, x <> y'));
	Assert::same("'x' and 'y', 'x' or 'y', 'x' xor 'y', 'x' and 'y' or 'x'", formatArgs('x and y, x or y, x xor y, x and y or x'));
	Assert::same("\$x = 'x', x = 1, 'x' . 'y'", formatArgs('$x = x, x = 1, x . y'));
});


test(function () { // strings
	Assert::same('"\"1, 2, symbol1, symbol2"', formatArgs('"\"1, 2, symbol1, symbol2"')); // unable to parse "${'"'}" yet
	Assert::same("'\\'1, 2, symbol1, symbol2'", formatArgs("'\\'1, 2, symbol1, symbol2'"));
	Assert::same("('hello')", formatArgs('(hello)'));
	Assert::exception(function () {
		formatArgs("'\\\\'1, 2, symbol1, symbol2'");
	}, Latte\CompileException::class, 'Unexpected %a% on line 1, column 27.');
});


test(function () { // key words
	Assert::same('TRUE, false, null, 1 or 1 and 2 xor 3, clone $obj, new Class', formatArgs('TRUE, false, null, 1 or 1 and 2 xor 3, clone $obj, new Class'));
	Assert::same('func (10)', formatArgs('func (10)'));
});


test(function () { // associative arrays
	Assert::same("'symbol1' => 'value','symbol2'=>'value'", formatArgs('symbol1 => value,symbol2=>value'));
	Assert::same("'symbol1' => array ('symbol2' => 'value')", formatArgs('symbol1 => array (symbol2 => value)'));
});


test(function () { // short ternary operators
	Assert::same("(\$first ? 'first' : null), \$var ? 'foo' : 'bar', \$var ? 'foo' : null", formatArgs('($first ? first), $var ? foo : bar, $var ? foo'));
	Assert::same("('a' ? 'b' : null) ? ('c' ? 'd' : null) : null", formatArgs('(a ? b) ? (c ? d)'));
	Assert::same("fce() ? 'a' : null, fce() ? 'b' : null", formatArgs('fce() ? a, fce() ? b'));
	Assert::same("fce() ?? 'a'", formatArgs('fce() ?? a')); // null coalesce is ignored
	Assert::same("'a'?", formatArgs('a?')); // value must exists
	Assert::same('$a ?(1) : null', formatArgs('$a?(1)')); // with braces
	Assert::same('$a ? \Foo::BAR : null', formatArgs('$a ? \Foo::BAR'));
	Assert::same('$c ?: ($a ?: $b)', formatArgs('$c ?: ($a ?: $b)'));
	Assert::same('$c ? ($a ?: $b) : null', formatArgs('$c ? ($a ?: $b)'));
	Assert::same('$a ?(1) : null', formatArgs('$a?(1)')); // with braces
});


test(function () { // special
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
	Assert::same("'symbol' => \$this->var, ", formatArgs('symbol => $this -> var, '));
	Assert::same("'symbol' => \$this->VAR, ", formatArgs('symbol => $this -> VAR, '));
	Assert::same("'symbol' => \$this->var", formatArgs('symbol => $this -> var'));
	Assert::same("'symbol1' => 'value'", formatArgs('symbol1 => /*value,* /symbol2=>*/value/**/'));
	Assert::same('(array)', formatArgs('(array)'));
	Assert::same('func()[1]', formatArgs('func()[1]'));
});


test(function () { // special UTF-8
	Assert::same("'Iñtërnâtiônàlizætiøn' => 'Iñtërnâtiônàlizætiøn'", formatArgs('Iñtërnâtiônàlizætiøn => Iñtërnâtiônàlizætiøn'));
	Assert::same('$våŕìăbłë', formatArgs('$våŕìăbłë'));
	Assert::same("'M_PIÁNO'", formatArgs('M_PIÁNO'));
	Assert::same("'symbôl-1' => 'vålue-2'", formatArgs('symbôl-1 => vålue-2'));
});


test(function () { // inline modifiers
	Assert::same('($this->filters->mod)(@)', formatArgs('(@|mod)'));
	Assert::same('($this->filters->mod3)(($this->filters->mod2)(($this->filters->mod1)(@)))', formatArgs('(@|mod1|mod2|mod3)'));
	Assert::same('($this->filters->mod3)(($this->filters->mod2)(($this->filters->mod1)(@)))', formatArgs('((@|mod1)|mod2|mod3)'));
	Assert::same('($this->filters->mod)(@, 1, 2, $var["pocet"])', formatArgs('(@|mod:1:2:$var["pocet"])'));
	Assert::same('($this->filters->mod)(@, 1, 2, $var["pocet"])', formatArgs('(@|mod,1,2,$var["pocet"])'));
	Assert::same('($this->filters->mod)(@, $var, 0, -0.0, "s\"\'tr", \'s"\\\'tr\')', formatArgs('(@|mod, $var, 0, -0.0, "s\"\'tr", \'s"\\\'tr\')'));
	Assert::same('($this->filters->mod)(@, array(1))', formatArgs('(@|mod: array(1))'));
	Assert::same('($this->filters->mod)($a ? $b : null)', formatArgs('($a ? $b|mod)'));

	Assert::same("'foo' => (\$this->filters->mod)(\$val, 'param', \"param2)\")", formatArgs('foo => ($val|mod:param:"param2)")'));
	Assert::same("'foo' => (\$this->filters->mod2)((\$this->filters->mod)(\$val))", formatArgs('foo => ($val|mod|mod2)'));
	Assert::same("'foo' => (\$this->filters->mod)(\$val, 'param', (\$this->filters->mod2)(1))", formatArgs('foo => ($val|mod:param:(1|mod2))'));
	Assert::same("'foo' => (\$this->filters->mod)(\$val, 'param', (\$this->filters->mod2)(1, round((\$this->filters->foo)(2))))", formatArgs('foo => ($val|mod:param:(1|mod2:round((2|foo))))'));
	Assert::same("'foo' => foo(\$val)", formatArgs('foo => foo($val)'));
	Assert::same("'foo' => foo((\$this->filters->bar)(\$val))", formatArgs('foo => foo($val|bar)'));
	Assert::same('foo(($this->filters->bar)($val),($this->filters->lorem)( $val))', formatArgs('foo($val|bar, $val|lorem)'));
	Assert::same("'foo' => array((\$this->filters->bar)(\$val),)", formatArgs('foo => array($val|bar,)'));
	Assert::same('[($this->filters->bar)($val),($this->filters->lorem)( $val)]', formatArgs('[$val|bar, $val|lorem]'));
	Assert::exception(function () {
		formatArgs('($val|mod:param:"param2"');
	}, Latte\CompileException::class, 'Missing )');

	Assert::same('($this->filters->escape)(@)', formatArgs('(@|escape)'));
	Assert::same('LR\Filters::safeUrl(@)', formatArgs('(@|checkurl)'));
});


test(function () { // in operator
	Assert::same("in_array(\$a, ['a', 'b'], true), 1", formatArgs('$a in [a, b], 1'));
	Assert::same('$a, in_array($b->func(), [1, 2], true)', formatArgs('$a, $b->func() in [1, 2]'));
	Assert::same('$a, in_array($b[1], [1, 2], true)', formatArgs('$a, $b[1] in [1, 2]'));
	Assert::same('in_array($b, [1, [2], 3], true)', formatArgs('$b in [1, [2], 3]'));
});


test(function () { // optionalChainingPass
	Assert::same('$a', formatArgs('$a'));
	Assert::same('($a ?? null)', formatArgs('$a?'));
	Assert::same('(($a ?? null))', formatArgs('($a?)'));
	Assert::same('(($_tmp = $foo ?? null) === null ? null : $_tmp[1])', formatArgs('$foo?[1]'));
	Assert::same('$var->prop->elem[1]->call(2)->item', formatArgs('$var->prop->elem[1]->call(2)->item'));
	Assert::same('(($_tmp = $var ?? null) === null ? null : (($_tmp = $_tmp->prop ?? null) === null ? null : (($_tmp = $_tmp->elem[1] ?? null) === null ? null : (($_tmp = $_tmp->call(2) ?? null) === null ? null : ($_tmp->item ?? null)))))', formatArgs('$var?->prop?->elem[1]?->call(2)?->item?'));
});


test(function () { // optionalChainingPass + ternary
	Assert::same('$a ?:$b', formatArgs('$a?:$b'));
	Assert::same('$a ? : $b', formatArgs('$a ? : $b'));
	Assert::same('$a ?? $b', formatArgs('$a ?? $b'));
	Assert::same('$a ? [1, 2, ([3 ? 2 : 1])]: $b', formatArgs('$a ? [1, 2, ([3 ? 2 : 1])]: $b'));
	Assert::same('(($_tmp = $a ?? null) === null ? null : ($_tmp->foo ?? null)) ? [1, 2, ([3 ? 2 : 1])] : $b', formatArgs('$a?->foo? ? [1, 2, ([3 ? 2 : 1])] : $b'));
});
