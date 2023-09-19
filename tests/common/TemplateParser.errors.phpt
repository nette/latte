<?php

declare(strict_types=1);

use Latte\Compiler\Nodes\NopNode;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function parse($s, $tag)
{
	$parser = new Latte\Compiler\TemplateParser;
	$parser->addTags(['foo' => $tag]);
	$parser->parse($s);
}


Assert::exception(
	fn() => parse(
		'{foo}',
		function () use (&$dontChangeToArrow) {
			return new NopNode;
			yield [];
		},
	),
	LogicException::class,
	'Incorrect behavior of {foo} parser, yield call is expected (on line 1)',
);


Assert::exception(
	fn() => parse(
		'{foo}{/foo}',
		function () use (&$dontChangeToArrow) {
			yield;
			yield;
		},
	),
	LogicException::class,
	'Incorrect behavior of {foo} parser, more yield calls than expected (on line 1)',
);
