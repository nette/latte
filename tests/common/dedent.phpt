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
	Assert::same("Hello\nWorld\n", dedent("{if true}\n\tHello\n\tWorld\n{/if}"));
});


test('deeper indentation preserved', function () {
	Assert::same("Hello\n\tIndented\n", dedent("{if true}\n\tHello\n\t\tIndented\n{/if}"));
});


test('nested tags', function () {
	Assert::same("Hello\n", dedent("{if true}\n\t{if true}\n\t\tHello\n\t{/if}\n{/if}"));
});


test('nested tags with expression', function () {
	$result = dedent("{if true}\n\t{if true}\n\t\t{=\$x}\n\t{/if}\n{/if}", ['x' => 'val']);
	Assert::same("val\n", $result);
});


test('if/else branches dedented independently', function () {
	Assert::same("A\n", dedent("{if true}\n\tA\n{else}\n\tB\n{/if}"));
	Assert::same("B\n", dedent("{if false}\n\tA\n{else}\n\tB\n{/if}"));
});


test('foreach', function () {
	$result = dedent("{foreach \$items as \$item}\n\t{\$item}\n{/foreach}", ['items' => ['a', 'b']]);
	Assert::same("a\nb\n", $result);
});


test('no indentation - no change', function () {
	Assert::same("Hello\n", dedent("{if true}\nHello\n{/if}"));
});


test('expression on indented line', function () {
	$result = dedent("{if true}\n\t{=\$x}\n{/if}", ['x' => 'val']);
	Assert::same("val\n", $result);
});


test('mixed text and expression on same line', function () {
	$result = dedent("{if true}\n\tHello {=\$x} World\n{/if}", ['x' => 'dear']);
	Assert::same("Hello dear World\n", $result);
});


test('block tag', function () {
	Assert::same("Hello\n", dedent("{block test}\n\tHello\n{/block}"));
});


test('capture tag', function () {
	Assert::same("Hello\n", dedent("{capture \$var}\n\tHello\n{/capture}{\$var}"));
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
