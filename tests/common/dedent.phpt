<?php declare(strict_types=1);

/**
 * Test: Feature::Dedent
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function dedent(string $template, array $params = []): string
{
	$latte = createLatte();
	$latte->setFeature(Latte\Feature::Dedent);
	return $latte->renderToString($template, $params);
}


test('feature disabled by default', function () {
	$latte = createLatte();
	Assert::same("    Hello\n", $latte->renderToString("{if true}\n    Hello\n{/if}"));
});


test('basic dedent with spaces', function () {
	Assert::same("Hello\n", dedent("{if true}\n    Hello\n{/if}"));
});


test('basic dedent with tab', function () {
	Assert::same("Hello\n", dedent("{if true}\n\tHello\n{/if}"));
});


test('multiple lines', function () {
	Assert::same("Hello\nWorld\n", dedent(<<<'XX'
		{if true}
			Hello
			World
		{/if}
		XX));
});


test('deeper indentation preserved', function () {
	Assert::same("Hello\n\tIndented\n", dedent(<<<'XX'
		{if true}
			Hello
				Indented
		{/if}
		XX));
});


test('nested tags', function () {
	Assert::same("Hello\n", dedent(<<<'XX'
		{if true}
			{if true}
				Hello
			{/if}
		{/if}
		XX));
});


test('nested tags with expression', function () {
	$result = dedent(<<<'XX'
		{if true}
			{if true}
				{='val'}
			{/if}
		{/if}
		XX);
	Assert::same("val\n", $result);
});


test('if/else branches dedented independently', function () {
	Assert::same("A\n", dedent("{if true}\n\tA\n{else}\n\tB\n{/if}"));
	Assert::same("B\n", dedent("{if false}\n\tA\n{else}\n\tB\n{/if}"));
});


test('foreach', function () {
	$result = dedent(<<<'XX'
		{foreach ['a', 'b'] as $item}
			{$item}
		{/foreach}
		XX);
	Assert::same("a\nb\n", $result);
});


test('no indentation - no change', function () {
	Assert::same("Hello\n", dedent("{if true}\nHello\n{/if}"));
});


test('expression on indented line', function () {
	$result = dedent(<<<'XX'
		{if true}
			{=$x}
		{/if}
		XX, ['x' => 'val']);
	Assert::same("val\n", $result);
});


test('mixed text and expression on same line', function () {
	$result = dedent(<<<'XX'
		{if true}
			Hello {='dear'} World
		{/if}
		XX);
	Assert::same("Hello dear World\n", $result);
});


test('block tag', function () {
	Assert::same("Hello\n", dedent("{block test}\n\tHello\n{/block}"));
});


test('capture tag', function () {
	Assert::same("Hello\n", dedent("{capture \$var}\n\tHello\n{/capture}{\$var}"));
});


test('inline block content not dedented (issue #412)', function () {
	$result = dedent('<div class="page-content{block class} content-area{/block}">stuff</div>');
	Assert::same('<div class="page-content content-area">stuff</div>', $result);
});


test('expressions with OutputKeepIndentation and varying indent (issue #413)', function () {
	$result = dedent(
		<<<'XX'
			{define test}
				{var $a = 1}
				{var $b = 2}
				{=$a}
					{=$b}
				{=$a}
			{/define}
			{include test}
			XX,
	);
	Assert::same("1\n\t2\n1\n", $result);
});


test('spaces after tab indent are content, not indentation', function () {
	$result = dedent(<<<'XX'
		{foreach [1] as $item}
			{if true}
				A
			{else}
				B
			{/if}
			   C
		{/foreach}
		XX);
	Assert::same("A\n   C\n", $result);
});


test('spaces after tab indent preserved in nested blocks', function () {
	$result = dedent(<<<'XX'
		{if true}
			   hello
		{/if}
		XX);
	Assert::same("   hello\n", $result);
});


test('indented tag preserves structural indent', function () {
	$result = dedent(<<<'XX'
		    {foreach [1] as $item}
		        line A
		        line B
		    {/foreach}
		XX);
	Assert::same("    line A\n    line B\n    ", $result);
});


test('nested indented tags each strip one level', function () {
	$result = dedent(<<<'XX'
		    {foreach [1] as $item}
		        {if true}
		            foo
		        {/if}
		    {/foreach}
		XX);
	Assert::same("    foo\n    ", $result);
});


test('three levels of nesting', function () {
	$result = dedent(<<<'XX'
		{foreach [1] as $a}
			{foreach [1] as $b}
				{if true}
					foo
				{/if}
			{/foreach}
		{/foreach}
		XX);
	Assert::same("foo\n", $result);
});


test('blank lines inside content', function () {
	$result = dedent(<<<'XX'
		{if true}
			Hello

			World
		{/if}
		XX);
	Assert::same("Hello\n\nWorld\n", $result);
});


test('inconsistent indentation throws exception', function () {
	Assert::exception(
		fn() => dedent("{if true}\n\tHello\nWorld\n{/if}"),
		Latte\CompileException::class,
		'Inconsistent indentation%a%',
	);
});


test('inconsistent indentation reports line number', function () {
	try {
		dedent("{if true}\n\tHello\nWorld\n{/if}");
	} catch (Latte\CompileException $e) {
		Assert::same(3, $e->position->line);
		return;
	}
	Assert::fail('Exception expected');
});
