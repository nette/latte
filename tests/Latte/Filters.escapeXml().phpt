<?php

/**
 * Test: Latte\Runtime\Filters::escapeXml
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('', Filters::escapeXml(NULL));
Assert::same('', Filters::escapeXml(''));
Assert::same('1', Filters::escapeXml(1));
Assert::same('string', Filters::escapeXml('string'));
Assert::same('&lt; &amp; &#039; &quot; &gt;', Filters::escapeXml('< & \' " >'));
Assert::same('&lt;br&gt;', Filters::escapeXml(new Latte\Runtime\Html('<br>')));
Assert::same("\x09\x0a\x0d", Filters::escapeXml("\x00\x01\x02\x03\x04\x05\x06\x07\x08\x09\x0a\x0b\x0c\x0d\x0e\x0f"));
Assert::same('', Filters::escapeXml("\x10\x11\x12\x13\x14\x15\x16\x17\x18\x19\x1a\x1b\x1c\x1d\x1e\x1f"));
