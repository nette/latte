<?php

/**
 * Test: Latte\Engine and Texy.
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class MockTexy
{
	public function process($text, $singleLine = false)
	{
		return '<pre>' . $text . '</pre>';
	}
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->addFilter('texy', [new MockTexy, 'process']);

$params['hello'] = '<i>Hello</i>';
$params['people'] = ['John', 'Mary', 'Paul'];

$result = $latte->renderToString(<<<'EOD'
{contentType text}
{block|lower|texy}
{$hello}
---------
- Escaped: {$hello}
- Non-escaped: {$hello|noescape}

- Escaped expression: {="<" . "b" . ">hello" . "</b>"}

- Non-escaped expression: {="<" . "b" . ">hello" . "</b>"|noescape}

- Array access: {$people[1]}

[* image.jpg *]
{/block}
EOD
, $params);

Assert::match(<<<'EOD'
<pre><i>hello</i>
---------
- escaped: <i>hello</i>
- non-escaped: <i>hello</i>

- escaped expression: <b>hello</b>

- non-escaped expression: <b>hello</b>

- array access: mary

[* image.jpg *]
</pre>
EOD
, $result);
