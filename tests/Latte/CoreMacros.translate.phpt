<?php

/**
 * Test: Latte\Macros\CoreMacros: {_translate}
 */

use Latte\Macros\CoreMacros;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$compiler = new Latte\Compiler;
CoreMacros::install($compiler);

// {_...}
Assert::same('<?php echo call_user_func($this->filters->escape, call_user_func($this->filters->translate, \'var\')) ?>',  $compiler->expandMacro('_', 'var', '')->openingCode);
Assert::same('<?php echo call_user_func($this->filters->escape, call_user_func($this->filters->filter, call_user_func($this->filters->translate, \'var\'))) ?>',  $compiler->expandMacro('_', 'var', '|filter')->openingCode);
