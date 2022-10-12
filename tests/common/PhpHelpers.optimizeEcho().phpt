<?php

declare(strict_types=1);

use Latte\Compiler\PhpHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(
	<<<'XX'
		<?php echo 'xy';
		XX,
	PhpHelpers::optimizeEcho(<<<'XX'
		<?php echo 'x'; echo 'y';
		XX),
);


Assert::match(
	<<<'XX'
		<?php echo "\\n"; echo 'x'; echo "\\n"; echo 'y';
		XX,
	PhpHelpers::optimizeEcho(<<<'XX'
		<?php echo "\\n"; echo 'x'; echo "\\n"; echo 'y';
		XX),
);
