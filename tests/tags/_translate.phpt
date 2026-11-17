<?php declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();
$latte->addExtension(new Latte\Essential\TranslatorExtension(null));

Assert::contains(
	'echo ((is_object($ʟ_val = ($this->filters->translate)(\'var\')) ? LR\Helpers::convertTrusted($ʟ_val, \'html\') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /*',
	$latte->compile('{_var}'),
);

Assert::contains(
	'echo ((is_object($ʟ_val = ($this->filters->filter)(($this->filters->translate)(\'var\'))) ? LR\Helpers::convertTrusted($ʟ_val, \'html\') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /*',
	$latte->compile('{_var|filter}'),
);

Assert::contains(
	'echo ($this->filters->translate)(\'var\') /*',
	$latte->compile('{_var|noescape}'),
);

Assert::contains(
	'echo ((is_object($ʟ_val = ($this->filters->translate)(\'messages.hello\', 10, 20)) ? LR\Helpers::convertTrusted($ʟ_val, \'html\') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val)) /* pos 1:1 */;',
	$latte->compile('{_messages.hello, 10, 20}'),
);


function translate($message, ...$parameters): string
{
	return strrev($message) . implode(',', $parameters);
}


$latte = createLatte();
$latte->addExtension(new Latte\Essential\TranslatorExtension('translate'));
Assert::contains(
	'echo ((is_object($ʟ_val = ($this->filters->translate)(\'a&b\', 1, 2)) ? LR\Helpers::convertTrusted($ʟ_val, \'html\') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val))',
	$latte->compile('{_"a&b", 1, 2}'),
);
Assert::same(
	'b&amp;a1,2',
	$latte->renderToString('{_"a&b", 1, 2}'),
);


$latte->addExtension(new Latte\Essential\TranslatorExtension('translate', 'en'));
Assert::contains(
	'echo ((is_object($ʟ_val = \'b&a1,2\') ? LR\Helpers::convertTrusted($ʟ_val, \'html\') : null) ?? LR\HtmlHelpers::escapeText($ʟ_val))',
	$latte->compile('{_"a&b", 1, 2}'),
);
Assert::same(
	'b&amp;a1,2',
	$latte->renderToString('{_"a&b", 1, 2}'),
);
