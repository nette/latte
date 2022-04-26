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
Assert::same('<?php if ($this->hasBlock("block")) { ?>', $compiler->expandMacro('ifset', '#block')->openingCode);
Assert::same('<?php if ($this->hasBlock("block")) { ?>', $compiler->expandMacro('ifset', 'block')->openingCode);
Assert::same('<?php if ($this->hasBlock($foo)) { ?>', $compiler->expandMacro('ifset', '#$foo')->openingCode);
Assert::same('<?php if ($this->hasBlock("foo")) { ?>', $compiler->expandMacro('ifset', 'block foo')->openingCode);
Assert::same('<?php if ($this->hasBlock($foo)) { ?>', $compiler->expandMacro('ifset', 'block $foo')->openingCode);
Assert::same('<?php if ($this->hasBlock(("f" . "oo"))) { ?>', $compiler->expandMacro('ifset', 'block "f" . "oo"')->openingCode);
Assert::same(
	'<?php if ($this->hasBlock("foo") && $this->hasBlock("block") && isset($item)) { ?>',
	$compiler->expandMacro('ifset', 'block foo, block, $item')->openingCode,
);
Assert::same(
	'<?php if ($this->hasBlock("block") && isset($item->var["#test"])) { ?>',
	$compiler->expandMacro('ifset', '#block, $item->var["#test"]')->openingCode,
);
Assert::same(
	'<?php if ($this->hasBlock("block1") && $this->hasBlock("block2") && isset($var3) && isset(item(\'abc\'))) { ?>',
	$compiler->expandMacro('ifset', '#block1, block2, $var3, item(abc)')->openingCode,
);
Assert::same(
	'<?php if ($this->hasBlock("footer") && $this->hasBlock("header") && $this->hasBlock("main")) { ?>',
	$compiler->expandMacro('ifset', 'footer, header, main')->openingCode,
);

Assert::exception(
	fn() => $compiler->expandMacro('ifset', '$var'),
	Latte\CompileException::class,
	'Unknown tag {ifset $var}',
);


// {elseifset ... }
Assert::same('<?php } elseif ($this->hasBlock("block")) { ?>', $compiler->expandMacro('elseifset', '#block')->openingCode);
Assert::same('<?php } elseif ($this->hasBlock("block")) { ?>', $compiler->expandMacro('elseifset', 'block')->openingCode);
Assert::same('<?php } elseif ($this->hasBlock("block") && isset($item->var["#test"])) { ?>', $compiler->expandMacro('elseifset', '#block, $item->var["#test"]')->openingCode);
Assert::same(
	'<?php } elseif ($this->hasBlock("block1") && $this->hasBlock("block2") && isset($var3) && isset(item(\'abc\'))) { ?>',
	$compiler->expandMacro('elseifset', '#block1, block2, $var3, item(abc)')->openingCode,
);


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(
	' test',
	$latte->renderToString('{block test}{/block} {ifset test}test{/ifset} {ifset xxx}xxx{/ifset}'),
);
