<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->addExtension(new Latte\Essential\TranslatorExtension(null));

Assert::contains(
	'echo LR\Filters::escapeHtmlText(($this->filters->translate)(\'var\')) /*',
	$latte->compile('{_var}'),
);

Assert::contains(
	'echo LR\Filters::escapeHtmlText(($this->filters->filter)(($this->filters->translate)(\'var\'))) /*',
	$latte->compile('{_var|filter}'),
);

Assert::contains(
	'echo LR\Filters::escapeHtmlText(($this->filters->translate)(\'messages.hello\', 10, 20)) /* line 1 */;',
	$latte->compile('{_messages.hello, 10, 20}'),
);


function translate($message, ...$parameters): string
{
	return strrev($message) . implode(',', $parameters);
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->addExtension(new Latte\Essential\TranslatorExtension('translate'));
Assert::contains(
	'echo LR\Filters::escapeHtmlText(($this->filters->translate)(\'a&b\', 1, 2))',
	$latte->compile('{_"a&b", 1, 2}'),
);
Assert::same(
	'b&amp;a1,2',
	$latte->renderToString('{_"a&b", 1, 2}'),
);


$latte->addExtension(new Latte\Essential\TranslatorExtension('translate', 'en'));
Assert::contains(
	'echo LR\Filters::escapeHtmlText(\'b&a1,2\')',
	$latte->compile('{_"a&b", 1, 2}'),
);
Assert::same(
	'b&amp;a1,2',
	$latte->renderToString('{_"a&b", 1, 2}'),
);
