<?php

/**
 * Test: unclosed macro.
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::exception(function () use ($latte) {
	$latte->compile('{if 1}');
}, Latte\CompileException::class, 'Unexpected end, expecting {/if}');

Assert::exception(function () use ($latte) {
	$latte->compile('<p n:foreach=1><span n:if=1>');
}, Latte\CompileException::class, 'Unexpected end, expecting </span> for element started on line 1');

Assert::exception(function () use ($latte) {
	$latte->compile('<p n:foreach=1><span n:if=1></i>');
}, Latte\CompileException::class, 'Unexpected </i, expecting </span> for element started on line 1');

Assert::exception(function () use ($latte) {
	$latte->compile('{/if}');
}, Latte\CompileException::class, 'Unexpected {/if}');

Assert::exception(function () use ($latte) {
	$latte->compile('{if 1}{/foreach}');
}, Latte\CompileException::class, 'Unexpected {/foreach}, expecting {/if}');

Assert::exception(function () use ($latte) {
	$latte->compile('{if 1}{/if 2}');
}, Latte\CompileException::class, 'Unexpected {/if 2}, expecting {/if}');

Assert::exception(function () use ($latte) {
	$latte->compile('<span n:if=1 n:foreach=2>{foreach x}</span>');
}, Latte\CompileException::class, 'Unexpected end, expecting {/foreach}');

Assert::exception(function () use ($latte) {
	$latte->compile('<span n:if=1 n:foreach=2>{/foreach}');
}, Latte\CompileException::class, 'Unexpected {/foreach}, expecting </span> for element started on line 1');

Assert::exception(function () use ($latte) {
	$latte->compile('<span n:if=1 n:foreach=2>{/if}');
}, Latte\CompileException::class, 'Unexpected {/if}, expecting </span> for element started on line 1');

Assert::exception(function () use ($latte) {
	$latte->compile(
		<<<'XX'

				{foreach [] as $item}
					<li><a n:tag-if="$iterator->odd"></li>
				{/foreach}

			XX,
	);
}, Latte\CompileException::class, 'Unexpected </li, expecting </a> for element started on line 3 (on line 3)');
