<?php

/**
 * Test: Latte\PhpWriter::formatModifiers()
 */

use Latte\PhpWriter;
use Latte\MacroTokens;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


function formatModifiers($arg, $modifiers, $isContent = FALSE) {
	$writer = new PhpWriter(new MacroTokens(''), $modifiers, ['html']);
	return $writer->formatModifiers($arg, $isContent);
}


test(function () { // special
	Assert::same('@',  formatModifiers('@', ''));
	Assert::same('@',  formatModifiers('@', '|'));
	Assert::exception(function () {
		formatModifiers('@', ':');
	}, 'Latte\CompileException', 'Modifier name must be alphanumeric string%a%');
	Assert::exception(function () {
		Assert::same('call_user_func($this->filters->mod, @, \'\\\\\', "a", "b", "c", "arg2")',  formatModifiers('@', "mod:'\\\\':a:b:c':arg2"));
	}, 'Latte\CompileException', 'Unexpected %a% on line 1, column 15.');
});


test(function () { // common
	Assert::same('call_user_func($this->filters->mod, @)',  formatModifiers('@', 'mod'));
	Assert::same('call_user_func($this->filters->mod3, call_user_func($this->filters->mod2, call_user_func($this->filters->mod1, @)))',  formatModifiers('@', 'mod1|mod2|mod3'));
});


test(function () { // arguments
	Assert::same('call_user_func($this->filters->mod, @, \'arg1\', 2, $var["pocet"])',  formatModifiers('@', 'mod:arg1:2:$var["pocet"]'));
	Assert::same('call_user_func($this->filters->mod, @, \'arg1\', 2, $var["pocet"])',  formatModifiers('@', 'mod,arg1,2,$var["pocet"]'));
	Assert::same('call_user_func($this->filters->mod, @, " :a:b:c", "", 3, "")',  formatModifiers('@', 'mod:" :a:b:c":"":3:""'));
	Assert::same('call_user_func($this->filters->mod, @, "\":a:b:c")',  formatModifiers('@', 'mod:"\\":a:b:c"'));
	Assert::same("call_user_func(\$this->filters->mod, @, '\':a:b:c')",  formatModifiers('@', "mod:'\\':a:b:c'"));
	Assert::same('call_user_func($this->filters->mod, @ , \'param\' , \'param\')',  formatModifiers('@', 'mod : param : param'));
	Assert::same('call_user_func($this->filters->mod, @, $var, 0, -0.0, "str", \'str\')',  formatModifiers('@', 'mod, $var, 0, -0.0, "str", \'str\''));
	Assert::same('call_user_func($this->filters->mod, @, true, false, null)',  formatModifiers('@', 'mod: true, false, null'));
	Assert::same('call_user_func($this->filters->mod, @, TRUE, FALSE, NULL)',  formatModifiers('@', 'mod: TRUE, FALSE, NULL'));
	Assert::same('call_user_func($this->filters->mod, @, \'True\', \'False\', \'Null\')',  formatModifiers('@', 'mod: True, False, Null'));
	Assert::same('call_user_func($this->filters->mod, @, array(1))',  formatModifiers('@', 'mod: array(1)'));
});

test(function() { // inline modifiers
	Assert::same('call_user_func($this->filters->mod, @, call_user_func($this->filters->mod2, 2))', formatModifiers('@', 'mod:(2|mod2)'));
});

test(function() { // FilterInfo aware modifiers
	Assert::same('LR\Filters::convertTo($_fi, \'html\', $this->filters->filterContent(\'mod\', $_fi, @))',  formatModifiers('@', 'mod', TRUE));
	Assert::same('LR\Filters::convertTo($_fi, \'html\', $this->filters->filterContent(\'mod2\', $_fi, $this->filters->filterContent(\'mod1\', $_fi, @)))',  formatModifiers('@', 'mod1|mod2', TRUE));
});
