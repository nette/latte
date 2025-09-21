<?php

/**
 * Test: Latte\Engine and auto-safe URL.
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->addFilter('datastream', 'Latte\Essential\Filters::dataStream');
$params['url1'] = 'javascript:alert(1)';
$params['url2'] = ' javascript:alert(1)';
$params['url3'] = 'data:text/html;base64,PHN2Zy9vbmxvYWQ9YWxlcnQoMik+';
$params['url4'] = 'ok';
$params['url5'] = '';
$params['url6'] = 'tel:+420123456789';
$params['url7'] = 'sms:+420123456789';


Assert::match('
<a href="" src="" action="" formaction="" title="javascript:alert(1)"></a>
<a href=""></a>
<a href="javascript:alert(1)"></a>
<a href="http://nette.org?val=javascript:alert(1)"></a>
<a data="javascript:alert(1)"></a>
<OBJECT DATA=""></OBJECT>
<a HREF=""></a>
<a href=""></a>
<a href="ok">ok</a>
<a href=""></a>
<a href="tel:+420123456789"></a>
<a href="sms:+420123456789"></a>
<a href="data:%a%;base64,b2s="></a>
<a href="data:%a%;base64,b2s="></a>
<a href=""></a>
', $latte->renderToString(
	'
<a href={$url1} src="{$url1}" action={$url1} formaction={$url1} title={$url1}></a>
<a {if true}href={$url1}{/if}></a>
<a href={$url1|nocheck}></a>
<a href="http://nette.org?val={$url1}"></a>
<a data={$url1}></a>
<OBJECT DATA={$url1}></object>
<a HREF={$url2}></a>
<a href={$url3}></a>
<a href={$url4}>ok</a>
<a href={$url5}></a>
<a href={$url6}></a>
<a href={$url7}></a>
<a href={$url4|dataStream}></a>
<a href={$url4|dataStream|noCheck}></a>
<a href={$url4|dataStream|checkUrl}></a>
',
	$params,
));


Assert::match(
	'<a href="javascript:alert(1)" src="javascript:alert(1)" action="javascript:alert(1)" formaction="javascript:alert(1)" title="javascript:alert(1)"></a>
<object data="javascript:alert(1)"></object>
',
	$latte->renderToString('
{contentType xml}
<a href={$url1} src="{$url1}" action={$url1} formaction={$url1} title={$url1}></a>
<object data={$url1}></object>
', $params),
);


Assert::contains(
	'LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl(($this->filters->upper)($url1)))',
	$latte->compile('<a href="{$url1|upper}"></a>'),
);


// accepts HtmlStringable
Assert::match(
	'<img src="https://nette.org?a=1&amp;b=&lt;a&gt;">',
	$latte->renderToString('{capture $url}https://nette.org?a=1&amp;b={="<a>"}{/capture}<img src="{$url}">'),
);

Assert::match(
	'<img src="">',
	$latte->renderToString('{capture $url}&#x6a;&#x61;vascript:foo{/capture}<img src="{$url}">'),
);

// accepts Stringable
Assert::match(
	'<img src="">',
	$latte->renderToString(
		'<img src="{$url}">',
		['url' => new class {
			public function __toString()
			{
				return 'javascript:foo';
			}
		}],
	),
);
