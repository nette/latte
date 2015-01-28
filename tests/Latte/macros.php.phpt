<?php

/**
 * Test: Latte\Engine: {php}
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match('%A%
<?php $a = \'test\' ? array() : NULL ;
%A%
', $latte->compile('
{php}
{php $a = test ? []}
'));

Assert::match('%A%
<?php $a = \'test\' ? array() : NULL ;
%A%
', $latte->compile('
{?}
{? $a = test ? []}
'));
