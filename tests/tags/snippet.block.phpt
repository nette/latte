<?php

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'
<div n:snippet="snippet" n:block="block1">
		static
</div>


{snippet outer}
begin
<div n:snippet="inner-$id" n:block="block2">
		dynamic
</div>
end
{/snippet}
EOD;

Assert::matchFile(
	__DIR__ . '/expected/snippet.block.phtml',
	$latte->compile($template)
);
