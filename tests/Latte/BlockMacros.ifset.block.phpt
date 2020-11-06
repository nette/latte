<?php

/**
 * Test: Latte\Macros\BlockMacros {ifset block}
 */

declare(strict_types=1);

use Latte\Macros\BlockMacros;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$compiler = new Latte\Compiler;
BlockMacros::install($compiler);

// {ifset ... }
Assert::same('<?php if (isset($this->blockQueue["block"])) { ?>', $compiler->expandMacro('ifset', '#block')->openingCode);
Assert::same('<?php if (isset($this->blockQueue["block"])) { ?>', $compiler->expandMacro('ifset', 'block')->openingCode);
Assert::same('<?php if (isset($this->blockQueue["block"], $item->var["#test"])) { ?>', $compiler->expandMacro('ifset', '#block, $item->var["#test"]')->openingCode);
Assert::same(
	'<?php if (isset($this->blockQueue["block1"], $this->blockQueue["block2"], $var3, item(\'abc\'))) { ?>',
	$compiler->expandMacro('ifset', '#block1, block2, $var3, item(abc)')->openingCode
);

Assert::exception(function () use ($compiler) {
	$compiler->expandMacro('ifset', '$var');
}, Latte\CompileException::class, 'Unknown tag {ifset $var}');


// {elseifset ... }
Assert::same('<?php } elseif (isset($this->blockQueue["block"])) { ?>', $compiler->expandMacro('elseifset', '#block')->openingCode);
Assert::same('<?php } elseif (isset($this->blockQueue["block"])) { ?>', $compiler->expandMacro('elseifset', 'block')->openingCode);
Assert::same('<?php } elseif (isset($this->blockQueue["block"], $item->var["#test"])) { ?>', $compiler->expandMacro('elseifset', '#block, $item->var["#test"]')->openingCode);
Assert::same(
	'<?php } elseif (isset($this->blockQueue["block1"], $this->blockQueue["block2"], $var3, item(\'abc\'))) { ?>',
	$compiler->expandMacro('elseifset', '#block1, block2, $var3, item(abc)')->openingCode
);


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(
	' test',
	$latte->renderToString('{block test}{/block} {ifset test}test{/ifset} {ifset xxx}xxx{/ifset}')
);
