<?php

/**
 * Test: Latte\Macros\CoreMacros: {_translate}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

// {_...}
Assert::contains(
	'echo LR\Filters::escapeHtmlText(($this->filters->translate)(\'var\')) /*',
	$latte->compile('{_var}'),
);

Assert::contains(
	'echo LR\Filters::escapeHtmlText(($this->filters->filter)(($this->filters->translate)(\'var\'))) /*',
	$latte->compile('{_var|filter}'),
);

// {_} ... {/}
Assert::match(
	<<<'XX'
		%A%
				$ʟ_fi = new LR\FilterInfo('html');
				echo LR\Filters::convertTo($ʟ_fi, 'html', $this->filters->filterContent('translate', $ʟ_fi, 'abc')) /* line 1 */;
		%A%
		XX,
	$latte->compile('{_}abc{/_}'),
);

Assert::match(
	<<<'XX'
		%A%
				$ʟ_fi = new LR\FilterInfo('html');
				echo LR\Filters::convertTo($ʟ_fi, 'html', $this->filters->filterContent('filter', $ʟ_fi, $this->filters->filterContent('translate', $ʟ_fi, 'abc'))) /* line 1 */;
		%A%
		XX,
	$latte->compile('{_|filter}abc{/_}'),
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
	$latte->compile('{_}{if true}abc{/if}{/_}'),
);

Assert::match(
	<<<'XX'
		%A%
				extract($this->params);
				return get_defined_vars();
		%A%
		XX,
	$latte->compile('{_ /}'),
);
