<?php

/**
 * Test: comments HTML test
 */

declare(strict_types=1);

use Latte\ContentType;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class ContentTypeLoader implements Latte\Loader
{
	private $contents = [
		'file.js' => '// JavaScript <img> {=latte} <script></script>',
		'file.txt' => '// text <img> {=latte} <script></script>',
		'file.html' => '// HTML <img> {=latte} <script></script>',
	];


	public function load(string $name): Latte\LoadedContent
	{
		if (!isset($this->contents[$name])) {
			return new Latte\LoadedContent($name);
		}
		[$contentType, $static] = (new Latte\Loaders\FileLoader)->detectContentType($name);
		return new Latte\LoadedContent(
			$this->contents[$name],
			contentType: $contentType,
			static: $static,
		);
	}


	public function getReferredName(string $name, string $referringName): string
	{
		return $name;
	}


	public function getUniqueId(string $name): string
	{
		return $name;
	}
}


$latte = new Latte\Engine;
$latte->setLoader(new ContentTypeLoader);
$latte->setAutoRefresh();

Assert::same(
	'// JavaScript &lt;img&gt; &#123;=latte} &lt;script&gt;&lt;/script&gt;',
	$latte->renderToString('{include file.js}'),
);

Assert::same(
	'<div> // HTML <img> {=latte} <script></script> </div>',
	$latte->renderToString('<div> {include file.html} </div>'),
);

Assert::same(
	'<div title="// HTML  &#123;=latte} "></div>',
	$latte->renderToString('<div title="{include file.html}"></div>'),
);

// <script>
Assert::same(
	'<script> // JavaScript <img> {=latte} <script><\/script> </script>',
	$latte->renderToString('<script> {include file.js} </script>'),
);

Assert::exception(
	fn() => $latte->renderToString('<script> {include file.txt} </script>'),
	Latte\RuntimeException::class,
	"Including 'file.txt' with content type TEXT into incompatible type HTML/RAW/JS.",
);

Assert::exception(
	fn() => $latte->renderToString('<script> {include file.html} </script>'),
	Latte\RuntimeException::class,
	"Including 'file.html' with content type HTML into incompatible type HTML/RAW/JS.",
);

// HTML to Text
$latte->setContentType(ContentType::Text);
Assert::same(
	'* // HTML  {=latte}  *',
	$latte->renderToString('* {include file.html} *'),
);
