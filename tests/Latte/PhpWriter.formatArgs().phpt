<?php

/**
 * Test: Latte\PhpWriter::formatArgs()
 */

use Latte\PhpWriter;
use Latte\MacroTokens;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


function formatArgs($args) {
	$writer = new PhpWriter(new MacroTokens($args));
	return $writer->formatArgs();
}


test(function () { // symbols
	Assert::same('',  formatArgs(''));
	Assert::same('1',  formatArgs('1'));
	Assert::same("'symbol'",  formatArgs('symbol'));
	Assert::same("1, 2, 'symbol1', 'symbol-2'",  formatArgs('1, 2, symbol1, symbol-2'));
	Assert::same("('a', 'b', 'c' => 'd', 'e' ? 'f' : 'g', h['i'], j('k'))",  formatArgs('(a, b, c => d, e ? f : g, h[i], j(k))'));
	Assert::same("'x' && 'y', 'x' || 'y', 'x' < 'y', 'x' <= 'y', 'x' > 'y', 'x' => 'y', 'x' == 'y', 'x' === 'y', 'x' != 'y', 'x' !== 'y', 'x' <> 'y'",  formatArgs('x && y, x || y, x < y, x <= y, x > y, x => y, x == y, x === y, x != y, x !== y, x <> y'));
	Assert::same("'x' and 'y', 'x' or 'y', 'x' xor 'y', 'x' and 'y' or 'x'",  formatArgs('x and y, x or y, x xor y, x and y or x'));
	Assert::same("\$x = 'x', x = 1, 'x' . 'y'",  formatArgs('$x = x, x = 1, x . y'));
});


test(function () { // strings
	Assert::same('"\"1, 2, symbol1, symbol2"',  formatArgs('"\"1, 2, symbol1, symbol2"')); // unable to parse "${'"'}" yet
	Assert::same("'\\'1, 2, symbol1, symbol2'",  formatArgs("'\\'1, 2, symbol1, symbol2'"));
	Assert::same("('hello')",  formatArgs('(hello)'));
	Assert::exception(function () {
		formatArgs("'\\\\'1, 2, symbol1, symbol2'");
	}, 'Latte\CompileException', 'Unexpected %a% on line 1, column 27.');
});


test(function () { // key words
	Assert::same('TRUE, false, null, 1 or 1 and 2 xor 3, clone $obj, new Class',  formatArgs('TRUE, false, null, 1 or 1 and 2 xor 3, clone $obj, new Class'));
	Assert::same('func (10)',  formatArgs('func (10)'));
});


test(function () { // associative arrays
	Assert::same("'symbol1' => 'value','symbol2'=>'value'",  formatArgs('symbol1 => value,symbol2=>value'));
	Assert::same("'symbol1' => array ('symbol2' => 'value')",  formatArgs('symbol1 => array (symbol2 => value)'));
});


test(function () { // short ternary operators
	Assert::same("(\$first ? 'first' : NULL), \$var ? 'foo' : 'bar', \$var ? 'foo' : NULL",  formatArgs('($first ? first), $var ? foo : bar, $var ? foo'));
	Assert::same("('a' ? 'b' : NULL) ? ('c' ? 'd' : NULL) : NULL",  formatArgs('(a ? b) ? (c ? d)'));
	Assert::same("fce() ? 'a' : NULL, fce() ? 'b' : NULL",  formatArgs('fce() ? a, fce() ? b'));
	Assert::same("fce() ?? 'a'",  formatArgs('fce() ?? a')); // NULL coalesce is ignored
});


test(function () { // special
	Assert::same('$var',  formatArgs('$var'));
	Assert::same('$var => $var',  formatArgs('$var => $var'));
	Assert::same("'truex' => 0word, 0true, true-true, true-1",  formatArgs('truex => 0word, 0true, true-true, true-1'));
	Assert::same("'symbol' => 'PI'",  formatArgs('symbol => PI'));
	Assert::error(function () {
		formatArgs('NOTCONST');
	}, E_USER_DEPRECATED, "Replace literal NOTCONST with constant('NOTCONST')");
	Assert::same("'symbol' => NOTCONST",  @formatArgs('symbol => NOTCONST')); // @ not contant
	Assert::same("'symbol' => M_PI, NAN, INF ",  formatArgs('symbol => M_PI, NAN, INF '));
	Assert::same("'symbol' => Class::CONST, ",  formatArgs('symbol => Class::CONST, '));
	Assert::same("'symbol' => Class::method(), ",  formatArgs('symbol => Class::method(), '));
	Assert::same("'symbol' => Namespace\\Class::method()",  formatArgs('symbol => Namespace\Class::method()'));
	Assert::same("'symbol' => Namespace \\ Class :: method ()",  formatArgs('symbol => Namespace \ Class :: method ()'));
	Assert::same("'symbol' => \$this->var, ",  formatArgs('symbol => $this->var, '));
	Assert::same("'symbol' => \$this->VAR, ",  formatArgs('symbol => $this->VAR, '));
	Assert::same("'symbol' => \$this -> var, ",  formatArgs('symbol => $this -> var, '));
	Assert::same("'symbol' => \$this -> VAR, ",  formatArgs('symbol => $this -> VAR, '));
	Assert::same("'symbol' => \$this -> var",  formatArgs('symbol => $this -> var'));
	Assert::same("'symbol1' => 'value'",  formatArgs('symbol1 => /*value,* /symbol2=>*/value/**/'));
	Assert::same('(array)',  formatArgs('(array)'));
	Assert::same('func()[1]',  formatArgs('func()[1]'));
});


