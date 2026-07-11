<?php

/**
 * Test: violations of the tag parser generator protocol are reported the same way
 * for {tags} and n:attributes.
 */

declare(strict_types=1);

use Latte\Compiler\Nodes\NopNode;
use Latte\Compiler\Tag;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function compileWith(Closure $parser, string $template): void
{
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader);
	$latte->addExtension(new class ($parser) extends Latte\Extension {
		public function __construct(
			private \Closure $parser,
		) {
		}


		public function getTags(): array
		{
			return ['buggy' => $this->parser, 'n:buggy' => $this->parser];
		}
	});
	$latte->compile($template);
}


test('generator yielding more times than expected', function () {
	$parser = function (Tag $tag): Generator {
		yield;
		yield;
		return new NopNode;
	};

	Assert::exception(
		fn() => compileWith($parser, '{buggy}x{/buggy}'),
		Latte\CompileException::class,
		"Thrown exception 'Incorrect behavior of {buggy} parser, more yield calls than expected%a%",
	);

	Assert::exception(
		fn() => compileWith($parser, '<p n:buggy>x</p>'),
		Latte\CompileException::class,
		"Thrown exception 'Incorrect behavior of n:buggy parser, more yield calls than expected%a%",
	);
});


test('generator returning a non-node', function () {
	$parser = function (Tag $tag): Generator {
		yield;
		return 'not a node';
	};

	Assert::exception(
		fn() => compileWith($parser, '{buggy}x{/buggy}'),
		Latte\CompileException::class,
		"Thrown exception 'Incorrect behavior of {buggy} parser, unexpected returned value%a%",
	);

	Assert::exception(
		fn() => compileWith($parser, '<p n:buggy>x</p>'),
		Latte\CompileException::class,
		'Unexpected value returned by n:buggy parser (on line 1 at column 4)',
	);
});
