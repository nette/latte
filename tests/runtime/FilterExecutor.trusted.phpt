<?php declare(strict_types=1);

/**
 * Test: Latte\Runtime\FilterExecutor and trusted content
 */

use Latte\ContentType;
use Latte\Runtime\{FilterExecutor, FilterInfo, HtmlStringable, TrustedCss, TrustedHtml, TrustedJavaScript, TrustedXml};
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class MyJavaScript extends TrustedJavaScript
{
}


class MyHtml implements HtmlStringable
{
	public function __toString(): string
	{
		return '<i>my</i>';
	}
}


$filters = new FilterExecutor;
$filters->add('describe', function (FilterInfo $info, $val, ?string $newType = null) {
	$res = var_export($info->contentType, true) . ',' . get_debug_type($val) . ',' . $val;
	if (func_num_args() > 2) {
		$info->contentType = $newType;
	}
	return $res;
});

$filterContent = fn(?string $type, mixed $val) => $filters->filterContent('describe', new FilterInfo($type), $val);


test('content-aware filter called as classic gets a trusted value as a string of its type', function () use ($filters) {
	Assert::equal(new TrustedHtml("'html',string,<b>"), ($filters->describe)(new TrustedHtml('<b>')));
	Assert::equal(new TrustedHtml("'html',string,<i>my</i>"), ($filters->describe)(new MyHtml));
	Assert::equal(new TrustedJavaScript("'js',string,a()"), ($filters->describe)(new TrustedJavaScript('a()')));
	Assert::equal(new TrustedJavaScript("'js',string,b()"), ($filters->describe)(new MyJavaScript('b()')));
	Assert::equal(new TrustedCss("'css',string,a{}"), ($filters->describe)(new TrustedCss('a{}')));
	Assert::equal(new TrustedXml("'xml',string,<x/>"), ($filters->describe)(new TrustedXml('<x/>')));
});


test('the result is wrapped in the class of the type the filter left', function () use ($filters) {
	Assert::equal(new TrustedHtml("'js',string,a()"), ($filters->describe)(new TrustedJavaScript('a()'), ContentType::Html));
	Assert::equal(new TrustedCss("'js',string,a()"), ($filters->describe)(new TrustedJavaScript('a()'), ContentType::Css));
	Assert::equal(new TrustedXml("'html',string,<b>"), ($filters->describe)(new TrustedHtml('<b>'), ContentType::Xml));
	Assert::same("'css',string,a{}", ($filters->describe)(new TrustedCss('a{}'), ContentType::Text));
	Assert::same("'xml',string,<x/>", ($filters->describe)(new TrustedXml('<x/>'), ContentType::ICal));
	Assert::same("'js',string,a()", ($filters->describe)(new TrustedJavaScript('a()'), 'html/raw/js'));
	Assert::same("'html',string,<b>", ($filters->describe)(new TrustedHtml('<b>'), null));
});


test('a filter may declare the type of its output for any value', function () use ($filters) {
	Assert::equal(new TrustedJavaScript('NULL,string,a()'), ($filters->describe)('a()', ContentType::JavaScript));
	Assert::equal(new TrustedHtml('NULL,string,<b>'), ($filters->describe)('<b>', ContentType::Html));
});


test('other values are passed unchanged and without a type', function () use ($filters) {
	$obj = new class {
		public function __toString(): string
		{
			return '<b>';
		}
	};
	Assert::same('NULL,class@anonymous,<b>', ($filters->describe)($obj));
	Assert::same('NULL,Nette\Utils\Html,<b></b>', ($filters->describe)(Nette\Utils\Html::el('b')));
	Assert::same('NULL,string,<b>', ($filters->describe)('<b>'));
	Assert::same('NULL,int,123', ($filters->describe)(123));
	Assert::same('NULL,null,', ($filters->describe)(null));
});


test('filter over content gets an object of the type of the content as a string', function () use ($filterContent) {
	Assert::same("'js',string,a()", $filterContent(ContentType::JavaScript, new TrustedJavaScript('a()')));
	Assert::same("'js',string,b()", $filterContent(ContentType::JavaScript, new MyJavaScript('b()')));
	Assert::same("'css',string,a{}", $filterContent(ContentType::Css, new TrustedCss('a{}')));
	Assert::same("'xml',string,<x/>", $filterContent(ContentType::Xml, new TrustedXml('<x/>')));
	Assert::same("'html',string,<b>", $filterContent(ContentType::Html, new TrustedHtml('<b>')));
	Assert::same("'html/rcdata',string,<b>", $filterContent('html/rcdata', new TrustedHtml('<b>')));
	Assert::same("'xml',string,<b>", $filterContent(ContentType::Xml, new TrustedHtml('<b>'))); // {capture} in XML
});


test('filter over content gets an object of another type unchanged', function () use ($filterContent) {
	Assert::same("'html',Latte\\Runtime\\TrustedJavaScript,a()", $filterContent(ContentType::Html, new TrustedJavaScript('a()')));
	Assert::same("'html/raw/js',Latte\\Runtime\\TrustedJavaScript,a()", $filterContent('html/raw/js', new TrustedJavaScript('a()')));
	Assert::same("'js',Latte\\Runtime\\TrustedCss,a{}", $filterContent(ContentType::JavaScript, new TrustedCss('a{}')));
	Assert::same("'html',Latte\\Runtime\\TrustedXml,<x/>", $filterContent(ContentType::Html, new TrustedXml('<x/>')));
	Assert::same("'js',Latte\\Runtime\\TrustedHtml,<b>", $filterContent(ContentType::JavaScript, new TrustedHtml('<b>')));
	Assert::same("'html/attr',Latte\\Runtime\\TrustedHtml,<b>", $filterContent('html/attr', new TrustedHtml('<b>')));
});


test('classic filter over text content gets a trusted object unchanged', function () {
	$filters = new FilterExecutor;
	$filters->add('cls', fn($val) => get_debug_type($val));
	Assert::same(TrustedJavaScript::class, $filters->filterContent('cls', new FilterInfo(ContentType::Text), new TrustedJavaScript('a()')));
	Assert::same(TrustedHtml::class, $filters->filterContent('cls', new FilterInfo(ContentType::Text), new TrustedHtml('<b>')));
});
