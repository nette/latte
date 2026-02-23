<?php declare(strict_types=1);

/**
 * Test: Latte\Essential\CoreExtension::getFilters()
 */

use Latte\Essential\CoreExtension;
use Latte\Runtime\FilterInfo;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$ext = new CoreExtension;
$filters = $ext->getFilters();

if (extension_loaded('mbstring')) {
	Assert::same('Hello', ($filters['capitalize'])('hello'));
	Assert::same('Ahoj', ($filters['firstUpper'])('ahoj'));
	Assert::same('abc', ($filters['lower'])('ABC'));
	Assert::same('ABC', ($filters['upper'])('abc'));
}
Assert::same('a%20b', ($filters['escapeUrl'])('a b'));
Assert::same('foo bar', ($filters['replaceRe'])('foo baz', '/baz/', 'bar'));
Assert::same('foo bar', ($filters['replaceRE'])('foo baz', '/baz/', 'bar'));
Assert::true(str_starts_with(($filters['dataStream'])('abc'), 'data:'));
Assert::true(str_starts_with(($filters['datastream'])('abc'), 'data:'));
Assert::same(['a', 'b'], ($filters['explode'])('a,b', ','));
Assert::same('a,b', ($filters['implode'])(['a', 'b'], ','));
Assert::same('a,b', ($filters['join'])(['a', 'b'], ','));
Assert::same('abc', ($filters['trim'])(new FilterInfo('html'), ' abc '));
Assert::same('abc', ($filters['strip'])(new FilterInfo('html'), '  abc  '));
Assert::same('abc', ($filters['stripHtml'])(new FilterInfo('html'), '<b>abc</b>'));
Assert::same('abc', ($filters['stripTags'])(new FilterInfo('html'), '<b>abc</b>'));
Assert::same('abc', ($filters['striptags'])(new FilterInfo('html'), '<b>abc</b>'));
Assert::same('abc', ($filters['striphtml'])(new FilterInfo('html'), '<b>abc</b>'));
Assert::same('abc', ($filters['substr'])('abc', 0, 3));
Assert::same('abc', ($filters['truncate'])('abc', 10));
Assert::same('abc', ($filters['reverse'])('cba'));
Assert::same('abc', ($filters['replace'])(new FilterInfo('html'), 'abc', 'a', 'a'));
Assert::same('abc', ($filters['first'])(['abc']));
Assert::same('abc', ($filters['last'])(['abc']));
Assert::same(1, ($filters['length'])([1]));
Assert::same(' ', ($filters['padLeft'])('', 1));
Assert::same(' ', ($filters['padRight'])('', 1));
Assert::same(1.0, ($filters['round'])(1));
Assert::same(1.0, ($filters['floor'])(1.1));
Assert::same(2.0, ($filters['ceil'])(1.1));
Assert::same('abc', ($filters['escape'])('abc'));
Assert::same('abc', ($filters['escapeHtml'])('abc'));
Assert::same('abc', ($filters['escapeHtmlComment'])('abc'));
Assert::same('abc', ($filters['escapeICal'])('abc'));
Assert::same('"abc"', ($filters['escapeJs'])('abc'));
Assert::same('abc', ($filters['escapeXml'])('abc'));
Assert::type(Latte\Runtime\Html::class, ($filters['breakLines'])('abc'));
Assert::type(Latte\Runtime\Html::class, ($filters['breaklines'])('abc'));
Assert::same("\tabc", ($filters['indent'])(new FilterInfo('html'), 'abc'));
Assert::same('123', ($filters['number'])(123));
Assert::same('a=b', ($filters['query'])(['a' => 'b']));
Assert::same('abc', ($filters['random'])(['abc']));
Assert::same('abc', ($filters['repeat'])(new FilterInfo('html'), 'abc', 1));
Assert::same(['abc'], ($filters['slice'])(['abc'], 0, 1));
Assert::same(['abc'], ($filters['sort'])(['abc']));
Assert::same('abc', ($filters['spaceless'])(new FilterInfo('html'), 'abc'));
Assert::same(['a', 'b'], ($filters['split'])('a,b', ','));
Assert::type(Latte\Essential\AuxiliaryIterator::class, ($filters['group'])([], fn($a) => $a * 10));
Assert::same(1, ($filters['clamp'])(1, 1, 1));
Assert::same('11. 6. 2025', ($filters['date'])(1_749_596_689));
Assert::type(Generator::class, ($filters['batch'])(['abc'], 1));
if (class_exists('Nette\Utils\Strings')) {
	Assert::same('abc', ($filters['webalize'])('abc'));
}
