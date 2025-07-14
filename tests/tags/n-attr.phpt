<?php

/**
 * n:attr
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();

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
				echo Latte\Essential\Nodes\NAttrNode::attrs($ʟ_tmp, false) /* pos 2:4 */;
				echo '> </p>

		<input';
				$ʟ_tmp = ['checked' => true, 'disabled' => false];
				echo Latte\Essential\Nodes\NAttrNode::attrs($ʟ_tmp, false) /* pos 4:8 */;
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

		<input>
		XX,
	@$latte->renderToString($template),
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
	'<input a="&lt;&gt;&quot;" b="&apos;">',
	$latte->renderToString('<input n:attr="$attrs">', ['attrs' => ['a' => '<>"', 'b' => "'"]]),
);

// invalid
Assert::exception(
	fn() => $latte->renderToString('<input n:attr="2 => true">'),
	Latte\RuntimeException::class,
	'Attribute name must be string, int given',
);

Assert::exception(
	fn() => $latte->renderToString('<input n:attr="\'rowspan=2\' => true">'),
	Latte\RuntimeException::class,
	"Invalid attribute name 'rowspan=2'",
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
