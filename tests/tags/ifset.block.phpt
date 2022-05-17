<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

// {ifset ... }
Assert::contains('if ($this->hasBlock("block")) ', $latte->compile('{ifset #block/}'));
Assert::contains('if ($this->hasBlock("block")) ', $latte->compile('{ifset block/}'));
Assert::contains('if ($this->hasBlock($foo)) ', $latte->compile('{ifset #$foo/}'));
Assert::contains('if ($this->hasBlock("foo")) ', $latte->compile('{ifset block foo/}'));
Assert::contains('if ($this->hasBlock($foo)) ', $latte->compile('{ifset block $foo/}'));
Assert::contains('if ($this->hasBlock(("f" . "oo"))) ', $latte->compile('{ifset block "f" . "oo"/}'));
Assert::contains(
	'if ($this->hasBlock("foo") && $this->hasBlock("block") && isset($item)) ',
	$latte->compile('{ifset block foo, block, $item/}'),
);
Assert::contains(
	'if ($this->hasBlock("block") && isset($item->var["#test"])) ',
	$latte->compile('{ifset #block, $item->var["#test"]/}'),
);
Assert::contains(
	'if ($this->hasBlock("block1") && $this->hasBlock("block2") && isset($var3) && isset(item(\'abc\'))) ',
	$latte->compile('{ifset #block1, block2, $var3, item(abc)/}'),
);
Assert::contains(
	'if ($this->hasBlock("footer") && $this->hasBlock("header") && $this->hasBlock("main")) ',
	$latte->compile('{ifset footer, header, main/}'),
);


// {elseifset ... }
Assert::contains('} elseif ($this->hasBlock("block")) ', $latte->compile('{if 1}{elseifset #block}{/if}'));
Assert::contains('} elseif ($this->hasBlock("block")) ', $latte->compile('{if 1}{elseifset block}{/if}'));
Assert::contains('} elseif ($this->hasBlock("block") && isset($item->var["#test"])) ', $latte->compile('{if 1}{elseifset #block, $item->var["#test"]}{/if}'));
Assert::contains(
	'} elseif ($this->hasBlock("block1") && $this->hasBlock("block2") && isset($var3) && isset(item(\'abc\'))) ',
	$latte->compile('{if 1}{elseifset #block1, block2, $var3, item(abc)}{/if}'),
);


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(
	' test',
	$latte->renderToString('{block test}{/block} {ifset test}test{/ifset} {ifset xxx}xxx{/ifset}'),
);
