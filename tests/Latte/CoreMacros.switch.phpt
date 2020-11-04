<?php

/**
 * Test: {switch}
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'

{switch 0}
{case ''}string
{case 0.0}flot
{default}def
{/switch}

---

{switch a}
{case a}a
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
	__DIR__ . '/expected/CoreMacros.switch.phtml',
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
