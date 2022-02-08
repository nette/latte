<?php

/**
 * Test: Latte\Macros\CoreMacros: {_translate}
 */

declare(strict_types=1);

use Latte\Macros\CoreMacros;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$compiler = new Latte\Compiler;
CoreMacros::install($compiler);

// {_...}
Assert::same('<?php echo LR\Filters::escapeHtmlText(($this->filters->translate)(\'var\')); ?>', $compiler->expandMacro('_', 'var', '')->openingCode);
Assert::same('<?php echo LR\Filters::escapeHtmlText(($this->filters->filter)(($this->filters->translate)(\'var\'))); ?>', $compiler->expandMacro('_', 'var', '|filter')->openingCode);
