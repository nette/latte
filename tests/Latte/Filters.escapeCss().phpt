<?php

/**
 * Test: Latte\Runtime\Filters::escapeCss
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('', Filters::escapeCss(NULL));
Assert::same('', Filters::escapeCss(''));
Assert::same('1', Filters::escapeCss(1));
Assert::same('string', Filters::escapeCss('string'));
Assert::same('\<br\>', Filters::escapeCss(new Latte\Runtime\Html('<br>')));
Assert::same('\!\"\#\$\%\&\\\'\(\)\*\+\,\.\/\:\;\<\=\>\?\@\[\\\\\]\^\`\{\|\}\~', Filters::escapeCss('!"#$%&\'()*+,./:;<=>?@[\]^`{|}~'));
