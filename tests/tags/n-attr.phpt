<?php

/**
 * n:attr
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'

	<p n:attr="title => hello, lang => isset($lang) ? $lang"> </p>

	<input n:attr="checked => true, disabled => false">

	EOD;

Assert::match(
	<<<'XX'
		%A%
				echo '
		<p';
				$ʟ_tmp = ['title' => 'hello', 'lang' => isset($lang) ? $lang : null];
				echo LR\Filters::htmlAttributes(isset($ʟ_tmp[0]) && is_array($ʟ_tmp[0]) ? $ʟ_tmp[0] : $ʟ_tmp) /* line 2 */;
				echo '> </p>

		<input';
				$ʟ_tmp = ['checked' => true, 'disabled' => false];
				echo LR\Filters::htmlAttributes(isset($ʟ_tmp[0]) && is_array($ʟ_tmp[0]) ? $ʟ_tmp[0] : $ʟ_tmp) /* line 4 */;
				echo '>
		';
		%A%
		XX
,
	$latte->compile($template),
);

Assert::match(
	<<<'XX'

		<p title="hello"> </p>

		<input checked>
		XX
,
	$latte->renderToString($template),
);

Assert::match(
	<<<'XX'

		<p title="hello"> </p>

		<input checked="checked">
		XX
,
	$latte->setContentType($latte::CONTENT_XML)->renderToString($template),
);


Assert::exception(
	fn() => $latte->compile('<div n:attr/>'),
	Latte\CompileException::class,
	'Missing arguments in n:attr',
);


Assert::exception(
	fn() => $latte->compile('<div n:inner-attr/>'),
	Latte\CompileException::class,
	'Unknown attribute n:inner-attr',
);
