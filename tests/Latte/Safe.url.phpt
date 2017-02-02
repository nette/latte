<?php

/**
 * Test: Latte\Engine and auto-safe URL.
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->addFilter('datastream', 'Latte\Runtime\Filters::dataStream');
$params['url1'] = 'javascript:alert(1)';
$params['url2'] = ' javascript:alert(1)';
$params['url3'] = 'data:text/html;base64,PHN2Zy9vbmxvYWQ9YWxlcnQoMik+';
$params['url4'] = 'ok';
$params['url5'] = '';


Assert::match('
<a href="" src="" action="" formaction="" title="javascript:alert(1)"></a>
<a href="javascript:alert(1)"></a>
<a href="http://nette.org?val=ok"></a>
<a data="javascript:alert(1)"></a>
<OBJECT DATA=""></object>
<a HREF=""></a>
<a href=""></a>
<a href=ok>ok</a>
<a href=""></a>
<a href="data:%a%;base64,b2s="></a>
<a href="data:%a%;base64,b2s="></a>
<a href=""></a>
', $latte->renderToString(
'
<a href={$url1} src="{$url1}" action={$url1} formaction={$url1} title={$url1}></a>
<a href={$url1|nocheck}></a>
<a href="http://nette.org?val={$url4}"></a>
<a data={$url1}></a>
<OBJECT DATA={$url1}></object>
<a HREF={$url2}></a>
<a href={$url3}></a>
<a href={$url4}>ok</a>
<a href={$url5}></a>
<a href={$url4|dataStream}></a>
<a href={$url4|dataStream|noCheck}></a>
<a href={$url4|dataStream|checkURL}></a>
', $params));


Assert::match('
<a href="javascript:alert(1)" src="javascript:alert(1)" action="javascript:alert(1)" formaction="javascript:alert(1)" title="javascript:alert(1)"></a>
<object data="javascript:alert(1)"></object>
', $latte->renderToString('
{contentType xml}
<a href={$url1} src="{$url1}" action={$url1} formaction={$url1} title={$url1}></a>
<object data={$url1}></object>
', $params));


// former |safeurl & |nosafeurl
Assert::error(function () use ($latte, $params) {
	$latte->renderToString('<a href={$url1|nosafeurl}></a>', $params);
}, LogicException::class, "Filter 'nosafeurl' is not defined.");

Assert::error(function () use ($latte, $params) {
	$latte->renderToString('<a href={$url4|dataStream|safeURL}></a>', $params);
}, LogicException::class, "Filter 'safeurl' is not defined.");
