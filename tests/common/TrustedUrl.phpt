<?php declare(strict_types=1);

/**
 * Test: Latte\Runtime\TrustedUrl in templates.
 */

use Latte\Runtime\TrustedUrl;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();
$str = 'app://x?a=1&b="<>';
$trusted = new TrustedUrl($str);


// URL attributes: trusted URL passes, plain string is dropped, both are escaped as attribute
$templates = [
	'<a href={$url}></a>',
	'<a href="{$url}"></a>',
	'<a n:attr="href => $url"></a>',
	'<object data={$url}></object>',
];
foreach ($templates as $template) {
	$attr = str_contains($template, 'data=') ? 'data' : 'href';
	Assert::match(
		'%A%' . $attr . '="app://x?a=1&amp;b=&quot;&lt;&gt;"%A%',
		$latte->renderToString($template, ['url' => $trusted]),
	);
	Assert::match(
		'%A%' . $attr . '=""%A%',
		$latte->renderToString($template, ['url' => $str]),
	);
}


// outside URL check it is an ordinary string
$templates = [
	'<p>{$url}</p>',
	'<p title={$url}></p>',
	'<a onclick={$url}></a>',
];
foreach ($templates as $template) {
	Assert::same(
		$latte->renderToString($template, ['url' => $str]),
		$latte->renderToString($template, ['url' => $trusted]),
		$template,
	);
}


// not a content type: in JS it is an object like any other Stringable
$stringable = new class ($str) implements Stringable {
	public function __construct(
		private string $s,
	) {
	}


	public function __toString(): string
	{
		return $this->s;
	}
};
Assert::same(
	$latte->renderToString('<script>x = {$url}</script>', ['url' => $stringable]),
	$latte->renderToString('<script>x = {$url}</script>', ['url' => $trusted]),
);
