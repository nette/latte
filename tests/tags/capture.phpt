<?php

/**
 * Test: {capture}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(
	'',
	$latte->renderToString('{capture $var}<html>{/capture}'),
);

Assert::match(
	'string',
	$latte->renderToString('{capture $var}{/capture}{=gettype($var)}'),
);

Assert::match(
	Latte\Runtime\Html::class,
	$latte->renderToString('{capture $var}<html>{/capture}{=get_class($var)}'),
);

Assert::match(
	'TEST',
	$latte->renderToString('{capture$var|stripHtml|upper}<b>Test</b>{/capture}{=$var}'),
);

Assert::noError(function () use ($latte) { // uses keyword new
	$latte->setPolicy(Latte\Sandbox\SecurityPolicy::createSafePolicy());
	$latte->setSandboxMode();
	$latte->renderToString('{capture $var}<html>{/capture}');
});

Assert::match( // bug #215
	'',
	$latte->renderToString('{capture $var|strip} <html> {/capture}'),
);

Assert::match(
	'<!--  --> &lt;foo&gt;',
	$latte->renderToString('<!-- {capture $x}<foo>{/capture} --> {$x}'),
);

Assert::exception(
	fn() => $latte->renderToString('{capture $x->x() |foo}{/capture}'),
	Latte\CompileException::class,
	"It is not possible to write into '\$x->x()' in {capture} (on line 1 at column 1)",
);

$node = $latte->parse('{capture $var|strip}...{/capture}');
Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			Capture:
				Variable:
					name: var
				Modifier:
					Filter:
						Identifier:
							name: strip
				Fragment:
					Text:
						content: '...'
	XX, exportAST($node));
