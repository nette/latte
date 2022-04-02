<?php

/**
 * Test: Latte\Compiler: <?xml test.
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function xml($v)
{
	echo $v;
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::contains('<?xml version="3.0"?>', $latte->compile('<?xml version="3.0"?>'));
Assert::match(<<<'XX'
	%A%
			echo '<?xml version="';
			echo LR\Filters::escapeHtml($var) /* line 1 */;
			echo '"?>';
	%A%
	XX, $latte->compile('<?xml version="{$var}"?>'));
Assert::contains('<?xml ?>', $latte->compile('<div title="<?xml ?>">'));
Assert::contains('<?xml ?>', $latte->compile('<div <?xml ?> >'));
Assert::contains('<?xml ?>', $latte->compile('<!-- <?xml ?> -->'));
Assert::contains('<?xml ?>', $latte->compile('<script> <?xml ?> </script>'));
