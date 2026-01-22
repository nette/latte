<?php declare(strict_types=1);

/**
 * PHPStan type tests.
 */

use Latte\Essential\Filters;
use Latte\Runtime\Template;
use function PHPStan\Testing\assertType;


function testFirstString(string $s): void
{
	assertType('string', Filters::first($s));
}


/** @param array<int, string> $arr */
function testFirstArray(array $arr): void
{
	assertType('mixed', Filters::first($arr));
}


function testLastString(string $s): void
{
	assertType('string', Filters::last($s));
}


/** @param array<int, string> $arr */
function testLastArray(array $arr): void
{
	assertType('mixed', Filters::last($arr));
}


function testRandomString(string $s): void
{
	assertType('string', Filters::random($s));
}


/** @param array<int, string> $arr */
function testRandomArray(array $arr): void
{
	assertType('mixed', Filters::random($arr));
}


function testExplode(string $value): void
{
	assertType('list<string>', Filters::explode($value, ','));
}


function testReverseString(string $s): void
{
	assertType('string', Filters::reverse($s));
}


/** @param array<string, int> $arr */
function testReverseArray(array $arr): void
{
	assertType('array<string, int>', Filters::reverse($arr));
}


function testSliceString(string $s): void
{
	assertType('string', Filters::slice($s, 0, 5));
}


function testGetParameters(Template $template): void
{
	assertType('array<string, mixed>', $template->getParameters());
}


function testGetBlockNames(Template $template): void
{
	assertType('list<string>', $template->getBlockNames());
}
