<?php

/**
 * Test: Latte\Runtime\Filters::escapeXmlAttrUnquoted
 */

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('""', Filters::escapeXmlAttrUnquoted(NULL));
Assert::same('""', Filters::escapeXmlAttrUnquoted(''));
Assert::same('1', Filters::escapeXmlAttrUnquoted(1));
Assert::same('string', Filters::escapeXmlAttrUnquoted('string'));
Assert::same('N:string-string', Filters::escapeXmlAttrUnquoted('N:string-string'));
Assert::same('"&lt; &amp; &#039; &quot; &gt;"', Filters::escapeXmlAttrUnquoted('< & \' " >'));
Assert::same('"&lt;br&gt;"', Filters::escapeXmlAttrUnquoted(new Latte\Runtime\Html('<br>')));
Assert::same('"`hello"', Filters::escapeXmlAttrUnquoted('`hello'));
Assert::same("\"\x09\x0a\x0d\"", Filters::escapeXmlAttrUnquoted("\x00\x01\x02\x03\x04\x05\x06\x07\x08\x09\x0a\x0b\x0c\x0d\x0e\x0f"));
Assert::same('""', Filters::escapeXmlAttrUnquoted("\x10\x11\x12\x13\x14\x15\x16\x17\x18\x19\x1a\x1b\x1c\x1d\x1e\x1f"));
