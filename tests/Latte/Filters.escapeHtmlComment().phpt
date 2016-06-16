<?php

/**
 * Test: Latte\Runtime\Filters::escapeHtmlComment
 */

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('', Filters::escapeHtmlComment(NULL));
Assert::same('', Filters::escapeHtmlComment(''));
Assert::same('1', Filters::escapeHtmlComment(1));
Assert::same('string', Filters::escapeHtmlComment('string'));
Assert::same('< & \' " >', Filters::escapeHtmlComment('< & \' " >'));
Assert::same('&quot;', Filters::escapeHtmlComment('&quot;'));
Assert::same('<br>', Filters::escapeHtmlComment(new Latte\Runtime\Html('<br>')));
Assert::same(' - ', Filters::escapeHtmlComment('-'));
Assert::same(' - - ', Filters::escapeHtmlComment('--'));
Assert::same(' - - - ', Filters::escapeHtmlComment('---'));
Assert::same(' >', Filters::escapeHtmlComment('>'));
Assert::same(' !', Filters::escapeHtmlComment('!'));
