<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->addExtension(new Latte\Essential\TranslatorExtension(null));

Assert::match(
	<<<'XX'
		%A%
				$ʟ_fi = new LR\FilterInfo('html');
				echo LR\Filters::convertTo($ʟ_fi, 'html', $this->filters->filterContent('translate', $ʟ_fi, 'abc')) /* line 1 */;
		%A%
		XX,
	$latte->compile('{translate}abc{/translate}'),
);

Assert::match(
	<<<'XX'
		%A%
				$ʟ_fi = new LR\FilterInfo('html');
				echo LR\Filters::convertTo($ʟ_fi, 'html', $this->filters->filterContent('filter', $ʟ_fi, $this->filters->filterContent('translate', $ʟ_fi, 'abc'))) /* line 1 */;
		%A%
		XX,
	$latte->compile('{translate|filter}abc{/translate}'),
);

Assert::match(
	<<<'XX'
		%A%
				ob_start(fn() => '');
				try {
					if (true) /* line 1 */ {
						echo 'abc';
					}

				} finally {
					$ʟ_tmp = ob_get_clean();
				}
				$ʟ_fi = new LR\FilterInfo('html');
				echo LR\Filters::convertTo($ʟ_fi, 'html', $this->filters->filterContent('translate', $ʟ_fi, $ʟ_tmp)) /* line 1 */;
		%A%
		XX,
	$latte->compile('{translate}{if true}abc{/if}{/translate}'),
);

Assert::match(
	<<<'XX'
		%A%
			{
			}
		%A%
		XX,
	$latte->compile('{translate /}'),
);
