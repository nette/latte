<?php declare(strict_types=1);

/**
 * Test: {exitIf}
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();

Assert::exception(
	fn() => $latte->compile('{foreach $a as $b}{exitIf true}'),
	Latte\CompileException::class,
	'Tag {exitIf} is unexpected here (on line 1 at column 19)',
);

Assert::exception(
	fn() => $latte->compile('{exitIf}'),
	Latte\CompileException::class,
	'Missing arguments in {exitIf} (on line 1 at column 1)',
);


$template = <<<'XX'
	{exitIf true}
	c
	XX;

Assert::match(
	<<<'XX'
		%A%
			{
				if (true) /* pos 1:1 */ return;
				echo 'c';
			}
		%A%
		XX,
	$latte->compile($template),
);


$template = <<<'XX'
	a
	{exitIf true}
	b
	{exitIf false}
	c
	XX;

Assert::match(
	<<<'XX'
		%A%
			{
				echo 'a
		';
				if (true) /* pos 2:1 */ return;
				echo 'b
		';
				if (false) /* pos 4:1 */ return;
				echo 'c';
			}
		%A%
		XX,
	$latte->compile($template),
);


$template = <<<'XX'
	{block foo}
		a
		{exitIf true}
		b
		{exitIf false}
		c
	{/block}
	XX;

Assert::match(
	<<<'XX'
		%A%
			{
				echo '	a
		';
				if (true) /* pos 3:2 */ return;
				echo '	b
		';
				if (false) /* pos 5:2 */ return;
				echo '	c
		';
			}
		%A%
		XX,
	$latte->compile($template),
);


$template = <<<'XX'
	{define foo}
		a
		{exitIf true}
		b
		{exitIf false}
		c
	{/define}
	XX;

Assert::match(
	<<<'XX'
		%A%
			{
				echo '	a
		';
				if (true) /* pos 3:2 */ return;
				echo '	b
		';
				if (false) /* pos 5:2 */ return;
				echo '	c
		';
			}
		%A%
		XX,
	$latte->compile($template),
);


$template = <<<'XX'
	<div>{exitIf true}</div>
	XX;

Assert::match(
	<<<'XX'
		%A%
				echo '<div>';
				try {
					if (true) /* pos 1:6 */ return;
				} finally {
					echo '</div>';
				}
		%A%
		XX,
	$latte->compile($template),
);
