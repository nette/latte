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

	<p n:attr="title => hello, lang => isset($lang) ? $lang, data-foo => $data"> </p>

	<input n:attr="checked => true, disabled => false">

	EOD;

Assert::match(
	<<<'XX'
		%A%
				echo '
		<p';
				$ʟ_tmp = ['title' => 'hello', 'lang' => isset($lang) ? $lang : null, 'data-foo' => $data];
				Latte\Essential\Nodes\NAttrNode::attrs(is_array($ʟ_tmp[0] ?? null) ? $ʟ_tmp[0] : $ʟ_tmp, false) /* line 2 */;
				echo '> </p>

		<input';
				$ʟ_tmp = ['checked' => true, 'disabled' => false];
				Latte\Essential\Nodes\NAttrNode::attrs(is_array($ʟ_tmp[0] ?? null) ? $ʟ_tmp[0] : $ʟ_tmp, false) /* line 4 */;
				echo '>
		';
		%A%
		XX,
	$latte->compile($template),
);

Assert::match(
	<<<'XX'

		<p title="hello" data-foo='[1,2,3]'> </p>

		<input checked>
		XX,
	$latte->renderToString($template, ['data' => [1, 2, 3]]),
);

Assert::match(
	<<<'XX'

		<p title="hello" data-foo="123"> </p>

		<input checked="checked">
		XX,
	$latte->setContentType(Latte\ContentType::Xml)->renderToString($template, ['data' => '123']),
);


Assert::exception(
	fn() => $latte->compile('<div n:attr/>'),
	Latte\CompileException::class,
	'Missing arguments in n:attr (on line 1 at column 6)',
);


Assert::exception(
	fn() => $latte->compile('<div n:inner-attr/>'),
	Latte\CompileException::class,
	'Unexpected attribute n:inner-attr, did you mean n:inner-try? (on line 1 at column 6)',
);
