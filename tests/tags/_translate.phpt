<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();
$latte->addExtension(new Latte\Essential\TranslatorExtension(null));

Assert::contains(
	'echo LR\HtmlHelpers::escapeText(($this->filters->translate)(\'var\')) /*',
	$latte->compile('{_var}'),
);

Assert::contains(
	'echo LR\HtmlHelpers::escapeText(($this->filters->filter)(($this->filters->translate)(\'var\'))) /*',
	$latte->compile('{_var|filter}'),
);

Assert::contains(
	'echo ($this->filters->translate)(\'var\') /*',
	$latte->compile('{_var|noescape}'),
);

Assert::contains(
	'echo LR\HtmlHelpers::escapeText(($this->filters->translate)(\'messages.hello\', 10, 20)) /* pos 1:1 */;',
	$latte->compile('{_messages.hello, 10, 20}'),
);


function translate($message, ...$parameters): string
{
	return strrev($message) . implode(',', $parameters);
}


$latte = createLatte();
$latte->addExtension(new Latte\Essential\TranslatorExtension('translate'));
Assert::contains(
	'echo LR\HtmlHelpers::escapeText(($this->filters->translate)(\'a&b\', 1, 2))',
	$latte->compile('{_"a&b", 1, 2}'),
);
Assert::same(
	'b&amp;a1,2',
	$latte->renderToString('{_"a&b", 1, 2}'),
);


$latte->addExtension(new Latte\Essential\TranslatorExtension('translate', 'en'));
Assert::contains(
	'echo LR\HtmlHelpers::escapeText(\'b&a1,2\')',
	$latte->compile('{_"a&b", 1, 2}'),
);
Assert::same(
	'b&amp;a1,2',
	$latte->renderToString('{_"a&b", 1, 2}'),
);
