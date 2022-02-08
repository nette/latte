<?php

/**
 * Test: {switch}
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::exception(function () use ($latte) {
	$latte->compile('{case}');
}, Latte\CompileException::class, 'Tag {case} is unexpected here.');

Assert::exception(function () use ($latte) {
	$latte->compile('{switch}{case}{/switch}');
}, Latte\CompileException::class, 'Missing arguments in {case}');

Assert::exception(function () use ($latte) {
	$latte->compile('{switch}{default 123}{/switch}');
}, Latte\CompileException::class, 'Arguments are not allowed in {default}');

Assert::exception(function () use ($latte) {
	$latte->compile('{switch}{default}{default}{/switch}');
}, Latte\CompileException::class, 'Tag {switch} may only contain one {default} clause.');

Assert::exception(function () use ($latte) {
	$latte->compile('{switch}{default}{case 1}{/switch}');
}, Latte\CompileException::class, 'Tag {default} must follow after {case} clause.');


$template = <<<'EOD'

{switch 0}
{case ''}string
{case 0.0}flot
{default}def
{/switch}

---

{switch a}
{case 1, 2, a}a
{/switch}

---

{switch a}
{default}def
{/switch}

---

{switch a}
{/switch}

EOD;

Assert::matchFile(
	__DIR__ . '/expected/switch.phtml',
	$latte->compile($template)
);

Assert::match(
	'
def

---

a

---

def

---
',
	$latte->renderToString($template)
);
