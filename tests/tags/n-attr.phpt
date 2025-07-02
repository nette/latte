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
				$ʟ_tmp = [$ʟ_tmp[0] ?? null] === $ʟ_tmp ? $ʟ_tmp[0] : $ʟ_tmp;
				foreach ((array) $ʟ_tmp as $ʟ_nm => $ʟ_v) {
					if ($ʟ_tmp = LR\AttributeHandler::formatHtmlAttribute($ʟ_nm, $ʟ_v)) {
						echo ' ', $ʟ_tmp /* line 2:4 */;
					}
				}
				echo '> </p>

		<input';
				$ʟ_tmp = ['checked' => true, 'disabled' => false];
				$ʟ_tmp = [$ʟ_tmp[0] ?? null] === $ʟ_tmp ? $ʟ_tmp[0] : $ʟ_tmp;
				foreach ((array) $ʟ_tmp as $ʟ_nm => $ʟ_v) {
					if ($ʟ_tmp = LR\AttributeHandler::formatHtmlAttribute($ʟ_nm, $ʟ_v)) {
						echo ' ', $ʟ_tmp /* line 4:8 */;
					}
				}
				echo '>
		';
		%A%
		XX,
	$latte->compile($template),
);

Assert::match(
	<<<'XX'

		<p title="hello"> </p>

		<input checked>
		XX,
	$latte->renderToString($template),
);

$latte->setContentType(Latte\ContentType::Xml);
Assert::match(
	<<<'XX'

		<p title="hello"> </p>

		<input checked="checked">
		XX,
	$latte->renderToString($template),
);
$latte->setContentType(Latte\ContentType::Html);

Assert::match(
	'<input>',
	$latte->renderToString('<input n:attr="null">'),
);

Assert::match(
	'<input>',
	$latte->renderToString('<input n:attr="[]">'),
);

Assert::match(
	'<input checked>',
	$latte->renderToString('<input n:attr="[checked: true]">'),
);

Assert::match(
	'<input a=\'<>"\' b="\'">',
	$latte->renderToString('<input n:attr="$attrs">', ['attrs' => ['a' => '<>"', 'b' => "'"]]),
);

// misuse of
Assert::match(
	'<input rowspan=2>',
	$latte->renderToString('<input n:attr="\'rowspan=2\' => true">'),
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
