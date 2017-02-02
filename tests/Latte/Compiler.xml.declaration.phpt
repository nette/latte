<?php

/**
 * Test: Latte\Compiler: <?xml test.
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


function xml($v) {
	echo $v;
}

$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::contains('<<?php ?>?xml version="3.0"?>', $latte->compile('<?xml version="3.0"?>'));
Assert::contains('<<?php ?>?xml version="<?php echo LR\Filters::escapeHtml($var)', $latte->compile('<?xml version="{$var}"?>'));
Assert::contains('<<?php ?>?xml ?>', $latte->compile('<div title="<?xml ?>">'));
Assert::contains('<<?php ?>?xml ?>', $latte->compile('<div <?xml ?> >'));
Assert::contains('<<?php ?>?xml ?>', $latte->compile('<!-- <?xml ?> -->'));
Assert::contains('<<?php ?>?xml ?>', $latte->compile('<script> <?xml ?> </script>'));
