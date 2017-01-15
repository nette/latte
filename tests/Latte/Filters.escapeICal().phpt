<?php

/**
 * Test: Latte\Runtime\Filters::escapeICal
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('', Filters::escapeICal(NULL));
Assert::same('', Filters::escapeICal(''));
Assert::same('1', Filters::escapeICal(1));
Assert::same('string', Filters::escapeICal('string'));
Assert::same('\"\;\\\\\,\:', Filters::escapeICal('";\,:'));
Assert::same('<br>', Filters::escapeICal(new Latte\Runtime\Html('<br>')));
Assert::same("\x09\\n", Filters::escapeICal("\x00\x01\x02\x03\x04\x05\x06\x07\x08\x09\x0a\x0b\x0c\x0d\x0e\x0f"));
Assert::same('', Filters::escapeICal("\x10\x11\x12\x13\x14\x15\x16\x17\x18\x19\x1a\x1b\x1c\x1d\x1e\x1f"));