test(function () { // special UTF-8
	Assert::same("'Iñtërnâtiônàlizætiøn' => 'Iñtërnâtiônàlizætiøn'",  formatArgs('Iñtërnâtiônàlizætiøn => Iñtërnâtiônàlizætiøn'));
	Assert::same('$våŕìăbłë',  formatArgs('$våŕìăbłë'));
	Assert::same("'M_PIÁNO'",  formatArgs('M_PIÁNO'));
	Assert::same("'symbôl-1' => 'vålue-2'",  formatArgs('symbôl-1 => vålue-2'));
});


test(function () { // inline modifiers
	Assert::same('call_user_func($this->filters->mod, @)',  formatArgs('(@|mod)'));
	Assert::same('call_user_func($this->filters->mod3, call_user_func($this->filters->mod2, call_user_func($this->filters->mod1, @)))',  formatArgs('(@|mod1|mod2|mod3)'));
	Assert::same('call_user_func($this->filters->mod3, call_user_func($this->filters->mod2, call_user_func($this->filters->mod1, @)))',  formatArgs('((@|mod1)|mod2|mod3)'));
	Assert::same('call_user_func($this->filters->mod, @, 1, 2, $var["pocet"])',  formatArgs('(@|mod:1:2:$var["pocet"])'));
	Assert::same('call_user_func($this->filters->mod, @, 1, 2, $var["pocet"])',  formatArgs('(@|mod,1,2,$var["pocet"])'));
	Assert::same('call_user_func($this->filters->mod, @, $var, 0, -0.0, "s\"\'tr", \'s"\\\'tr\')',  formatArgs('(@|mod, $var, 0, -0.0, "s\"\'tr", \'s"\\\'tr\')'));
	Assert::same('call_user_func($this->filters->mod, @, array(1))',  formatArgs('(@|mod: array(1))'));
	Assert::same('call_user_func($this->filters->mod, $a ? $b : NULL)',  formatArgs('($a ? $b|mod)'));

	Assert::same("'foo' => call_user_func(\$this->filters->mod, \$val, 'param', \"param2)\")", formatArgs('foo => ($val|mod:param:"param2)")'));
	Assert::same("'foo' => call_user_func(\$this->filters->mod2, call_user_func(\$this->filters->mod, \$val))", formatArgs('foo => ($val|mod|mod2)'));
	Assert::same("'foo' => call_user_func(\$this->filters->mod, \$val, 'param', call_user_func(\$this->filters->mod2, 1))", formatArgs('foo => ($val|mod:param:(1|mod2))'));
	Assert::same("'foo' => call_user_func(\$this->filters->mod, \$val, 'param', call_user_func(\$this->filters->mod2, 1, round(call_user_func(\$this->filters->foo, 2))))", formatArgs('foo => ($val|mod:param:(1|mod2:round((2|foo))))'));
	Assert::same("'foo' => foo(\$val)", formatArgs('foo => foo($val)'));
	Assert::same("'foo' => foo(call_user_func(\$this->filters->bar, \$val))", formatArgs('foo => foo($val|bar)'));
	Assert::same("foo(call_user_func(\$this->filters->bar, \$val),call_user_func(\$this->filters->lorem,  \$val))", formatArgs('foo($val|bar, $val|lorem)'));
	Assert::same("'foo' => array(call_user_func(\$this->filters->bar, \$val),)", formatArgs('foo => array($val|bar,)'));
	Assert::same("[call_user_func(\$this->filters->bar, \$val),call_user_func(\$this->filters->lorem,  \$val)]", formatArgs('[$val|bar, $val|lorem]'));
	Assert::exception(function () {
		formatArgs('($val|mod:param:"param2"');
	}, 'Latte\CompileException', 'Unbalanced brackets.');

	Assert::same('call_user_func($this->filters->escape, @)',  formatArgs('(@|escape)'));
	Assert::same('LFilters::safeUrl(@)',  formatArgs('(@|safeurl)'));
});
