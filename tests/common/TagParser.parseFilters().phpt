<?php

declare(strict_types=1);

use Latte\Compiler\PrintContext;
use Latte\Compiler\TagLexer;
use Latte\Compiler\TagParser;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function format(string $str, bool $content = false)
{
	$tokens = (new TagLexer)->tokenize($str);
	$parser = new TagParser($tokens);
	$node = $parser->parseModifier();
	if (!$parser->isEnd()) {
		$parser->stream->throwUnexpectedException();
	}
	return $content
		? $node->printContentAware(new PrintContext, '@')
		: $node->printSimple(new PrintContext, '@');
}


test('common', function () {
	Assert::same('($this->filters->mod)(@)', format('|mod'));
	Assert::same('($this->filters->mod3)(($this->filters->mod2)(($this->filters->mod1)(@)))', format('|mod1|mod2|mod3'));
});


test('arguments', function () {
	Assert::same("(\$this->filters->mod)(@, 'arg1', 2, \$var['pocet'])", format('|mod,arg1,2,$var["pocet"]'));
	Assert::same("(\$this->filters->mod)(@, ' ,a,b,c', '', 3, '')", @format('|mod:" ,a,b,c", "", 3, ""'));
	Assert::same("(\$this->filters->mod)(@, '\",a,b,c')", format('|mod:"\\",a,b,c"'));
	Assert::same("(\$this->filters->mod)(@, '\\',a,b,c')", format("|mod:'\\',a,b,c'"));
	Assert::same("(\$this->filters->mod)(@, 'param', 'param')", @format('|mod , param , param'));
	Assert::same("(\$this->filters->mod)(@, \$var, 0, -0.0, 'str', 'str')", format('|mod, $var, 0, -0.0, "str", \'str\''));
	Assert::same('($this->filters->mod)(@, true, false, null)', format('|mod: true, false, null'));
	Assert::same('($this->filters->mod)(@, true, false, null)', format('|mod: TRUE, FALSE, NULL'));
	Assert::same('($this->filters->mod)(@, true, false, null)', format('|mod: True, False, Null'));
	Assert::same('($this->filters->mod)(@, [1])', format('|mod: array(1)'));
});

test('inline modifiers', function () {
	Assert::same('($this->filters->mod)(@, ($this->filters->mod2)(2))', format('|mod:(2|mod2)'));
});

test('FilterInfo aware modifiers', function () {
	Assert::same('$this->filters->filterContent(\'mod\', $ʟ_fi, @)', format('|mod', true));
	Assert::same('$this->filters->filterContent(\'escape\', $ʟ_fi, $this->filters->filterContent(\'mod2\', $ʟ_fi, $this->filters->filterContent(\'mod1\', $ʟ_fi, @)))', format('|mod1|mod2|escape', true));
});

test('depth', function () {
	Assert::same('($this->filters->mod)(@, 1 ? 2 : 3)', format('|mod:(1?2:3)'));
});


test('optionalChainingPass', function () {
	Assert::same(
		'($this->filters->mod)(@, $var?->prop?->elem[1]?->call(2)?->item)',
		format('|mod:$var?->prop?->elem[1]?->call(2)?->item'),
	);
	Assert::same(
		'($this->filters->mod)(@, ((((($var ?? null)?->prop ?? null)?->elem)[1] ?? null)?->call(2) ?? null)?->item)',
		format('|mod:$var??->prop??->elem[1]??->call(2)??->item'),
	);
});


test('named arguments', function () {
	Assert::same('($this->filters->mod)(@, a: 1)', format('|mod:a: 1'));
	Assert::same('($this->filters->mod)(@, a: 1, b: 2)', format('|mod:a: 1, b: 2'));
});
