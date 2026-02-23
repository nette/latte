<?php declare(strict_types=1);

/**
 * n:else
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();

// errors
Assert::exception(
	fn() => $latte->compile('<span n:else></span>'),
	Latte\CompileException::class,
	'n:else must be immediately after n:if, n:foreach etc (on line 1 at column 7)',
);

Assert::exception(
	fn() => $latte->compile('<span n:elseif=1></span>'),
	Latte\CompileException::class,
	'n:else must be immediately after n:if, n:foreach etc (on line 1 at column 7)',
);

Assert::exception(
	fn() => $latte->compile('<div n:if=1>in</div> ... <span n:else></span>'),
	Latte\CompileException::class,
	'n:else must be immediately after n:if, n:foreach etc (on line 1 at column 32)',
);

Assert::exception(
	fn() => $latte->compile('<div n:inner-if=1>in</div> <span n:else></span>'),
	Latte\CompileException::class,
	'n:else must be immediately after n:if, n:foreach etc (on line 1 at column 34)',
);

Assert::exception(
	fn() => $latte->compile('{if true}in1{else}in2{/if} <span n:else></span>'),
	Latte\CompileException::class,
	'Multiple "else" found (on line 1 at column 34)',
);

Assert::exception(
	fn() => $latte->compile('<div n:if=0>if</div><p n:else>else</p><span n:elseif=1>elseif</span>'),
	Latte\CompileException::class,
	'n:else must be immediately after n:if, n:foreach etc (on line 1 at column 45)',
);



// n:if & n:else
Assert::match(
	<<<'XX'
		begin
			<div>in1</div>
		end
		XX,
	$latte->renderToString(
		<<<'XX'
			begin
				<div n:if=1>in1</div>

				<p n:else>else</p>
			end
			XX,
	),
);

Assert::match(
	<<<'XX'
		begin
		<p>else</p>
		end
		XX,
	$latte->renderToString(
		<<<'XX'
			begin
			<div n:if=0>in1</div>
			<p n:else>else</p>
			end
			XX,
	),
);


// if & n:elseif
Assert::match(
	<<<'XX'
		begin
		<div>if true</div>
		end
		XX,
	$latte->renderToString(
		<<<'XX'
			begin
			<div n:if=1>if true</div>

			<p n:elseif=1>elseif 1</p>

			<p n:elseif=1>elseif 2</p>
			end
			XX,
	),
);

Assert::match(
	<<<'XX'
		begin
		<p>elseif 2</p>
		end
		XX,
	$latte->renderToString(
		<<<'XX'
			begin
			<div n:if=0>if false</div>

			<p n:elseif=0>elseif 1</p>

			<p n:elseif=1>elseif 2</p>
			end
			XX,
	),
);

Assert::match(
	<<<'XX'
		begin
		<p>else</p>
		end
		XX,
	$latte->renderToString(
		<<<'XX'
			begin
			<div n:if=0>if false</div>

			<p n:elseif=0>elseif true</p>

			<p n:else>else</p>
			end
			XX,
	),
);


// n:foreach & n:else
Assert::match(
	<<<'XX'
		begin
		<div>in1</div>
		end
		XX,
	$latte->renderToString(
		<<<'XX'
			begin
			<div n:foreach="[1] as $x">in1</div>
			<p n:else>else</p>
			end
			XX,
	),
);

Assert::match(
	<<<'XX'
		begin
		<p>else</p>
		end
		XX,
	$latte->renderToString(
		<<<'XX'
			begin
			<div n:foreach="[] as $x">in1</div>
			<p n:else>else</p>
			end
			XX,
	),
);


// n:ifchanged & n:else
Assert::match(
	<<<'XX'
		begin
			<span>1</span>
			<p>else</p>
			<span>3</span>
		end
		XX,
	$latte->renderToString(
		<<<'XX'
			begin
			{foreach [1, 1, 3] as $i}
				<span n:ifchanged>{$i}</span>
				<p n:else>else</p>
			{/foreach}
			end
			XX,
	),
);

Assert::match(
	<<<'XX'
		begin
		<p>else</p>
		end
		XX,
	$latte->renderToString(
		<<<'XX'
			begin
			<div n:foreach="[] as $x">in1</div>
			<p n:else>else</p>
			end
			XX,
	),
);


// n:ifcontent & n:else
Assert::match(
	<<<'XX'
		begin
			<span>x</span>
			<p>else</p>
			<span>y</span>
		end
		XX,
	$latte->renderToString(
		<<<'XX'
			begin
			{foreach ['x', '', 'y'] as $i}
				<span n:ifcontent>{$i}</span>
				<p n:else>else</p>
			{/foreach}
			end
			XX,
	),
);

Assert::match(
	<<<'XX'
		begin
		<p>else</p>
		end
		XX,
	$latte->renderToString(
		<<<'XX'
			begin
			<div n:foreach="[] as $x">in1</div>
			<p n:else>else</p>
			end
			XX,
	),
);


// n:try & n:else
Assert::match(
	<<<'XX'
		begin
		<div>in1</div>
		end
		XX,
	$latte->renderToString(
		<<<'XX'
			begin
			<div n:try>in1</div>
			<p n:else>else</p>
			end
			XX,
	),
);

Assert::match(
	<<<'XX'
		begin
		<p>else</p>
		end
		XX,
	$latte->renderToString(
		<<<'XX'
			begin
			<div n:try>in1 {rollback} in2</div>
			<p n:else>else</p>
			end
			XX,
	),
);
